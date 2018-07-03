<?php
/**
* Create REST Endpoint
*/
add_action( 'rest_api_init', 'gsa_rest_endpoint_admin');

add_action( 'rest_api_init', 'gsa_rest_endpoint_user');

add_action( 'rest_api_init', 'gsa_rest_endpoint_rma');

add_action( 'rest_api_init', 'gsa_rest_endpoint_rma_reject');

add_action( 'rest_api_init', 'gsa_rest_endpoint_user_tracking');

function gsa_rest_endpoint_admin() {

	register_rest_route( 'process_emails', '/admin/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.\s]+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_admin_email',
	));
}

function gsa_rest_endpoint_user() {

	register_rest_route( 'process_emails', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_user_email',
	));
}

function gsa_rest_endpoint_rma() {

	register_rest_route( 'process_rma', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<message>.+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_rma_email',
	));
}

function gsa_rest_endpoint_rma_reject() {

	register_rest_route( 'process_rma_reject', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<message>.+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_rma_email_reject',
	));
}

function gsa_rest_endpoint_user_tracking() {

	register_rest_route( 'process_emails', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<tracking>[a-zA-Z0-9\!%]+)/(?P<service>[a-zA-Z0-9%]+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_user_email_tracking',
	));
}

function gsa_rest_process_admin_email($data) {

	$args = array('p' => $data['id'], 'post_type' => 'orders');
	$custom_query = new WP_Query($args);
	$admin_email_text = false;
	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$admin_email_text = get_field('admin_email_text');
	}
	wp_reset_postdata();

	if ( $admin_email_text && $data['email'] ) {

		$subject = 'GS Accessories Order';
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$email_wrap = GSA_EMAIL_WRAP;

		$email_wrap_close = '</div>';

		$admin_email_final = $email_wrap . $admin_email_text . $email_wrap_close;

		$mail_sent = wp_mail( $data['email'], $subject, $admin_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
}

function gsa_rest_process_user_email($data) {

	$args = array('p' => $data['id'], 'post_type' => 'orders');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$user_email_text = get_field('user_email_text');
	}
	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories Order';
		$headers = array('Content-Type: text/html; charset=UTF-8');


		// I need to limit the amount of the email text that gets sent so it doesn't have the entire 
		// instructions as well... 
		// make color this: #4E76A0

		$email_wrap = GSA_EMAIL_WRAP;
		
		$email_wrap_close = '</div>';

		$user_email_final = $email_wrap . $user_email_text . $email_wrap_close;

		$mail_sent = wp_mail( $data['email'], $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
	
}

function gsa_rest_process_rma_email($data) {

	$args = array('p' => $data['id'], 'post_type' => 'rmas');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$rma_number = get_field('rma_number');
		$rma_instructions = get_field('rma_instructions', 'option');
		if ( $data['message']) {
			$user_email_text = '<div>' . urldecode($data['message']) . '<br /><br />' . $rma_instructions . '</div><br /><div>RMA Number: <strong>' . $rma_number . '</strong></div>';
		} else {
			$user_email_text = '<div>' . $rma_instructions . '</div><br /><div>RMA Number: <strong>' . $rma_number . '</strong></div>';
		}
		//$user_email_text = get_field('user_email_text');
	}
	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories RMA';
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$email_wrap = GSA_EMAIL_WRAP;
		
		$email_wrap_close = '</div>';

		$user_email_final = $email_wrap . $user_email_text . $email_wrap_close;

		$mail_sent = wp_mail( $data['email'], $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
}

function gsa_rest_process_rma_email_reject($data) {

	$args = array('p' => $data['id'], 'post_type' => 'rmas');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$rma_number = get_field('rma_number');
		$rma_instructions_rejected = get_field('rma_instructions_rejected', 'option');
		if ( $data['message']) {
			$user_email_text = '<div>' . urldecode($data['message']) . '<br /><br />' . $rma_instructions_rejected;
		} else {
			$user_email_text = '<div>' . $rma_instructions_rejected;
		}
		//$user_email_text = get_field('user_email_text');
	}
	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories RMA';
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$email_wrap = GSA_EMAIL_WRAP;
		
		$email_wrap_close = '</div>';

		$user_email_final = $email_wrap . $user_email_text . $email_wrap_close;

		$mail_sent = wp_mail( $data['email'], $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
}

function gsa_rest_process_user_email_tracking($data) {

	$args = array('p' => $data['id'], 'post_type' => 'orders');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		//$user_email_text = get_field('user_email_text');
		$user_email_text = get_field('user_email_shorter_text');
	}
	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories Order';
		$headers = array('Content-Type: text/html; charset=UTF-8');


		// I need to limit the amount of the email text that gets sent so it doesn't have the entire 
		// instructions as well... 
		// make color this: #4E76A0

		if ( $data['tracking'] ) {

			//$tracking_text = urldecode($data['tracking']);
			$tracking_number = 'Your tracking number is <strong>' . $data['tracking'] . '</strong>.';
		} else {
			$tracking_number = '';
		}

		if ( $data['service'] ) {
			//$service = $data['service'];
			$service = 'Your shipment will be sent via <strong>' . $data['service'] . '</strong>.';
		} else {
			$service = '';
		}
		
		$email_wrap = GSA_EMAIL_WRAP;
		
		$email_wrap_close = '</div>';

		$tracking_service_email = '<div style="font-size: 25px; padding: 15px 0; color: #4E76A0; margin-bottom: 20px;">Thanks for your order. ' . $tracking_number . '<br />' . $service . '</div>';

		$user_email_final = $email_wrap . $tracking_service_email . $user_email_text . $email_wrap_close;

		$mail_sent = wp_mail( $data['email'], $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
	
}