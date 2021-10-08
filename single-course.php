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


<div class="white-wrap">
<div class="alignwide" id="coursetop">
<div class="entry-content">
	<div class="allcourseslink"><a href="/portal/course/">All Courses</a></div>
	<?php the_title( '<h1 class="coursehead">', '</h1>' ); ?>

</div>
</div>
</div>

<div class="entry-content my-1">
<div class="course bg-white">
<div style="background: #28537d; height: 6px; width: 100%;"></div> 
<div class="p-1">
    <div class="details" id="course-<?= $post->ID ?>">

        <div class="coursedesc bg-white mt-wee">
            <?php the_content(); ?>
        </div>
        <div class="learningpartner mt-wee">
            <?php the_terms( $post->ID, 'learning_partner', 'Offered by: ', ', ', ' ' ); ?>
        </div>
        <div class="courseregister my-1">
        <?php $exsys = get_the_terms( $post->ID, 'external_system', '', ', ', ' ' ) ?>
        <a class="registerbutton" 
            href="<?= $post->course_link ?>" 
            target="_blank" 
            rel="noopener">
                Register on <?= $exsys[0]->name ?>
        </a>
        </div>
        <div class="coursecats mt-1">
            <?php the_terms( $post->ID, 'course_category', 'Categories: ', ', ', ' ' ); ?>
        </div>
        <div class="delivery-method">
            <?php the_terms( $post->ID, 'delivery_method', 'Delivery Method: ', ', ', ' ' ); ?>
        </div>

    </div>
    </div>
</div> <!-- /.course -->
</div><!-- .entry-content -->

<?php endwhile; // End of the loop.

get_footer();
