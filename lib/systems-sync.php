<?php
/**
 * Resumable manual sync. Each item and its checkpoint commit together.
 * Snapshots are per-site, non-autoloaded options, never browser-supplied data.
 */

function learninghub_sync_check_result($result) {
    if (is_wp_error($result)) {
        throw new RuntimeException($result->get_error_message());
    }
    return $result;
}

function learninghub_sync_stages() {
    return array(
        'fetch_courses' => 'Download courses',
        'fetch_development' => 'Download development partners',
        'fetch_learning' => 'Download learning partners',
        'development' => 'Update development partners',
        'learning' => 'Update learning partners',
        'courses' => 'Update courses',
        'course_cleanup' => 'Check missing and expired courses',
        'development_cleanup' => 'Remove missing development partners',
        'learning_cleanup' => 'Remove missing learning partners',
        'done' => 'Sync complete',
    );
}

/** Read checkpoints from the database, never an uncommitted object-cache entry. */
function learninghub_sync_load() {
    global $wpdb;
    return maybe_unserialize($wpdb->get_var($wpdb->prepare(
        "SELECT option_value FROM $wpdb->options WHERE option_name = %s",
        'learninghub_sync_job'
    )));
}

function learninghub_sync_save($job) {
    global $wpdb;
    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO $wpdb->options (option_name, option_value, autoload) VALUES (%s, %s, 'no')
        ON DUPLICATE KEY UPDATE option_value = VALUES(option_value), autoload = 'no'",
        'learninghub_sync_job',
        maybe_serialize($job)
    ));
    if ($result === false) {
        throw new RuntimeException('Could not save sync progress.');
    }
    wp_cache_delete('learninghub_sync_job', 'options');
}

function learninghub_sync_status($job) {
    if (!$job) {
        return array('stage' => 'idle', 'message' => 'Ready to sync.');
    }
    $labels = learninghub_sync_stages();
    return array(
        'id' => $job['id'],
        'stage' => $job['stage'],
        'message' => $labels[$job['stage']] . ($job['total'] !== null ? ': ' . $job['offset'] . ' / ' . $job['total'] : ''),
        'started' => $job['started'],
    );
}

/** Transactions are essential to avoid duplicate/half-created courses after a fatal timeout. */
function learninghub_sync_check_tables() {
    global $wpdb;
    $required = array($wpdb->posts, $wpdb->postmeta, $wpdb->terms, $wpdb->term_taxonomy, $wpdb->term_relationships, $wpdb->termmeta, $wpdb->options);
    $placeholders = implode(', ', array_fill(0, count($required), '%s'));
    $tables = $wpdb->get_results($wpdb->prepare(
        "SHOW TABLE STATUS WHERE Name IN ($placeholders)",
        $required
    ));
    if (!is_array($tables) || $wpdb->last_error) {
        throw new RuntimeException('Could not read this site\'s table storage engines. No sync work was started.');
    }
    foreach ($required as $name) {
        $supported = false;
        foreach ($tables as $table) {
            if ($table->Name === $name && strtoupper($table->Engine) === 'INNODB') {
                $supported = true;
                break;
            }
        }
        if (!$supported) {
            throw new RuntimeException('Resumable sync requires InnoDB tables. Please ask the database administrator to check ' . $name . '.');
        }
    }
}

