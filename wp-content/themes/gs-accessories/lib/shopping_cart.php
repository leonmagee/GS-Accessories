<?php


class shopping_cart {

	public $cart_data;
	public function __construct() {
		//die('shopping cart instantiated');
	}

	public function add_data($product_name, $quantity, $color = false, $id = false, $cat_id = false) {

		$this->cart_data = array(
			'product' => $product_name,
			'quantity' => $quantity,
			'color' => $color,
			'product_id' => $id,
			'cat_id' => $cat_id
		);

	}
}