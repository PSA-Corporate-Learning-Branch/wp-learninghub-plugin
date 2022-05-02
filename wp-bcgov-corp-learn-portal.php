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
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
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

}
add_action( 'admin_menu', 'course_menu' );

/**
 * Synchronize with the public feed for the PSA Learning System (ELM)
 */
function systems_sync() {

	if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    echo '<h1>External Systems Synchronize</h1>';
    echo '<p>Here we make all courses from the chosen external system ';
    echo 'private so that we can selectively reenable them ';
    echo 'when we read the associated feed of courses and compare it ';
    echo 'to what we already have. If the course exists, we check for updates and make ';
    echo 'those accordingly. If the course does not exist, we create it.</p>';
    echo '<p><strong>*NOTE</strong> that if the course existed previously, but is now no ';
    echo 'longer in the feed, it will remain set to "private" and not published on the site.</p>';
    echo '<div>';
    $go = admin_url('edit.php?noheader=true&post_type=course&page=course_mark_all_private');
    echo '<a href="'.$go.'" ';
    echo 'style="background-color: #222; color: #FFF; display: inline-block; padding: .75em 2em;">';
    echo 'Start synchronization with PSA Learning System';
    echo '</a>';
    echo '</div>';

}

function course_mark_all_private () {
    
    if ( !current_user_can( 'edit_posts' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    /**
     * This process generally takes longer that 30 seconds, at least on the OpenShift platform,
     * so we need to set the timeout to longer than that. We're going to double that here to 
     * 60 seconds, because if it takes longer than that, I should think that we might reconsider
     * the approach here and maybe do this in batches?
     */
    set_time_limit(60);

    /**
     * First let's make every page private so that if the course is no longer in the catalog, 
     * that it gets removed from the listing here. Note that we're just making these courses
     * private, and NOT deleting them. We're going to loop through the source catalog after 
     * this, and if the post already exists and nothing has changed, then we just make it 
     * published again and move on. We could just delete them, and may change to that in 
     * the future, depending on feedback.
     * 
     */
    $all_posts = get_posts(array(
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
    /**
     * In order to actually synchronize and not just add new terms, we go through each taxonomy
     * and delete all of its terms. If we don't do this, then we go through our feed and update
     * we will _only add_ new terms to whatever course we're looking at, instead of removing them
     * For example, if we change the learning partner of a course, without this we'd have courses 
     * that belong to many partners (its history, if you will). This is expensive but necessary
     * unless we take another look at straight-up deleting the courses and re-creating them
     * wholesale upon each sync (daily). This might work, but the internal wordpress IDs would
     * start to inflate somewhat dramatically... imagine after a year or daily syncs where a couple
     * hundred courses are created 260x200=52000 posts per year???
     */
  
    foreach ($all_posts as $single_post){
        $single_post->post_status = 'private';
        wp_delete_object_term_relationships( $single_post->ID, 'course_category' );
        wp_delete_object_term_relationships( $single_post->ID, 'learning_partner' );
        wp_delete_object_term_relationships( $single_post->ID, 'keywords' );
        wp_delete_object_term_relationships( $single_post->ID, 'delivery_method' );
        wp_update_post( $single_post );
    }
    echo '<div><a href="' . admin_url('edit.php?post_type=course&page=course_elm_sync'). '" ';
    echo 'style="background-color: #222; color: #FFF; display: inline-block; padding: .75em 2em;">';
    echo 'Run Sync</a></div>';
    // $go = 'Location: ' . admin_url('edit.php?post_type=course&page=course_elm_sync');
    // header($go);
}

function course_elm_sync () {
  if ( !current_user_can( 'edit_posts' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  /**
   * Now that all those courses are private and have had their taxonomy terms stripped, 
   * let's grab the public listing of courses from the PSA Learning System and loop 
   * through those, updating existing ones as required and publishing new ones.
   * Old feed:
   * https://learn.bcpublicservice.gov.bc.ca/learningcentre/courses/feed.json
   */
  
  /**
   * This process generally takes longer that 30 seconds, at least on the OpenShift platform,
   * so we need to set the timeout to longer than that. We're going to double that here to 
   * 60 seconds, because if it takes longer than that, I should think that we might reconsider
   * the approach here and maybe do this in batches?
   */
  set_time_limit(60);

  $feed = file_get_contents('https://learn.bcpublicservice.gov.bc.ca/learning-hub/learning-partner-courses.json');
  $courses = json_decode($feed);
  echo '<h3>' . count($courses->items) . ' Courses.</h3>';

  $existingcourses = [];
  $newcourses = [];
  foreach($courses->items as $course) {

      if(!empty($course->title)) {
          $existing = post_exists($course->title);
          if($existing) {
              // Get existing course details
              $existingcourse = get_post($existing);
              // Check the basics to see if details match; if they don't
              // then update them wile incrementing the $updated variable
              // so that we can show which courses have been updated
              // in the UI
              if($existingcourse->post_content != $course->summary) {

                  $existingcourse->post_content = $course->summary;
                  // set updated to 1 so that we know to add this course to 
                  // the updated courses list that we will show in the UI
                  $updated = 1;
              }

              $cats = explode(',', $course->tags);
              foreach($cats as $cat) {
                  $catesc = sanitize_text_field($cat);
                  wp_set_object_terms( $existingcourse->ID, $catesc, 'course_category', true);
              }
              $keywords = explode(',', $course->_keywords);
              foreach($keywords as $key) {
                  $keyesc = sanitize_text_field($key);
                  wp_set_object_terms( $existingcourse->ID, $key, 'keywords', true);
              }
              wp_set_object_terms( $existingcourse->ID, sanitize_text_field($course->delivery_method), 'delivery_method', false);
              wp_set_object_terms( $existingcourse->ID, sanitize_text_field($course->_learning_partner), 'learning_partner', false);

              
              if($existingcourse->elm_course_code != $course->id) {
                $existingcourse->course_link = esc_url_raw($course->url);
                $existingcourse->elm_course_code = $course->id;
                  // set updated to 1 so that we know to add this course to 
                  // the updated courses list that we will show in the UI
                  $updated = 1;
              }

              // Even if there aren't any changes, if the course exists in
              // the feed then we need to set this back to publish. In this
              // way, if the course no longer exists in the feed, it won't
              // get changed back and will remain private
              $existingcourse->post_status = 'publish';

              wp_update_post( $existingcourse );
              // We loop through $existingcourses below
              if($updated > 0) {
                  array_push($existingcourses,$existingcourse);
              }
              // set back to 0 so it doesn't trigger on the next loop
              $updated = 0;
              
          } else { // This course does NOT already exist, so we create it

              // Set up the new course with basic settings in place
              $new_course = array(
                  'post_title' => sanitize_text_field($course->title),
                  'post_type' => 'course',
                  'post_status' => 'publish', 
                  'post_content' => sanitize_text_field($course->summary),
                  'post_excerpt' => substr(sanitize_text_field($course->summary), 0, 100),
                  'meta_input'   => array(
                      'course_link' => esc_url_raw($course->url),
                      'elm_course_code' => $course->id
                  )
              );
              // Actually create the new post so that we can move on 
              // to updating it with taxonomy etc
              $post_id = wp_insert_post( $new_course );

              wp_set_object_terms( $post_id, sanitize_text_field($course->delivery_method), 'delivery_method', false);
              wp_set_object_terms( $post_id, sanitize_text_field($course->_learning_partner), 'learning_partner', false);
              wp_set_object_terms( $post_id, 'PSA Learning System', 'external_system', false);

              if(!empty($course->_keywords)) {
                  $keywords = explode(',', $course->_keywords);
                  foreach($keywords as $key) {
                      $keyesc = sanitize_text_field($key);
                      wp_set_object_terms( $post_id, $keyesc, 'keywords', true);
                  }
              }
              if(!empty($course->tags)) {
                  $cats = explode(',', $course->tags);
                  foreach($cats as $cat) {
                    $catesc = sanitize_text_field($cat);
                      wp_set_object_terms( $post_id, $catesc, 'course_category', true);
                  }
              }
              array_push($newcourses,$post_id);
          }
      }
  }
  
  echo '<h1>' . count($existingcourses) . ' courses Updated.</h1>';
  echo '<h1>' . count($newcourses) . ' new courses created.</h1>';

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
