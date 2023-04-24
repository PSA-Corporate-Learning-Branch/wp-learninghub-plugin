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



<div class="alignwide">

<div class="wp-block-columns alignfull" style="padding-top: 2em;"><!-- wp:column -->
<div class="wp-block-column" style="flex: 66%;">
<?php the_title( '<h1 class="coursehead">', '</h1>' ); ?>
<div class="course">




    <div style="font-size: 1.2em">
        <?php the_content(); ?>
    </div>    

    <div class="coursemeta">
    <?php the_terms( $post->ID, 'delivery_method', '', ', ', ' ' ); ?>
    <?php the_terms( $post->ID, 'learning_partner', 'offered by ', ', ', ' ' ); ?>
    
    <?php $exsys = get_the_terms( $post->ID, 'external_system', '', ', ', ' ' ) ?>
    <?php if(!empty($exsys[0]->name)): ?>
        via 
        <a class="" href="/learninghub/external_system/<?= $exsys[0]->slug ?>">
            <?= $exsys[0]->name ?>
        </a>
    <?php endif ?>
    </div>
        <?php if(!empty($post->course_link)): ?>
        <div style="margin-bottom: 1em;">
        <a class="outboundlink" 
            href="<?= $post->course_link ?>" 
            target="_blank" 
            rel="noopener">
                Launch 
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                    <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                </svg>
        </a>
        <span style="font-size: 12px">
            <a title="Permanent link to this course\'s page" style="text-decoration: none;" href="<?= the_permalink() ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link" viewBox="0 0 16 16">
                    <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                    <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
                </svg>
            </a>
        </span>
        </div>
        <?php else: ?>

            <div>Oh no! There's something wrong with this course. Please <?php the_terms( $post->ID, 'learning_partner', 'contact', ', ', ' ' ); ?></div>

        <?php endif ?>
        


        <div class="coursecats mt-1" style="display:none;">
            <?php the_terms( $post->ID, 'course_category', 'Categories: ', ', ', ' ' ); ?>
        </div>
        </div>
    



<?php endwhile; // End of the loop. ?>


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
<?php 
$news_args = array(
    'post_type'                => 'post',
    'post_status'              => 'publish',
    'posts_per_page'           => 3,
    'ignore_sticky_posts'      => 0,
    'child_of'                 => 0,
    'parent'                   => 0,
    'orderby'                  => array('post_date' =>'ASC'),
    'hide_empty'               => 0,
    'hierarchical'             => 1,
    'exclude'                  => '',
    'include'                  => '',
    'number'                   => '',
    'pad_counts'               => true, 
);
$news = null;
$news = new WP_Query($news_args);
if( $news->have_posts() ) : ?>

    <h4 class="alignwide">Recent News</h4>
    <div style="background: #FFF; border-radius: 5px; padding: .5em;">
    <?php while ($news->have_posts()) : $news->the_post(); ?>
    <div>
        <a href="<?= the_permalink() ?>">
            <?= the_title() ?>
        </a>
    </div>
    <?php endwhile ?>
	</div>
    
<?php else: ?>
    <p>No news is bad news?</p>
<?php endif; ?>
<?php wp_reset_query($news); ?>

<!-- /wp:column -->
</div>
</div>



<?php

get_footer();