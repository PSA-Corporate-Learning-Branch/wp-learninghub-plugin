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
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$parent = get_term($term->parent, get_query_var('taxonomy') ); // get parent term
?>

<?php if ( have_posts() ) : ?>

	<header class="entry-header alignfull" style="background: #FFF; padding: 2em 2em 3em 2em;">
		<div class="alignwide">
	
	<div>
		<a href="/portal/course/">
			All Courses
		</a> 
		<?php if(!empty($parent->slug)): ?>
		/ 
		<a href="/portal/course_category/<?php echo $parent->slug ?>">
			<?php echo $parent->name ?>
		</a>
		<?php endif ?>
	</div>
	
	<h1><?php echo $term->name ?> Courses</h1>
		<?php //the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
		
<div class="" style="margin: 1em 0 0 0;">
<?php 
// Get a list of all sub-categories and output them as simple links
$methodlist = get_categories(
						array(
							'taxonomy' => 'delivery_method',
							'orderby' => 'id',
							'order' => 'DESC',
							'hide_empty' => 1
						));

foreach($methodlist as $method) {
	$active = '';
	if($method->name == $term->name) $active = 'active';
	echo '<a class="'.$active.'" href="/portal/delivery_method/'. $method->slug . '">' . $method->name . '</a> | ';
}

//print_r($catlist);
?>
</div>
</div>
</div>
	</header><!-- .page-header -->
<div class="alignwide">
<div class="entry-content">
	<div id="courselist">
    <div class="searchbox" style="margin-top: 1em">
    <input class="search form-control mb-3" placeholder="Type to filter courses">
	</div>
	<div class="list">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/course/single-course' ) ?>
	<?php endwhile; ?>
</div> <!-- /.list -->
</div> <!-- /#courselist -->
</div>
</div>
<div style="clear: both">
	<?php twenty_twenty_one_the_posts_navigation(); ?>
</div>
<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

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
