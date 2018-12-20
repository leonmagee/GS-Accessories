<?php
/**
* Create REST Endpoint
*/
add_action( 'rest_api_init', 'gsa_rest_endpoint_admin');

add_action( 'rest_api_init', 'gsa_rest_endpoint_user');

add_action( 'rest_api_init', 'gsa_rest_endpoint_rma');

add_action( 'rest_api_init', 'gsa_rest_endpoint_rma_custom_message');

add_action( 'rest_api_init', 'gsa_rest_endpoint_rma_reject');

add_action( 'rest_api_init', 'gsa_rest_endpoint_rma_resend_email');

add_action( 'rest_api_init', 'gsa_rest_endpoint_user_tracking');

function gsa_rest_endpoint_admin() {

	register_rest_route( 'process_emails', '/admin/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.\s]+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_admin_email',
	));
}

function gsa_rest_endpoint_user() {

	register_rest_route( 'process_emails', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_user_email',
	));
}

function gsa_rest_endpoint_rma() {

	register_rest_route( 'process_rma', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<message>.+)/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_rma_email',
	));
}

function gsa_rest_endpoint_rma_custom_message() {

	register_rest_route( 'process_rma_custom_message', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<message>.+)/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_rma_custom_message',
	));
}

function gsa_rest_endpoint_rma_reject() {

	register_rest_route( 'process_rma_reject', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<message>.+)/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_rma_email_reject',
	));
}

function gsa_rest_endpoint_rma_resend_email() {

	register_rest_route( 'rma_resend_email', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_rma_resend_email',
	));
}

function gsa_rest_endpoint_user_tracking() {

	register_rest_route( 'process_emails', '/user/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)/(?P<tracking>[a-zA-Z0-9\!%]+)/(?P<service>[a-zA-Z0-9%]+)/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_process_user_email_tracking',
	));
}

function gsa_rest_process_admin_email($data) {

	$args = array('p' => $data['id'], 'post_type' => 'orders');
	$custom_query = new WP_Query($args);
	$admin_email_text = false;

	$sn_imei_section = '';

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$admin_email_text = get_field('admin_email_text');

		if ( $sn_imei_field = get_field('imei__serial_numbers') ) {

			$sn_imei_section .= '<br /><div><h3>IMEI / Serial Numbers</h3>';
			
			foreach( $sn_imei_field as $item ) {
				$sn_imei_section.= '<div><label style="color: #32b79d">' . $item['product_name'] . '</label> - <strong>' . $item['imei__serial_number'] . '</strong></div>';
			}

			$sn_imei_section .= "</div><br />";
		}
	}
	wp_reset_postdata();

	if ( $admin_email_text && $data['email'] ) {

		$subject = 'GS Accessories Order';
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$email_wrap = GSA_EMAIL_WRAP;

		$email_wrap_close = '</div>';

		$admin_email_final = $email_wrap . $admin_email_text . $sn_imei_section . $email_wrap_close;

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

	$ref_agent_email = get_agent_email($data['user_id']);

	$sn_imei_section = '';

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$user_email_text = get_field('user_email_text');

		if ( $sn_imei_field = get_field('imei__serial_numbers') ) {

			$sn_imei_section .= '<br /><div><h3>IMEI / Serial Numbers</h3>';
			
			foreach( $sn_imei_field as $item ) {
				$sn_imei_section.= '<div><label style="color: #32b79d">' . $item['product_name'] . '</label> - <strong>' . $item['imei__serial_number'] . '</strong></div>';
			}

			$sn_imei_section .= "</div><br />";
		}
	}

	//var_dump($sn_imei_section);

	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories Order';
		$headers = array('Content-Type: text/html; charset=UTF-8');


		// I need to limit the amount of the email text that gets sent so it doesn't have the entire 
		// instructions as well... 
		// make color this: #4E76A0

		$email_wrap = GSA_EMAIL_WRAP;
		
		$email_wrap_close = '</div>';

		$user_email_final = $email_wrap . $user_email_text . $sn_imei_section . $email_wrap_close;

		if ( $ref_agent_email ) {

			$to = array($ref_agent_email, $data['email']);

		} else {

			$to = $data['email'];
		}

		$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
	
}

