<?php
/**
 * Template Name: Shopping Cart
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GS_Accessories
 */

restricted_page();

get_header();

// reset session
// session_start();
// $_SESSION['shopping_cart'] = '';
?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <h1 class="entry-title">Cart</h1>

    <div class="add-more-items-wrap">
      <a href="/place-your-order">Add More Accessories</a>
    </div>


    <div class='cart-wrap'>

      <?php

      //var_dump(unserialize($_SESSION['shopping_cart']));

      // $cart_data = unserialize($_SESSION['shopping_cart']);

      // $email_body = '';

      // foreach( $cart_data as $id => $data ) {
      //   $product = strtoupper(str_replace('-', ' ' , $data['product']));
      //   $email_body .= '<div>Product ID: ' . $id . ' Product: ' . $product . ' Quantity: ' . $data['quantity'] . ' Color: ' . $data['color'] . '</div>';
      // }

      // var_dump($email_body);



      if ( $_SESSION['shopping_cart'] ) {
        $cart_data = unserialize($_SESSION['shopping_cart']);
        //var_dump($cart_data);
        $total_cost = 0;
        foreach( $cart_data as $product_id => $item ) { // $id => $item

          /**
          * @todo remove this - it will be moved to process function
          * @todo and then key will reference post ID?
          */
          $product_id_exp = explode('-', $product_id);
          $product_id_actual = $product_id_exp[0];
        // $page_object = get_page_by_path($item['product'], OBJECT, 'accessories');
        // $post_id = $page_object->ID;
        $post_image = get_field('image_gallery', $product_id_actual);
        if ( $post_image ) {
          $img_url = $post_image[0]['sizes']['thumbnail'];
        } else {
          // create new placeholder image
          $img_url = get_template_directory_uri() . "/assets/img/placeholder-image-small.jpg";
        }



        if ( current_user_can('delete_published_posts')) {
            $acf_price = get_field('wholesale_price', $product_id_actual);
        } else {
            $acf_price = get_field('retail_price', $product_id_actual);
        }

        if ( $acf_price ) {
          $price = $acf_price * $item['quantity'];
          $acf_price_per = '$' . number_format($acf_price, 2);
          $price_value = '$' . number_format($price, 2);
          $total_cost = $price + $total_cost;
        } else {
          $acf_price = false;
          $price_value = 'N/A';
        }

        //$quantity_array = array('1000','2000','3000','4000','5000');

        $colors = get_field('accessory_colors', $product_id_actual );

          ?>

          <div class="cart-item">
            <div class="cart-property thumb">
              <img src="<?php echo $img_url; ?>" />
            </div>

            <div class="cart-property product"><label>Product</label>
              <a href="/accessories/<?php echo $item['product']; ?>">
                <?php echo str_replace('-', ' ', $item['product']); ?>
              </a>
            </div>

            <div class="cart-property price">
              <label>Total Cost</label>
              <div class="price-line"><?php echo $price_value; ?></div>
              <?php if ( $acf_price ) { ?>
              <div class="details"><strong><?php echo $acf_price_per; ?></strong> per unit</div>
              <?php } ?>
            </div>

            <form class="details-form" method="post" action="#">

            <div class="cart-property quantity"><span>Quantity:</span>

              <input name="accessory-quantity" type="number" value="<?php echo $item['quantity']; ?>" />
              
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

          <div class="cart-total">
            Total Cost: <span>$<?php echo number_format($total_cost, 2); ?></span>
          </div>


          <div class="min-amount-wrap">

          <?php
            if ( current_user_can('delete_published_posts')) {
                $min_amount = 3000;
            } else {
                $min_amount = 300;
            }

            if ( ! $require_text = get_field('require_text', 'option') ) {
                $require_text = 'MOQ Requirement';
            }
          ?>

            <?php echo $require_text; ?> $<?php echo number_format($min_amount, 2); ?>

          </div>


        <?php if ($total_cost >= $min_amount) { ?>

        <form method="post" action="#">
          
          <input type="hidden" name="place-cart-order" />

          <button type="submit" class="submit-order-button">Place Your Order</button>

        </form>

        <?php } else { ?>
  
          <button class="submit-order-button disabled">Place Your Order</button>

        <?php } ?>

        <?php } else {?>
          
          <div class="empty-cart">Your Cart is Empty</div>

          <?php } ?>

        </div>




      </main><!-- #main -->
    </div>
  </div><!-- #primary -->

  <?php
  get_footer();















