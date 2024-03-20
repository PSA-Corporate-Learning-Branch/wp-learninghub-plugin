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
$taxquery = array(
	array (
		'taxonomy' => 'learning_partner',
		'field' => 'slug',
		'terms' => sanitize_text_field(get_query_var('learning_partner')),
	)
);

$post_args = array(
    'post_type'                => 'course',
    'post_status'              => 'publish',
    'posts_per_page'           => -1,
    'ignore_sticky_posts'      => 0,
    'tax_query' 			   => $taxquery,
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



	<header class="entry-header alignfull" style="background: #FFF; padding: 2em 2em 3em 2em;">
		<div class="alignwide">
			<div>
				<a href="/learninghub/course/">
					All Courses
				</a> 
			</div>
			<h1>Partner<?php //echo $term->name ?></h1>
			<?php if ( $description ) : ?>
				<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
		</div>
	</header><!-- .page-header -->

	<div class="wp-block-columns alignwide" style="padding-top: 2em;">
	<div class="wp-block-column menus" style="background-color: #FFF; border-radius: .5em; flex: 29%; padding: 2%; margin-right: 1%;">
	<div><strong>Groups</strong></div>
	<?php 
	$groups = get_categories(
							array(
								'taxonomy' => 'groups',
								'orderby' => 'id',
								'order' => 'DESC',
								'hide_empty' => '0'
							));
	?>
	<?php foreach($groups as $g): ?>
		<?php $active = ''; if($g->slug == $groupterm) $active = 'active'; ?>
		<?php $to = ''; if($topicterm) $to = 'topics/' . $topicterm . '/'; ?>
		<?php $aud = ''; if($audienceterm) $aud = 'audience/' . $audienceterm . '/'; ?>
		
		<div style="margin:0;padding:0;"><a class="<?= $active ?>" href="/learninghub/groups/<?= $g->slug ?>/<?= $to ?><?= $aud ?>"><?= $g->name ?></a></div>
	<?php endforeach ?>

	<div><strong>Topics</strong></div>
	<?php 
	$topics = get_categories(
							array(
								'taxonomy' => 'topics',
								'orderby' => 'name',
								'order' => 'ASC',
								'hide_empty' => '0'
							));
	?>
	<?php foreach($topics as $t): ?>
		<?php $active = ''; if($t->slug == $topicterm) $active = 'active'; ?>
		<?php $gr = ''; if($groupterm) $gr = 'groups/' . $groupterm . '/'; ?>
		<div style="margin:0;padding:0;"><a class="<?= $active ?>" href="/learninghub/<?= $gr ?>topics/<?= $t->slug ?>"><?= $t->name ?></a></div>
	<?php endforeach ?>
	
	<div><strong>Audience</strong></div>
	<?php 
	$audiences = get_categories(
							array(
								'taxonomy' => 'audience',
								'orderby' => 'id',
								'order' => 'DESC',
								'hide_empty' => '0'
							));
	?>
	<?php foreach($audiences as $a): ?>
		<?php $active = ''; if($a->slug == $audienceterm) $active = 'active'; ?>
		<?php $gr = ''; if($groupterm) $gr = 'groups/' . $groupterm . '/'; ?>
		<?php $to = ''; if($topicterm) $to = 'topics/' . $topicterm . '/'; ?>
		<div style="margin:0;padding:0;"><a class="<?= $active ?>" href="/learninghub/<?= $gr ?><?= $to ?>audience/<?= $a->slug ?>"><?= $a->name ?></a></div>
	<?php endforeach ?>


	</div>
	<div class="wp-block-column" style="flex: 66%;">
	<div style="background-color: #FFF; border-radius: .5em; magrin: 1em 0; padding: 1em;">
		Filters: 
		<?php if($groupterm): ?>
		<?php if($topicterm): ?>
		<a href="/learninghub/topics/<?= $topicterm ?>">x <?= $groupterm ?></a>
		<?php else: ?>
		<a href="/learninghub/course/">x <?= $groupterm ?></a>
		<?php endif ?>
		<?php endif ?>
		<?php if($topicterm): ?>
		<a href="/learninghub/course/">x <?= $topicterm ?></a>
		<?php else: ?>
		<a href="/learninghub/course/">x <?= $topicterm ?></a>
		<?php endif ?>
		<?php if($audienceterm): ?>
		<a href="/learninghub/">x <?= $audienceterm ?></a>
		<?php endif ?>

	</div>
	<?php if( $post_my_query->have_posts() ) : ?>
		<div><?= $post_my_query->found_posts ?> courses</div>
	<?php while ($post_my_query->have_posts()) : $post_my_query->the_post(); ?>
		<?php get_template_part( 'template-parts/course/single-course' ) ?>
	<?php endwhile; ?>
	<?php else : ?>
		<p>Oh no! There are no courses that match your filters.</p>
	<?php //get_template_part( 'template-parts/content/content-none' ); ?>
	<?php endif; ?>

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
