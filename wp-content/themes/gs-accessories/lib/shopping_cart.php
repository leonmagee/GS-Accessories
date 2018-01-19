<?php


class shopping_cart {

	public $cart_data;
	public function __construct() {
		//die('shopping cart instantiated');
	}

	public function add_data($id, $product_name, $quantity, $color = false) {

		$this->cart_data = array(
			'id' => $id,
			'product' => $product_name,
			'quantity' => $quantity,
			'color' => $color
		);

	}
}