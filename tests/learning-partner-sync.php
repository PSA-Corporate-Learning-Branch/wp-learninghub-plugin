<?php
/**
 * Run with wp eval-file tests/learning-partner-sync.php on a disposable WordPress install.
 * Requires transactional tables. All database changes are rolled back.
 */

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

global $wpdb;

foreach ($wpdb->get_results('SHOW TABLE STATUS') as $table) {
    if ($table->Engine !== 'InnoDB') {
        WP_CLI::error('This rollback-based check requires InnoDB tables.');
    }
}

function learninghub_sync_assert($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
    WP_CLI::log('PASS: ' . $message);
}

$original_post = $_POST;
$_POST = array();
$wpdb->query('START TRANSACTION');

try {
    $suffix = wp_generate_uuid4();
    $name = 'Sync regression ' . $suffix;
    $slug = sanitize_title($name);
    $inserted = wp_insert_term($name, 'learning_partner', array('slug' => $slug));
    learninghub_sync_assert(!is_wp_error($inserted), 'Create an imported partner without a source ID');
    $term_id = $inserted['term_id'];
    update_term_meta($term_id, 'partner-url', 'https://example.com/old');
    update_term_meta($term_id, 'category-image-id', '123');
    $post_id = wp_insert_post(array('post_type' => 'course', 'post_title' => $name));
    wp_set_object_terms($post_id, array($term_id), 'learning_partner');

    $partner = array(
        'id' => 999999991,
        'name' => $name,
        'slug' => $slug,
        'description' => 'Updated description',
        'link' => 'https://example.com/updated',
        'employee_facing_contact' => 'learning@example.com',
        'status' => 'active',
    );
    sync_learning_partners_with_feed(array($partner));
    learninghub_sync_assert((int) get_term_meta($term_id, 'learning_partner_source_id', true) === $partner['id'], 'Adopt an imported term by slug');
    learninghub_sync_assert(get_term_meta($term_id, 'partner-url', true) === $partner['link'], 'Update the URL used by the editor and theme');
    learninghub_sync_assert(get_term_meta($term_id, 'partner-contact', true) === $partner['employee_facing_contact'], 'Update partner contact');
    learninghub_sync_assert(get_term_meta($term_id, 'category-image-id', true) === '123', 'Preserve the partner logo');
    learninghub_sync_assert(has_term($term_id, 'learning_partner', $post_id), 'Preserve course relationships');

    sync_learning_partners_with_feed(array($partner));
    learninghub_sync_assert(get_term_by('slug', $slug, 'learning_partner')->term_id === $term_id, 'Repeated sync reuses the same term');

    delete_term_meta($term_id, 'learning_partner_source_id');
    $partner['slug'] .= '-renamed';
    sync_learning_partners_with_feed(array($partner));
    learninghub_sync_assert(get_term($term_id)->slug === $partner['slug'], 'Adopt an imported term by name when its slug differs');

    $partner['name'] .= ' renamed';
    $partner['link'] = '';
    unset($partner['employee_facing_contact']);
    sync_learning_partners_with_feed(array($partner));
    learninghub_sync_assert(get_term($term_id)->name === $partner['name'], 'Update a mapped partner by source ID');
    learninghub_sync_assert(get_term_meta($term_id, 'partner-url', true) === '' && get_term_meta($term_id, 'partner-contact', true) === '', 'Clear fields removed from the feed');

    $new_partner = $partner;
    $new_partner['id']++;
    $new_partner['name'] .= ' new';
    $new_partner['slug'] .= '-new';
    $new_partner['link'] = 'https://example.com/new';
    sync_learning_partners_with_feed(array($partner, $new_partner));
    $new_term = get_term_by('slug', $new_partner['slug'], 'learning_partner');
    learninghub_sync_assert($new_term && get_term_meta($new_term->term_id, 'partner-url', true) === $new_partner['link'], 'Create and populate a new partner');

    wp_update_term($term_id, 'learning_partner', array('description' => 'Programmatic edit'));
    learninghub_sync_assert(get_term_meta($term_id, 'category-image-id', true) === '123', 'Programmatic edits preserve fields absent from POST');
    $_POST['category-image-id'] = '';
    wp_update_term($term_id, 'learning_partner', array('description' => 'Editor edit'));
    learninghub_sync_assert(get_term_meta($term_id, 'category-image-id', true) === '', 'An explicitly cleared editor field still clears');
    $_POST = array();

    $new_partner['status'] = 'inactive';
    sync_learning_partners_with_feed(array($partner, $new_partner));
    learninghub_sync_assert(!get_term($new_term->term_id, 'learning_partner'), 'Remove an inactive synced partner');
} finally {
    $wpdb->query('ROLLBACK');
    wp_cache_flush();
    $_POST = $original_post;
}
