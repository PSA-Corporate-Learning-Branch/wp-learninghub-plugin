<?php
/*
Plugin Name: BC Gov Corporate Learning Hub
Plugin URI: https://github.com/allanhaggett/wp-bcgov-learning-hub
Description: A gateway to everything that BC Gov has to offer for learning opportunities.
Author: Allan Haggett <allan.haggett@gov.bc.ca>
Version: 1
Author URI: https://learningcenter.gww.gov.bc.ca/hub
*/


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
    );  
    register_taxonomy( 'learning_partner', 'course', $args );
}

/**
 * Course Categories
 */
function my_taxonomies_course_category() {
    $labels = array(
        'name'              => _x( 'Course Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Course Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Course Categories' ),
        'all_items'         => __( 'All Course Categories' ),
        'parent_item'       => __( 'Parent Course Category' ),
        'parent_item_colon' => __( 'Parent Course Category:' ),
        'edit_item'         => __( 'Edit Course Category' ), 
        'update_item'       => __( 'Update Course Category' ),
        'add_new_item'      => __( 'Add New Course Category' ),
        'new_item_name'     => __( 'New Course Category' ),
        'menu_name'         => __( 'Course Categories' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
    );
    register_taxonomy( 'course_category', 'course', $args );
}

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
 * Now let's initiate all of those awesome taxonomies!
 */

add_action( 'init', 'my_taxonomies_course_category', 0 );
add_action( 'init', 'my_taxonomies_course_delivery_method', 0 );
add_action( 'init', 'my_taxonomies_course_keywords', 0 );
add_action( 'init', 'my_taxonomies_learning_partner', 0 );
add_action( 'init', 'my_taxonomies_system', 0 );



// search all taxonomies, based on: http://projects.jesseheap.com/all-projects/wordpress-plugin-tag-search-in-wordpress-23

function lzone_search_where($where){
    global $wpdb;
    if (is_search())
      $where .= "OR (t.name LIKE '%".get_search_query()."%' AND {$wpdb->posts}.post_status = 'publish')";
    return $where;
  }
  
  function lzone_search_join($join){
    global $wpdb;
    if (is_search())
      $join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
    return $join;
  }
  
  function lzone_search_groupby($groupby){
    global $wpdb;
  
    // we need to group on post ID
    $groupby_id = "{$wpdb->posts}.ID";
    if(!is_search() || strpos($groupby, $groupby_id) !== false) return $groupby;
  
    // groupby was empty, use ours
    if(!strlen(trim($groupby))) return $groupby_id;
  
    // wasn't empty, append ours
    return $groupby.", ".$groupby_id;
  }
  
  add_filter('posts_where','lzone_search_where');
  add_filter('posts_join', 'lzone_search_join');
  add_filter('posts_groupby', 'lzone_search_groupby');



/**
 * Now let's make sure that we're using our own customized template
 * so that courses can show the meta data in a customizable fashion.
 *  
 * #TODO extend this to include archive.php for main index page
 * and also taxonomy pages
 * 
 */
function load_course_template( $template ) {
    global $post;
    if ( 'course' === $post->post_type && locate_template( array( 'single-course.php' ) ) !== $template ) {
        /*
         * This is a 'course' page
         * AND a 'single course template' is not found on
         * theme or child theme directories, so load it
         * from our plugin directory.
         */
        return plugin_dir_path( __FILE__ ) . 'single-course.php';
    }
    return $template;
}

function course_archive_template( $archive_template ) {
     global $post;
     if ( is_post_type_archive ( 'course' ) ) {
          $archive_template = dirname( __FILE__ ) . '/archive-course.php';
     }
     return $archive_template;
}

function course_tax_template( $tax_template ) {
  global $post;
  if ( is_tax ( 'course_category' ) ) {
    $tax_template = dirname( __FILE__ ) . '/taxonomy.php';
  }
  if ( is_tax ( 'learning_partner' ) ) {
    $tax_template = dirname( __FILE__ ) . '/taxonomy-partner.php';
  }
  if ( is_tax ( 'delivery_method' ) ) {
    $tax_template = dirname( __FILE__ ) . '/taxonomy-delivery-method.php';
  }
  if ( is_tax ( 'external_system' ) ) {
    $tax_template = dirname( __FILE__ ) . '/taxonomy-external-system.php';
  }
  return $tax_template;
}

add_filter( 'single_template', 'load_course_template' );
add_filter( 'archive_template', 'course_archive_template');
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
		'curator_sync',
		'curator_sync'
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
    echo '<h1>External Systems Synchronize</h1>';
    echo '<p>The LearningHUB synchronizes with other systems, currently including the ';
    echo '<a href="https://learning.gov.bc.ca/CHIPSPLM/signon.html" target="_blank">PSA Learning System</a> and the ';
    echo '<a href="https://learningcurator.gww.gov.bc.ca/" target="_blank">Learning Curator</a>.</p>';
    $psalslink = admin_url('edit.php?noheader=true&post_type=course&page=course_elm_sync');
    echo '<div style="margin-bottom: 1em;">';
    echo '<a href="'.$psalslink.'" ';
    echo 'style="background-color: #222; border-radius: 5px; color: #FFF; display: inline-block; padding: .75em 2em; text-decoration: none;">';
    echo 'Start Systems Sync';
    echo '</a>';
    echo '</div>';
    // echo '<div>';
    // $curatorlink = admin_url('edit.php?noheader=true&post_type=course&page=curator_sync');
    // echo '<a href="'.$curatorlink.'" ';
    // echo 'style="background-color: #222; color: #FFF; display: inline-block; padding: .75em 2em;">';
    // echo 'Start synchronization with Learning Curator';
    // echo '</a>';
    // echo '</div>';

}

