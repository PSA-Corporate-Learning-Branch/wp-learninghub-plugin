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
$sort = esc_html($_GET['sort'] ?? 'post_name');
$ob = 'ASC';
if($sort == 'post_date') $ob = 'DESC';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_args=array(
    'post_type'                => 'course',
    'post_status'              => 'publish',
    'posts_per_page'           => 500,
    'paged'                    => $paged, 
    'ignore_sticky_posts'      => 0,
    'child_of'                 => 0,
    'parent'                   => 0,
    'orderby'                  => array($sort => $ob),
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

<header class="alignfull" style="background: #FFF; padding: 2em;">
    <div class="alignwide">
        <h1>All Courses</h1>
        <?php if($sort == 'post_date'): ?>
        <div>Sorted by most recent. <a href="/learninghub/course/">Sort Alphabetically</a></div>
        <?php else: ?>
        <div>Sorted alphabetically. <a href="/learninghub/course/?sort=post_date">Sort by recently added</a></div>
        <?php endif ?>
    </div>
</header><!-- .page-header -->


	<div class="alignwide">


<!-- wp:columns {"align":"full"} -->
<div class="wp-block-columns alignfull"><!-- wp:column -->
<div class="wp-block-column" style="flex: 66%;">
<div class="">
<button id="expandcollapse" style="background: #FFF; border:0; border-radius: 5px; color: #333; font-size: 14px; float: right; padding: 0 1em;">
    Expand All
</button>
<div style="clear: both"></div>
<?php if( $post_my_query->have_posts() ) : ?>

    

<?php

while ($post_my_query->have_posts()) : $post_my_query->the_post(); 



get_template_part( 'template-parts/course/single-course' );


endwhile;
?>


<?php
else :      
    echo '<p>No Courses Found!</p>';   
endif;
wp_reset_query($post_my_query);

?>

<script>

let exco = document.getElementById('expandcollapse');
exco.addEventListener('click', (e) => { 
    e.preventDefault();
    if(exco.innerHTML == 'Collapse All') {
        exco.innerHTML = 'Expand All';
    } else {
        exco.innerHTML = 'Collapse All';
    }
    toggleAll();
});
function toggleAll() {
    let foo = document.body.querySelectorAll('details').forEach((e) => {
        (e.hasAttribute('open')) ? e.removeAttribute('open') : e.setAttribute('open',true);
    });
    return foo;
}
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
</div>
</div>




<?php get_footer(); ?>


	

