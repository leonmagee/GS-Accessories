<?php
/**
* Create REST Endpoint
*/
add_action( 'rest_api_init', 'gsa_create_rest_api_endpoint');

function gsa_create_rest_api_endpoint() {

	register_rest_route( 'process_emails', '/info/(?P<id>\d+)/(?P<email>[a-zA-Z0-9-_\@\.]+)', array(
		'methods' => 'GET',
		'callback' => 'gsa_rest_api_process_emails',
	));
}

function gsa_rest_api_process_emails($data) {

	$orders_array = array();
	$args = array('post_type' => 'orders');
	$custom_query = new WP_Query($args);
	while( $custom_query->have_posts() ) {
		$custom_query->the_post();
		$charge = get_field('total_charge');
		$orders_array[] = get_the_title() . ' - ' . $charge . ' - ' . $data['id'] . ' - ' . $data['email'];
	}
	wp_reset_postdata();
	
	return $orders_array;
}