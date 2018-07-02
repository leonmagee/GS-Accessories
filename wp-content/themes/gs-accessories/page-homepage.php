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
  </div>

</div>

<div class="homepage-amazon-wrap">
  <a href="https://www.amazon.com/s?marketplaceID=ATVPDKIKX0DER&me=A1AC06F3GET8FM&merchant=A1AC06F3GET8FM&redirect=true" target="_blank">
  <img src="<?php echo site_url(); ?>/wp-content/uploads/amazon_buy_link.jpg" />
</a>

</div>

<div class="gs-homepage-content">

    <div class="max-width-wrap">

      <?php
      while ( have_posts() ) : the_post();

        the_content();

      endwhile;
      ?>

  </div> 

</div>

<div class="gswireless-link-wrap">
  <a href="https://mygswireless.com" target="_blank">
    <img src="https://mygsaccessories.com/wp-content/uploads/slides_accessories_link.jpg" />
  </a>
</div>

</div><!-- homepage outer wrap -->

</div>

<?php
get_footer();







