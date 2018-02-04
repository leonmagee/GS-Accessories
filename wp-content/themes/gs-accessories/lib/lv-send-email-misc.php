<?php

/**
 * Class mp_send_email_misc
 */
class lv_send_email_misc {

	public $recipient_email;
	public $recipient_name;
	public $email_subject;
	public $email_text;

	public function __construct( $recipient_email, $recipient_name, $email_subject, $email_text ) {

		$this->recipient_email = $recipient_email;
		$this->recipient_name  = $recipient_name;
		$this->email_subject   = $email_subject;
		$this->email_text      = $email_text;
	}

	/**
	 * function for sending admin email and user email?
	 */
	public function send_email() {

		$website_url = site_url();

		$message_body = '<div style="padding-left: 10px; padding-right: 10px;">

		<div style="margin: 5px 0">' . $this->recipient_name . ',</div>
		<div style="margin: 5px 0">' . $this->email_text . '</div>
		<div><a href="' . $website_url . '">GS Accessories</a></div>
	</div>';

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);

		/**
		 * Send Email
		 */
		wp_mail(
			$this->recipient_email,
			$this->email_subject,
			$message_body,
			$headers
		);

	}
}