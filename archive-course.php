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

<div class="wp-block-cover alignfull has-background-dim-80 has-background-dim hero" 
	style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;background-color:#28537d;min-height:220px">
		
			<img loading="lazy" 
					class="wp-block-cover__image-background wp-image-4447" 
					alt="" 
					src="https://learningcentre.gww.gov.bc.ca/learninghub/wp-content/uploads/sites/6/2021/11/courses2.jpg" 
					style="object-position:100% 50%" 
					data-object-fit="cover" 
					data-object-position="100% 50%" 
					sizes="(max-width: 1352px) 100vw, 1352px" 
					width="1352" 
					height="888">

	<div class="wp-block-cover__inner-container has-text-align-center">
		<h1>All Courses</h1>
	</div>
</div>



<div class="entry-content" id="courselist">

<div class="searchbox">

<input class="search form-control mb-3" placeholder="Type to filter courses below">


</div>
<?php if( $post_my_query->have_posts() ) : ?>

    <div class="alignwide">
<div class="list">
<?php
$lastletter = '';
while ($post_my_query->have_posts()) : $post_my_query->the_post(); 



get_template_part( 'template-parts/course/single-course' );

$lastletter = $firstletter;

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



<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script>

var courseoptions = {
    valueNames: [ 'coursename', 'coursedesc', 'coursecats', 'coursekeys' ]
};
var courses = new List('courselist', courseoptions);
// document.getElementById('coursecount').innerHTML = courses.update().matchingItems.length;
// courses.on('searchComplete', function(){
//     //console.log(upcomingClasses.update().matchingItems.length);
//     //console.log(courses.update().matchingItems.length);
//     document.getElementById('coursecount').innerHTML = courses.update().matchingItems.length;
// });

</script>
<?php get_footer(); ?>
