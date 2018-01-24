<?php

/**
 * Class mp_register_user
 * 1. wp_create_user (username, email, password)
 * 2. wp_update_user (first and last name, role)
 * 3. update_user_meta * (number of meta fields)
 */
class lv_register_user {

	public $username;
	public $email;
	public $password;
	public $first_name;
	public $last_name;
	public $phone_number;
	public $company;
	public $user_id;

	public function __construct(
		$username,
		$first_name,
		$last_name,
		$email,
		$password,
		$phone_number,
		$company
	) {
		$this->username     = $username;
		$this->email        = $email;
		$this->password     = $password;
		$this->first_name   = $first_name;
		$this->last_name    = $last_name;
		$this->phone_number = $phone_number;
		$this->company      = $company;
	}

	private function register_user() {

		$this->user_id = wp_create_user( $this->username, $this->password, $this->email );

		wp_update_user( array(
			'ID'         => $this->user_id,
			'first_name' => $this->first_name,
			'last_name'  => $this->last_name,
			//'role'       => 'agent'
			'role'       => 'subscriber'
		) );

		update_user_meta( $this->user_id, 'phone_number', $this->phone_number );
		update_user_meta( $this->user_id, 'company', $this->company );
		/**
		 * Here we send email to Dan Haas and to the user - for Dan the email he gets will just include the basic info.
		 */
		$email_name = '';
		if ( isset( $this->first_name ) && isset( $this->last_name ) ) {
			$email_name = $this->first_name . ' ' . $this->last_name;
		} elseif ( isset( $this->first_name ) ) {
			$email_name = $this->first_name;
		} elseif ( isset( $this->last_name ) ) {
			$email_name = $this->last_name;
		}

		$user_email_text = get_field( 'new_account_email_text', 'option' );
		$send_user_email = new lv_send_email_misc( $this->email, $email_name, 'MarketPier User Registration', $user_email_text );
		$send_user_email->send_email();


		$admin_email_text = 'New user registered: ' . $email_name . ' - ' . $this->company . ' - ' . $this->phone_number;
		$admin_email      = get_bloginfo( 'admin_email' );
		$send_admin_email = new lv_send_email_misc( $admin_email, 'MarketPier Admin', 'MarketPier User Registration', $admin_email_text );
		$send_admin_email->send_email();
	}

	public function process_registration_form() {
		if ( isset( $_POST['username'] ) ) {
			$this->register_user();
		}
	}


}