/**
 * Synchronize with the public feed for the PSA Learning System (ELM)
 */
function course_elm_sync () {

  if ( !current_user_can( 'edit_posts' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  // Get the feed and parse it into an array.
  // $f = file_get_contents('https://bigaddposse.com/learning-partner-courses.json');
  $f = file_get_contents('https://learn.bcpublicservice.gov.bc.ca/learning-hub/learning-partner-courses.json');
  $feed = json_decode($f);
  
  // Create a simple index of course names that are in the feed
  // so that we can easily use in_array to compare against while
  // we loop through all the published courses.
  $feedindex = [];
  foreach($feed->items as $feedcourse) {
      array_push($feedindex, $feedcourse->title);
  }
  // Now we can loop through each of the exisiting published courses
  // and check each against the feedindex array.
  //
  // If we find a match, then we can look to getting the info from the feed 
  // and updating anything that needs updating e.g., add/remove keywords/topics.
  // 
  // If there isn't a match, then the course isn't in the feed and needs to 
  // be made private.
  //
  // This loop through published courses only covers updates to exisiting 
  // courses and marking private (removing) courses that aren't in the feed.
  // After this loop is complete we do another run through the individual 
  // courses in the feed to cover adding any new courses that don't exist yet.
  // 

  //
  // Start by getting all the courses that are listed as being in the 
  // PSA Learning System, whatever the status (we even want existing private 
  // courses so that we can simply update and set back to published instead
  // of creating a whole new one.)
  //
  $courses = get_posts(array(
      'post_type' => 'course',
      'numberposts' => -1,
      'post_status'    => 'any',
      'tax_query' => array(
          array(
          'taxonomy' => 'external_system',
          'field' => 'slug',
          'terms' => 'psa-learning-system')
      ))
    );

  //
  // Create the array to array_push the existing course titles into
  $courseindex = [];
  // Loop though all the PSALS courses in the system.
  foreach ($courses as $course) {
    
      // Start by adding all the course titles to the courseindex array so that
      // after this loop runs through, we can loop through the feed again
      // and find the courses that are new and need to be created from scratch.
      array_push($courseindex, $course->post_title);

      // Does the course title match a title that's in the feed?
      if(in_array($course->post_title, $feedindex)) {

          // Get the details for the feedcourse so we can compare
          foreach($feed->items as $f) {
              if($f->title == $course->post_title) {
                $feedcourse = $f;
              }
          }

          // Compare more throughly for any updates.
          // If everything is the same then we're not actually touching the 
          // database at all in this process.
          if($feedcourse->summary != $course->post_content) {
              // update post content
              $course->post_content = $feedcourse->summary;
              $course->post_excerpt = $feedcourse->summary;
          }
          if($feedcourse->url != $course->course_link) {
              update_post_meta( $course->ID, 'course_link', $feedcourse->url );
          }
          // Make sure it's published just in case we're matching against a 
          // course that is currently private.
          $course->post_status = 'publish';

          // That's it for core details, so let's update the post.
          // After this we're updating the taxonomies which happens with 
          // separate functions.
          wp_update_post($course);

          // Get the categories for this course from the feed
          $feedcats = explode(',', $feedcourse->tags);
          // Load up the categories currently associated with the course
          $coursecats = get_the_terms($course->ID,'course_category');
          if(!empty($coursecats)) {
            // Update the course with the feed categories.
            wp_set_object_terms( $course->ID, $feedcats, 'course_category', false);
            // But now we also need to account for categories that have been 
            // removed, so we quickly create an index of the existing ones to 
            // match against.
            $ccindex = [];
            foreach($coursecats as $cc) {
              array_push($ccindex,$cc->name);
            }
            // Loop through each of the existing cats so as to remove terms 
            // which don't exist in the terms in the feed.
            foreach($coursecats as $cc) {
                if(!in_array($cc->name, $feedcats)) {
                    // The name of this course cat isn't in the feed cats
                    // Delete the old category!
                    wp_remove_object_terms( $course->ID, $cc->name, 'course_category' );
                }
            }
          }

          // Repeat the process above but for keywords instead of categories.
          // Get the keywords for this course from the feed.
          $feedkeys = explode(',', $feedcourse->_keywords);
          // Load up the categories currently associated with the course.
          $coursekeys = get_the_terms($course->ID,'keywords');
          if(!empty($coursekeys)) {
            // Update the course with the feed keywords.
            wp_set_object_terms( $course->ID, $feedkeys, 'keywords', false);
            // But now we also need to account for keywords that have been 
            // removed, so we quickly create an index of the existing ones to 
            // match against.
            $ckindex = [];
            foreach($coursekeys as $kc) {
              array_push($ckindex,$kc->name);
            }
            // Loop through each of the existing keywords so as to remove terms 
            // which don't exist in the terms in the feed
            foreach($coursekeys as $ck) {
                if(!in_array($ck->name, $ckindex)) {
                    // The name of this course key isn't in the feed keys
                    // Delete the old keyword!
                    wp_remove_object_terms( $course->ID, $ck->name, 'keywords' );

                }
            }
          }
          // Coming into the home stretch updating the partner and delivery method.
          $coursepartner = get_the_terms($course->ID,'learning_partner');
          // There's only ever one partner #TODO support multiple partners?
          if($coursepartner[0]->name != $feedcourse->_learning_partner) {
              wp_set_object_terms( $course->ID, sanitize_text_field($feedcourse->_learning_partner), 'learning_partner', false);
          }
          // There's only ever one delivery method
          $coursemethod = get_the_terms($course->ID,'delivery_method');
          if($coursemethod[0]->name != $feedcourse->delivery_method) {
              wp_set_object_terms( $course->ID, sanitize_text_field($feedcourse->delivery_method), 'delivery_method', false);
          }

      } else { // Does the course title match a title that's in the feed?

          // This course is not in the feed anymore.
          // Make it PRIVATE.
          $course->post_status = 'private';
          wp_update_post( $course );

      }
  }

  // Next, let's loop through the feed again, this time looking at the newly created
  // $courseindex array with just the published course names in it for easy lookup
  //
  // If the course doesn't exist within the catalog yet, then we create it!
  //
  foreach($feed->items as $feedcourse) {

      if(!in_array($feedcourse->title, $courseindex) && !empty($feedcourse->title)) {

          // This course isn't in the list of published courses
          // so it is new, so we need to create this course from scratch.
          // Set up the new course with basic settings in place
          $new_course = array(
              'post_title' => sanitize_text_field($feedcourse->title),
              'post_type' => 'course',
              'post_status' => 'publish', 
              'post_content' => sanitize_text_field($feedcourse->summary),
              'post_excerpt' => substr(sanitize_text_field($feedcourse->summary), 0, 100),
              'meta_input'   => array(
                  'course_link' => esc_url_raw($feedcourse->url),
                  'elm_course_code' => $feedcourse->id
              )
          );
          // Actually create the new post so that we can move on 
          // to updating it with taxonomy etc
          $post_id = wp_insert_post( $new_course );

          wp_set_object_terms( $post_id, sanitize_text_field($feedcourse->delivery_method), 'delivery_method', false);
          wp_set_object_terms( $post_id, sanitize_text_field($feedcourse->_learning_partner), 'learning_partner', false);
          wp_set_object_terms( $post_id, 'PSA Learning System', 'external_system', false);

          if(!empty($feedcourse->_keywords)) {
            $keywords = explode(',', $feedcourse->_keywords);
            wp_set_object_terms( $post_id, $keywords, 'keywords', true);
          }
          if(!empty($feedcourse->tags)) {
            $cats = explode(',', $feedcourse->tags);
            wp_set_object_terms( $post_id, $cats, 'course_category', true);
          }

      } 
      // otherwise, we've already dealt with things in the previous loop 
      // so do nothing else
  }

  // header('Location: /learninghub/wp-admin/edit.php?post_type=course');
  header('Location: edit.php?noheader=true&post_type=course&page=curator_sync');
}

/**
 * Synchronize with the public feed for the Learning Curator
 * https://learningcurator.gww.gov.bc.ca/
 */
function curator_sync () {

  if ( !current_user_can( 'edit_posts' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  // Get the feed and parse it into an array.
  $f = file_get_contents('https://learningcurator.ca/pathways/jsonfeed');
  // $f = file_get_contents('https://learningcurator.gww.gov.bc.ca/pathways/jsonfeed');
  $feed = json_decode($f);
  
  // Create a simple index of course names that are in the feed
  // so that we can easily use in_array to compare against while
  // we loop through all the published courses.
  $feedindex = [];
  foreach($feed->pathways as $feedcourse) {
    array_push($feedindex, htmlentities(trim($feedcourse->name)));
  }

  // Now we can loop through each of the exisiting published courses
  // and check each against the feedindex array.
  //
  // If we find a match, then we can look to getting the info from the feed 
  // and updating anything that needs updating e.g., add/remove keywords/topics.
  // 
  // If there isn't a match, then the course isn't in the feed and needs to 
  // be made private.
  //
  // This loop through published courses only covers updates to exisiting 
  // courses and marking private (removing) courses that aren't in the feed.
  // After this loop is complete we do another run through the individual 
  // courses in the feed to cover adding any new courses that don't exist yet.
  // 

  //
  // Start by getting all the courses that are listed as being in the 
  // PSA Learning Curator, whatever the status (we even want existing private 
  // courses so that we can simply update and set back to published instead
  // of creating a whole new one.)
  //
  $courses = get_posts(array(
      'post_type' => 'course',
      'numberposts' => -1,
      'post_status'    => 'any',
      'tax_query' => array(
          array(
          'taxonomy' => 'external_system',
          'field' => 'slug',
          'terms' => 'psa-learning-curator')
      ))
    );

  //
  // Create the array to array_push the existing course titles into
  $courseindex = [];
  // Loop though all the PSALS courses in the system.
  foreach ($courses as $course) {
    
      // Start by adding all the course titles to the courseindex array so that
      // after this loop runs through, we can loop through the feed again
      // and find the courses that are new and need to be created from scratch.
      array_push($courseindex, htmlentities(trim($course->post_title)));

      // Does the course title match a title that's in the feed?
      if(in_array(htmlentities(trim($course->post_title)), $feedindex)) {

          // Get the details for the feedcourse so we can compare
          foreach($feed->pathways as $f) {
              if(trim($f->name) == trim($course->post_title)) {
                $feedcourse = $f;
              }
          }
          
          // Compare more throughly for any updates.
          // If everything is the same then we're not actually touching the 
          // database at all in this process.
          if($feedcourse->objective != $course->post_content) {
              // update post content
              $course->post_content = $feedcourse->objective;
              $course->post_excerpt = $feedcourse->objective;
          }
          $fcurl = 'https://learningcurator.gww.gov.bc.ca/p/' . $feedcourse->slug;
          if($fcurl != $course->course_link) {
              update_post_meta( $course->ID, 'course_link', $fcurl );
          }
          // Make sure it's published just in case we're matching against a 
          // course that is currently private.
          $course->post_status = 'publish';

          // That's it for core details, so let's update the post.
          // After this we're updating the taxonomies which happens with 
          // separate functions.
          wp_update_post($course);


          #TODO update topics and maybe keywords too?


          // Coming into the home stretch updating the partner and delivery method.
          $coursepartner = get_the_terms($course->ID,'learning_partner');
          // There's only ever one partner #TODO support multiple partners?
          if($coursepartner[0]->name != 'Learning Centre') {
              wp_set_object_terms( $course->ID, 'Learning Centre', 'learning_partner', false);
          }


      } else { // Does the course title match a title that's in the feed?

          // This course is not in the feed anymore.
          // Make it PRIVATE.
          $course->post_status = 'private';
          wp_update_post( $course );

      }
  }

  // Next, let's loop through the feed again, this time looking at the newly created
  // $courseindex array with just the published course names in it for easy lookup
  //
  // If the course doesn't exist within the catalog yet, then we create it!
  //
  foreach($feed->pathways as $feedcourse) {

      if(!in_array($feedcourse->name, $courseindex) && !empty($feedcourse->name)) {

          // This course isn't in the list of published courses
          // so it is new, so we need to create this course from scratch.
          // Set up the new course with basic settings in place
          $fcurl = 'https://learningcurator.gww.gov.bc.ca/p/' . $feedcourse->slug;
          $new_course = array(
              'post_title' => trim($feedcourse->name),
              'post_type' => 'course',
              'post_status' => 'publish', 
              'post_content' => sanitize_text_field($feedcourse->objective),
              'post_excerpt' => substr(sanitize_text_field($feedcourse->objective), 0, 100),
              'meta_input'   => array(
                  'course_link' => esc_url_raw($fcurl)
              )
          );
          // Actually create the new post so that we can move on 
          // to updating it with taxonomy etc
          $post_id = wp_insert_post( $new_course );

          wp_set_object_terms( $post_id, 'Curated Pathway', 'delivery_method', false);
          // wp_set_object_terms( $post_id, sanitize_text_field($feedcourse->_learning_partner), 'learning_partner', false);
          wp_set_object_terms( $post_id, 'Learning Centre', 'learning_partner', false);
          wp_set_object_terms( $post_id, 'PSA Learning Curator', 'external_system', false);


      } 
      // otherwise, we've already dealt with things in the previous loop 
      // so do nothing else
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
    $new_meta_value = ( isset( $_POST['course-link'] ) ? $_POST['course-link'] : ’ );

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
