<?php
/**
 * Plugin Name: Learning Hub Storage Check
 * Description: Read-only report of the current site's table storage engines. Does not run or modify the Learning Hub sync.
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * License: GPL-2.0-or-later
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Register only a site-admin Tools page. No activation or background work. */
function learninghub_storage_check_menu() {
    add_management_page(
        'Learning Hub Storage Check',
        'Learning Hub Storage Check',
        'manage_options',
        'learninghub-storage-check',
        'learninghub_storage_check_page'
    );
}
add_action('admin_menu', 'learninghub_storage_check_menu');

/**
 * Read engine metadata for exactly the seven tables used by the sync.
 * WordPress supplies their names, including the current multisite site's prefix.
 * No arbitrary table names or SQL are accepted from the browser.
 */
function learninghub_storage_check_report() {
    global $wpdb;

    $tables = array(
        'Courses and other posts' => $wpdb->posts,
        'Course and post metadata' => $wpdb->postmeta,
        'Terms' => $wpdb->terms,
        'Taxonomies' => $wpdb->term_taxonomy,
        'Course and term relationships' => $wpdb->term_relationships,
        'Term metadata' => $wpdb->termmeta,
        'Site options and saved progress' => $wpdb->options,
    );
    $placeholders = implode(', ', array_fill(0, count($tables), '%s'));
    $sql = $wpdb->prepare(
        "SHOW TABLE STATUS WHERE Name IN ($placeholders)",
        array_values($tables)
    );

    // A denied metadata query is reported as unknown, never as MyISAM or a pass.
    $previous_suppression = $wpdb->suppress_errors(true);
    $results = $wpdb->get_results($sql);
    $query_failed = !empty($wpdb->last_error);
    $wpdb->suppress_errors($previous_suppression);

    $engines = array();
    if (!$query_failed && is_array($results)) {
        foreach ($results as $result) {
            $engines[$result->Name] = (string) $result->Engine;
        }
    }

    $rows = array();
    $all_innodb = true;
    $has_unknown = false;
    foreach ($tables as $purpose => $name) {
        $engine = isset($engines[$name]) && $engines[$name] !== '' ? $engines[$name] : 'Unknown';
        $matches = strcasecmp($engine, 'InnoDB') === 0;
        $all_innodb = $all_innodb && $matches;
        $has_unknown = $has_unknown || $engine === 'Unknown';
        $rows[] = array(
            'purpose' => $purpose,
            'name' => $name,
            'engine' => $engine,
        );
    }

    return array(
        'rows' => $rows,
        'all_innodb' => $all_innodb,
        'has_unknown' => $has_unknown,
        'query_failed' => $query_failed,
    );
}

function learninghub_storage_check_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to view this report.');
    }

    $report = learninghub_storage_check_report();
    if ($report['all_innodb']) {
        $summary = 'All seven tables use InnoDB. No table conversion is needed for the proposed sync.';
    } elseif ($report['has_unknown']) {
        $summary = 'Could not verify every table. Unknown means the metadata was unavailable; it does not mean the table uses MyISAM.';
    } else {
        $summary = 'One or more tables use another engine. The proposed sync needs a different recovery design or an independently approved database conversion.';
    }

    $lines = array(
        'Learning Hub Storage Check 1.0.0',
        'Site: ' . home_url('/'),
        'Site ID: ' . get_current_blog_id(),
        'Multisite: ' . (is_multisite() ? 'Yes' : 'No'),
        'WordPress: ' . get_bloginfo('version'),
        'PHP: ' . PHP_VERSION,
        'PHP max_execution_time: ' . ini_get('max_execution_time'),
        'External object cache: ' . (wp_using_ext_object_cache() ? 'Yes' : 'No'),
        '',
        $summary,
        '',
    );
    foreach ($report['rows'] as $row) {
        $lines[] = $row['name'] . ': ' . $row['engine'];
    }
    $lines[] = '';
    $lines[] = 'Metadata inspection only. No content, schema, settings, or sync changes were made by this checker.';
    $lines[] = 'Coordination locks, transaction rollback, database routing, and cache behaviour were not tested.';
    ?>
    <div class="wrap">
        <h1>Learning Hub Storage Check</h1>
        <p>This report reads table metadata for the current site only. It does not run a sync, convert tables, or inspect course content.</p>
        <p>
            <strong>Current site:</strong>
            <?php echo esc_html(home_url('/')); ?>
            (site ID <?php echo esc_html((string) get_current_blog_id()); ?>)
        </p>
        <p><strong><?php echo esc_html($summary); ?></strong></p>
        <?php if ($report['query_failed']) : ?>
            <p>The database did not allow or complete the metadata query. No changes were attempted.</p>
        <?php endif; ?>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th scope="col">Purpose</th>
                    <th scope="col">Table</th>
                    <th scope="col">Storage engine</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report['rows'] as $row) : ?>
                    <tr>
                        <td><?php echo esc_html($row['purpose']); ?></td>
                        <td><code><?php echo esc_html($row['name']); ?></code></td>
                        <td><?php echo esc_html($row['engine']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Copyable report</h2>
        <p>This checks the storage-engine prerequisite only. It does not test transaction rollback, coordination locks, database routing, or cache behaviour.</p>
        <textarea class="large-text code" rows="21" readonly aria-label="Storage engine report"><?php echo esc_textarea(implode("\n", $lines)); ?></textarea>
        <p>In Multisite, open this page from the Learning Hub site's dashboard. The report follows that site's table names. You can deactivate and delete the checker after collecting the report.</p>
    </div>
    <?php
}
