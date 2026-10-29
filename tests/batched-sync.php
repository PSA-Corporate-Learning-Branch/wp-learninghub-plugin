<?php
/**
 * Destructive integration check: use a disposable local site and restore its DB afterwards.
 * wp eval-file wp-content/plugins/wp-learninghub-plugin/tests/batched-sync.php
 */
if (!defined('WP_CLI') || !WP_CLI || wp_get_environment_type() !== 'local') {
    throw new RuntimeException('Run only on a disposable WP_ENVIRONMENT_TYPE=local site.');
}

function learninghub_batch_assert($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
    WP_CLI::log('PASS: ' . $message);
}

function learninghub_batch_matches($id) {
    return get_posts(array('post_type' => 'course', 'post_status' => 'any', 'numberposts' => -1, 'meta_key' => 'elm_course_id', 'meta_value' => $id));
}

$items = array();
for ($i = 1; $i <= 12; $i++) {
    $items[] = (object) array(
        '_course_id' => 990000000 + $i,
        'id' => 'BATCH-' . $i,
        'title' => 'Batch regression ' . $i,
        '_slug' => 'batch-regression-' . $i,
        'summary' => '**Updated** course description.',
        'url' => 'https://example.com/course/' . $i,
        'date_published' => '2026-01-01',
        'date_modified' => '2026-10-29',
        '_keywords' => 'regression,testing',
        '_audience' => 'All employees',
        '_topic' => 'Testing',
        '_learning_partner' => 'Batch learning partner',
        'delivery_method' => 'Online',
        '_platform' => 'PSA Learning System',
        '_development_partners' => array((object) array('name' => 'Batch development partner')),
    );
}
$items[10]->_course_id = 'ELM_20261029000000_0001';
$items[11]->_course_id = '20261029000000-1';
$feeds = array(
    'bcps-corporate-learning-courses.json' => (object) array('items' => $items),
    'bcps-corporate-learning-partners.json' => array((object) array('id' => 990000001, 'name' => 'Batch learning partner', 'status' => 'active')),
    'bcps-development-partners.json' => array((object) array('id' => 990000002, 'name' => 'Batch development partner', 'status' => 'active', 'type' => 'test')),
);
$bad_feed = false;
$fetch_count = 0;
add_filter('pre_http_request', static function ($response, $args, $url) use (&$feeds, &$bad_feed, &$fetch_count) {
    $file = basename(wp_parse_url($url, PHP_URL_PATH));
    if (!isset($feeds[$file])) {
        return $response;
    }
    $fetch_count++;
    learninghub_batch_assert($args['timeout'] === 10, 'Feed requests have a 10-second timeout');
    return array('headers' => array(), 'body' => $bad_feed ? '{broken' : wp_json_encode($feeds[$file]), 'response' => array('code' => 200, 'message' => 'OK'), 'cookies' => array());
}, 10, 3);

$old = wp_insert_post(array('post_type' => 'course', 'post_status' => 'publish', 'post_title' => 'Missing from feed', 'meta_input' => array('elm_course_id' => 990000099)));
$manual = wp_insert_post(array('post_type' => 'course', 'post_status' => 'publish', 'post_title' => 'Expired manual course', 'meta_input' => array('course_expire' => '2020-01-01')));
$keep = wp_insert_post(array('post_type' => 'course', 'post_status' => 'publish', 'post_title' => 'Manual course without expiry'));
$existing = wp_insert_post(array('post_type' => 'course', 'post_status' => 'private', 'post_title' => 'Old title', 'post_content' => 'Old description', 'meta_input' => array('elm_course_id' => 990000001)));
wp_set_object_terms($existing, array('Old regression topic'), 'topics');
$missing_partner = wp_insert_term('Missing batch partner', 'learning_partner')['term_id'];
update_term_meta($missing_partner, 'learning_partner_source_id', 990000099);

$job = learninghub_sync_load();
if ($job) {
    learninghub_sync_run('reset', $job['id']);
}
$job = learninghub_sync_run('start');
$bad_feed = true;
try {
    learninghub_sync_run('step', $job['id']);
    throw new RuntimeException('Invalid JSON was accepted');
} catch (RuntimeException $error) {
    learninghub_batch_assert(strpos($error->getMessage(), 'not valid JSON') !== false, 'Reject invalid JSON');
}
learninghub_batch_assert(learninghub_sync_load()['stage'] === 'fetch_courses' && get_post_status($old) === 'publish', 'Failed download preserves progress and published courses');
$bad_feed = false;
while (learninghub_sync_load()['stage'] !== 'courses') {
    learninghub_sync_run('step', $job['id']);
}
learninghub_batch_assert(get_post_status($old) === 'publish' && get_term($missing_partner), 'Partial batches do not prune unrelated courses or partners');

