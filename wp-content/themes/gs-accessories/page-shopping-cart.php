<?php
/**
 * Template Name: Shopping Cart
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */
get_header();
?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <h2>Cart</h2>


    <div class='cart-wrap'>

      <?php

      var_dump(unserialize($_SESSION['shopping_cart']));

      if ( $_SESSION['shopping_cart']) {
        $cart_data = unserialize($_SESSION['shopping_cart']);
        //var_dump($cart_data);
        foreach( $cart_data as $item ) {

        $page_object = get_page_by_path($item['product'], OBJECT, 'accessories');
        $post_id = $page_object->ID;
        $post_image = get_field('image_gallery', $post_id);
        if ( $post_image ) {
          $img_url = $post_image[0]['sizes']['thumbnail'];
        } else {
          // create new placeholder image
          $img_url = '';
        }

          ?>

          <div class="cart-item">
            <div class="cart-property thumb">
              <img src="<?php echo $img_url; ?>" />
            </div>
            <div class="cart-property product"><span>Product:</span><?php echo str_replace('-', ' ', $item['product']); ?></div>
            <div class="cart-property quantity"><span>Quantity:</span><?php echo $item['quantity']; ?></div>
            <div class="cart-property color"><span>Color:</span><?php echo $item['color']; ?></div>
            <div class="cart-property remove"><a href="#">Remove</a></div>
          </div>

          <?php } }?>

        </div>


      </main><!-- #main -->
    </div>
  </div><!-- #primary -->

  <?php
  get_footer();















