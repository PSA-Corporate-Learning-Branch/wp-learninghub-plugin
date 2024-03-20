<?php
/**
 * The template for displaying course archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();



$post_args = array(
    'post_type'                => 'course',
    'post_status'              => 'publish',
    'posts_per_page'           => -1,
    'ignore_sticky_posts'      => 0,
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
		<div style="margin:0;padding:0;">
			<a class="<?= $active ?>" href="/learninghub/groups/<?= $g->slug ?>/<?= $to ?><?= $aud ?><?= $dms ?>">
				<?= $g->name ?>
			</a>
			(<?= $g->count ?>)
		</div>
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
		<div style="margin:0;padding:0;">
			<a class="<?= $active ?>" href="/learninghub/<?= $gr ?>topics/<?= $t->slug ?>/<?= $aud ?><?= $dms ?>">
				<?= $t->name ?>
			</a>
			(<?= $t->count ?>)
		</div>
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
		<div style="margin:0;padding:0;">
			<a class="<?= $active ?>" href="/learninghub/<?= $gr ?><?= $to ?>audience/<?= $a->slug ?>/<?= $dms ?>">
				<?= $a->name ?>
			</a>
			(<?= $a->count ?>)
		</div>
	<?php endforeach ?>
	<div><strong>Delivery Method</strong></div>
	<?php 
	$dms = get_categories(
							array(
								'taxonomy' => 'delivery_method',
								'orderby' => 'id',
								'order' => 'DESC',
								'hide_empty' => '0',
								'include' => array(3,37,82,236,410)
							));
	?>
	<?php foreach($dms as $d): ?>
		<?php $active = ''; if($d->slug == $dmterm) $active = 'active'; ?>
		<div style="margin:0;padding:0;">
			<a class="<?= $active ?>" href="/learninghub/<?= $gr ?><?= $to ?><?= $aud ?>delivery_method/<?= $d->slug ?>">
				<?= $d->name ?>
			</a>
			(<?= $d->count ?>)
		</div>
	<?php endforeach ?>

	</div>
	<div class="wp-block-column" style="flex: 66%;">
	
	<?php if( $post_my_query->have_posts() ) : ?>
		<div><?= $post_my_query->found_posts ?> courses</div>
		<details>
			<summary>Suggested Searches</summary>
			<div style="background-color: #FFF; border-radius: 5px; padding: .5em;">
			<div><a href="/learninghub/?s=flexibleBCPS">#flexibleBCPS</a></div>
			<p>Flexible workplaces? Managing remote teams? The courses and resources you need.</p>
			</div>
			<div style="background-color: #FFF; border-radius: 5px; margin-top: 1em; padding: .5em;">
			<div><a href="/learninghub/?s=BCPSBelonging">#BCPSBelonging</a></div>
			<p>Great courses that cover equity, diversity and inclusion.</p>
			</div>
		</details>
	<?php while ($post_my_query->have_posts()) : $post_my_query->the_post(); ?>
		<?php get_template_part( 'template-parts/course/single-course' ) ?>
	<?php endwhile; ?>
	<?php else : ?>
		<p>Oh no! There are no courses that match your filters.</p>
	<?php //get_template_part( 'template-parts/content/content-none' ); ?>
	<?php endif; ?>

	</div>
	</div>



<?php get_footer(); ?>
