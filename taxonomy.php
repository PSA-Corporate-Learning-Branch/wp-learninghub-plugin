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

$taxquery = [];
if(get_query_var('groups')) {
	$groupterm = sanitize_text_field(get_query_var('groups'));
	$g = array (
		'taxonomy' => 'groups',
		'field' => 'slug',
		'terms' => $groupterm,
	);
	array_push($taxquery, $g);
	$gterm = get_term_by( 'slug', get_query_var( 'groups' ), 'groups');
}
if(get_query_var('topics')) {
	$topicterm = sanitize_text_field(get_query_var('topics'));
	$t = array (
		'taxonomy' => 'topics',
		'field' => 'slug',
		'terms' => $topicterm,
	);
	array_push($taxquery, $t);
	$tterm = get_term_by( 'slug', get_query_var( 'topics' ), 'topics');
}
if(get_query_var('audience')) { 
	$audienceterm = sanitize_text_field(get_query_var('audience'));
	$t = array (
		'taxonomy' => 'audience',
		'field' => 'slug',
		'terms' => $audienceterm,
	);
	array_push($taxquery, $t);
	$aterm = get_term_by( 'slug', get_query_var( 'audience' ), 'audience');
}
if(get_query_var('delivery_method')) { 
	$dmterm = sanitize_text_field(get_query_var('delivery_method'));
	$t = array (
		'taxonomy' => 'delivery_method',
		'field' => 'slug',
		'terms' => $dmterm,
	);
	array_push($taxquery, $t);
	$dterm = get_term_by( 'slug', get_query_var( 'delivery_method' ), 'delivery_method');
}
// print_r($gterm);

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

// Setup URLs
$gr = ''; if($groupterm) $gr = 'groups/' . $groupterm . '/';
$to = ''; if($topicterm) $to = 'topics/' . $topicterm . '/';
$aud = ''; if($audienceterm) $aud = 'audience/' . $audienceterm . '/';
$dm = ''; if($dmterm) $dm = 'delivery_method/' . $dmterm . '/';
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
			<a class="<?= $active ?>" href="/learninghub/filter/<?= $gr ?>topics/<?= $t->slug ?>/<?= $aud ?><?= $dms ?>">
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
			<a class="<?= $active ?>" href="/learninghub/filter/<?= $gr ?><?= $to ?>audience/<?= $a->slug ?>/<?= $dms ?>">
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
			<a class="<?= $active ?>" href="/learninghub/filter/<?= $gr ?><?= $to ?><?= $aud ?>delivery_method/<?= $d->slug ?>">
				<?= $d->name ?>
			</a>
			(<?= $d->count ?>)
		</div>
	<?php endforeach ?>


	</div>
	<div class="wp-block-column" style="flex: 66%;">
	<div style="background-color: #FFF; border-radius: .5em; magrin: 1em 0; padding: 1em;">
	<div class="">
		<div><strong>Filters:</strong></div>
		<?php if($groupterm): ?>
		<div class="">
		<?php $home = ''; if(!$to && !$aud && !$dm) $home = 'course/'; ?>
			<a style="background-color: #FFF; border-radius: 5px; color: #333; display: inline-block; padding: 2px 10px; text-decoration: none;" 
				href="/learninghub/filter/<?= $home ?><?= $to ?><?= $aud ?><?= $dm ?>">
					<span style="background-color: #003366; border-radius: 3px; color: #FFF; display: inline-block; font-size: 12px; padding: 0 4px;">Remove</span> 
			</a>
			<strong><?= $gterm->name ?></strong>
			
		</div>
		<?php endif ?>
		<?php if($topicterm): ?>
		<div class="">
		<?php $home = ''; if(!$gr && !$aud && !$dm) $home = 'course/'; ?>
		<a style="background-color: #FFF; border-radius: 5px; color: #333; display: inline-block; padding: 2px 10px; text-decoration: none;" 
			href="/learninghub/filter/<?= $home ?><?= $gr ?><?= $aud ?><?= $dm ?>">
				<span style="hover:background-color: #145693; background-color: #003366; border-radius: 3px; color: #FFF; display: inline-block; font-size: 12px; padding: 0 4px;">Remove</span> 
			</a>
			<strong><?= $tterm->name ?></strong>
		</div>
		<?php endif ?>
		<?php if($audienceterm): ?>
		<div class="">
		<?php $home = ''; if(!$gr && !$to && !$dm) $home = 'course/'; ?>
		<a style="background-color: #FFF; border-radius: 5px; color: #333; display: inline-block; padding: 2px 10px; text-decoration: none;" 
			href="/learninghub/filter/<?= $home ?><?= $gr ?><?= $to ?><?= $dm ?>">
				<span style="background-color: #003366; border-radius: 3px; color: #FFF; display: inline-block; font-size: 12px; padding: 0 4px;">Remove</span> 
			</a>
			<strong><?= $aterm->name ?></strong>
		</div>
		<?php endif ?>
		<?php if($dmterm): ?>
		<div class="">
		<?php $home = ''; if(!$gr && !$to && !$aud) $home = 'course/'; ?>
		<a style="background-color: #FFF; border-radius: 5px; color: #333; display: inline-block; padding: 2px 10px; text-decoration: none;" 
			href="/learninghub/filter/<?= $home ?><?= $gr ?><?= $to ?><?= $aud ?>">
				<span style="background-color: #003366; border-radius: 3px; color: #FFF; display: inline-block; font-size: 12px; padding: 0 4px;">Remove</span> 
			</a>
			<strong><?= $dterm->name ?></strong>
		</div>
		<?php endif ?>
		</div>
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



<?php get_footer(); ?>
