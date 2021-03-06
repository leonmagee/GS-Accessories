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

$coupon_array = get_coupon_array();

$coupon_applied = false;
$coupon_percent = null;
$current_coupon = '';

if(isset($_GET['coupon'])) {
  if ( $current_coupon = $_GET['coupon']) {
    $coupon_applied = true;
    $coupon_percent = $coupon_array[strtolower($current_coupon)];
  }
}

$current_credit = intval(get_field('credit_value', 'user_' . LV_LOGGED_IN_ID));

if (isset($_GET['paypal']) && $paypal = $_GET['paypal']) {
  $paypal_mode = true;
} else {
  $paypal_mode = false;
}

$behalf_total = 0;
if (isset($_GET['behalf']) && $paypal = $_GET['behalf']) {
  $behalf_mode = true;
  $behalf_total = $_GET['total'];
} else {
  $behalf_mode = false;
}
?>

<div id="primary" class="content-area">

  <div class="max-width-wrap accessories-archive">

   <main id="main" class="site-main">

    <?php if ( ( ! $paypal_mode ) && ( ! $behalf_mode ) ) { ?>

      <h1 class="entry-title">Cart</h1>

      <div class="add-more-items-wrap">
        <a href="/place-your-order">Add More Accessories</a>
      </div>

      <div class='cart-wrap'>

        <?php

        $product_details_array = array();
        $product_cost_array = array();

        if ( isset($_SESSION['shopping_cart']) && ($cart_data = unserialize($_SESSION['shopping_cart']))) {
          //$cart_data = unserialize($_SESSION['shopping_cart']);
          $total_cost = 0;
        foreach( $cart_data as $product_id => $item ) { // $id => $item

          $product_id_exp = explode('-', $product_id);
          $product_id_actual = $product_id_exp[0];
          $post_image = get_field('image_gallery', $product_id_actual);
          if ( $post_image ) {
            $img_url = $post_image[0]['sizes']['thumbnail'];
          } else {
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

          $colors = get_accessory_colors($product_id_actual);

          $product_details_string = ( str_replace('-', ' ', $item['product']) ) . ' ';

          if ( $item['color'] ) {
            $product_details_string .= '(' . $item['color'] . ') ';
          }

          $product_details_string .= 'x ' . $item['quantity'];

          $product_details_array[] = $product_details_string;
          $product_cost_array[] = $price;

          $product_object = get_page_by_path($item['product'], OBJECT, 'accessories');
          $product_name_new = $product_object->post_title;



          ?>

          <div class="cart-item">
            <div class="cart-property thumb">
              <img src="<?php echo $img_url; ?>" />
            </div>

            <div class="cart-property product"><label>Product</label>
              <a href="/accessories/<?php echo $item['product']; ?>">
                <?php echo $product_name_new; ?>
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

        <?php
        $credit_used = 0;
        $original_cost = $total_cost;
        $show_paypal_button = true;

        if ( $coupon_percent ) {

          if ( ( $current_credit ) && ( $current_credit >= 0 ) ) {

            if ( $total_cost === $current_credit ) {
              $show_paypal_button = false;
            }
            if ( $total_cost >= $current_credit ) {
              $credit_used = $current_credit;
              $after_coupon_cost = percent_price($total_cost, $coupon_percent);
              $final_final_cost = $after_coupon_cost - $current_credit;
              //$minus_credit_cost = ( $total_cost - $current_credit );
            } else {
              $show_paypal_button = false;
              $credit_used = $total_cost;
              $after_coupon_cost = percent_price($total_cost, $coupon_percent);
              $current_credit = $after_coupon_cost;
              $final_final_cost = 0;
              $final_final_cost = $after_coupon_cost - $current_credit;
            }



            ?>
            <div class="cart-total">
              Total Cost: <span><strike>$<?php echo number_format($total_cost, 2); ?></strike></span> <span>$<?php echo number_format($after_coupon_cost, 2); ?></span> - (<span class="credit">$<?php echo number_format($current_credit, 2); ?></span> credit) = <span>$<?php echo number_format($final_final_cost, 2); ?></span>
            </div>
            <?php


          } else {

            $after_coupon_cost = percent_price($total_cost, $coupon_percent);
            $final_final_cost = $after_coupon_cost;
            ?>
            <div class="cart-total">
              Total Cost: <span><strike>$<?php echo number_format($total_cost, 2); ?></strike></span> <span>$<?php echo number_format($final_final_cost, 2); ?></span>
            </div>
            <?php

          }

        } else {

          if ( ( $current_credit ) && ( $current_credit >= 0 ) ) {

            if ( $total_cost === $current_credit ) {
              $show_paypal_button = false;
            }
            if ( $total_cost >= $current_credit ) {
              $credit_used = $current_credit;
              $final_final_cost = ( $total_cost - $current_credit );
            } else {
              $show_paypal_button = false;
              $credit_used = $total_cost;
              $current_credit = $total_cost;
              $final_final_cost = 0;
            }

            ?>
            <div class="cart-total">
              Total Cost: <span>$<?php echo number_format($total_cost, 2); ?></span> - (<span class="credit">$<?php echo number_format($current_credit, 2); ?></span> credit) = <span>$<?php echo number_format($final_final_cost, 2); ?></span>
            </div>

            <?php

          } else {

            $final_final_cost = $total_cost;
            ?>
            <div class="cart-total">
              Total Cost: <span>$<?php echo number_format($final_final_cost, 2); ?></span>
            </div>

            <?php
          }

        }

        $product_details_final = substr($product_details_string, 0, -3);

        ?>

        <div class="min-amount-wrap">

          <?php
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


        <?php if ($original_cost >= $min_amount) {

          $_SESSION['product_names'] = serialize($product_details_array);
          $_SESSION['product_values'] = serialize($product_cost_array);

          ?>

          <div class="form-grip-wrap">

            <form id="main_form_id" method="post" action="#">

              <label class='add-comment-label'>Add Comment or Suggestion</label>
              <textarea name="customer-comments"></textarea>

              <input type="hidden" name="coupon-code" value="<?php echo $current_coupon; ?>" />

              <input type="hidden" name="place-cart-order" />

              <input type="hidden" name="payment-type" value="Pick Up" />

              <input type="hidden" name="credit-used" value="<?php echo $credit_used; ?>" />

              <?php if ( current_user_can('edit_posts')) { ?>

                <div class="button-wrap">
                  <button id="submit_cart_button" type="submit" class="submit-order-button">Pick Up / Drop Off</button>
                  <p class="only-sd-text">ONLY Available to San Diego Retailers</p>
                </div>

              <?php } ?>

              <?php
              if ( $show_paypal_button ) { ?>

              <div class="button-wrap">
                <button id="behalf_button_id" class="behalf-button" type="submit">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/behalf-checkout.png"/>
                </button>
              </div>

              <?php } ?>

              <?php if ( current_user_can('edit_posts')) { ?>

              <div class="button-wrap">
                <button id="wire_direct_button" type="submit" class="submit-order-button">Wire Transfer / Direct Deposit</button>
              </div>

              <?php } ?>

              <?php
              if ( $show_paypal_button ) { ?>

                <div class="button-wrap">
                  <button id="paypal_checkout_button" class="paypal-button" type="submit">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/paypal-checkout-larger.png"/>
                  </button>
                </div>
              <?php } ?>

              <?php if ( current_user_can('edit_posts')) { ?>

                <div class="button-wrap">
                  <button id="venmo_ca_button" type="submit" class="paypal-button">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cash-app-venmo-checkout.jpg"/>
                  </button>
                </div>

              <?php } ?>

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

  <?php } else if ($paypal_mode) { // else if paypal? I need to update this to work with behalf

   $product_details_array = unserialize($_SESSION['product_names']);
   $product_cost_array = unserialize($_SESSION['product_values']);

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
        $discount_string = '';
        if ( isset( $_GET['misc'])) {

          //$discount_value = 200;
          $salt_1 = 'sldkfj29374297%%!!sldfj';
          $salt_2 = 'xxxx2937429347&sdklhfsl';
          //$salted_string = $salt_1 . $discount_value . $salt_2;
          //$encrypted_string = urlencode(base64_encode($salted_string));
          $encrypted_string = $_GET['misc'];
          //var_dump($encrypted_string);
          //c2xka2ZqMjkzNzQyOTclJSEhc2xkZmo5OXh4eHgyOTM3NDI5MzQ3JnNka2xoZnNs
          $decrypted_string = base64_decode(urldecode($encrypted_string));

          if ( ( ! preg_match('/' . $salt_1 . '/', $decrypted_string ) ) || (! preg_match('/' . $salt_2 . '/', $decrypted_string ) ) ) {
            $discount_applied = false;
          }

          $string_1 = str_replace($salt_1, '', $decrypted_string);
          $discount_amount = intval(str_replace($salt_2, '', $string_1));
          $discount_string = '<input type="hidden" name="discount_amount_cart" value="' . $discount_amount . '">';
        }

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
        }

        echo $discount_string;
        ?>

        <input type="hidden" name="currency_code" value="USD">

        <button id="paypal_button_id" discount_amount_cart
        =333 class="paypal-button" type="submit"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/paypal-checkout-larger.png"/></button>

      </form>

    </div>

  <?php } else if ($behalf_mode) { ?>
    <div class="paypal-wrap-outer">
      <h1 class="entry-title">Behalf Checkout</h1>
       <p><strong>Thank you</strong> for submitting your order with GS Wireless. We highly appreciate your business and the great opportunity you are giving us to serve you. Your order will be processed and shipped within 24 hours after receiving the full payment. A tracking number and shipping carrier information will be emailed to you when it becomes available.</p>
       <p>Please pay the following amount through Behalf: <h2>$<?php echo $behalf_total; ?></h2></p>
      <div class="paypal-wrap">
        <div class="behalf-wrap">
          <div id="behalf-payment-element"></div>
        </div>
      </div>
    </div>
    <?php }
    ?>
  </div>

</main><!-- #main -->

</div>

</div><!-- #primary -->

<?php get_footer();