/** Validate the complete snapshot before allowing any course or term changes. */
function learninghub_sync_fetch($kind) {
    $files = array(
        'courses' => 'bcps-corporate-learning-courses.json',
        'development' => 'bcps-development-partners.json',
        'learning' => 'bcps-corporate-learning-partners.json',
    );
    $base = defined('LEARNINGHUB_SYNC_FEED_BASE_URL') ? LEARNINGHUB_SYNC_FEED_BASE_URL : 'https://learn.bcpublicservice.gov.bc.ca/learning-hub/publish/';
    $response = wp_remote_get(trailingslashit($base) . $files[$kind], array('timeout' => 10, 'redirection' => 2));
    learninghub_sync_check_result($response);
    if (wp_remote_retrieve_response_code($response) !== 200) {
        throw new RuntimeException('The ' . $kind . ' feed returned HTTP ' . wp_remote_retrieve_response_code($response) . '. Resume to retry.');
    }
    $feed = json_decode(wp_remote_retrieve_body($response));
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException('The ' . $kind . ' feed is not valid JSON. No cleanup has run.');
    }
    $items = $kind === 'courses' ? ($feed->items ?? null) : $feed;
    if (!is_array($items) || ($kind === 'courses' && !$items)) {
        throw new RuntimeException('The ' . $kind . ' feed is missing its records or is unexpectedly empty.');
    }
    $ids = array();
    foreach ($items as $item) {
        $id = is_object($item) ? ($kind === 'courses' ? ($item->_course_id ?? null) : ($item->id ?? null)) : null;
        $name = is_object($item) ? ($kind === 'courses' ? ($item->title ?? null) : ($item->name ?? null)) : null;
        // LSApp course IDs are opaque strings (including timestamp and ELM_ IDs).
        $valid_id = (is_string($id) || is_int($id)) && trim((string) $id) !== '' && (string) $id !== '0';
        if ($kind !== 'courses') {
            $valid_id = $valid_id && preg_match('/^[1-9][0-9]*$/', (string) $id);
        }
        if (!$valid_id || !is_string($name) || trim($name) === '' || isset($ids[$id])) {
            throw new RuntimeException('The ' . $kind . ' feed contains an invalid or duplicate record. No cleanup has run.');
        }
        if ($kind === 'courses') {
            foreach (array('summary', '_slug', 'url', 'id', 'date_published', 'date_modified', '_keywords', '_audience', '_topic', '_learning_partner', 'delivery_method') as $field) {
                if (!isset($item->$field) || !is_scalar($item->$field)) {
                    throw new RuntimeException('Course ' . $id . ' has an invalid ' . $field . ' field.');
                }
            }
        }
        $ids[$id] = true;
    }
    return $items;
}

function learninghub_sync_next_stage(&$job) {
    $stages = array_keys(learninghub_sync_stages());
    $job['stage'] = $stages[array_search($job['stage'], $stages, true) + 1];
    $job['offset'] = 0;
    $job['total'] = null;
}

function learninghub_sync_snapshot($kind) {
    $items = get_option('learninghub_sync_feed_' . $kind, null);
    if (!is_array($items)) {
        throw new RuntimeException('Saved feed is unavailable. Start a new sync to download it again.');
    }
    return $items;
}

function learninghub_sync_cleanup_course($post_id, $feed_ids) {
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'course') {
        return;
    }
    $source_id = get_post_meta($post_id, 'elm_course_id', true);
    $missing = $source_id && !isset($feed_ids[$source_id]);
    $expiry = get_post_meta($post_id, 'course_expire', true);
    $expired = $post->post_status === 'publish' && $expiry && current_time('Y-m-d') > $expiry
        && !has_term(array('psa-learning-system', 'psa-learning-curator'), 'external_system', $post_id);
    if (($missing || $expired) && $post->post_status !== 'private') {
        learninghub_sync_check_result(wp_update_post(array('ID' => $post_id, 'post_status' => 'private'), true));
    }
}

