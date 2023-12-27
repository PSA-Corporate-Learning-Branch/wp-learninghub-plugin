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

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_args = array(
    'post_type'                => 'course',
    'post_status'              => 'publish',
    'posts_per_page'           => 500,
    'paged'                    => $paged, 
    'ignore_sticky_posts'      => 0,
    'tax_query' => array(
        array (
            'taxonomy' => 'groups',
            'field' => 'slug',
            'terms' => $term,
		),
        array (
            'taxonomy' => 'topics',
            'field' => 'slug',
            'terms' => 'house-of-indigenous-learning',
        )
    ),
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

?>

<?php if( $post_my_query->have_posts() ) : ?>

	<header class="entry-header alignfull" style="background: #FFF; margin: 0 0 2em 0; padding: 2em 2em 3em 2em;">
		<div class="alignwide">
	
	<div>
		<a href="/learninghub/course/">
			All Courses
		</a> 
		<?php if(!empty($parent->slug)): ?>
		/ 
		<a href="/learninghub/groups/<?php echo $parent->slug ?>">
			<?php echo $parent->name ?>
		</a>
		<?php endif ?>
	</div>
	
	<h1><?php echo $term->name ?> </h1>
		<?php //the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
		
<div class="" style="margin: 1em 0 0 0;">
<?php 
// Get a list of all sub-categories and output them as simple links
$topiclist = get_categories(
						array(
							'taxonomy' => 'groups',
							'orderby' => 'id',
							'order' => 'DESC',
							'hide_empty' => 1
						));

foreach($topiclist as $topic) {
	$active = '';
	if($topic->name == $term->name) $active = 'active';
	echo '<a class="'.$active.'" href="/learninghub/groups/'. $topic->slug . '">' . $topic->name . '</a> | ';
}

//print_r($catlist);
?>
</div>
</div>
</div>
</header><!-- .page-header -->
<div class="alignwide">

<div class="wp-block-columns alignfull"><!-- wp:column -->
<div class="wp-block-column" style="flex: 66%;">

	<div id="courselist">
    <div class="searchbox" style="margin-top: 1em">
    <input class="search form-control mb-3" placeholder="Type to filter courses">
	</div>
	<div class="list">
	<?php while ($post_my_query->have_posts()) : $post_my_query->the_post();  ?>
		
		<?php get_template_part( 'template-parts/course/single-course' ) ?>
	<?php endwhile; ?>
</div> <!-- /.list -->
</div> <!-- /#courselist -->

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




</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="flex: 29%; padding: 0 2%;">

<h4>Suggested Courses</h4>
<div style="background-color: #FFF; border-radius: 5px; padding: .5em;">
<div>
    <a href="/learninghub/foundational-courses/">Mandatory &amp; Foundational</a>
</div>
<div>
    <a href="/learninghub/supervisors-and-managers/">Supervisors &amp; Managers</a>
</div>
<div>
    <a href="/learninghub/leadership/">Leadership in the BCPS</a>
</div>
</div>
<h4>Suggested Searches</h4>
<div style="background-color: #FFF; border-radius: 5px; padding: .5em;">
<div><a href="/learninghub/?s=flexibleBCPS">#flexibleBCPS</a></div>
<p>Flexible workplaces? Managing remote teams? The courses and resources you need.</p>
</div>
<div style="background-color: #FFF; border-radius: 5px; margin-top: 1em; padding: .5em;">
<div><a href="/learninghub/?s=BCPSBelonging">#BCPSBelonging</a></div>
<p>Great courses that cover equity, diversity and inclusion.</p>
</div>


<!-- /wp:column -->

</div>
</div>
</div>


<?php get_footer(); ?>
