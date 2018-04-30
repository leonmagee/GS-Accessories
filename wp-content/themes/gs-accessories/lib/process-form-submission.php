<?php

/**
* Add item to cart
*/
if ( isset($_POST['product-order-form'])) {

	$product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_SPECIAL_CHARS);
	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_SPECIAL_CHARS);

	if ( $product ) {
		$color = filter_input(INPUT_POST, 'colors-' . $product, FILTER_SANITIZE_SPECIAL_CHARS);
	}

	if ( !$color ) {
		$color = false;
	}

	if ( $quantity ) {

		$page_object = get_page_by_path($product, OBJECT, 'accessories');
		$post_id = $page_object->ID;
		$time = time();
		$array_key = $post_id . '-' . $time;

	//die($product . ' - ' . $quantity . ' - ' . $color);

		$ShopingCart = new shopping_cart();

    //$Basket->do_actions(); 
    // my own hooks to allow me to add housekeeping code without messing with my core code

		$ShopingCart->add_data($product, $quantity, $color);
    //$ShopingCart->add_data(time(),'magnetic case', 2000, 'black');
    // $ShopingCart->add_data('USB Charger', 1000, 'white');
    //var_dump($ShopingCart);

		session_start();

		if ( $_SESSION['shopping_cart'] ) {

			$current_data = unserialize($_SESSION['shopping_cart']);

		} else {
			$current_data = array();
		}

		$current_data[$array_key] = $ShopingCart->cart_data;

		$_SESSION['shopping_cart'] = serialize($current_data);

		wp_redirect('/place-your-order?success=true');
		exit;

	} else {
		wp_redirect('/place-your-order?required=quantity');
		exit;
	}
}

/**
* Add item to cart from single product page
*/
if ( isset($_POST['add-one-accessory'])) {

	$product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_SPECIAL_CHARS);

	$post_id = filter_input(INPUT_POST, 'add-one-accessory', FILTER_SANITIZE_SPECIAL_CHARS);


	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_SPECIAL_CHARS);

	if ( ! $quantity ) {
		$quantity = 1;
	}

	// if ( $product ) {
	// 	$color = filter_input(INPUT_POST, 'colors-' . $product, FILTER_SANITIZE_SPECIAL_CHARS);
	// }

	$color = filter_input(INPUT_POST, 'color-select', FILTER_SANITIZE_SPECIAL_CHARS);



	$time = time();
	$array_key = $post_id . '-' . $time;

	// $colors = get_field('accessory_colors', $post_id);

	// if ( $colors ) {
	// 	$color = $colors[0];
	// } else {
	// 	$color = false;
	// }

	$ShopingCart = new shopping_cart();

	$ShopingCart->add_data($product, $quantity, $color);

	session_start();

	if ( $_SESSION['shopping_cart'] ) {

		$current_data = unserialize($_SESSION['shopping_cart']);
		
	} else {
		$current_data = array();
	}

	$current_data[$array_key] = $ShopingCart->cart_data;

	$_SESSION['shopping_cart'] = serialize($current_data);

	$redirect_url = '/' . $product . '?added-to-cart=true';

	wp_redirect($redirect_url);

	exit;
}

/**
* Remove Cart Item from Session
*/
if ( isset($_POST['remove-cart-accessory'])) {

	$post_id = filter_input(INPUT_POST, 'remove-cart-accessory', FILTER_SANITIZE_SPECIAL_CHARS);
	session_start();

	$shopping_cart_array = unserialize($_SESSION['shopping_cart']);

	unset($shopping_cart_array[$post_id]);

	if ( $shopping_cart_array ) {

		$_SESSION['shopping_cart'] = serialize($shopping_cart_array);
	} else {

		$_SESSION['shopping_cart'] = '';
	}

}


/**
* Update Cart Item in Session
*/
if ( isset($_POST['update-cart-accessory'])) {


	$post_id = filter_input(INPUT_POST, 'update-cart-accessory', FILTER_SANITIZE_SPECIAL_CHARS);

	$quantity = filter_input(INPUT_POST, 'accessory-quantity', FILTER_SANITIZE_SPECIAL_CHARS);

	$color = filter_input(INPUT_POST, 'accessory-color', FILTER_SANITIZE_SPECIAL_CHARS);

	session_start();

	$shopping_cart_array = unserialize($_SESSION['shopping_cart']);

	$item_array = $shopping_cart_array[$post_id];

	$item_array['quantity'] = $quantity;

	$item_array['color'] = $color;

	$shopping_cart_array[$post_id] = $item_array;

	$_SESSION['shopping_cart'] = serialize($shopping_cart_array);
}