/** Run one bounded unit. The caller commits its writes and checkpoint atomically. */
function learninghub_sync_step(&$job) {
    $stage = $job['stage'];
    if (strpos($stage, 'fetch_') === 0) {
        $kind = substr($stage, 6);
        $snapshot = learninghub_sync_fetch($kind);
        update_option('learninghub_sync_feed_' . $kind, $snapshot, false);
        if (get_option('learninghub_sync_feed_' . $kind) != $snapshot) {
            throw new RuntimeException('Could not save the downloaded feed.');
        }
        learninghub_sync_next_stage($job);
        return;
    }

    $cleanup = substr($stage, -8) === '_cleanup';
    $kind = $cleanup ? substr($stage, 0, -8) : $stage;
    if ($kind === 'course') {
        $kind = 'courses';
    }
    $feed = learninghub_sync_snapshot($kind);
    if ($cleanup) {
        if ($job['total'] === null) {
            if ($kind === 'courses') {
                $ids = get_posts(array('post_type' => 'course', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC'));
            } else {
                $ids = learninghub_sync_check_result(get_terms(array('taxonomy' => $kind . '_partner', 'hide_empty' => false, 'fields' => 'ids')));
            }
            update_option('learninghub_sync_cleanup_ids', $ids, false);
            $job['total'] = count($ids);
            return;
        }
        $items = get_option('learninghub_sync_cleanup_ids', array());
    } else {
        $items = $feed;
        $job['total'] = count($items);
    }
    if ($job['offset'] >= count($items)) {
        learninghub_sync_next_stage($job);
        if ($job['stage'] === 'done') {
            foreach (array('courses', 'development', 'learning') as $feed_kind) {
                delete_option('learninghub_sync_feed_' . $feed_kind);
            }
            delete_option('learninghub_sync_cleanup_ids');
        }
        return;
    }

    $item = $items[$job['offset']];
    if ($cleanup) {
        $feed_ids = array();
        foreach ($feed as $record) {
            if ($kind === 'courses') {
                $feed_ids[$record->_course_id] = true;
            } elseif (empty($record->status) || strtolower($record->status) === 'active') {
                $feed_ids[$record->id] = true;
            }
        }
        if ($kind === 'courses') {
            learninghub_sync_cleanup_course($item, $feed_ids);
        } else {
            $taxonomy = $kind . '_partner';
            $source_id = get_term_meta($item, $taxonomy . '_source_id', true);
            if ($source_id && !isset($feed_ids[$source_id])) {
                learninghub_sync_check_result(wp_delete_term($item, $taxonomy));
            }
        }
    } elseif ($kind === 'courses') {
        $matches = get_posts(array(
            'post_type' => 'course',
            'post_status' => 'any',
            'numberposts' => -1,
            'meta_key' => 'elm_course_id',
            'meta_value' => (string) $item->_course_id,
        ));
        sync_courses_with_feed((object) array('items' => array($item)), $matches, false);
    } else {
        $function = 'sync_' . $kind . '_partners_with_feed';
        $function(array((array) $item), false);
    }
    $job['offset']++;
}

/**
 * A connection-scoped DB lock serializes browsers; a killed PHP worker releases it.
 * Never expire a time-based lease while an old worker could still be writing.
 */
function learninghub_sync_run($command, $job_id = '') {
    global $wpdb;
    $lock = 'learninghub_sync_' . md5(DB_NAME . ':' . $wpdb->options);
    $lock_result = $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 0)', $lock));
    if ($lock_result === null) {
        throw new RuntimeException('The database could not acquire the sync coordination lock. No sync work was started.');
    }
    if ((string) $lock_result !== '1') {
        throw new RuntimeException('Another sync request is still running. Wait a moment, then Resume.');
    }
    try {
        $job = learninghub_sync_load();
        if ($command === 'start') {
            if (!$job || $job['stage'] === 'done') {
                learninghub_sync_check_tables();
                $job = array('id' => wp_generate_uuid4(), 'stage' => 'fetch_courses', 'offset' => 0, 'total' => null, 'started' => current_time('mysql'));
                learninghub_sync_save($job);
            }
            return learninghub_sync_status($job);
        }
        if (!$job || !hash_equals($job['id'], $job_id)) {
            throw new RuntimeException('This sync has changed. Reload the page to see its current progress.');
        }
        if ($command === 'reset') {
            delete_option('learninghub_sync_job');
            foreach (array('courses', 'development', 'learning') as $kind) {
                delete_option('learninghub_sync_feed_' . $kind);
            }
            delete_option('learninghub_sync_cleanup_ids');
            return learninghub_sync_status(false);
        }
        // Fatal errors do not run finally blocks. Roll back and evict uncommitted
        // WordPress cache entries on shutdown as well as on ordinary exceptions.
        $in_transaction = false;
        register_shutdown_function(static function () use (&$in_transaction, $wpdb) {
            if ($in_transaction) {
                $wpdb->query('ROLLBACK');
                wp_cache_flush();
            }
        });
        $started = microtime(true);
        for ($count = 0; $count < 5 && microtime(true) - $started < 8 && $job['stage'] !== 'done'; $count++) {
            $fetching = strpos($job['stage'], 'fetch_') === 0;
            if ($wpdb->query('START TRANSACTION') === false) {
                throw new RuntimeException('Could not start a sync transaction.');
            }
            $in_transaction = true;
            try {
                learninghub_sync_step($job);
                learninghub_sync_save($job);
                if ($wpdb->query('COMMIT') === false) {
                    throw new RuntimeException('Could not save sync progress.');
                }
                $in_transaction = false;
            } catch (Throwable $error) {
                $wpdb->query('ROLLBACK');
                $in_transaction = false;
                // WordPress may have cached uncommitted posts/options/terms.
                wp_cache_flush();
                throw $error;
            }
            // Each HTTP download gets its own request and timeout budget.
            if ($fetching) {
                break;
            }
        }
        return learninghub_sync_status($job);
    } finally {
        $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock));
    }
}

