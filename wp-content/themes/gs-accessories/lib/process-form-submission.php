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

		$ShopingCart->add_data($product, $quantity, $color, $post_id);
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

	// $colors = get_field('accessory_xxx_colors', $post_id);

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
* Place Cart Order
*/
if ( isset($_POST['place-cart-order'])) {

	session_start();

	$comments = filter_input(INPUT_POST, 'customer-comments', FILTER_SANITIZE_SPECIAL_CHARS);

	$payment_type = filter_input(INPUT_POST, 'payment-type', FILTER_SANITIZE_SPECIAL_CHARS);

	$coupon_code = filter_input(INPUT_POST, 'coupon-code', FILTER_SANITIZE_SPECIAL_CHARS);

	$credit_used = filter_input(INPUT_POST, 'credit-used', FILTER_SANITIZE_SPECIAL_CHARS);

	$shopping_cart_array = unserialize($_SESSION['shopping_cart']);

	// get user email address
	$user = wp_get_current_user();
	$user_email = $user->data->user_email;

	// get admin email address
	$admin_email = get_option('admin_email');
	$user_id = $user->data->ID;
	$first_name = get_user_meta($user_id, 'first_name', true);
	$last_name = get_user_meta($user_id, 'last_name', true);
	$company_name = get_user_meta($user_id, 'company', true);
	$address = get_user_meta($user_id, 'address', true);
	$city = get_user_meta($user_id, 'city', true);
	$state = get_user_meta($user_id, 'state', true);
	$zip = get_user_meta($user_id, 'zip', true);
	$ref_agent_id = get_user_meta($user_id, 'referring_agent', true);
	$ref_agent_email = false;
	if ( $ref_agent_id ) {
		$user_object = get_userdata($ref_agent_id);
		$ref_agent_email = $user_object->user_email;
	}

	if ( $first_name && $last_name ) {
		$user_name = $first_name . ' ' . $last_name;
	} else {
		$user_name = $user->data->user_nicename;
	}

	// process credit
	if ( $credit_used ) {
		$current_credit = intval(get_field('credit_value', 'user_' . $user_id));
		$credit_used_val = intval($credit_used);
		$new_credit_value = ( $current_credit - $credit_used_val );
		update_field('credit_value', $new_credit_value, 'user_' . $user_id);
	}

	// create new order to get po number
	$order_title = $user_name . ' - ID: ' . $user_id . ' - ' . date("F j, Y, g:i a");

	$args = array(
		'post_title' => $order_title,
		'post_type' => 'orders',
		'post_status' => 'publish'
	);

	$new_order_id = wp_insert_post($args);

	$po_number = 'GSA-ODR-' . $new_order_id;
	// end order create for now


	//$email_body = '';
	$email_body = '<div><span style="color: #0E509C">PO Number: </span><strong>' . $po_number . '</strong></div><br />';

	$total_cost = 0;

	$change_quantity_array = array();

	foreach( $shopping_cart_array as $id => $data ) {

		//$product = strtoupper(str_replace('-', ' ' , $data['product']));
		$product_id_exp = explode('-', $id);
		$product = get_the_title($id);
		$product_id_actual = $product_id_exp[0];

		$change_quantity_array[] = array(
			'id' => $product_id_actual,
			'quantity' => $data['quantity'],
			'color' => $data['color']
		);

		if ( current_user_can('delete_published_posts')) {
			$acf_price = get_field('wholesale_price', $product_id_actual);
		} elseif (current_user_can('edit_posts')) {
			$acf_price = get_field('retail_price', $product_id_actual);
		} else {
			$acf_price = get_field('market_price', $product_id_actual);
		}

		if ( $acf_price ) {
			$price = $acf_price * $data['quantity'];
			$acf_price_per = '$' . number_format($acf_price, 2);
			$price_value = '$' . number_format($price, 2);
			$total_cost = $price + $total_cost;
		} else {
			$price_value = 'N/A';
		}

		$quantity_fmt = number_format($data['quantity']);

		if ( ! $coupon_code ) {

			$email_body .= '<div><span style="color: #32b79d">Product:</span> <strong>' . $product . '</strong><br /><span style="color: #32b79d">Quantity:</span> <strong>' . $quantity_fmt . '</strong><br /><span style="color: #32b79d">Color:</span> <strong>' . $data['color'] . '</strong><br /><span style="color: #32b79d">Unit Cost:</span> <strong>' . $acf_price_per . '</strong><br /><span style="color: #32b79d">Total Cost:</span> <strong>' . $price_value . '</strong></div><br />';

		} else {

			$coupon_array = get_coupon_array();
	    	$coupon_percent = $coupon_array[strtolower($coupon_code)];

	    	if ( $coupon_percent ) {

	    		$price_value_new = '$' . number_format(percent_price($price, $coupon_percent), 2);
	    		$acf_price_per_new = '$' . number_format(percent_price($acf_price, $coupon_percent), 2);

				$email_body .= '<div><span style="color: #32b79d">Product:</span> <strong>' . $product . '</strong><br /><span style="color: #32b79d">Quantity:</span> <strong>' . $quantity_fmt . '</strong><br /><span style="color: #32b79d">Color:</span> <strong>' . $data['color'] . '</strong><br /><span style="color: #32b79d">Unit Cost:</span> <strong><strike style="color: red;">' . $acf_price_per . '</strike> ' . $acf_price_per_new . '</strong><br /><span style="color: #32b79d">Total Cost:</span> <strong><strike style="color: red;">' . $price_value . '</strike> ' . $price_value_new . '</strong></div><br />';
			} else {
	    		$email_body .= '<div><span style="color: #32b79d">Product:</span> <strong>' . $product . '</strong><br /><span style="color: #32b79d">Quantity:</span> <strong>' . $quantity_fmt . '</strong><br /><span style="color: #32b79d">Color:</span> <strong>' . $data['color'] . '</strong><br /><span style="color: #32b79d">Unit Cost:</span> <strong>' . $acf_price_per . '</strong><br /><span style="color: #32b79d">Total Cost:</span> <strong>' . $price_value . '</strong></div><br />';
	    	}

	    }

	}

	$field_key = "colors_and_quantity";

	foreach( $change_quantity_array as $purchase ) {

		$post_id = $purchase['id'];

		$value = get_field($field_key, $post_id);

		foreach( $value as $key => $item ) {
		  if ( $item['color'] === $purchase['color'] ) {
		  	$old_quantity = $value[$key]['quantity'];
		  	$new_quantity = ( $old_quantity - $purchase['quantity'] );
		  	if ( $new_quantity < 0 ) {
		  		$new_quantity = 0;
		  	}
		    $value[$key]['quantity'] = $new_quantity;
		  }
		}

		update_field( $field_key, $value, $post_id );

	}

	$total_cost_final = '$' . number_format( $total_cost, 2 );

	if ( $coupon_code ) {

	    if ( $coupon_percent ) {

	    	if ( $credit_used ) {

		    	$after_coupon_cost = percent_price($total_cost, $coupon_percent);

		    	$after_coupon_cost_final = '$' . number_format( $after_coupon_cost, 2 );

		    	$after_credit_subtracted = $after_coupon_cost - $credit_used;

		    	$final_final_final = '$' . number_format( $after_credit_subtracted, 2 );

		    	$email_body = $email_body . '<br />
		    	<div style="margin-top: 20px"><strong>Comments</strong><br />' . $comments . '</div><br />
		    	<div><strong>Coupon Applied: ' . strtoupper($coupon_code) . '</strong></div>
		    	<div><strong>Total Charges: <strike style="color: red;">' . $total_cost_final . '</strike> ' . $after_coupon_cost_final . '</strong> - (<strong style="color: red;">$' . number_format($credit_used, 2) . '</strong> credit) = <strong>' . $final_final_final . '</strong></div>';
		    	$after_coupon_cost_final = $final_final_final;
		    } else {
		    	$after_coupon_cost = percent_price($total_cost, $coupon_percent);
		    	$after_coupon_cost_final = '$' . number_format( $after_coupon_cost, 2 );

		    	$email_body = $email_body . '<br />
		    	<div style="margin-top: 20px"><strong>Comments</strong><br />' . $comments . '</div><br />
		    	<div><strong>Coupon Applied: ' . strtoupper($coupon_code) . '</strong></div>
		    	<div><strong>Total Charges: <strike style="color: red;">' . $total_cost_final . '</strike> ' . $after_coupon_cost_final . '</strong></div>';
		    }

	    } else {

	    	if ( $credit_used ) {

				$after_credit_cost = ( $total_cost - $credit_used );
				$after_credit_final = '$' . number_format($after_credit_cost, 2);

			$email_body = $email_body . '<br /><div style="margin-top: 20px"><strong>Comments</strong><br />' . $comments . '</div><br />
    	<div><strong>Credit Applied:</strong> <strong>' . $total_cost_final . '</strong> - <strong style="color: red;">$' . number_format($credit_used, 2) . '</strong> = <strong>' . $after_credit_final . '</strong><br /><div><strong>Total Charges: ' . $after_credit_final . '</strong></div>';
	    	} else {

				$email_body = $email_body . '<br /><div style="margin-top: 20px"><strong>Comments</strong><br />' . $comments . '</div><br /><div><strong>Total Charges: ' . $total_cost_final . '</strong></div>';
	    	}
	    }

	} else {


    	if ( $credit_used ) {

			$after_credit_cost = ( $total_cost - $credit_used );
			$after_credit_final = '$' . number_format($after_credit_cost, 2);

		$email_body = $email_body . '<br /><div style="margin-top: 20px"><strong>Comments</strong><br />' . $comments . '</div><br />
	<div><strong>Credit Applied:</strong> <strong>' . $total_cost_final . '</strong> - <strong style="color: red;">$' . number_format($credit_used, 2) . '</strong> = <strong>' . $after_credit_final . '</strong><br /><div><strong>Total Charges: ' . $after_credit_final . '</strong></div>';
    	} else {

			$email_body = $email_body . '<br /><div style="margin-top: 20px"><strong>Comments</strong><br />' . $comments . '</div><br /><div><strong>Total Charges: ' . $total_cost_final . '</strong></div>';
    	}
	}

	if ( ( ! $credit_used ) && ( ! $coupon_percent ) ) {
		$final_final_total_cost = $total_cost_final;
	} elseif ( $coupon_percent ) {
		$final_final_total_cost = $after_coupon_cost_final;
	} else {
		$final_final_total_cost = $after_credit_final;
	}

	// send email to admin
	$admin_intro = '<div><span style="color: #32b79d">Order placed by</span> <strong>' . $user_name . '</strong><br /><span style="color: #32b79d">Company:</span> <strong>' . $company_name . '</strong><br /><span style="color: #32b79d">Address:</span> <strong>' . $address . '</strong><br /><strong>' . $city . ', ' . $state . ' ' . $zip . '</strong><br /><span style="color: #32b79d">Email:</span> <strong>' . $user_email . '</strong><br /><span style="color: #32b79d">Order Type:</span> <strong>' . $payment_type . '</strong></div><br />';
	if ( $ref_agent_email ) {
		$to = array($admin_email, 'leonmagee33@gmail.com', $ref_agent_email);
	} else {
		$to = array($admin_email, 'leonmagee33@gmail.com');
	}
	//$to = array($admin_email, 'leonmagee@hotmail.com');

	$email_wrap = GSA_EMAIL_WRAP;
	$email_wrap_close = '</div>';

	$subject = 'GS Accessories Order';
	$body_admin = $admin_intro . $email_body;
	$body_final_admin = $email_wrap . $body_admin . $email_wrap_close;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body_final_admin, $headers );

	if ( $payment_type == 'PayPal' ) {
		$payment_instructions = get_field('paypal_instructions', 'option');
	} elseif ( $payment_type == 'Venmo - Cash App' ) {
		$payment_instructions = get_field('venmo_cash_app_instructions', 'option');
	} elseif ( $payment_type == 'Wire Transfer - Direct Deposit' ) {
		$payment_instructions = get_field('wire_transfer_direct_deposit_instructions', 'option');
	} else {
		$payment_instructions = get_field('pick_up_instructions', 'option');
	}


	// send email to user
	$to = $user_email;
	$subject = 'GS Accessories Order';
	$body_customer = $payment_instructions . '<div style="margin-top: 20px; margin-bottom: 10px"><strong>Order Details</strong></div>' . $email_body;
	$body_final_customer = $email_wrap . $body_customer . $email_wrap_close;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body_final_customer, $headers );

	// clear out cart of items (empty Session)
	$_SESSION['shopping_cart'] = '';

	/**
	* Create Post to record order
	*/
	// $new_data = '<pre>' . $body_customer . '</pre';
	// var_dump($new_data);
	// die('working');


	update_field('comments', $comments, $new_order_id);
	update_field('order_type', $payment_type, $new_order_id);
	update_field('paid', 'Completed', $new_order_id);
	update_field('sub_total', $total_cost_final, $new_order_id);
	update_field('total_charge', $final_final_total_cost, $new_order_id);
	update_field('customer_email', $user_email, $new_order_id);
	update_field('user_email_text', $body_customer, $new_order_id);
	update_field('user_email_shorter_text', '<div style="margin-top: 20px; margin-bottom: 10px"><strong>Order Details</strong></div><br />' .  $email_body, $new_order_id); // tracking field
	//update_field('user_email_shorter_text', '<div><strong>Order Details</strong><div><br />' .  $email_body, $new_order_id);
	update_field('admin_email_text', $body_admin, $new_order_id);
	update_field('user_id', $user_id, $new_order_id);

	update_field('po_number', $po_number, $new_order_id);
	if ( $coupon_percent ) {
		update_field('coupon_percent', $coupon_percent, $new_order_id);
	}
	if ( $credit_used ) {
		update_field('credit_applied', $credit_used, $new_order_id);
	}


	$acf_user_id = 'user_' . $user_id;
	$referring_agent_id = get_field('referring_agent', $acf_user_id);
	if ( ! ( $agent_id = $referring_agent_id['ID'] ) ) {
		$agent_id = 0;
	}
	update_field('agent_id', $agent_id, $new_order_id);

	// var_dump($shopping_cart_array);
	// die('so far');

	foreach( $shopping_cart_array as $id => $data ) {

		$product_id_exp = explode('-', $id);
		$product_id_actual = $product_id_exp[0];
		$product_title_new = get_the_title($id);

		$product = strtoupper(str_replace('-', ' ' , $data['product']));
		$quantity = $data['quantity'];
		$color = $data['color'];

		if ( current_user_can('delete_published_posts')) {
			$acf_price = get_field('wholesale_price', $product_id_actual);
		} elseif (current_user_can('edit_posts')) {
			$acf_price = get_field('retail_price', $product_id_actual);
		} else {
			$acf_price = get_field('market_price', $product_id_actual);
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
			'product_name'	=> $product_title_new,
			'product_quantity'	=> $quantity,
			'product_color'	=> $color,
			'product_id' => $product_id_actual,
			//'cat_id' => '11111',
			'unit_cost' => $acf_price_per,
			'cost_total' => $price_value
		);

		add_row('product_entries', $row, $new_order_id);
	}

	// var_dump($new_order_id);
	// die('new post working?');

	// here we need to determine if this is a PayPal order or a pick up order

	// redirect to order placed page
	if ( ( $payment_type == 'Pick Up' ) ||  ( $payment_type == 'Venmo - Cash App' ) || ( $payment_type == 'Wire Transfer - Direct Deposit' ) ) {
		wp_redirect('/order-placed');
	} elseif ( $payment_type == 'PayPal' ) {
		//wp_redirect('/cart?paypal=show&paypal_names=' . $product_names . '&paypal_values=' . $product_values);

		if ( $credit_used ) {
		  // @todo make salts constants
		  $salt_1 = 'sldkfj29374297%%!!sldfj';
          $salt_2 = 'xxxx2937429347&sdklhfsl';
          $salted_string = $salt_1 . $credit_used . $salt_2;
          $encrypted_string = urlencode(base64_encode($salted_string));
          $credit_param = '&misc=' . $encrypted_string;
		}

		if ( $coupon_code ) {
			wp_redirect('/cart?paypal=show&coupon=' . $coupon_code . $credit_param);
		} else {
			wp_redirect('/cart?paypal=show' . $credit_param);
		}
	}
	exit;
}



/**
* Apply Coupon
*/
if ( isset($_POST['coupon-apply-submit'])) {


	$coupon_name = filter_input(INPUT_POST, 'coupon', FILTER_SANITIZE_SPECIAL_CHARS);

	wp_redirect('/cart?coupon=' . $coupon_name);
	exit;
}

/**
* Agent Page change date
*/
if ( isset($_POST['change-month-year'])) {

	$month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);
	$year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);

	wp_redirect('/agent-admin?data_month=' . $month . '&data_year=' . $year);
	exit;
}





