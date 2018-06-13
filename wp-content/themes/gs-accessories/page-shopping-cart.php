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

//$session_data = $_SESSION;

// foreach( $session_data as $key => $item ) {
//   var_dump($key);
//   echo "<br />";
//   var_dump($item);
//   echo "<br />";
//   echo "<br />";
// }
// if( have_rows('colors_and_quantity') ) {

//   $i = 0;

//   while( have_rows('colors_and_quantity') ) {

//     the_row();

//     $i++;

//     echo "item number " . $i . "<br />";



//     //update_sub_field('caption', "This caption is in row {$i}");

//   }

// }

// $field_key = "colors_and_quantity";
// $post_id = 206;
// $value = get_field($field_key, $post_id);

// foreach( $value as $key => $item ) {
//   if ( $item['color'] === 'Rose Gold' ) {
//     $value[$key]['quantity'] = 777;
//   }
// }
// var_dump($value);

// update_field( $field_key, $value, $post_id );




// $shopping_cart = unserialize($_SESSION['shopping_cart']);
// $product_names = unserialize($_SESSION['product_names']);
// $product_values = unserialize($_SESSION['product_values']);
//$product_id = unserialize($_SESSION['product_id']);
//var_dump($shopping_cart);
// foreach( $shopping_cart as $item ) {
//   //var_dump($item);
// }
// var_dump($_SESSION);
// echo "<br />";
// echo "<br />";
// var_dump($product_names);
// echo "<br />";
// echo "<br />";
// var_dump($product_values);
// echo "<br />";
// echo "<br />";
// var_dump($product_id);

// die();

//shopping_cart
//product_names
//product_values







//die();





$coupon_array = get_coupon_array();

$coupon_applied = false;
$coupon_percent = null;
$current_coupon = '';

if ( $current_coupon = $_GET['coupon']) {
  $coupon_applied = true;
  $coupon_percent = $coupon_array[strtolower($current_coupon)];
}

