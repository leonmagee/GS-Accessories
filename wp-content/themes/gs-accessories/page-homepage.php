<?php
/**
 * Template Name: Homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

get_header(); ?>

<div class="homepage-outer-wrap">

<div class="homepage-wrap">
<?php if ( $home_text = get_field('homepage_cta_text', 'option')) { ?>
	<h1><?php //echo $home_text; ?></h1>
<?php } ?>

</div>

<div class="homepage-slider">


<div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
  <div class="orbit-wrapper">
    <div class="orbit-controls">
      <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
      <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
    </div>
    <ul class="orbit-container">


    <?php

$args = array('post_type' => 'accessories');
$accessories_query = new WP_Query($args);    
while ( $accessories_query->have_posts() ) {
  $accessories_query->the_post(); ?>

<li class="orbit-slide">
  <figure class="orbit-figure">
    <div class="accessorie-slide">
  
      <div class="slide-image-wrap">
      <?php 
      if ( has_post_thumbnail() ) {
      //the_post_thumbnail('medium');
      the_post_thumbnail();
    } else { ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image.jpg" />
    <?php } ?>
      </div>

      <div class="slide-content-wrap">
      <h2><?php the_title(); ?></h2>
      <p><?php echo get_field('accessory_text'); ?></p>
    <a href="<?php the_permalink(); ?>">View Product</a>
      
      </div>

 </div>
  </figure>
  </li>

<?php } ?>

    </ul>
  </div>
  <nav class="orbit-bullets">
  <?php 
  $counter = 0;
  while ( $accessories_query->have_posts() ) {
  $accessories_query->the_post(); ?>
    <button <?php if ( $counter == 0 ) { echo 'class="is-active"'; }?> data-slide="<?php echo $counter; ?>"><span class="show-for-sr">Slide <?php echo ( $counter + 1); ?> details.</span></button>

  <?php
$counter++;
} ?>

  </nav>
</div>


</div>



<div class="homepage-slider-items">


 
</div>

</div><!-- homepage outer wrap -->

<?php
get_footer();
