<?php
/**
 * Template Name: Order Form
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

if ( isset( $_GET['required'])) {
  if ($_GET['required'] == 'quantity' ) {
    $required_warning = true;
  } else {
    $required_warning = false;
  }
} else {
  $required_warning = false;
}

if ( isset( $_GET['success'])) {
  if ($_GET['success'] == 'true' ) {
    $success_notice = true;
  } else {
    $success_notice = false;
  }
} else {
  $success_notice = false;
}

restricted_page();

get_header(); ?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <h2>Order Accessories</h2>

    <?php if ( $required_warning ) { ?>
      <div class="quantity-required callout alert">
        A Quantity is Required
      </div>
    <?php } ?>

    <?php if ( $success_notice ) { ?>
      <div class="added-to-cart callout success">
        Successfully added to cart. <a href="/cart">View Cart</a>.
      </div>
    <?php } ?>

    <form class="order-form" id="add_to_order" method="POST" action="#">

      <div class="form-inner-wrap">

        <div class="input-wrap">
          <label>Choose Product<span>*</span></label>
          <input type="hidden" name="product-order-form" />
          <select name="product" id="product_select_field">

            <?php

            $args = array(
              'post_type' => 'accessories', 
              'order' => 'ASC',
              'post__not_in' => LV_HIDE_ACCESSORIES
            );
            $accessories_query = new WP_Query($args);
            $colors_array = array();
            while ($accessories_query->have_posts()) {

              $accessories_query->the_post();

              global $post;

              $product_slug = $post->post_name; 

              $colors = get_accessory_colors();

              if ( ! $colors ) {
                continue;
              }

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

       <label>Enter Quantity<span>*</span></label>

       <?php 

       $outer_counter = 1;
       foreach ( $colors_array as $product_slug => $color_item ) {
        $counter = 1;
        foreach ( $color_item as $color => $quantity ) {
          if ( $outer_counter === 1 ) {
            $namer = 'quantity';
          } else {
            $namer = 'not-quantity';
          }
          $counter_class = 'item-' . $counter;
          $counter_class_outer = 'item-outer-' . $outer_counter;
          $slug_class = $product_slug . '-' . $counter;
          $counter++;
          $outer_counter++;
          $color_class = strtolower(str_replace(' ', '-', $color));
          ?>

          <input quantity="<?php echo $quantity; ?>" class="quantity-input <?php echo $color_class . ' ' . $counter_class . ' ' . $counter_class_outer . ' ' . $slug_class; ?> " name="<?php echo $namer; ?>" type="number" placeholder="Max <?php echo $quantity; ?>" />

        <?php } ?>


      <?php } ?>

    </div>

    <?php 

    $counter = 1;

    foreach ( $colors_array as $product_slug => $color_item ) {

     if ( $color_item ) {
      ?>

      <div class="input-wrap color-select color-select-<?php echo $counter; ?> <?php echo $product_slug; ?>">

        <label>Colors</label>

        <select name="colors-<?php echo $product_slug; ?>">

          <?php 

          $inner_counter = 1;

          foreach ( $color_item as $color => $quantity ) { 

            $counter_class = $product_slug . '-' . $inner_counter;

            ?>

            <option item_id="<?php echo $counter_class; ?>" value="<?php echo $color; ?>"><?php echo $color; ?></option>

          <?php 
          
          $inner_counter++;
        
          } ?>

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

<div class="view-cart-wrap">

  <a class="gs-button" href="/cart">View Cart</a> 

</div>

</main><!-- #main -->
</div>
</div><!-- #primary -->

<?php

get_footer();