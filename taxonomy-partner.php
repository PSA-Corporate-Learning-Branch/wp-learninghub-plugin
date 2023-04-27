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
            'taxonomy' => 'learning_partner',
            'field' => 'slug',
            'terms' => $term,
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

$partnerurl = '';
$partnerlogo = '';
$term_vals = get_term_meta($term->term_id);
foreach($term_vals as $key=>$val){
    //echo $val[0] . '<br>';
    if($key == 'partner-url') {
        $partnerurl = $val[0];
    }
    if($key == 'category-image-id') {
        $partnerlogo = $val[0];
    }
    
} 


?>

<?php if( $post_my_query->have_posts() ) : ?>
	
<div class="white-wrap" style="background-color: #FFF; margin-bottom: 2em; padding: 2em;">
<div class="wp-block-columns alignwide">
<div class="wp-block-column" style="flex-basis:33.33%">
<div class="">
	<?php if(!empty($partnerlogo)): ?>
    <?php $image_attributes = wp_get_attachment_image_src( $attachment_id = $partnerlogo, $size = 'large' ) ?>
    <?php if ( $image_attributes ) : ?>
    <div style="margin-top: 2em; text-align:center;">
    <img src="<?php echo $image_attributes[0]; ?>" 
            width="<?php echo $image_attributes[1]; ?>" 
            height="<?php echo $image_attributes[2]; ?>">
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
</div>


<div class="wp-block-column">
<div class="">

	<div><a class="allpartnerslink" href="/learninghub/corporate-learning-partners/">All Partners</a></div>
	<h1><?php echo $term->name ?></h1>
    <?php //the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
    <?php if ( $description ) : ?>
    <div class="" style="margin: 1.5em 0;"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
    <?php endif; ?>
    <?php if(!empty($partnerurl)): ?>
    <div>
        <a class="" 
            target="_blank" 
            rel="noopener" 
            href="<?= $partnerurl ?>">
                View Partner Website
        </a>
    </div>
    <?php endif ?>

</div>
</div>
</div>
</div>


<?php 
// Get a list of all sub-categories and output them as simple links
$catlist = get_categories(
						array(
							'taxonomy' => 'course_category',
							'child_of' => $term->term_id,
							'orderby' => 'id',
							'order' => 'DESC',
							'hide_empty' => '0'
						));

foreach($catlist as $childcat) {
	echo '<a href="/learninghub/course_category/'. $childcat->slug . '">' . $childcat->name . '</a> | ';
}

//print_r($catlist);
?>
</div>



<div class="alignwide">

<div class="wp-block-columns alignfull"><!-- wp:column -->
<div class="wp-block-column" style="flex: 66%;">

    <div id="courselist">
    <div class="searchbox">
    <input class="search form-control mb-3" placeholder="Type to filter courses">
	</div>
	<div class="list">
	<?php while ($post_my_query->have_posts()) : $post_my_query->the_post(); ?>
    <?php get_template_part( 'template-parts/course/single-course' ) ?>
	<?php endwhile; ?>
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


    </script>







</div>
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
    'orderby'                  => array('post_date' =>'DESC'),
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
</div>


<?php get_footer(); ?>