/**
* Place Cart Order
*/
if ( isset($_POST['place-cart-order'])) {

	session_start();

	$comments = filter_input(INPUT_POST, 'customer-comments', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$payment_type = filter_input(INPUT_POST, 'payment-type', FILTER_SANITIZE_SPECIAL_CHARS);

	// if ( $payment_type == 'PayPal') {

	// 	$product_names = filter_input(INPUT_POST, 'product-names', FILTER_SANITIZE_SPECIAL_CHARS);
	// 	$product_values = filter_input(INPUT_POST, 'product-names', FILTER_SANITIZE_SPECIAL_CHARS);
	// }

	$shopping_cart_array = unserialize($_SESSION['shopping_cart']);

	// get user email address
	$user = wp_get_current_user(); 
	$user_email = $user->data->user_email;
	//var_dump($user->data->user_email);

	// get admin email address
	$admin_email = get_option('admin_email');
	//var_dump($admin_email);
	$user_id = $user->data->ID;
	$first_name = get_user_meta($user_id, 'first_name', true);
	$last_name = get_user_meta($user_id, 'last_name', true);
	$company_name = get_user_meta($user_id, 'company', true);
	$address = get_user_meta($user_id, 'address', true);
	$city = get_user_meta($user_id, 'city', true);
	$state = get_user_meta($user_id, 'state', true);
	$zip = get_user_meta($user_id, 'zip', true);

	// var_dump($user->data->user_nicename);
	// var_dump($first_name . ' ' . $last_name);
	if ( $first_name && $last_name ) {
		$user_name = $first_name . ' ' . $last_name;
	} else {
		$user_name = $user->data->user_nicename;
	}
	//die();

	//die('working so far');

	//$cart_data = unserialize($_SESSION['shopping_cart']);

	//$email_body = '<br /><div><strong>Comments</strong><br />' . $comments . '</div><br />';

	$email_body = '';

	$total_cost = 0;

	foreach( $shopping_cart_array as $id => $data ) {
		$product = strtoupper(str_replace('-', ' ' , $data['product']));
		$product_id_exp = explode('-', $id);
		$product_id_actual = $product_id_exp[0];

		if ( current_user_can('delete_published_posts')) {
			$acf_price = get_field('wholesale_price', $product_id_actual);
		} else {
			$acf_price = get_field('retail_price', $product_id_actual);
		}

		if ( $acf_price ) {
			$price = $acf_price * $data['quantity'];
			$acf_price_per = '$' . number_format($acf_price, 2);
			$price_value = '$' . number_format($price, 2);
			$total_cost = $price + $total_cost;
		} else {
          //$acf_price = false;
			$price_value = 'N/A';
		}

		$quantity_fmt = number_format($data['quantity']);


		$email_body .= '<div>Product: <strong>' . $product . '</strong><br />Quantity: <strong>' . $quantity_fmt . '</strong><br />Color: <strong>' . $data['color'] . '</strong><br />Unit Cost: <strong>' . $acf_price_per . '</strong><br />Total Cost: <strong>' . $price_value . '</strong></div><br />';
	}

	$total_cost_final = '$' . number_format( $total_cost, 2 );

	$email_body = $email_body . '<br /><div><strong>Comments</strong><br />' . $comments . '</div><br />' . '<div><strong>Total Charges: ' . $total_cost_final . '</strong></div>';

	// send email to admin
	$admin_intro = '<div>Order placed by <strong>' . $user_name . '</strong><br />Company: <strong>' . $company_name . '</strong><br />Address: <strong>' . $address . '</strong><br /><strong>' . $city . ', ' . $state . ' ' . $zip . '</strong><br />Email: <strong>' . $user_email . '</strong><br />Order Type: <strong>' . $payment_type . '</strong></div><br />';
	$to = array($admin_email, 'leonmagee33@gmail.com');
	//$to = array($admin_email, 'leonmagee@hotmail.com');

	$subject = 'GS Accessories Order';
	$body = $admin_intro . $email_body;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body, $headers );

	if ( $payment_type == 'PayPal' ) {
		$payment_instructions = get_field('paypal_instructions', 'option');
	} else {
		$payment_instructions = get_field('pick_up_instructions', 'option');
	}


	// $payment_instructions = '<div>
	// <div>Thank you for submitting your order with GS Wireless, we highly appreciate your business. You are one step away from completing your order by submitting your payment to us through either option below.</div>
	// <div>
	// <br />
	// <div><strong>Option #1 (PayPal Payment)</strong></div>
	// <div>
	// Remit payment through PayPal to ' . $admin_email . ' and choose send to (family or friends) to avoid extra fee otherwise 3% charge will be applied to your total invoice.</div>
	// </div>
	// <div>
	// <br />
	// <div><strong>Option #2 (Bank Wire, Check Or Cash Deposit)</strong><div>
	// <div>Cash deposit can be made at any US Bank, check or other form of deposit including money order could take more than 72H to clear. We required a copy of the deposit slip, branch phone number and teller name to confirm deposit type.</div>
	// </div>
	// <div>
	// <br />
	// <div>Wire Transfer Information:</div>
	// <div>Bank Name: U.S. Bank</div>
	// <div>Account Holder: Golden State Wireless Inc.</div>
	// <div>Account Number: 153497481058</div>
	// <div>Routing Number: 122235821</div>
	// <div>U.S. Bank SWIFT code: USBKUS44IMT (for international use)</div>
	// </div>
	// <br />
	// <div><strong>Order Details</strong><div><br />';


	// send email to user
	$to = $user_email; // get admin email here
	$subject = 'GS Accessories Order';
	$body = $payment_instructions . $email_body;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body, $headers );

	// clear out cart of items (empty Session)
	$_SESSION['shopping_cart'] = '';


	/**
	* Create Post to record order
	*/

	$order_title = $user_name . ' - ID: ' . $user_id . ' - ' . date("F j, Y, g:i a");

	$args = array(
		'post_title' => $order_title,
		'post_type' => 'orders',
		'post_status' => 'publish'
	);

	$new_order_id = wp_insert_post($args);

	update_field('comments', $comments, $new_order_id);
	update_field('order_type', $payment_type, $new_order_id);
	update_field('total_charge', $total_cost_final, $new_order_id);


	foreach( $shopping_cart_array as $id => $data ) {

		$product_id_exp = explode('-', $id);
		$product_id_actual = $product_id_exp[0];

		$product = strtoupper(str_replace('-', ' ' , $data['product']));
		$quantity = $data['quantity'];
		$color = $data['color'];

		if ( current_user_can('delete_published_posts')) {
			$acf_price = get_field('wholesale_price', $product_id_actual);
		} else {
			$acf_price = get_field('retail_price', $product_id_actual);
		}

		if ( $acf_price ) {
			$price = $acf_price * $data['quantity'];
			$acf_price_per = '$' . number_format($acf_price, 2);
			$price_value = '$' . number_format($price, 2);
			//$total_cost = $price + $total_cost;
		} else {
          //$acf_price = false;
			$acf_price_per = '';
			$price_value = '';
		}



		$row = array(
			'product_name'	=> $product,
			'product_quantity'	=> $quantity,
			'product_color'	=> $color,
			'unit_cost' => $acf_price_per,
			'cost_total' => $price_value
		);

		add_row('product_entries', $row, $new_order_id);


	}


	// var_dump($new_order_id);
	// die('new post working?');

	// here we need to determine if this is a PayPal order or a pick up order


	// redirect to order placed page
	if ( $payment_type == 'Pick Up' ) {
		wp_redirect('/order-placed');
	} elseif ( $payment_type == 'PayPal' ) {
		//wp_redirect('/cart?paypal=show&paypal_names=' . $product_names . '&paypal_values=' . $product_values);
		wp_redirect('/cart?paypal=show');
	}
	exit;
}



/**
* Update Cart Item in Session
*/
if ( isset($_POST['coupon-apply-submit'])) {


	$coupon_name = filter_input(INPUT_POST, 'coupon', FILTER_SANITIZE_SPECIAL_CHARS);

	wp_redirect('/cart?coupon=' . $coupon_name);
	// var_dump($coupon_name);
	// die('coupons are working?');

}





