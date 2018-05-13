<?php
/**
* Create REST Endpoint
*/
add_action( 'rest_api_init', 'gsa_rest_endpoint_admin');

function gsa_rest_endpoint_admin() {

	register_rest_route( 'process_emails', '/admin/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_processa_admin_email',
	));
}

function gsa_rest_processa_admin_email($data) {

	//$orders_array = array();
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

		$mail_sent = wp_mail( $data['email'], $subject, $admin_email_text, $headers );

		//return 'not working 1';
		return $mail_sent;

	} else {

		return 'not working 2';
	}
	
}