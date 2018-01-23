<?php
/**
 * Template Name: Order Form
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

restricted_page();

get_header(); ?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <h2>Order Accessories</h2>

    <form class="order-form" method="POST" action="#">

      <div class="form-inner-wrap">

        <div class="input-wrap">
          <label>Choose Product</label>
          <input type="hidden" name="product-order-form" />
          <select name="product" id="product_select_field">

            <?php


            $args = array('post_type' => 'accessories', 'order' => 'ASC');
            $accessories_query = new WP_Query($args);
            $colors_array = array();
            while ($accessories_query->have_posts()) {

              $accessories_query->the_post();

              global $post;

              $product_slug = $post->post_name; 

              $colors = get_field('accessory_colors'); 

              if ( $colors ) {
               $colors_array[$product_slug] =  $colors;
             } else {
              $colors_array[$product_slug] =  false;
            }

            ?>

            <option value="<?php echo $product_slug; ?>"><?php the_title(); ?></option>
            <?php } ?>

          </select>

        </div>

        <div class="input-wrap">

         <label>Choose Quantity</label>
         <select name="quantity">
           <option value="1000">1,000 units</option>
           <option value="2000">2,000 units</option>
           <option value="3000">3,000 units</option>
           <option value="4000">4,000 units</option>
           <option value="5000">5,000 units</option>
         </select>
       </div>

       <?php 

       $counter = 1;

       foreach ( $colors_array as $product_slug => $color_item ) {

         if ( $color_item ) {
          ?>

          <div class="input-wrap color-select color-select-<?php echo $counter; ?> <?php echo $product_slug; ?>">

            <label>Colors</label>

            <select name="colors-<?php echo $product_slug; ?>">

              <?php foreach ( $color_item as $color ) { ?>

              <option value="<?php echo $color; ?>"><?php echo $color; ?></option>

              <?php } ?>

            </select>

          </div>

          <?php } else { ?>

          <div class="input-wrap color-select color-select-<?php echo $counter; ?> <?php echo $product_slug; ?>">

            <label>Colors</label>

            <span class='no-color-options'>N/A</span>

          </div>
          <?php } 

          $counter++;
        } ?>

      </div>

      <div class="button-wrap">

        <button class="gs-button" type="submit">Add Product</button> 

      </div> 

    </form>

  </main><!-- #main -->
</div>
</div><!-- #primary -->

<?php

get_footer();