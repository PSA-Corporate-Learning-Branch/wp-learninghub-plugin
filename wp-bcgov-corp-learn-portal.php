<?php
/*
Plugin Name: BC Gov Corporate Learning Hub
Plugin URI: https://github.com/allanhaggett/wp-bcgov-learning-hub/
Description: A gateway to everything that BC Gov has to offer for learning opportunities.
Author: Allan Haggett <allan.haggett@gov.bc.ca>
Version: 3
Author URI: https://learningcenter.gww.gov.bc.ca/learninghub/
*/

// Include Parsedown library for markdown parsing
if (file_exists(__DIR__ . '/lib/Parsedown.php')) {
    require_once __DIR__ . '/lib/Parsedown.php';
}


/**
 * Start by defining the course content type, then start tacking on our taxonomies
 */
function my_custom_post_course() {
    $labels = array(
        'name'               => _x( 'Courses', 'post type general name' ),
        'singular_name'      => _x( 'Course', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'course' ),
        'add_new_item'       => __( 'Add New Course' ),
        'edit_item'          => __( 'Edit Course' ),
        'new_item'           => __( 'New Course' ),
        'all_items'          => __( 'All Courses' ),
        'view_item'          => __( 'View Course' ),
        'search_items'       => __( 'Search Courses' ),
        'not_found'          => __( 'No courses found' ),
        'not_found_in_trash' => __( 'No courses found in the Trash' ), 
        'parent_item_colon'  => __( 'Parent courses: ' ), 
        'menu_name'          => 'Courses'
    );
    $args = array(
        'labels'              => $labels,
        'description'         => 'Holds courses and course specific data',
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'page',
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'          => 'dashicons-book',
        'supports'            => array( 'title', 
                                        'editor', 
                                        'thumbnail',
                                        'author',
                                        'page-attributes',
                                        'custom-fields')
        // , 'custom-fields'        
    );
    register_post_type( 'course', $args ); 
}
add_action( 'init', 'my_custom_post_course' );


/**
 * Start applying various taxonomies; start with the methods, 
 * then init them all in one place
 */

/**
 * Courses can synchronize from multiple different Systems; 
 * e.g. PSA Learning System We use this taxonomy to keep things fresh with that system, 
 * so we can update/add/remove courses within each system separately.
 */
