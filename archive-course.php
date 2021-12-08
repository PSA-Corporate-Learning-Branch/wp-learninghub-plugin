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

<p class="has-background has-text-align-center has-black-color has-text-color has-small-font-size" style="background-color:#c3d4e4;text-align: center;">
    <a href="https://learningcentre.gww.gov.bc.ca/learninghub/foundational-courses/">Foundational Courses</a> | 
    <a href="https://learningcentre.gww.gov.bc.ca/learninghub/supervisors-and-managers/">Supervisors &amp; Managers</a> | 
    <a href="https://learningcentre.gww.gov.bc.ca/learninghub/leadership/">Leadership in the BCPS</a>
</p>


<div class="alphabet">
    <a href="#A">A</a>
    <a href="#B">B</a>
    <a href="#C">C</a>
    <a href="#D">D</a>
    <a href="#E">E</a>
    <a href="#F">F</a>
    <a href="#G">G</a>
    <a href="#H">H</a>
    <a href="#I">I</a>
    <a href="#J">J</a>
    <a href="#K">K</a>
    <a href="#L">L</a>
    <a href="#M">M</a>
    <a href="#N">N</a>
    <a href="#O">O</a>
    <a href="#P">P</a>
    <a href="#Q">Q</a>
    <a href="#R">R</a>
    <a href="#S">S</a>
    <a href="#T">T</a>
    <a href="#U">U</a>
    <a href="#V">V</a>
    <a href="#W">W</a>
    <a href="#X">X</a>
    <a href="#Y">Y</a>
    <a href="#Z">Z</a>
</div> <!-- /.alphabet -->




<div class="entry-content" id="courselist">

<div class="searchbox">

<input class="search form-control mb-3" placeholder="Type to filter courses below">


</div>
<?php if( $post_my_query->have_posts() ) : ?>

<div class="entry-content">
<div class="list">
<?php
$lastletter = '';
while ($post_my_query->have_posts()) : $post_my_query->the_post(); 

$title = get_the_title();
$firstletter = substr($title, 0, 1);
$secondletter = substr($title, 0, 2);

if($firstletter != '{' && $firstletter != '(') {            
    if($firstletter != $lastletter) {
        // not sure what to do here as list.js is counting these headers as courses :(
            // As it turns out, this is more important than a counter update, so we're
            // implementing it and removing the live count update onfilter
        echo '<h2 id="' . $firstletter . '">' . $firstletter . '</h2>';
    }
}

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