function gsa_rest_process_rma_email($data) {

	$args = array('p' => $data['id'], 'post_type' => 'rmas');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	$ref_agent_email = get_agent_email($data['user_id']);

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$rma_number = get_field('rma_number');
		$company_name = get_field('company_name');
		$rma_instructions = get_field('rma_instructions', 'option');
		if ( $data['message'] && $data['message'] !== 'BLANK' ) {
			$user_email_text = '<div>' . urldecode($data['message']) . '<br /><br />' . $rma_instructions . '</div><br /><div>RMA Number: <strong>' . $rma_number . '</strong></div><br /><div><strong>' . $company_name . '</strong></div>';
		} else {
			$user_email_text = '<div>' . $rma_instructions . '</div><br /><div>RMA Number: <strong>' . $rma_number . '</strong></div><br /><div><strong>' . $company_name . '</strong></div>';
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


		// $ref_agent_id = get_user_meta($user_id, 'referring_agent', true);

		// if ( $ref_agent_id ) {
		// 	$user_object = get_userdata($ref_agent_id);
		// 	$ref_agent_email = $user_object->user_email;
		// 	$to = array($data['email'], $ref_agent_email);
		// } else {
		// 	$to = array($data['email']);
		// }

		//$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		if ( $ref_agent_email ) {

			$to = array($ref_agent_email, $data['email']);

		} else {

			$to = $data['email'];
		}
		
		$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
}


function gsa_rest_process_rma_custom_message($data) {

	/**
	* here's how to seee a response in Chrome Dev Tools - just var_dump it!
	*/
	// $array_tester = ['one', 'two', 'three'];
	// var_dump($array_tester);

	$args = array('p' => $data['id'], 'post_type' => 'rmas');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	$ref_agent_email = get_agent_email($data['user_id']);

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$rma_number = get_field('rma_number');
		$company_name = get_field('company_name');

		//$rma_instructions = get_field('rma_instructions', 'option');
		if ( $data['message'] && $data['message'] !== 'BLANK' ) {
			$user_email_text = '<div>' . urldecode($data['message']) . '</div><br /><div>RMA Number: <strong>' . $rma_number . '</strong></div><br /><div><strong>' . $company_name . '</strong></div>';
		} 

		// else {
		// 	$user_email_text = '<div>' . $rma_instructions . '</div><br /><div>RMA Number: <strong>' . $rma_number . '</strong></div><br /><div><strong>' . $company_name . '</strong></div>';
		// }
		//$user_email_text = get_field('user_email_text');
	}
	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories RMA';
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$email_wrap = GSA_EMAIL_WRAP;
		
		$email_wrap_close = '</div>';

		$user_email_final = $email_wrap . $user_email_text . $email_wrap_close;


		// $ref_agent_id = get_user_meta($user_id, 'referring_agent', true);

		// if ( $ref_agent_id ) {
		// 	$user_object = get_userdata($ref_agent_id);
		// 	$ref_agent_email = $user_object->user_email;
		// 	$to = array($data['email'], $ref_agent_email);
		// } else {
		// 	$to = array($data['email']);
		// }

		//$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		if ( $ref_agent_email ) {

			$to = array($ref_agent_email, $data['email']);

		} else {

			$to = $data['email'];
		}
		
		$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
}

