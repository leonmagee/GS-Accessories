<?php
/**
 * Template Name: Homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

get_header();

          // $accessories = get_field('homepage_featured_accessories', 'option');
          // var_dump($accessories);
          // die('some content');
?>

<div class="homepage-outer-wrap">

  <div class="homepage-slider">

    <div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
      <div class="orbit-wrapper">
        <div class="orbit-controls">
          <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
          <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
        </div>
        <ul class="orbit-container">

          <?php

          $accessories = get_field('homepage_featured_accessories', 'option');
          //var_dump($accessories);
          //die('some content');

          //$args = array('post_type' => 'accessories');
          // $accessories_query = new WP_Query($args);
          // while ($accessories_query->have_posts()) {
          //  $accessories_query->the_post();




          foreach( $accessories as $accessory ) {


           $features = get_field('accessory_features', $accessory['accessory']->ID);
           $additional_features = get_field('additional_features', $accessory['accessory']->ID);
           $protections = get_field('accessory_protections', $accessory['accessory']->ID);
           $add_features_array = array();

           if ($additional_features) {
            foreach ($additional_features as $feature) {
             $add_features_array[] = $feature['feature'];
           }

           $combined_features = array_merge($features, $add_features_array);

         } else {

          $combined_features = $features;
        }
        ?>

        <li class="orbit-slide">
          <figure class="orbit-figure">
            <div class="accessorie-slide">


              <div class="max-width-wrap-slider">
                <div class="grid-x">


                  <div class="slide-image-wrap cell large-5">

                    <?php
                    $image_gallery = get_field('image_gallery', $accessory['accessory']->ID);
                  //var_dump($image_gallery);
                    if ( $image_gallery && $img_featured = array_shift($image_gallery)) {

                      ?>

                      <a href="<?php echo get_permalink($accessory['accessory']->ID);?>">
                        <img src="<?php echo $img_featured['sizes']['accessory_image']; ?>" />
                        </a> <?php

                      } else {?>
                      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image.jpg" />
                      <?php }?>
                    </div>



                    <div class="slide-content-wrap cell large-7">


                      <h2><?php echo $accessory['accessory']->post_title; ?></h2>

                      <div class="accessory-text-wrap">
                        <p><?php echo content_excerpt(get_field('accessory_text', $accessory['accessory']->ID), 125); ?></p>
                      </div>

                      <div class="features-protections-wrap">

                        <?php if ( $combined_features ) { ?>
                        <div class="features-section">
                          <h4>Features</h4>
                          <ul>
                            <?php
                            $counter = 0;
                            foreach ($combined_features as $feature) {
                              if ($counter < 5) {
                               ?>
                               <li><?php echo $feature; ?></li>
                               <?php
                               $counter++;
                             }
                           }?>
                         </ul>
                       </div>
                       <?php } ?>



                       <?php if ( $protections ) { ?>

                       <div class="protections-wrap">

                        <h4>Protections</h4>

                        <?php foreach( $protections as $protection ) {

                          if ( $protection == 'Overcharge Protection' ) { ?>

                          <div class="protection">
                            <?php get_template_part('assets/svg/over-charge-protection'); ?>
                            <span>Overcharge Protection</span>
                          </div>
                          <?php }

                          if ( $protection == 'Over-Voltage Protection' ) { ?>

                          <div class="protection">
                            <?php get_template_part('assets/svg/voltage-protection'); ?>
                            <span>Over-Voltage Protection</span>
                          </div>
                          <?php }

                          if ( $protection == 'Short Circuit Protection' ) { ?>

                          <div class="protection">
                            <?php get_template_part('assets/svg/short-circuit-protection'); ?>
                            <span>Short Circuit Protection</span>
                          </div>
                          <?php }

                          if ( $protection == 'Over-Current Protection' ) { ?>

                          <div class="protection">
                            <?php get_template_part('assets/svg/current-protection'); ?>
                            <span>Over-Current Protection</span>
                          </div>
                          <?php }

                        } ?>

                      </div>

                      <?php } ?>

                    </div>

                    <a class="gs-button" href="<?php echo get_permalink($accessory['accessory']->ID);?>">View Product</a>

                  </div>
                </div>

              </div>
            </div>
          </figure>
        </li>







        <?php }?>

      </ul>
    </div>
    <nav class="orbit-bullets">
      <?php
      $counter = 0;
      //while ($accessories_query->have_posts()) {
      foreach ( $accessories as $accessory ) {
       //$accessories_query->the_post();?>
       <button <?php if ($counter == 0) {echo 'class="is-active"';}?> data-slide="<?php echo $counter; ?>"><span class="show-for-sr">Slide <?php echo ($counter + 1); ?> details.</span></button>

       <?php
       $counter++;
     }?>

   </nav>
 </div>


</div>



<div class="homepage-slider-items">



</div>

</div><!-- homepage outer wrap -->

<?php
get_footer();







