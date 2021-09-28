<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

$description = get_the_archive_description();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_args=array(
    'post_type'                => 'course',
    'post_status'              => 'publish',
    'posts_per_page'           => 300,
    'paged'                    => $paged, 
    'ignore_sticky_posts'      => 0,
    'child_of'                 => 0,
    'parent'                   => 0,
    'orderby'                  => 'name', 
    'order'                    => 'ASC',
    'hide_empty'               => 0,
    'hierarchical'             => 1,
    'exclude'                  => '',
    'include'                  => '',
    'number'                   => '',
    'pad_counts'               => true, 
);
$post_my_query = null;
$post_my_query = new WP_Query($post_args);
$categories = get_terms( 
    'course_category', 
    array('parent' => 0)
);
?>
<div class="dark-wrap">
<div class="wp-block-columns alignwide" id="pagetop">
<div class="wp-block-column" style="flex-basis:33.33%">
<figure class="post-thumbnail">
    <img src="https://lc.virtuallearn.ca/portal/wp-content/uploads/sites/6/2021/06/hero-2.jpg" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" srcset="https://lc.virtuallearn.ca/portal/wp-content/uploads/sites/6/2021/06/hero-2.jpg 888w, https://lc.virtuallearn.ca/portal/wp-content/uploads/sites/6/2021/06/hero-2-300x225.jpg 300w, https://lc.virtuallearn.ca/portal/wp-content/uploads/sites/6/2021/06/hero-2-768x575.jpg 768w" sizes="(max-width: 888px) 100vw, 888px" style="width:100%;height:74.89%;max-width:888px;" width="888" height="665">
</figure>
</div>
<div class="wp-block-column">
    <div style="padding: 2em 0;">

    <h1>Courses</h1>
    <div style="padding: 1em 0;">
    <?php echo $post_my_query->post_count; ?> 
    courses in 3 top-level categories from 14 
    <a href="/portal/corporate-learning-partners/" style="color: #FFF">Learning Partners</a>
    </div>
    <div>
    <?php foreach( $categories as $category ): ?>
    <?php if($category->name == "Ministry") continue ?>
    <?php 
    $category_link = sprintf( 
        '<a href="%1$s" alt="%2$s" class="coursecat">%3$s</a>',
        esc_url( get_category_link( $category->term_id ) ),
        esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
        esc_html( $category->name )
    );
    ?>

    <?= sprintf( esc_html__( '%s', 'textdomain' ), $category_link ) . ' ' ?>


    <?php endforeach ?>
</div>
</div>
</div>
</div>
</div>

<div class="entry-content" id="courselist" style="margin-top: 1em;">
<div class="searchbox">
<input class="search form-control mb-3" placeholder="Search">
<!-- <div><span id="coursecount"></span> courses</div> -->

<?php if( $post_my_query->have_posts() ) : ?>

</div>    
<div class="entry-content">
<div class="list">
<?php
while ($post_my_query->have_posts()) : $post_my_query->the_post(); 

get_template_part( 'template-parts/course/single-course' );

endwhile;
?>
</div>
</div>
</div>
<?php
else :      
    echo '<p>No Courses Found!</p>';   
endif;
wp_reset_query($post_my_query);

?>
</div>
</div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script>

var courseoptions = {
    valueNames: [ 'coursename', 'coursedesc', 'coursecats', 'coursekeys' ]
};
var courses = new List('courselist', courseoptions);
document.getElementById('coursecount').innerHTML = courses.update().matchingItems.length;
courses.on('searchComplete', function(){
    //console.log(upcomingClasses.update().matchingItems.length);
    //console.log(courses.update().matchingItems.length);
    document.getElementById('coursecount').innerHTML = courses.update().matchingItems.length;
});

</script>
<?php get_footer(); ?>