function my_taxonomies_system() {
    $labels = array(
        'name'              => _x( 'Systems', 'taxonomy general name' ),
        'singular_name'     => _x( 'System', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Systems' ),
        'all_items'         => __( 'All Systems' ),
        'parent_item'       => __( 'Parent System' ),
        'parent_item_colon' => __( 'Parent System:' ),
        'edit_item'         => __( 'Edit System' ), 
        'update_item'       => __( 'Update System' ),
        'add_new_item'      => __( 'Add New System' ),
        'new_item_name'     => __( 'New System' ),
        'menu_name'         => __( 'External Systems' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'capabilities' => array(
          'manage_terms' => 'edit_posts',
          'edit_terms' => 'manage_options',
          'delete_terms' => 'manage_options',
          'assign_terms' => 'edit_posts'
        ),
    );
    register_taxonomy( 'external_system', 'course', $args );
}

 /**
 * Learning Partner. Courses can synchronize from multiple different Learning Partners; 
 * e.g. PSA Learning System We use this taxonomy to keep things fresh with that system, 
 * so we can update/add/remove courses within each system separately.
 */
function my_taxonomies_learning_partner() {
    $labels = array(
        'name'              => _x( 'Learning Partners', 'taxonomy general name' ),
        'singular_name'     => _x( 'Learning Partners', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Learning Partners' ),
        'all_items'         => __( 'All Learning Partners' ),
        'parent_item'       => __( 'Parent Learning Partner' ),
        'parent_item_colon' => __( 'Parent Learning Partner:' ),
        'edit_item'         => __( 'Edit Learning Partner' ), 
        'update_item'       => __( 'Update Learning Partner' ),
        'add_new_item'      => __( 'Add New Learning Partner' ),
        'new_item_name'     => __( 'New Learning Partner' ),
        'menu_name'         => __( 'Learning Partners' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'capabilities' => array(
          'manage_terms' => 'edit_posts',
          'edit_terms' => 'manage_options',
          'delete_terms' => 'manage_options',
          'assign_terms' => 'edit_posts'
        ),
    );  
    register_taxonomy( 'learning_partner', 'course', $args );
}

/**
 * Development Partners
 */
function my_taxonomies_development_partner() {
    $labels = array(
        'name'              => _x( 'Development Partners', 'taxonomy general name' ),
        'singular_name'     => _x( 'Development Partner', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Development Partners' ),
        'all_items'         => __( 'All Development Partners' ),
        'edit_item'         => __( 'Edit Development Partner' ),
        'update_item'       => __( 'Update Development Partner' ),
        'add_new_item'      => __( 'Add New Development Partner' ),
        'new_item_name'     => __( 'New Development Partner' ),
        'menu_name'         => __( 'Development Partners' ),
    );
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'capabilities'      => array(
            'manage_terms' => 'edit_posts',
            'edit_terms'   => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_posts',
        ),
    );
    register_taxonomy( 'development_partner', 'course', $args );
}

/**
 * Course Categories
 */
// function my_taxonomies_course_category() {
//     $labels = array(
//         'name'              => _x( 'Course Categories', 'taxonomy general name' ),
//         'singular_name'     => _x( 'Course Category', 'taxonomy singular name' ),
//         'search_items'      => __( 'Search Course Categories' ),
//         'all_items'         => __( 'All Course Categories' ),
//         'parent_item'       => __( 'Parent Course Category' ),
//         'parent_item_colon' => __( 'Parent Course Category:' ),
//         'edit_item'         => __( 'Edit Course Category' ), 
//         'update_item'       => __( 'Update Course Category' ),
//         'add_new_item'      => __( 'Add New Course Category' ),
//         'new_item_name'     => __( 'New Course Category' ),
//         'menu_name'         => __( 'Course Categories' ),
//     );
//     $args = array(
//         'labels' => $labels,
//         'hierarchical' => true,
//         'show_in_rest' => true,
//     );
//     register_taxonomy( 'course_category', 'course', $args );
// }

/**
 * Delivery Methods
 */
function my_taxonomies_course_delivery_method() {
    $labels = array(
        'name'              => _x( 'Delivery Methods', 'taxonomy general name' ),
        'singular_name'     => _x( 'Delivery Method', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Delivery Methods' ),
        'all_items'         => __( 'All Delivery Methods' ),
        'parent_item'       => __( 'Parent Delivery Method' ),
        'parent_item_colon' => __( 'Parent Delivery Method:' ),
        'edit_item'         => __( 'Edit Delivery Method' ), 
        'update_item'       => __( 'Update Delivery Method' ),
        'add_new_item'      => __( 'Add New Delivery Method' ),
        'new_item_name'     => __( 'New Delivery Method' ),
        'menu_name'         => __( 'Delivery Methods' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'capabilities' => array(
          'manage_terms' => 'edit_posts',
          'edit_terms' => 'manage_options',
          'delete_terms' => 'manage_options',
          'assign_terms' => 'edit_posts'
        ),
    );
    register_taxonomy( 'delivery_method', 'course', $args );
}


/** 
 * Course keywords for more targeted searches
 */
function my_taxonomies_course_keywords() {
    $labels = array(
        'name'              => _x( 'Keywords', 'taxonomy general name' ),
        'singular_name'     => _x( 'Keyword', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Keywords' ),
        'all_items'         => __( 'All Keywords' ),
        'parent_item'       => __( 'Parent Keyword' ),
        'parent_item_colon' => __( 'Parent Keyword:' ),
        'edit_item'         => __( 'Edit Keyword' ), 
        'update_item'       => __( 'Update Keyword' ),
        'add_new_item'      => __( 'Add New Keyword' ),
        'new_item_name'     => __( 'New Keyword' ),
        'menu_name'         => __( 'Keywords' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'show_in_rest' => true,
    );
    register_taxonomy( 'keywords', 'course', $args );
}

/** 
 * Course topics aligning with Corporate Learning Framework
 */
function my_taxonomies_course_topics() {
    $labels = array(
        'name'              => _x( 'Topics', 'taxonomy general name' ),
        'singular_name'     => _x( 'Topic', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Topics' ),
        'all_items'         => __( 'All Topics' ),
        'parent_item'       => __( 'Parent Topic' ),
        'parent_item_colon' => __( 'Parent Topic' ),
        'edit_item'         => __( 'Edit Topic' ), 
        'update_item'       => __( 'Update Topic' ),
        'add_new_item'      => __( 'Add New Topic' ),
        'new_item_name'     => __( 'New Topic' ),
        'menu_name'         => __( 'Topics' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'capabilities' => array(
          'manage_terms' => 'edit_posts',
          'edit_terms' => 'manage_options',
          'delete_terms' => 'manage_options',
          'assign_terms' => 'edit_posts'
        ),
    );
    register_taxonomy( 'topics', 'course', $args );
}

/** 
 * Course audience aligning with Corporate Learning Framework
 */
function my_taxonomies_course_audience() {
    $labels = array(
        'name'              => _x( 'Audiences', 'taxonomy general name' ),
        'singular_name'     => _x( 'Audience', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Audiences' ),
        'all_items'         => __( 'All Audiences' ),
        'parent_item'       => __( 'Parent Audience' ),
        'parent_item_colon' => __( 'Parent Audience' ),
        'edit_item'         => __( 'Edit Audience' ), 
        'update_item'       => __( 'Update Audience' ),
        'add_new_item'      => __( 'Add New Audience' ),
        'new_item_name'     => __( 'New Audiences' ),
        'menu_name'         => __( 'Audiences' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'capabilities' => array(
          'manage_terms' => 'edit_posts',
          'edit_terms' => 'manage_options',
          'delete_terms' => 'manage_options',
          'assign_terms' => 'edit_posts'
        ),
    );
    register_taxonomy( 'audience', 'course', $args );
}

/** 
 * Course journeys
 */
function my_taxonomies_course_journey() {
    $labels = array(
        'name'              => _x( 'Journeys', 'taxonomy general name' ),
        'singular_name'     => _x( 'Journey', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Journeys' ),
        'all_items'         => __( 'All Journeys' ),
        'parent_item'       => __( 'Parent Journey' ),
        'parent_item_colon' => __( 'Parent Journey' ),
        'edit_item'         => __( 'Edit Journey' ), 
        'update_item'       => __( 'Update Journey' ),
        'add_new_item'      => __( 'Add New Journey' ),
        'new_item_name'     => __( 'New Journey' ),
        'menu_name'         => __( 'Journeys' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'capabilities' => array(
          'manage_terms' => 'edit_posts',
          'edit_terms' => 'manage_options',
          'delete_terms' => 'manage_options',
          'assign_terms' => 'edit_posts'
        ),
    );
    register_taxonomy( 'journey', 'course', $args );
}

/** 
 * Now let's initiate all of those awesome taxonomies!
 */

add_action( 'init', 'my_taxonomies_course_journey', 0 );
add_action( 'init', 'my_taxonomies_course_audience', 0 );

add_action( 'init', 'my_taxonomies_course_topics', 0 );
// add_action( 'init', 'my_taxonomies_course_category', 0 );
add_action( 'init', 'my_taxonomies_course_delivery_method', 0 );
add_action( 'init', 'my_taxonomies_course_keywords', 0 );
add_action( 'init', 'my_taxonomies_learning_partner', 0 );
add_action( 'init', 'my_taxonomies_development_partner', 0 );
add_action( 'init', 'my_taxonomies_system', 0 );



// search all taxonomies, based on: http://projects.jesseheap.com/all-projects/wordpress-plugin-tag-search-in-wordpress-23
function lzone_custom_search( $search, $query ) {
  global $wpdb;

  if ( ( $query->is_search() || $query->get('custom_search') ) && ! empty( $query->get('s') ) ) {
      // Sanitize the search term
      $search_term = esc_sql( $query->get('s') );

      // Build the search SQL
      $search = " AND (";
      $search .= "{$wpdb->posts}.post_title LIKE '%{$search_term}%'";
      $search .= " OR {$wpdb->posts}.post_content LIKE '%{$search_term}%'";
      $search .= " OR EXISTS (
          SELECT 1 FROM {$wpdb->term_relationships} tr
          INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
          INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
          WHERE tr.object_id = {$wpdb->posts}.ID
          AND t.name LIKE '%{$search_term}%'
      )";
      $search .= " )";
  }

  return $search;
}
add_filter( 'posts_search', 'lzone_custom_search', 10, 2 );

// Add the custom query variable
function add_custom_query_vars($vars) {
    $vars[] = 'custom_search';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');



function course_tax_template( $tax_template ) {
  global $post;
  $tax_template = dirname( __FILE__ ) . '/taxonomy.php';
  if ( is_tax ( 'learning_partner' ) ) {
    $tax_template = get_stylesheet_directory() . '/taxonomy-learning_partner.php';
  }
  if ( is_tax ( 'external_system' ) ) {
    $tax_template = get_stylesheet_directory() . '/taxonomy-external_system.php';
  }
  if ( is_tax ( 'audience' ) ) {
    $tax_template = get_stylesheet_directory() . '/taxonomy.php';
  }
  if ( is_tax ( 'topics' ) ) {
    $tax_template = get_stylesheet_directory() . '/taxonomy.php';
  }
  if ( is_tax ( 'delivery_method' ) ) {
    $tax_template = get_stylesheet_directory() . '/taxonomy.php';
  }
  if ( is_tax ( 'development_partner' ) ) {
    $tax_template = get_stylesheet_directory() . '/taxonomy.php';
  }
  return $tax_template;
}

add_filter( 'taxonomy_template', 'course_tax_template');


function course_menu() {
	add_submenu_page(
		'edit.php?post_type=course',
		__( 'External Systems Sync', 'ext-sys-sync' ),
		__( 'Systems Sync', 'systems-sync' ),
		'edit_posts',
		'systems-sync',
		'systems_sync'
	);
	add_submenu_page(
		null,
		null,
		null,
		'edit_posts',
		'course_mark_all_private',
		'course_mark_all_private'
	);
	add_submenu_page(
		null,
		null,
		null,
		'edit_posts',
		'course_elm_sync',
		'course_elm_sync'
	);

	add_submenu_page(
		null,
		null,
		null,
		'edit_posts',
		'expired_courses',
		'expired_courses'
	);

}
add_action( 'admin_menu', 'course_menu' );

/**
 * Create an index jumping off point to the system sync processes.
 * Currently both PSA Learning System (ELM) and LearningHUB are being 
 * supported.
 */
function systems_sync() {

	if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    echo '<h1>External Systems Synchronization</h1>';
    echo '<p>The LearningHUB synchronizes with other systems, currently including the ';
    echo '<a href="https://learning.gov.bc.ca/CHIPSPLM/signon.html" target="_blank">PSA Learning System</a>';
    echo '<p>Additionally, running this sync process will examine courses that aren\'t in a sync system which have an ';
    echo 'expiration date set. If the date is older than today, the course is marked private.</p>';
    $psalslink = admin_url('edit.php?noheader=true&post_type=course&page=course_elm_sync');
    echo '<div style="margin-bottom: 1em;">';
    echo '<a href="'.$psalslink.'" ';
    echo 'style="background-color: #222; border-radius: 5px; color: #FFF; display: inline-block; padding: .75em 2em; text-decoration: none;">';
    echo 'Start Systems Sync';
    echo '</a>';
    echo '</div>';


}

function course_elm_sync() {

  if (!current_user_can('edit_posts')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  opcache_reset();

  // Single endpoint URL for fetching the course feed
  $endpoint = 'https://learn.bcpublicservice.gov.bc.ca/learning-hub/bcps-corporate-learning-courses.json';

  // Fetch and process the course feed
  $feed = fetch_course_feed($endpoint);
  if ($feed) {
      sync_courses_with_feed($feed);
  } else {
      // Log or handle error: feed could not be retrieved from the endpoint
      error_log("Failed to fetch course feed from $endpoint");
  }

  $development_partners_endpoint = 'https://learn.bcpublicservice.gov.bc.ca/learning-hub/bcps-development-partners.json';
  $partner_feed = fetch_development_partners_feed($development_partners_endpoint);
  if ($partner_feed !== false) {
      sync_development_partners_with_feed($partner_feed);
  } else {
      error_log("Failed to fetch development partner feed from $development_partners_endpoint");
  }

  $learning_partners_endpoint = 'https://learn.bcpublicservice.gov.bc.ca/learning-hub/bcps-corporate-learning-partners.json';
  $learning_partner_feed = fetch_learning_partners_feed($learning_partners_endpoint);
  if ($learning_partner_feed !== false) {
      sync_learning_partners_with_feed($learning_partner_feed);
  } else {
      error_log("Failed to fetch learning partner feed from $learning_partners_endpoint");
  }

  header('Location: edit.php?noheader=true&post_type=course&page=expired_courses');
  // echo 'Done.';
}

/**
* Fetches and decodes the JSON course feed from the given endpoint URL.
*/
function fetch_course_feed($endpoint) {
  $f = file_get_contents($endpoint);
  if ($f === FALSE) {
      return false; // Handle error appropriately
  }
  $feed = json_decode($f);
  if (json_last_error() !== JSON_ERROR_NONE) {
      return false; // Handle JSON parse error
  }
  return $feed;
}

/**
 * Fetch the development partners feed as an associative array.
 */
function fetch_development_partners_feed($endpoint) {
    $response = file_get_contents($endpoint);
    if ($response === false) {
        return false;
    }

    $partners = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    return $partners;
}

/**
 * Sync the Development Partners taxonomy with the feed data.
 */
function sync_development_partners_with_feed($partners) {
    if ($partners === false || !is_array($partners)) {
        return;
    }

    $existing_terms = get_terms(array(
        'taxonomy'   => 'development_partner',
        'hide_empty' => false,
    ));

    $existing_map = array();
    if (!is_wp_error($existing_terms)) {
        foreach ($existing_terms as $term) {
            $source_id = get_term_meta($term->term_id, 'development_partner_source_id', true);
            if ($source_id) {
                $existing_map[(int) $source_id] = $term;
            }
        }
    }

    $synced_ids = array();

    foreach ($partners as $partner) {
        $partner_id = isset($partner['id']) ? absint($partner['id']) : 0;
        $partner_name = isset($partner['name']) ? sanitize_text_field($partner['name']) : '';

        if (!$partner_id || empty($partner_name)) {
            continue;
        }

        if (!empty($partner['status']) && strtolower($partner['status']) !== 'active') {
            continue;
        }

        $synced_ids[] = $partner_id;
        $description = !empty($partner['description']) ? wp_kses_post($partner['description']) : '';
        $slug = sanitize_title($partner_name);

        if (isset($existing_map[$partner_id])) {
            $term_id = $existing_map[$partner_id]->term_id;
            $update_result = wp_update_term($term_id, 'development_partner', array(
                'name'        => $partner_name,
                'slug'        => $slug,
                'description' => $description,
            ));

            if (is_wp_error($update_result)) {
                error_log('Failed to update Development Partner term: ' . $update_result->get_error_message());
                continue;
            }
        } else {
            $insert_result = wp_insert_term($partner_name, 'development_partner', array(
                'slug'        => $slug,
                'description' => $description,
            ));

            if (is_wp_error($insert_result)) {
                error_log('Failed to insert Development Partner term: ' . $insert_result->get_error_message());
                continue;
            }

            $term_id = $insert_result['term_id'];
            $term_obj = get_term($term_id, 'development_partner');
            if ($term_obj && !is_wp_error($term_obj)) {
                $existing_map[$partner_id] = $term_obj;
            }
        }

        update_term_meta($term_id, 'development_partner_source_id', $partner_id);
        update_term_meta($term_id, 'development_partner_status', sanitize_text_field($partner['status']));
        update_term_meta($term_id, 'development_partner_type', sanitize_text_field($partner['type']));
        update_term_meta($term_id, 'development_partner_url', !empty($partner['url']) ? esc_url_raw($partner['url']) : '');
        update_term_meta($term_id, 'development_partner_contact_name', isset($partner['contact_name']) ? sanitize_text_field($partner['contact_name']) : '' );
        update_term_meta($term_id, 'development_partner_contact_email', isset($partner['contact_email']) ? sanitize_email($partner['contact_email']) : '' );
    }

    if (!empty($existing_map)) {
        foreach ($existing_map as $source_id => $term_obj) {
            if (!in_array($source_id, $synced_ids, true)) {
                wp_delete_term($term_obj->term_id, 'development_partner');
            }
        }
    }
}

/**
 * Fetch the learning partners feed as an associative array.
 */
function fetch_learning_partners_feed($endpoint) {
    $response = file_get_contents($endpoint);
    if ($response === false) {
        return false;
    }

    $partners = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    return $partners;
}

/**
 * Sync the Learning Partners taxonomy with the feed data.
 */
function sync_learning_partners_with_feed($partners) {
    if ($partners === false || !is_array($partners)) {
        return;
    }

    $existing_terms = get_terms(array(
        'taxonomy'   => 'learning_partner',
        'hide_empty' => false,
    ));

    $existing_map = array();
    if (!is_wp_error($existing_terms)) {
        foreach ($existing_terms as $term) {
            $source_id = get_term_meta($term->term_id, 'learning_partner_source_id', true);
            if ($source_id) {
                $existing_map[(int) $source_id] = $term;
            }
        }
    }

    $synced_ids = array();

    foreach ($partners as $partner) {
        $partner_id = isset($partner['id']) ? absint($partner['id']) : 0;
        $partner_name = isset($partner['name']) ? sanitize_text_field($partner['name']) : '';

        if (!$partner_id || empty($partner_name)) {
            continue;
        }

        if (!empty($partner['status']) && strtolower($partner['status']) !== 'active') {
            continue;
        }

        $synced_ids[] = $partner_id;
        $description = !empty($partner['description']) ? wp_kses_post($partner['description']) : '';
        $slug = !empty($partner['slug']) ? sanitize_title($partner['slug']) : sanitize_title($partner_name);

        if (isset($existing_map[$partner_id])) {
            $term_id = $existing_map[$partner_id]->term_id;
            $update_result = wp_update_term($term_id, 'learning_partner', array(
                'name'        => $partner_name,
                'slug'        => $slug,
                'description' => $description,
            ));

            if (is_wp_error($update_result)) {
                error_log('Failed to update Learning Partner term: ' . $update_result->get_error_message());
                continue;
            }
        } else {
            $insert_result = wp_insert_term($partner_name, 'learning_partner', array(
                'slug'        => $slug,
                'description' => $description,
            ));

            if (is_wp_error($insert_result)) {
                error_log('Failed to insert Learning Partner term: ' . $insert_result->get_error_message());
                continue;
            }

            $term_id = $insert_result['term_id'];
            $term_obj = get_term($term_id, 'learning_partner');
            if ($term_obj && !is_wp_error($term_obj)) {
                $existing_map[$partner_id] = $term_obj;
            }
        }

        update_term_meta($term_id, 'learning_partner_source_id', $partner_id);
        update_term_meta($term_id, 'learning_partner_status', sanitize_text_field($partner['status']));
        update_term_meta($term_id, 'learning_partner_link', !empty($partner['link']) ? esc_url_raw($partner['link']) : '');
        update_term_meta($term_id, 'learning_partner_contact', isset($partner['employee_facing_contact']) ? sanitize_text_field($partner['employee_facing_contact']) : '' );
    }

    if (!empty($existing_map)) {
        foreach ($existing_map as $source_id => $term_obj) {
            if (!in_array($source_id, $synced_ids, true)) {
                wp_delete_term($term_obj->term_id, 'learning_partner');
            }
        }
    }
}

/**
 * Syncs the courses with the provided feed.
 */
function sync_courses_with_feed($feed) {
  // Create an associative array for feed courses by their _course_id (cast to integer)
  $feed_map = [];
  foreach ($feed->items as $feedcourse) {
      if (!empty($feedcourse->_course_id)) {
          $feed_map[$feedcourse->_course_id] = $feedcourse; // Cast to integer
      }
  }

  // Get all existing courses in the system
  $courses = get_posts(array(
      'post_type' => 'course',
      'numberposts' => -1,
      'post_status' => 'any',
  ));

  // Build an index of existing course IDs in the system
  $courseindex = [];
  foreach ($courses as $course) {
      // Retrieve elm_course_id using get_post_meta() and cast it to integer
      $elm_course_id = get_post_meta($course->ID, 'elm_course_id', true);
      if (!empty($elm_course_id)) {
          $courseindex[$elm_course_id] = $course->ID;
      }
  }

  // PHASE 1: Add new courses from the feed that aren't in the system
  foreach ($feed->items as $feedcourse) {
      if (!empty($feedcourse->_course_id) && !array_key_exists($feedcourse->_course_id, $courseindex)) {
          // Set external_system from the feed record
          $external_system = !empty($feedcourse->_platform) ? sanitize_text_field($feedcourse->_platform) : 'default-system';

          // Create the new course
          // Convert markdown to HTML using Parsedown
          $parsedown = new Parsedown();
          $parsedown->setSafeMode(true); // Enable safe mode to prevent XSS
          $content_html = $feedcourse->summary;
          if (class_exists('Parsedown')) {
              $content_html = $parsedown->text($content_html);
          }
          // Allow safe HTML tags while stripping potentially dangerous ones
          $content_html = wp_kses_post($content_html);
          
          // Create excerpt from plain text version
          $excerpt_text = wp_strip_all_tags($feedcourse->summary);
          $excerpt = substr($excerpt_text, 0, 100);
          
          $new_course = array(
              'post_title'   => sanitize_text_field($feedcourse->title),
			  'post_name'   => sanitize_text_field($feedcourse->_slug),
              'post_type'    => 'course',
              'post_status'  => 'publish',
              'post_content' => $content_html,
              'post_excerpt' => $excerpt,
              'meta_input'   => array(
                  'course_link'        => esc_url_raw($feedcourse->url),
                  'elm_course_code'    => $feedcourse->id,
                  'elm_course_id'      => $feedcourse->_course_id,
                  'elm_date_published' => $feedcourse->date_published,
                  'elm_date_modified'  => $feedcourse->date_modified
              )
          );
          
          // Add persistence fields if _persistent is set to "yes"
          if (!empty($feedcourse->_persistent) && $feedcourse->_persistent === 'yes') {
              if (!empty($feedcourse->_persist_state)) {
                  $new_course['meta_input']['persist_state'] = sanitize_text_field($feedcourse->_persist_state);
              }
              if (!empty($feedcourse->_persist_message)) {
                if (class_exists('Parsedown')) {
                    $persistent_message = $parsedown->text(sanitize_text_field($feedcourse->_persist_message));
                    $persistent_message = wp_kses_post($persistent_message);
                    $new_course['meta_input']['persist_message'] = $persistent_message;
                } else {
                    error_log("Parsedown class not found, using raw text for persist_message.");
                    $new_course['meta_input']['persist_message'] = 'Something went wrong with the course persistence. Please file a CRM ticket and let us know.';
                }

              }
          }
          
          $post_id = wp_insert_post($new_course);

          // Track the new course
          $courseindex[$feedcourse->_course_id] = $post_id;

          // Set taxonomies
          wp_set_object_terms($post_id, sanitize_text_field($feedcourse->delivery_method), 'delivery_method', false);
          wp_set_object_terms($post_id, sanitize_text_field($feedcourse->_learning_partner), 'learning_partner', false);
          wp_set_object_terms($post_id, sanitize_text_field($feedcourse->_topic), 'topics', true);
          wp_set_object_terms($post_id, sanitize_text_field($feedcourse->_audience), 'audience', true);
          wp_set_object_terms($post_id, $external_system, 'external_system', false); // Set external system from the feed record
          $development_partner_terms = get_development_partner_terms_from_feed(isset($feedcourse->_development_partners) ? $feedcourse->_development_partners : []);
          if (!empty($development_partner_terms)) {
              wp_set_object_terms($post_id, $development_partner_terms, 'development_partner', false);
          }

          if (!empty($feedcourse->_keywords)) {
              $keywords = explode(',', $feedcourse->_keywords);
              wp_set_object_terms($post_id, $keywords, 'keywords', true);
          }

          error_log("Created new course: " . $feedcourse->title . " (ID: $post_id) with external_system: $external_system");
      }
  }

  // PHASE 2: Update existing courses based on the feed
  foreach ($courses as $course) {
	  
      $elm_course_id = get_post_meta($course->ID, 'elm_course_id', true);

      // Skip if elm_course_id is empty or not in the feed
      if (empty($elm_course_id) || !array_key_exists($elm_course_id, $feed_map)) {
          continue;
      }

      $feedcourse = $feed_map[$elm_course_id];
      $external_system = !empty($feedcourse->_platform) ? sanitize_text_field($feedcourse->_platform) : 'default-system'; // Set external system from the feed record
      $courseupdated = false;

      // Compare and update post details
      if ($feedcourse->title != $course->post_title) {
          $course->post_title = $feedcourse->title;
          $courseupdated = true;
      }
      if ($feedcourse->_slug != $course->post_name) {
          $course->post_name = $feedcourse->_slug;
          $courseupdated = true;
      }

      // Convert markdown to HTML for comparison and update
      $content_html = $feedcourse->summary;
      if (class_exists('Parsedown')) {
          $parsedown = new Parsedown();
          $parsedown->setSafeMode(true); // Enable safe mode to prevent XSS
          $content_html = $parsedown->text($content_html);
      }
      $content_html = wp_kses_post($content_html);
      
      if ($content_html != $course->post_content) {
          $course->post_content = $content_html;
          // Create excerpt from plain text version
          $excerpt_text = wp_strip_all_tags($feedcourse->summary);
          $course->post_excerpt = substr($excerpt_text, 0, 100);
          $courseupdated = true;
      }

      if ($course->post_status == 'private') {
          $course->post_status = 'publish';
          $courseupdated = true;
      }

      if ($courseupdated) {
          wp_update_post(array(
              'ID'          => $course->ID,
              'post_title'  => $course->post_title,
			  'post_name'   => $course->post_name,
              'post_content'=> $course->post_content,
              'post_excerpt'=> $course->post_excerpt,
              'post_status' => $course->post_status,
          ));
          error_log("Updated course: " . $course->post_title . " (ID: " . $course->ID . ")");
      }

      // Update post meta and taxonomies
      update_meta_if_changed($course->ID, 'course_link', $feedcourse->url);
      update_meta_if_changed($course->ID, 'elm_course_code', $feedcourse->id);
      update_meta_if_changed($course->ID, 'elm_date_published', $feedcourse->date_published);
      update_meta_if_changed($course->ID, 'elm_date_modified', $feedcourse->date_modified);
      
      
      // Update persistence fields if _persistent is set to "yes"
      if (!empty($feedcourse->_persistent) && $feedcourse->_persistent === 'yes') {
          if (!empty($feedcourse->_persist_state)) {
              update_meta_if_changed($course->ID, 'persist_state', $feedcourse->_persist_state);
          }
          if (!empty($feedcourse->_persist_message)) {
            $persistent_message = $parsedown->text(sanitize_text_field($feedcourse->_persist_message));
            $persistent_message = wp_kses_post($persistent_message);
            update_meta_if_changed($course->ID, 'persist_message', $persistent_message);
          }
      } else {
          // If _persistent is not "yes", remove any existing persistence metadata
          delete_post_meta($course->ID, 'persist_state');
          delete_post_meta($course->ID, 'persist_message');
      }

      sync_taxonomy_if_changed($course->ID, 'keywords', explode(',', $feedcourse->_keywords));
      sync_taxonomy_if_changed($course->ID, 'audience', array($feedcourse->_audience));
      sync_taxonomy_if_changed($course->ID, 'topics', array($feedcourse->_topic));
      sync_taxonomy_if_changed($course->ID, 'learning_partner', array($feedcourse->_learning_partner));
      sync_taxonomy_if_changed($course->ID, 'delivery_method', array($feedcourse->delivery_method));
      sync_taxonomy_if_changed($course->ID, 'external_system', array($external_system)); // Update external system
      $development_partner_terms = get_development_partner_terms_from_feed(isset($feedcourse->_development_partners) ? $feedcourse->_development_partners : []);
      sync_taxonomy_if_changed($course->ID, 'development_partner', $development_partner_terms);
  }

  // PHASE 3: Mark any courses not found in the feed as private
  foreach ($courses as $course) {
      $elm_course_id = get_post_meta($course->ID, 'elm_course_id', true);

      if (!empty($elm_course_id) && !array_key_exists($elm_course_id, $feed_map)) {
          wp_update_post(array(
              'ID'          => $course->ID,
              'post_status' => 'private',
          ));
          error_log("Marked course as private: " . $course->post_title . " (ID: " . $course->ID . ")");
      }
  }
}

/**
 * Helper function to update post meta only if the value has changed.
 */
function update_meta_if_changed($post_id, $meta_key, $new_value) {
    $current_value = get_post_meta($post_id, $meta_key, true);
    if ($new_value != $current_value) {
        update_post_meta($post_id, $meta_key, $new_value);
    }
}

/**
 * Helper function to sync taxonomies only if changed.
 */
function sync_taxonomy_if_changed($post_id, $taxonomy, $new_terms) {
    $current_terms = get_the_terms($post_id, $taxonomy);
    $current_term_names = (!empty($current_terms)) ? wp_list_pluck($current_terms, 'name') : [];

    $new_terms = array_filter(array_map('sanitize_text_field', (array) $new_terms));

    $sorted_current = $current_term_names;
    $sorted_new = $new_terms;
    sort($sorted_current);
    sort($sorted_new);

    if ($sorted_current !== $sorted_new) {
        wp_set_object_terms($post_id, $new_terms, $taxonomy, false);
    }
}

/**
 * Map feed-provided development partners to WordPress term names.
 */
function get_development_partner_terms_from_feed($feed_partners) {
    if (empty($feed_partners) || !is_array($feed_partners)) {
        return [];
    }

    $term_names = [];

    foreach ($feed_partners as $partner) {
        if (is_array($partner)) {
            $partner = (object) $partner;
        }

        if (!is_object($partner)) {
            continue;
        }

        $slug = !empty($partner->slug) ? sanitize_title($partner->slug) : '';
        $name = !empty($partner->name) ? sanitize_text_field($partner->name) : '';

        if (!$slug && !$name) {
            continue;
        }

        $term = null;
        if ($slug) {
            $term = get_term_by('slug', $slug, 'development_partner');
        }

        if (!$term && $name) {
            $term = get_term_by('name', $name, 'development_partner');
        }

        if ($term && !is_wp_error($term)) {
            $term_names[] = $term->name;
        }
    }

    return array_values(array_unique($term_names));
}


/**
 * Look through published courses not in the PSALS and check the expiry date
 * and make private if it's past today.
 * 
 */
function expired_courses () {

  if ( !current_user_can( 'edit_posts' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Start by getting all the published courses that are NOT listed as being in the 
  // PSA Learning Curator, or the PSA Learning System.
  //
  $courses = get_posts(array(
      'post_type' => 'course',
      'numberposts' => -1,
      'post_status'    => 'published',
      'tax_query' => array(
          array(
          'taxonomy' => 'external_system',
          'field' => 'slug',
          'terms' => ['psa-learning-system','psa-learning-curator'],
          'operator' => 'NOT IN'
          )
      ))
    );
    $today = date('Y-m-d');
    foreach ($courses as $course) {

      // Does the course have a expiration date set?
      $cuskeys = get_post_custom_keys( $course->ID );
      if(is_array($cuskeys)) {
        if( in_array( 'course_expire', $cuskeys ) ) {

          if($today > $course->course_expire) {
            
            $course->post_status = 'private';
            wp_update_post( $course );
          }
        }
      }
    }
    header('Location: /learninghub/wp-admin/edit.php?post_type=course');
}


/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'courses_meta_boxes_setup' );
add_action( 'load-post-new.php', 'courses_meta_boxes_setup' );

/* Meta box setup function. */
function courses_meta_boxes_setup() {

    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action( 'add_meta_boxes', 'courses_add_post_meta_boxes' );
    /* Save post meta on the 'save_post' hook. */
    add_action( 'save_post', 'course_save_course_link_meta', 10, 2 );
    add_action( 'save_post', 'course_save_course_expire_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function courses_add_post_meta_boxes() {

    add_meta_box(
        'course-link',      // Unique ID
        esc_html__( 'Course Link', 'course-link' ),    // Title
        'course_link_meta_box',   // Callback function
        'course',         // Admin page (or post type)
        'side',         // Context
        'default'         // Priority
    );
    add_meta_box(
        'course-expire',      // Unique ID
        esc_html__( 'Course Expiration', 'course-expire' ),    // Title
        'course_expire_meta_box',   // Callback function
        'course',         // Admin page (or post type)
        'side',         // Context
        'high'         // Priority
    );
}

/* Display the post meta box. */
function course_link_meta_box( $post ) { ?>

    <?php wp_nonce_field( basename( __FILE__ ), 'course_link_nonce' ); ?>
    <div>
        <label for="course-link">
        <?php _e( "A hyperlink to the session registration page for this course.", 'course-link' ); ?></label>
        <br />
        <input class="widefat" 
                type="text" 
                name="course-link" 
                id="course-link" 
                value="<?php echo esc_attr( get_post_meta( $post->ID, 'course_link', true ) ); ?>" 
                size="30" />
    </div>
<?php }
/* Display the post meta box. */
function course_expire_meta_box( $post ) { ?>

    <?php wp_nonce_field( basename( __FILE__ ), 'course_expire_nonce' ); ?>
    <div>
        <label for="course-expire">
        <?php _e( "The date after which this course should be removed from public view. ") ?>
        <?php _e("Only courses not being sync'ed pay attention to this. <br>(e.g., PSALS)", 'course-expire' ); ?>
        </label>
        <br />
        <input class="widefat" 
                type="date" 
                name="course-expire" 
                id="course-expire" 
                value="<?php echo esc_attr( get_post_meta( $post->ID, 'course_expire', true ) ); ?>" 
                size="30" />
    </div>
<?php }

/* Save a meta box’s post metadata. */
function course_save_course_link_meta ( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['course_link_nonce'] ) || !wp_verify_nonce( $_POST['course_link_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

    /* Get the posted data */
    $new_meta_value = ( isset( $_POST['course-link'] ) ? $_POST['course-link'] : '' );

    /* Get the meta key. */
    $meta_key = 'course_link';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && !$meta_value ) {
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );
    /* If the new meta value does not match the old value, update it. */
    } elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
        update_post_meta( $post_id, $meta_key, $new_meta_value );
    /* If there is no new meta value but an old value exists, delete it. */
    } elseif ( !$new_meta_value && $meta_value ) {
        delete_post_meta( $post_id, $meta_key, $meta_value );
    }
}
/* Save a meta box’s post metadata. */
function course_save_course_expire_meta ( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['course_expire_nonce'] ) || !wp_verify_nonce( $_POST['course_expire_nonce'], basename( __FILE__ ) ) ) {
      return $post_id;
  }
  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
  return $post_id;

  /* Get the posted data */
  $new_meta_value = ( isset( $_POST['course-expire'] ) ? $_POST['course-expire'] : '' );

  /* Get the meta key. */
  $meta_key = 'course_expire';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && !$meta_value ) {
      add_post_meta( $post_id, $meta_key, $new_meta_value, true );
  /* If the new meta value does not match the old value, update it. */
  } elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
      update_post_meta( $post_id, $meta_key, $new_meta_value );
  /* If there is no new meta value but an old value exists, delete it. */
  } elseif ( !$new_meta_value && $meta_value ) {
      delete_post_meta( $post_id, $meta_key, $meta_value );
  }
}







/**
 * Plugin class
 **/
if ( ! class_exists( 'CT_TAX_META' ) ) {

    class CT_TAX_META {
    
      public function __construct() {
        //
      }
    
     /*
      * Initialize the class and start calling our hooks and filters
      * @since 1.0.0
     */
     public function init() {
       add_action( 'learning_partner_add_form_fields', array ( $this, 'add_category_image' ), 10, 2 );
       add_action( 'created_learning_partner', array ( $this, 'save_category_image' ), 10, 2 );
       add_action( 'learning_partner_edit_form_fields', array ( $this, 'update_category_image' ), 10, 2 );
       add_action( 'edited_learning_partner', array ( $this, 'updated_category_image' ), 10, 2 );
       add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
       add_action( 'admin_footer', array ( $this, 'add_script' ) );
     }
    
    public function load_media() {
     wp_enqueue_media();
    }
    
     /*
      * Add a form field in the new category page
      * @since 1.0.0
     */
     public function add_category_image ( $taxonomy ) { ?>
       <div class="form-field term-group">
         <label for="category-image-id"><?php _e('Partner Logo', 'twentytwentyone-learning-hub-theme'); ?></label>
         <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
         <div id="category-image-wrapper"></div>
         <p>
           <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
           <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
        </p>
        </div>
        <div class="form-field term-group">
          <label for="partner-url">Partner URL</label>
           <input type="text" id="partner-url" name="partner-url" class="" value="">
        </div>
        <div class="form-field term-group">
          <label for="partner-contact">Partner Contact</label>
           <input type="text" id="partner-contact" name="partner-contact" class="" value="">
        </div>
     <?php
     }
    
     /*
      * Save the form field
      * @since 1.0.0
     */
     public function save_category_image ( $term_id, $tt_id ) {
       if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
         $image = $_POST['category-image-id'];
         add_term_meta( $term_id, 'category-image-id', $image, true );
       }
       if( isset( $_POST['partner-url'] ) && '' !== $_POST['partner-url'] ){
        $url = $_POST['partner-url'];
        add_term_meta( $term_id, 'partner-url', $url, true );
      }
       if( isset( $_POST['partner-contact'] ) && '' !== $_POST['partner-contact'] ){
        $url = $_POST['partner-contact'];
        add_term_meta( $term_id, 'partner-contact', $url, true );
      }
     }
    
     /*
      * Edit the form field
      * @since 1.0.0
     */
     public function update_category_image ( $term, $taxonomy ) { ?>
       <tr class="form-field term-group-wrap">
         <th scope="row">
           <label for="category-image-id"><?php _e('Partner Logo', 'twentytwentyone-learning-hub-theme'); ?></label>
         </th>
         <td>
           <?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
           <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
           <div id="category-image-wrapper">
             <?php if ( $image_id ) { ?>
               <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
             <?php } ?>
           </div>
           <p>
             <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
             <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
           </p>

         </td>
       </tr>
       <tr class="form-field term-group-wrap">
         <th scope="row">
           <label for="category-image-id"><?php _e('Partner URL', 'twentytwentyone-learning-hub-theme'); ?></label>
         </th>
         <td>
         <div class="form-field term-group">
              <?php $url = get_term_meta ( $term -> term_id, 'partner-url', true ); ?>
              <input type="text" id="partner-url" name="partner-url" class="" value="<?= $url ?>">
            </div>
        </td>
        </tr>
       <tr class="form-field term-group-wrap">
         <th scope="row">
           <label for=""><?php _e('Partner Contact', 'twentytwentyone-learning-hub-theme'); ?></label>
         </th>
         <td>
         <div class="form-field term-group">
              <?php $pcontactinfo = get_term_meta ( $term -> term_id, 'partner-contact', true ); ?>
              <input type="text" id="partner-contact" name="partner-contact" class="" value="<?= $pcontactinfo ?>">
            </div>
        </td>
        </tr>
     <?php
     }
    
    /*
     * Update the form field value
     * @since 1.0.0
     */
     public function updated_category_image ( $term_id, $tt_id ) {
       if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
         $image = $_POST['category-image-id'];
         update_term_meta ( $term_id, 'category-image-id', $image );
       } else {
         update_term_meta ( $term_id, 'category-image-id', '' );
       }
       if( isset( $_POST['partner-url'] ) && '' !== $_POST['partner-url'] ){
        $url = $_POST['partner-url'];
        update_term_meta ( $term_id, 'partner-url', $url );
      } else {
        update_term_meta ( $term_id, 'partner-url', '' );
      }
       if( isset( $_POST['partner-contact'] ) && '' !== $_POST['partner-contact'] ){
        $pcinfo = $_POST['partner-contact'];
        update_term_meta ( $term_id, 'partner-contact', $pcinfo );
      } else {
        update_term_meta ( $term_id, 'partner-contact', '' );
      }
     }
    
    /*
     * Add script
     * @since 1.0.0
     */
     public function add_script() { ?>
       <script>
         jQuery(document).ready( function($) {
           function ct_media_upload(button_class) {
             var _custom_media = true,
             _orig_send_attachment = wp.media.editor.send.attachment;
             $('body').on('click', button_class, function(e) {
               var button_id = '#'+$(this).attr('id');
               var send_attachment_bkp = wp.media.editor.send.attachment;
               var button = $(button_id);
               _custom_media = true;
               wp.media.editor.send.attachment = function(props, attachment){
                 if ( _custom_media ) {
                   $('#category-image-id').val(attachment.id);
                   $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                   $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
                 } else {
                   return _orig_send_attachment.apply( button_id, [props, attachment] );
                 }
                }
             wp.media.editor.open(button);
             return false;
           });
         }
         ct_media_upload('.ct_tax_media_button.button'); 
         $('body').on('click','.ct_tax_media_remove',function(){
           $('#category-image-id').val('');
           $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
         });
         // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
         $(document).ajaxComplete(function(event, xhr, settings) {
           var queryStringArr = settings.data.split('&');
           if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
             var xml = xhr.responseXML;
             $response = $(xml).find('term_id').text();
             if($response!=""){
               // Clear the thumb image
               $('#category-image-wrapper').html('');
             }
           }
         });
       });
     </script>
     <?php }
    
      }
    
    $CT_TAX_META = new CT_TAX_META();
    $CT_TAX_META -> init();
    
    }
