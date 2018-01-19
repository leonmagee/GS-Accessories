<?php


if ( isset($_POST['product-order-form'])) {



	$product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_SPECIAL_CHARS);
	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_SPECIAL_CHARS);

	if ( $product ) {
		$color = filter_input(INPUT_POST, 'colors-' . $product, FILTER_SANITIZE_SPECIAL_CHARS);
	}

	if ( !$color ) {
		$color = false;
	}

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

	$current_data[] = $ShopingCart->cart_data;

	// var_dump( $ShopingCart->cart_data);
 //    var_dump($_SESSION);
 //    die('die');
	/**
	* I need to do an array merge here - the sessoin
	*/
    $_SESSION['shopping_cart'] = serialize($current_data);

    var_dump( $ShopingCart);
    var_dump($_SESSION);
    die('die');



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