// Fail after inserting a new course, before its checkpoint can commit.
$fault = static function ($post_id, $post, $update) {
    if (!$update && $post->post_title === 'Batch regression 6') {
        throw new RuntimeException('Injected interruption after insert');
    }
};
add_action('save_post_course', $fault, 10, 3);
try {
    while (learninghub_sync_load()['stage'] === 'courses') {
        learninghub_sync_run('step', $job['id']);
    }
    throw new RuntimeException('Interruption did not trigger');
} catch (RuntimeException $error) {
    learninghub_batch_assert($error->getMessage() === 'Injected interruption after insert', 'Interrupt a course after insertion');
}
remove_action('save_post_course', $fault, 10);
learninghub_batch_assert(learninghub_sync_load()['offset'] === 5, 'Checkpoint retains the first five completed courses');
learninghub_batch_assert(count(learninghub_batch_matches(990000006)) === 0, 'Interrupted creation rolls back without an orphan post');
learninghub_batch_assert(get_post_status($old) === 'publish', 'Cleanup waits until all course updates succeed');

$before_resume = $fetch_count;
$requests = 0;
do {
    $job = learninghub_sync_run('step', $job['id']);
    $requests++;
} while ($job['stage'] !== 'done' && $requests < 1000);
learninghub_batch_assert($job['stage'] === 'done', 'Resume finishes in bounded requests');
learninghub_batch_assert($fetch_count === $before_resume, 'Resume reuses saved feeds');
learninghub_batch_assert(count(learninghub_batch_matches(990000006)) === 1, 'Resume creates exactly one copy');
learninghub_batch_assert(get_post($existing)->post_title === 'Batch regression 1' && get_post_status($existing) === 'publish', 'Update and republish existing course');
learninghub_batch_assert(strpos(get_post($existing)->post_content, '<strong>Updated</strong>') !== false, 'Existing course receives the changed Markdown description');
learninghub_batch_assert(wp_get_object_terms($existing, 'topics', array('fields' => 'names')) === array('Testing'), 'Existing course topic is replaced rather than appended');
learninghub_batch_assert(has_term('Batch development partner', 'development_partner', $existing), 'New development partner is available on the first sync');
learninghub_batch_assert(get_post_status($old) === 'private' && get_post_status($manual) === 'private', 'Cleanup hides missing and expired courses');
learninghub_batch_assert(get_post_status($keep) === 'publish', 'Manual course without expiry remains published');
learninghub_batch_assert(!get_term($missing_partner), 'Cleanup removes missing mapped partners');
learninghub_batch_assert(get_option('learninghub_sync_feed_courses', null) === null, 'Completed job removes saved feed payloads');

$feeds['bcps-corporate-learning-courses.json']->items[0]->summary = '**Changed again** in a later feed.';
$feeds['bcps-corporate-learning-courses.json']->items[0]->_topic = 'Changed regression topic';
$job = learninghub_sync_run('start');
$second = learninghub_sync_run('start');
learninghub_batch_assert($job['id'] === $second['id'], 'Two tabs resume the same active job');
$other = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$lock = 'learninghub_sync_' . md5(DB_NAME . ':' . $GLOBALS['wpdb']->options);
$other->get_var($other->prepare('SELECT GET_LOCK(%s, 0)', $lock));
try {
    learninghub_sync_run('step', $job['id']);
    throw new RuntimeException('Concurrent request was accepted');
} catch (RuntimeException $error) {
    learninghub_batch_assert(strpos($error->getMessage(), 'still running') !== false, 'Reject an overlapping worker');
} finally {
    $other->get_var($other->prepare('SELECT RELEASE_LOCK(%s)', $lock));
    $other->close();
}
$requests = 0;
do {
    $job = learninghub_sync_run('step', $job['id']);
    $requests++;
} while ($job['stage'] !== 'done' && $requests < 1000);
learninghub_batch_assert(count(learninghub_batch_matches(990000006)) === 1, 'A complete repeated sync does not duplicate courses');
learninghub_batch_assert(learninghub_batch_matches(990000001)[0]->ID === $existing, 'Changed feed updates the same post ID');
learninghub_batch_assert(strpos(get_post($existing)->post_content, '<strong>Changed again</strong>') !== false, 'A fresh sync reads a later description change');
learninghub_batch_assert(wp_get_object_terms($existing, 'topics', array('fields' => 'names')) === array('Changed regression topic'), 'A fresh sync replaces the previous topic with the later feed value');
WP_CLI::success('Batched sync checks passed. Restore the disposable database snapshot now.');
