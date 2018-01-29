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
	public $tin_ein_ssn;

	public function __construct(
		$username,
		$first_name,
		$last_name,
		$email,
		$password,
		$phone_number,
		$company,
		$tin_ein_ssn
	) {
		$this->username     = $username;
		$this->email        = $email;
		$this->password     = $password;
		$this->first_name   = $first_name;
		$this->last_name    = $last_name;
		$this->phone_number = $phone_number;
		$this->company      = $company;
		$this->tin_ein_ssn  = $tin_ein_ssn;
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
		update_user_meta( $this->user_id, 'tin_ein_or_ssn', $this->tin_ein_ssn );


		//update_field('tin_ein_or_ssn', $this->tin_ein_ssn, $this->user_id);
		/**
		 * Here we send email to the admin and to the user
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
		$send_user_email = new lv_send_email_misc( $this->email, $email_name, 'GS Accessories User Registration', $user_email_text );
		$send_user_email->send_email();


		$admin_email_text = 'New user registered: ' . $email_name . ' - ' . $this->company . ' - ' . $this->phone_number . ' - TIN, EIN, or SSN #: ' . $this->tin_ein_ssn;
		$admin_email      = get_bloginfo( 'admin_email' );
		$send_admin_email = new lv_send_email_misc( $admin_email, 'GS Accessories Admin', 'GS Accessories User Registration', $admin_email_text );
		$send_admin_email->send_email();
	}

	public function process_registration_form() {
		if ( isset( $_POST['username'] ) ) {
			$this->register_user();
		}
	}


}