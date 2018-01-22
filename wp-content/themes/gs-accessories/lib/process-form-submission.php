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

	$page_object = get_page_by_path($product, OBJECT, 'accessories');
	$post_id = $page_object->ID;

	//die($product . ' - ' . $quantity . ' - ' . $color);

	$ShopingCart = new shopping_cart();

    //$Basket->do_actions(); 
    // my own hooks to allow me to add housekeeping code without messing with my core code

	$ShopingCart->add_data(time(), $product, $quantity, $color);
    //$ShopingCart->add_data(time(),'magnetic case', 2000, 'black');
    // $ShopingCart->add_data('USB Charger', 1000, 'white');
    //var_dump($ShopingCart);

	session_start();

	if ( $_SESSION['shopping_cart'] ) {

		$current_data = unserialize($_SESSION['shopping_cart']);
		
	} else {
		$current_data = array();
	}

	$current_data[$post_id] = $ShopingCart->cart_data;

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

	$_SESSION['shopping_cart'] = serialize($shopping_cart_array);
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
	var_dump($user->data->user_email);

	// get admin email address
	$admin_email = get_option('admin_email');
	var_dump($admin_email);

	die('working so far');


	// send email to logged in user email and admin email (site admin?)
$to = 'leonmagee33@gmail.com'; // get admin email here
$subject = 'GS Accessories Order';
$body = 'Order Details Here';
$headers = array('Content-Type: text/html; charset=UTF-8');
 
wp_mail( $to, $subject, $body, $headers );






	// clear out cart of items (empty Session)
	$_SESSION['shopping_cart'] = '';

	// redirect to order placed page, or else
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