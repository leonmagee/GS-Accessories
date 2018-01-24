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

//           $slides = get_field('homepage_slides', 'option');
// var_dump($slides); die();
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

          //$accessories = get_field('homepage_featured_accessories', 'option');

          $slides = get_field('homepage_slides', 'option');

          //var_dump($accessories);
          //die('some content');

          //$args = array('post_type' => 'accessories');
          // $accessories_query = new WP_Query($args);
          // while ($accessories_query->have_posts()) {
          //  $accessories_query->the_post();




          foreach( $slides as $slide ) { 

              // var_dump($slide);

              // die();







         //   $features = get_field('accessory_features', $accessory['accessory']->ID);
         //   $additional_features = get_field('additional_features', $accessory['accessory']->ID);
         //   $protections = get_field('accessory_protections', $accessory['accessory']->ID);
         //   $add_features_array = array();

         //   if ($additional_features) {
         //    foreach ($additional_features as $feature) {
         //     $add_features_array[] = $feature['feature'];
         //   }

         //   $combined_features = array_merge($features, $add_features_array);

         // } else {

         //  $combined_features = $features;
         // }

        // if ( $combined_features && $protections ) {
        //   $flex_class = 'flex_class';
        // } else {
        //   $flex_class = '';
        // }
        ?>

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
      //while ($accessories_query->have_posts()) {
      foreach ( $slides as $slide ) {
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