if ($paypal = $_GET['paypal']) {
  $paypal_mode = true;
} else {
  $paypal_mode = false;
}
?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <?php if ( ! $paypal_mode ) { ?>

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

        $product_details_array = array();
        $product_cost_array = array();

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
          } elseif (current_user_can('edit_posts')) {
            $acf_price = get_field('retail_price', $product_id_actual);
          } else {
            $acf_price = get_field('market_price', $product_id_actual);
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

          $colors = get_accessory_colors($product_id_actual);

          $product_details_string = ( str_replace('-', ' ', $item['product']) ) . ' ';

          if ( $item['color'] ) {
            $product_details_string .= '(' . $item['color'] . ') ';
          }

          $product_details_string .= 'x ' . $item['quantity'];

          $product_details_array[] = $product_details_string;
          $product_cost_array[] = $price;


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

              <div class="cart-property quantity"><span>Quantity: </span><strong><?php echo $item['quantity']; ?></strong>
              </div>

              <div class="cart-property color">
                <span>Color:</span>
                <strong><?php echo $item['color']; ?></strong>
              </div>

              <input type="hidden" name="update-cart-accessory" value=<?php echo $product_id; ?> />

            </form>
            <div class="cart-property remove">
              <form method="post" action="#">

                <input type="hidden" name="remove-cart-accessory" value=<?php echo $product_id; ?> />

                <button type="submit">Remove</button>

              </form>


            </div>
          </div>

        <?php } ?>



        <?php if ( $coupon_percent ) {
          $after_coupon_cost = percent_price($total_cost, $coupon_percent); ?>
          <div class="cart-total">
            Total Cost: <span><strike>$<?php echo number_format($total_cost, 2); ?></strike></span> <span>$<?php echo number_format($after_coupon_cost, 2); ?></span>
          </div>
        <?php } else { ?>
          <div class="cart-total">
            Total Cost: <span>$<?php echo number_format($total_cost, 2); ?></span>
          </div>

        <?php }

        $product_details_final = substr($product_details_string, 0, -3);

        //var_dump($product_details_final); ?>


        <div class="min-amount-wrap">

          <?php
          // @todo test these are all working correctly
          if ( current_user_can('delete_published_posts')) {
            $min_amount = MOQ_WHOLESALER;
          } elseif (current_user_can('edit_posts')) {
            $min_amount = MOQ_DEALER;
          } else {
            $min_amount = 0;
          }

          if ( ! $require_text = get_field('require_text', 'option') ) {
            $require_text = 'MOQ Requirement';
          }
          ?>

          <?php 
          if ( $min_amount ) {
            echo $require_text; ?> $<?php echo number_format($min_amount, 2); 
          }
          ?>

        </div>


        <?php if ($total_cost >= $min_amount) {

          //var_dump($product_details_array);
          //$product_details_array_serial = htmlspecialchars(serialize($product_details_array));
          $_SESSION['product_names'] = serialize($product_details_array);
          $_SESSION['product_values'] = serialize($product_cost_array);

          //var_dump($product_details_array_serial);
          //$product_cost_array_serial = htmlspecialchars(serialize($product_cost_array));
          //var_dump($serial);

          ?>

          <div class="form-grip-wrap">

            <form id="main_form_id" method="post" action="#">

              <label class='add-comment-label'>Add Comment or Suggestion</label>
              <textarea name="customer-comments"></textarea>

              <input type="hidden" name="coupon-code" value="<?php echo $current_coupon; ?>" />

              <input type="hidden" name="place-cart-order" />

              <input type="hidden" name="payment-type" value="Pick Up" />

<!--           <ul class="payment-features-list">
            <li>FREE SHIPPING</li>
            <li>NO PayPal FEES</li>
          </ul> -->

          <?php if ( current_user_can('edit_posts')) { ?>

            <div class="button-wrap">
              <button id="submit_cart_button" type="submit" class="submit-order-button">Pick Up / Bank Wire</button>
            </div>

          <?php } ?>

          <div class="button-wrap">
            <button id="paypal_checkout_button" type="submit" class="submit-order-button">PayPal Checkout</button>
          </div>

        </form>


        <div class="coupon-form-wrapper">

          <?php if ( $coupon_applied ) {

            if ( $coupon_percent ) { ?>

              <div class="callout success">
                <p><span><?php echo $current_coupon; ?></span> Coupon Applied!</p>
              </div>

            <?php } else { ?>

              <div class="callout alert">
                <p>Invalid Coupon</p>
              </div>

            <?php }

          } ?>

          <form method="POST" id="coupon_name_form" action="#">
            <input type="hidden" name="coupon-apply-submit" />
            <input type="text" name="coupon" placeholder="Coupon Code" /> 
            <button type="submit" class="gs-button">Apply Coupon</button>
          </form>
        </div>

      </div>

    <?php } else { ?>

      <button class="submit-order-button disabled">Checkout</button>

    <?php } ?>

  <?php } else { ?>

    <div class="empty-cart">Your Cart is Empty</div>

  <?php } ?>

</div>

<?php } else {

 $product_details_array = unserialize($_SESSION['product_names']);
 $product_cost_array = unserialize($_SESSION['product_values']);
          //$_SESSION['product_values'] = serialize($product_cost_array);

 ?>

 <div class="paypal-wrap-outer">

  <h1 class="entry-title">PayPal Checkout</h1>

  <p><strong>Thank you</strong> for submitting your order with GS Wireless. We highly appreciate your business and the great opportunity you are giving us to serve you. Your order will be processed and shipped within 24 hours after receiving the full payment. A tracking number and shipping carrier information will be emailed to you when it becomes available.</p>

  <div class="paypal-wrap">

    <form id="paypal_form_id" target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post">

      <input type="hidden" name="business" value="gs-wireless@att.net">

      <input type="hidden" name="cmd" value="_cart">

      <input type="hidden" name="upload" value="1">

      <?php 
      $counter = 0;
      foreach($product_details_array as $product_name ) {

        if ( $coupon_percent ) {
          $value = percent_price($product_cost_array[$counter], $coupon_percent);
        } else {
          $value = $product_cost_array[$counter];
        }

        ?>
        <input type="hidden" name="item_name_<?php echo ($counter + 1); ?>" value="<?php echo $product_name; ?>">
        <input type="hidden" name="amount_<?php echo ($counter + 1); ?>" value="<?php echo $value; ?>">
        <?php 
        $counter++;
      } ?>

      <input type="hidden" name="currency_code" value="USD">

      <button id="paypal_button_id" class="paypal-button" type="submit"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/paypal-checkout.png"/></button>

    </form>

  </div>


<?php } ?>

</div>


</main><!-- #main -->
</div>
</div><!-- #primary -->

<?php
get_footer();















