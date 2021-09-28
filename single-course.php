<?php
/**
 * The template for displaying all pages of the Course content type. This is primarily
 * a copy of Twenty_Twenty_One's single.php but with added stuff in there and a lot of
 * theme-specific stuff deleted.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	?>


<div class="dark-wrap">
<div class="alignwide" id="coursetop">
<div class="entry-content">
	<div class="allcourseslink"><a href="/portal/course/">All Courses</a></div>
	<?php the_title( '<h1 class="coursehead">', '</h1>' ); ?>

</div>
</div>
</div>

<div class="entry-content">

	<div><?php the_terms( $post->ID, 'learning_partner', 'Learning Partner: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'course_category', 'Categories: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'delivery_method', 'Delivery Methods: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'role', 'Roles: ', ', ', ' ' ); ?></div>
	<div style="display:none"><?php the_terms( $post->ID, 'keywords', 'Keywords: ', ', ', ' ' ); ?></div>

	<?php the_content() ?>

	<div>
	<a style="background: #3a9bd9; color: #F2F2F2; display: block; font-size: 2rem; padding: .25em .5em; text-align: center" 
		href="<?= $post->course_link ?>" 
		target="_blank" 
		rel="noopener">
			Register Here
	</a>
	</div>


</div><!-- .entry-content -->

<?php endwhile; // End of the loop.

get_footer();