function gsa_rest_process_rma_email_reject($data) {

	$args = array('p' => $data['id'], 'post_type' => 'rmas');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	$ref_agent_email = get_agent_email($data['user_id']);

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$rma_number = get_field('rma_number');
		$company_name = get_field('company_name');
		$rma_instructions_rejected = get_field('rma_instructions_rejected', 'option');
		if ( $data['message'] && $data['message'] !== 'BLANK' ) {
			$user_email_text = '<div>' . urldecode($data['message']) . '<br /><br />' . $rma_instructions_rejected . '<br /><div><strong>' . $company_name . '</strong></div>';
		} else {
			$user_email_text = '<div>' . $rma_instructions_rejected . '<br /><div><strong>' . $company_name . '</strong></div>';
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

		if ( $ref_agent_email ) {

			$to = array($ref_agent_email, $data['email']);

		} else {

			$to = $data['email'];
		}

		$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
}


function gsa_rest_rma_resend_email($data) {

	$args = array('p' => $data['id'], 'post_type' => 'rmas');
	$custom_query = new WP_Query($args);
	$user_email_text = false;
	$ref_agent_email = get_agent_email($data['user_id']);

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		$rma_number = get_field('rma_number');
		$first_name = get_field('first_name');
		$last_name = get_field('last_name');
		$email_address = get_field('email_address');
		$company_name = get_field('company_name');
		$phone_number = get_field('phone_number');
		$address = get_field('address');
		$city = get_field('city');
		$state = get_field('state');
		$zip = get_field('zip');

		$product_details = '';

		for ( $i = 0; $i < 5; $i++ ) {

			$item_data = get_field('return_item_' . ( $i + 1 ) );

			$product_details .= '<div>
				<h3 style="margin-bottom: 8px"><span style="color: #32b79d">Item #' . ($i + 1) . '</span></h3>
				<div><span style="color: #32b79d">Quantity:</span> <strong>' . $item_data['quantity'] . '</strong></div>
				<div><span style="color: #32b79d">Item Name:</span> <strong>' . $item_data['item_name']  . '</strong></div>
				<div><span style="color: #32b79d">Unit Price:</span> <strong>' . $item_data['unit_price']  . '</strong></div>
				<div><span style="color: #32b79d">IMEI or S/N:</span> <strong>' . $item_data['serial_number']  . '</strong></div>
				<div><span style="color: #32b79d">PO Number:</span> <strong>' . $item_data['po_number']  . '</strong></div>
				<div><span style="color: #32b79d">Date Purchased:</span> <strong>' . $item_data['date_purchased']  . '</strong></div>
				<div><span style="color: #32b79d">Description:</span> <strong>' . $item_data['return_problem_description']  . '</strong></div>
			</div>';
		}
	}

	wp_reset_postdata();

	$admin_intro = '<div><span style="color: #32b79d">RMA Number: </span> <strong>' . $rma_number . '</strong><br /><span style="color: #32b79d">RMA submitted by</span> <strong>' . $first_name . ' ' . $last_name . '</strong><br /><span style="color: #32b79d">Company:</span> <strong>' . $company_name . '</strong><br /><span style="color: #32b79d">Address:</span> <strong>' . $address . '</strong><br /><strong>' . $city . ', ' . $state . ' ' . $zip . '</strong><br /><span style="color: #32b79d">Email:</span> <strong>' . $email_address . '</strong><br />';
	
	//$to = $data['email'];

	if ( $ref_agent_email ) {

		$to = array($ref_agent_email, $data['email']);

	} else {

		$to = $data['email'];
	}

	$email_wrap = GSA_EMAIL_WRAP;
	$email_wrap_close = '</div>';

	$subject = 'GS Accessories RMA';
	$body_admin = $admin_intro . $product_details;
	$body_final_admin = $email_wrap . $body_admin . $email_wrap_close;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	$mail_sent = wp_mail( $to, $subject, $body_final_admin, $headers );

	if ( $mail_sent ) {

		return true;

	} else {

		return false;
	}
}




function gsa_rest_process_user_email_tracking($data) {

	$args = array('p' => $data['id'], 'post_type' => 'orders');
	$custom_query = new WP_Query($args);
	$user_email_text = false;

	// $ref_agent_id = get_user_meta($data['user_id'], 'referring_agent', true);
	// $ref_agent_email = false;
	// if ( $ref_agent_id ) {
	// 	$user_object = get_userdata($ref_agent_id);
	// 	$ref_agent_email = $user_object->user_email;
	// }

	$ref_agent_email = get_agent_email($data['user_id']);

	while( $custom_query->have_posts() ) {

		$custom_query->the_post();
		//$user_email_text = get_field('user_email_text');
		$user_email_text = get_field('user_email_shorter_text');
	}
	wp_reset_postdata();

	if ( $user_email_text && $data['email'] ) {

		$subject = 'GS Accessories Tracking Number';
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

		if ( $ref_agent_email ) {
			$to = array($data['email'], $ref_agent_email);
		} else {
			$to = $data['email'];
		}

		$mail_sent = wp_mail( $to, $subject, $user_email_final, $headers );

		return $mail_sent;

	} else {

		return false;
	}
	
}

function get_agent_email($id) {
	$ref_agent_id = get_user_meta($id, 'referring_agent', true);
	$ref_agent_email = false;
	if ( $ref_agent_id ) {
		$user_object = get_userdata($ref_agent_id);
		$ref_agent_email = $user_object->user_email;
	}
	return $ref_agent_email;
}