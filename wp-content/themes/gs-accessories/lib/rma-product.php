<?php

class rma_product {

	public $product_name;
	public $purchase_date;
	public $po_number;
	private $unit_cost;
	private $coupon_percent;
	public $updated_cost;

	public function __construct(
		$name, 
		$date, 
		$order_id, 
		$unit_cost, 
		$coupon_percent
	) {
		$this->product_name = $name;
		$this->purchase_date = $date;
		$this->po_number = 'GSA-ODR-' . $order_id;
		$this->unit_cost = $unit_cost;
		$this->coupon_percent = $coupon_percent;
		if($coupon_percent != 'N/A') {
			/**
			* @todo calculate cost after coupon is added
			*/
			$unit_cost_new = str_replace([",", "$"], '', $unit_cost);
			$new_cost = ( intval($unit_cost_new) * ( (100 - intval($coupon_percent)) / 100 ) );
			$this->updated_cost = '$' . number_format($new_cost, 2);
		} else {
			$this->updated_cost = $this->unit_cost;
		}
	}
}

