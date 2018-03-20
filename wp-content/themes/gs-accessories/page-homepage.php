<?php
/**
 * Template Name: Homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */
get_header(); ?>

<div class="homepage-outer-outer-wrap">

<div class="homepage-outer-wrap">

  <div class="homepage-slider">

    <div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
      <div class="orbit-wrapper">
        <div class="orbit-controls">
          <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
          <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
        </div>
        <ul class="orbit-container">

          <?php $slides = get_field('homepage_slides', 'option');

          foreach( $slides as $slide ) { ?>

          <li class="orbit-slide">
            <figure class="orbit-figure">
              <div class="accessorie-slide">

                <img src="<?php echo $slide['slide_image']['url']; ?>" />

              </div>

            </figure>
          </li>

          <?php }?>
        </ul>
      </div>
      <nav class="orbit-bullets">
        <?php
        $counter = 0;
        foreach ( $slides as $slide ) { ?>
        <button <?php if ($counter == 0) {echo 'class="is-active"';}?> data-slide="<?php echo $counter; ?>"><span class="show-for-sr">Slide <?php echo ($counter + 1); ?> details.</span></button>

        <?php
        $counter++;
      }?>

    </nav>
  </div>

</div>

</div><!-- homepage outer wrap -->

<div class="homepage-video-wrapper">
  <div class="homepage-video-wrapper-inner">
    <span class="close-icon">&times;</span>
    <?php 
  //$embed_code = wp_oembed_get( 'https://youtu.be/cADAedU1_Eo?modestbranding=1' ); 
    $embed_code = wp_oembed_get( 'https://www.youtube.com/watch?v=cADAedU1_Eo&feature=youtu.be&showinfo=0&modestbranding=1' ); 
    echo $embed_code; 
    ?>
  </div>
</div>
</div>

<?php
get_footer();







