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

		wp_redirect('/cart');
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
	$time = time();
	$array_key = $post_id . '-' . $time;

	$colors = get_field('accessory_colors', $post_id);

	if ( $colors ) {
		$color = $colors[0];
	} else {
		$color = false;
	}

	$ShopingCart = new shopping_cart();

    //$Basket->do_actions(); 
    // my own hooks to allow me to add housekeeping code without messing with my core code

	$ShopingCart->add_data($product, 1000, $color);
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

	wp_redirect('/cart');
	exit;

	// var_dump( $ShopingCart);
	// var_dump($_SESSION);
	// die('die');
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


if ( isset($_POST['place-cart-order'])) {

	session_start();

	$shopping_cart_array = unserialize($_SESSION['shopping_cart']);

	// get user email address
	$user = wp_get_current_user(); 
	$user_email = $user->data->user_email;
	//var_dump($user->data->user_email);

	// get admin email address
	$admin_email = get_option('admin_email');
	//var_dump($admin_email);
	$first_name = get_user_meta($user->data->ID, 'first_name', true);
	$last_name = get_user_meta($user->data->ID, 'last_name', true);

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

	$email_body = '';

	$total_cost = 0;

	foreach( $shopping_cart_array as $id => $data ) {
		$product = strtoupper(str_replace('-', ' ' , $data['product']));
		$product_id_exp = explode('-', $id);
		$product_id_actual = $product_id_exp[0];


		if ( current_user_can('edit_posts')) {
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


		$email_body .= '<div>Product: ' . $product . ' - Quantity: ' . $quantity_fmt . ' - Color: ' . $data['color'] . ' - Unit Cost: ' . $acf_price_per . ' - Total Cost: ' . $price_value . '</div>';
	}

	$total_cost_final = '$' . number_format( $total_cost, 2 );

	$email_body = $email_body . '<div><strong>Total Charges: ' . $total_cost_final . '</strong></div>';

	// send email to admin
	$admin_intro = '<div>Order placed by ' . $user_name . ' - Email: ' . $user_email . '</div>';
	$to = $admin_email; // get admin email here
	$subject = 'GS Accessories Order';
	$body = $admin_intro . $email_body;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body, $headers );

	$payment_instructions = '<div>
	<div>Thank you for submitting your order with GS Wireless, we highly appreciate your business. You are one step away from completing your order by submitting your payment to us through either option below.</div>
	<div>
	<br />
	<div><strong>Option #1 (PayPal Payment)</strong></div>
	<div>
	Remit payment through PayPal to Sales@mygsaccessories.com and choose send to (family or friends) to avoid extra fee otherwise 3% charge will be applied to your total invoice.</div>
	</div>
	<div>
	<br />
	<div><strong>Option #2 (Bank Wire, Check Or Cash Deposit)</strong><div>
	<div>Cash deposit can be made at any US Bank, check or other form of deposit including money order could take more than 72H to clear. We required a copy of the deposit slip, branch phone number and teller name to confirm deposit type.</div>
	</div>
	<div>
	<br />
	<div>Wire Transfer Information:</div>
	<div>Bank Name: U.S. Bank</div>
	<div>Account Holder: Golden State Wireless Inc.</div>
	<div>Account Number: 153497481058</div>
	<div>Routing Number: 122235821</div>
	<div>U.S. Bank SWIFT code: USBKUS44IMT (for international use)</div>
	</div>
	<br />
	<div><strong>Order Details</strong><div>';


	// send email to user
	//$user_intro = '<div>Thank you for choosing GS Accessories. Your order:</div>';
	$to = $user_email; // get admin email here
	$subject = 'GS Accessories Order';
	$body = $payment_instructions . $email_body;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $body, $headers );

	// clear out cart of items (empty Session)
	$_SESSION['shopping_cart'] = '';

	// redirect to order placed page
	wp_redirect('/order-placed');
	exit;
}



// var_dump($_SESSION);
// var_dump($ShopingCart);
/**
* Move this somewhere else... 
*
* I'm not sure if I should just use the $_SESSION global or if I should also have a class
* that instantiates a singlegleton that works with the session... It's probably just easier if
* I check to see if the session exists, and if it does then I can add or remove from it, that 
* seems like a very simple way to do it... and once it has been added to the session I can 
* also pass it a product ID number which can then be used to remove the product from the 
* session global... the issue to deal with is when someone adds more than one product which is 
* exactly the same to the - so I should use a timestamp as the prduct id... 
*/
// function setup_session() {

//     global $ShopingCart;

//     // session_start();

//     // if (isset($_SESSION['shopping_cart'])) {
//     //     $ShopingCart = unserialize($_SESSION['shopping_cart']);
//     // } else {
//     //     $ShopingCart = new shopping_cart();
//     // }

//     $ShopingCart = new shopping_cart();

//     //$Basket->do_actions(); 
//     // my own hooks to allow me to add housekeeping code without messing with my core code

//     $ShopingCart->add_data(time(),'iphone 6', 1000, 'blue');
//     //$ShopingCart->add_data(time(),'magnetic case', 2000, 'black');
//     // $ShopingCart->add_data('USB Charger', 1000, 'white');
//     //var_dump($ShopingCart);


// 	session_start();
//     $_SESSION['shopping_cart'] = serialize($ShopingCart->cart_data);
// 	//var_dump($_SESSION);

// }

// function check_session() {

// 	session_start();
// 	var_dump($_SESSION);

// }
// add_action('init', 'check_session');





// function save_session() {

//     global $ShopingCart;
//     if (isset($ShopingCart)) {
//         $_SESSION['shopping_cart'] = serialize($Basket);
//     }

// }

//add_action( 'init', 'setup_session' );
//add_action( 'shutdown', 'save_session' ); // works even when redirecting away from a page