<?php
/**
 * Template Name: Shopping Cart
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */
get_header();

// reset session
// session_start();
// $_SESSION['shopping_cart'] = '';
?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <h2>Cart</h2>


    <div class='cart-wrap'>

      <?php

      //var_dump(unserialize($_SESSION['shopping_cart']));

      if ( $_SESSION['shopping_cart']) {
        $cart_data = unserialize($_SESSION['shopping_cart']);
        //var_dump($cart_data);
        foreach( $cart_data as $product_id => $item ) { // $id => $item

          /**
          * @todo remove this - it will be moved to process function
          * @todo and then key will reference post ID?
          */
        // $page_object = get_page_by_path($item['product'], OBJECT, 'accessories');
        // $post_id = $page_object->ID;
        $post_image = get_field('image_gallery', $product_id);
        if ( $post_image ) {
          $img_url = $post_image[0]['sizes']['thumbnail'];
        } else {
          // create new placeholder image
          $img_url = get_template_directory_uri() . "/assets/img/placeholder-image-small.jpg";
        }

        //$quantity_array = array('1000','2000','3000','4000','5000');
        $quantity_array = array('1000','2000','3000','4000','5000');

        $colors = get_field('accessory_colors', $product_id );
        //var_dump($colors);

          ?>

          <div class="cart-item">
            <div class="cart-property thumb">
              <img src="<?php echo $img_url; ?>" />
            </div>

            <div class="cart-property product"><span>Product:</span>
              <a href="/accessories/<?php echo $item['product']; ?>">
                <?php echo str_replace('-', ' ', $item['product']); ?>
              </a>
            </div>

            <form class="details-form" method="post" action="#">

            <div class="cart-property quantity"><span>Quantity:</span>


              <?php //echo $item['quantity']; ?>
              
               <select name="accessory-quantity">
                <?php foreach( $quantity_array as $quantity_item ) { 
                    if ( $quantity_item == $item['quantity'] ) {
                      $selected = 'selected="selected"';
                    } else {
                      $selected = '';
                    }
                  ?>
                 <option <?php echo $selected; ?> value="<?php echo $quantity_item; ?>"><?php echo number_format(intval($quantity_item)); ?></option>
                 <?php } ?>
               </select>

            </div>

            <div class="cart-property color">
              <?php if ( $colors ) { ?>
              <span>Color:</span>

               <select name="accessory-color">
                <?php foreach( $colors as $color ) { 
                    if ( $color == $item['color'] ) {
                      $selected = 'selected="selected"';
                    } else {
                      $selected = '';
                    }
                  ?>
                 <option <?php echo $selected; ?> value="<?php echo $color; ?>">
                  <?php echo $color; ?></option>
                 <?php } ?>
               </select>

              <?php } ?>

            </div>

            <input type="hidden" name="update-cart-accessory" value=<?php echo $product_id; ?> />

              <div class="cart-property update">

                    <button type="submit">Update</button>

              </div>

          </form>
            <div class="cart-property remove">
              <form method="post" action="#">

                <input type="hidden" name="remove-cart-accessory" value=<?php echo $product_id; ?> />

              <button type="submit">Remove</button>

            </form>


            </div>
          </div>

          <?php } ?>


        <form method="post" action="#">
          
          <input type="hidden" name="place-cart-order" />

          <button type="submit" class="submit-order-button">Place Your Order</button>

        </form>

        <?php } else {?>
          
          <div class="empty-cart">Your Cart is Empty</div>

          <?php } ?>

        </div>




      </main><!-- #main -->
    </div>
  </div><!-- #primary -->

  <?php
  get_footer();