function learninghub_sync_ajax() {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => 'You do not have permission to sync courses.'), 403);
    }
    check_ajax_referer('learninghub_sync', 'nonce');
    $command = sanitize_key($_POST['command'] ?? '');
    if (!in_array($command, array('start', 'step', 'reset'), true)) {
        wp_send_json_error(array('message' => 'Unknown sync command.'), 400);
    }
    try {
        $status = learninghub_sync_run($command, sanitize_text_field(wp_unslash($_POST['job_id'] ?? '')));
    } catch (Throwable $error) {
        wp_send_json_error(array('message' => $error->getMessage()), 500);
    }
    wp_send_json_success($status);
}
add_action('wp_ajax_learninghub_sync', 'learninghub_sync_ajax');

function learninghub_render_sync_page() {
    if (!current_user_can('edit_posts')) {
        wp_die('You do not have permission to sync courses.');
    }
    wp_enqueue_style('learninghub-sync', plugins_url('../assets/systems-sync.css', __FILE__), array(), '3.2.1');
    wp_enqueue_script('learninghub-sync', plugins_url('../assets/systems-sync.js', __FILE__), array(), '3.2.1', true);
    wp_localize_script('learninghub-sync', 'learninghubSync', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('learninghub_sync'),
        'status' => learninghub_sync_status(learninghub_sync_load()),
    ));
    ?>
    <div class="wrap">
        <h1>External Systems Synchronization</h1>
        <p>Sync courses and partners from LSApp, then check missing and expired courses.</p>
        <p>Keep this page open while syncing. Progress is saved after each item. If you close the page or a request fails, return here and choose Resume.</p>
        <p id="learninghub-sync-status" role="status" aria-live="polite"></p>
        <p id="learninghub-sync-error" role="alert"></p>
        <button type="button" class="button button-primary" id="learninghub-sync-start">Start Systems Sync</button>
        <button type="button" class="button" id="learninghub-sync-pause" hidden>Pause</button>
        <button type="button" class="button" id="learninghub-sync-reset" hidden>Discard saved progress</button>
        <p>Discarding progress keeps changes already applied. The next sync downloads fresh feeds and checks everything again.</p>
        <noscript><p>JavaScript is required to run the sync in small batches.</p></noscript>
    </div>
    <?php
}
