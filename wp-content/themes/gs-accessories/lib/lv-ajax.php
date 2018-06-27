<?php
/**
 *  Process data with Ajax
 */


/**
 *  Add Ajax url to header for selected pages
 */
function lv_ajaxurl() {

	//if ( is_page( 'your-profile' ) || is_page( 'register-account' ) ) {
	?>

    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    </script>

	<?php
	//}
}

add_action( 'wp_head', 'lv_ajaxurl' );


/**
 *  Update Account Settings
 */
function mp_settings_update() {

	if ( isset( $_POST['mp_agent_update_click'] ) ) {

		$user = wp_get_current_user();

		$agent_id = $user->ID;

		/**
		 *  Loop through agent fields
		 *
		 *  Process form submit method
		 * @todo this might not work for each form - I might need to make this into two classes?
		 */
		$input_fields = account_settings::output_input_array( $agent_id );

		foreach ( $input_fields as $input ) {

			$input->update_value();
		}
	}
}


/**
 *  Ajax Action Hooks - references name of JS function
 */
//add_action( 'wp_ajax_mp_settings_update', 'mp_settings_update' );


/**
 *  Update Agent Settings
 */
function mp_agent_update() {

	if ( isset( $_POST['mp_agent_update_click'] ) ) {

		$user = wp_get_current_user();

		$agent_id = $user->ID;

		/**
		 *  Loop through agent fields
		 *
		 *  Process form submit method
		 * @todo this might not work for each form - I might need to make this into two classes?
		 */
		$input_fields = agent_update::output_input_array( $agent_id );

		foreach ( $input_fields as $input ) {

			$input->update_value();
		}
	}
}


/**
 *  Ajax Action Hooks - references name of JS function
 */
//add_action( 'wp_ajax_mp_agent_update', 'mp_agent_update' );


/**
 *  Register New User
 */
function lv_register_user() {

	if ( isset( $_POST['mp_register_user_click'] ) ) {

		if ( isset( $_POST['username'] ) ) {
			$username      = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS );
			$first_name    = filter_input( INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$last_name     = filter_input( INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$email_address = filter_input( INPUT_POST, 'email_address', FILTER_SANITIZE_SPECIAL_CHARS );
			$password      = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS );
			//$agency_name   = filter_input( INPUT_POST, 'agency_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$company      = filter_input( INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS );
			$phone_number = filter_input( INPUT_POST, 'phone_number', FILTER_SANITIZE_SPECIAL_CHARS );
			$tin_ein_or_ssn = filter_input( INPUT_POST, 'tin_ein_or_ssn', FILTER_SANITIZE_SPECIAL_CHARS );

			$address = filter_input( INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS );
			$city = filter_input( INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS );
			$state = filter_input( INPUT_POST, 'state', FILTER_SANITIZE_SPECIAL_CHARS );
			$zip = filter_input( INPUT_POST, 'zip', FILTER_SANITIZE_SPECIAL_CHARS );

			// @todo get other inputs

			if ( email_exists( $email_address ) ) {
				wp_die( 'email_already_taken' );
			} elseif (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
				wp_die( 'invalid_email_address' );
			} else {
				require_once( 'lv-register-user.php' );
				$new_user = new lv_register_user(
					$username,
					$first_name,
					$last_name,
					$email_address,
					$password,
					$phone_number,
					$company,
					$tin_ein_or_ssn,
					$address,
					$city,
					$state,
					$zip );
				$new_user->process_registration_form();
				//wp_die( 'response' );
			}

		}


	}
}


/**
 *  Ajax Action Hooks - references name of JS function
 */
add_action( 'wp_ajax_lv_register_user', 'lv_register_user' ); //@todo remove this? (redirect if logged in?)
add_action( 'wp_ajax_nopriv_lv_register_user', 'lv_register_user' );




/**
 *  Submit RMA Form
 */
function lv_process_rma() {

	if ( isset( $_POST['lv_process_rma_click'] ) ) {

		if ( isset( $_POST['email_address'] ) ) {
			$first_name    = filter_input( INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$last_name     = filter_input( INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$email_address = filter_input( INPUT_POST, 'email_address', FILTER_SANITIZE_SPECIAL_CHARS );
			$company_name      = filter_input( INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS );
			$phone_number = filter_input( INPUT_POST, 'phone_number', FILTER_SANITIZE_SPECIAL_CHARS );

			$address = filter_input( INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS );
			$city = filter_input( INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS );
			$state = filter_input( INPUT_POST, 'state', FILTER_SANITIZE_SPECIAL_CHARS );
			$zip = filter_input( INPUT_POST, 'zip', FILTER_SANITIZE_SPECIAL_CHARS );
			
			$user_id = filter_input( INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS );

			$item_quantity = array();
			$item_name = array();
			$item_price = array();
			$item_serial = array();
			$item_po_number = array();
			$item_date = array();
			$item_description = array();

			for ( $i = 0; $i < 5; $i++ ) {

				$item_quantity[$i] = filter_input( INPUT_POST, 'item_quantity_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
				$item_name[$i] = filter_input( INPUT_POST, 'item_name_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
				$item_price[$i] = filter_input( INPUT_POST, 'item_price_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
				$item_serial[$i] = filter_input( INPUT_POST, 'item_serial_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
				$item_po_number[$i] = filter_input( INPUT_POST, 'item_po_number_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
				$item_date[$i] = filter_input( INPUT_POST, 'item_date_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
				$item_description[$i] = filter_input( INPUT_POST, 'item_description_' . ($i + 1), FILTER_SANITIZE_SPECIAL_CHARS );
			}

			// @todo get other inputs

			if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
				wp_die( 'invalid_email_address' );
			} else {

				//$data_string = $first_name . ' ' . $last_name . ' ' . $email_address . ' ' . $company . ' ' . $phone_number . ' ' . $address . ' ' . $city . ' ' . $state . ' ' . $zip . ' ' . $user_id;
					//wp_die('working so farz: ' . $data_string);



	/**
	* Create Post to record order
	*/
	$rma_title = $first_name . ' ' . $last_name . ' - RMA - ' . date("F j, Y, g:i a");

	$args = array(
		'post_title' => $rma_title,
		'post_type' => 'rmas',
		'post_status' => 'publish'
	);

	$new_rma_id = wp_insert_post($args);

	update_field( 'user_id', $user_id, $new_rma_id);
	update_field( 'first_name', $first_name, $new_rma_id);
	update_field( 'last_name', $last_name, $new_rma_id);
	update_field( 'email_address', $email_address, $new_rma_id);
	update_field( 'company_name', $company_name, $new_rma_id);
	update_field( 'phone_number', $phone_number, $new_rma_id);
	update_field( 'address', $address, $new_rma_id);
	update_field( 'city', $city, $new_rma_id);
	update_field( 'state', $state, $new_rma_id);
	update_field( 'zip', $zip, $new_rma_id);

	for ( $i = 0; $i < 5; $i++ ) {

		$item_data = array(
			'quantity' => $item_quantity[$i],
			'item_name' => $item_name[$i],
			'unit_price' => $item_price[$i],
			'serial_number' => $item_serial[$i],
			'po_number' => $item_po_number[$i],
			'date_purchased' => $item_date[$i],
			'return_problem_description' => $item_description[$i]
		);

		update_field( 'return_item_' . ($i + 1), $item_data, $new_rma_id);
	}

	// return_item_1
	// return_item_2
	// return_item_3
	// return_item_4
	// return_item_5


	// foreach( $shopping_cart_array as $id => $data ) {

	// 	$product_id_exp = explode('-', $id);
	// 	$product_id_actual = $product_id_exp[0];

	// 	$product = strtoupper(str_replace('-', ' ' , $data['product']));
	// 	$quantity = $data['quantity'];
	// 	$color = $data['color'];

	// 	if ( current_user_can('delete_published_posts')) {
	// 		$acf_price = get_field('wholesale_price', $product_id_actual);
	// 	} elseif (current_user_can('edit_posts')) {
	// 		$acf_price = get_field('retail_price', $product_id_actual);
	// 	} else {
	// 		$acf_price = get_field('market_price', $product_id_actual);
	// 	}

	// 	if ( $acf_price ) {
	// 		$price = $acf_price * $data['quantity'];
	// 		$acf_price_per = '$' . number_format($acf_price, 2);
	// 		$price_value = '$' . number_format($price, 2);
	// 		//$total_cost = $price + $total_cost;
	// 	} else {
 //          //$acf_price = false;
	// 		$acf_price_per = '';
	// 		$price_value = '';
	// 	}

	// 	$row = array(
	// 		'product_name'	=> $product,
	// 		'product_quantity'	=> $quantity,
	// 		'product_color'	=> $color,
	// 		'product_id' => $product_id_actual,
	// 		//'cat_id' => '11111',
	// 		'unit_cost' => $acf_price_per,
	// 		'cost_total' => $price_value
	// 	);

	// 	add_row('product_entries', $row, $new_order_id);
	// }




					// create RMA
					// process 

				// require_once( 'lv-register-user.php' );
				// $new_user = new lv_register_user(
				// 	$username,
				// 	$first_name,
				// 	$last_name,
				// 	$email_address,
				// 	$password,
				// 	$phone_number,
				// 	$company,
				// 	$tin_ein_or_ssn,
				// 	$address,
				// 	$city,
				// 	$state,
				// 	$zip );
				// $new_user->process_registration_form();
				// //wp_die( 'response' );


			}

		}


	} 
}

/**
 *  Ajax Action Hooks - references name of JS function
 */
add_action( 'wp_ajax_lv_process_rma', 'lv_process_rma' ); //@todo remove this? (redirect if logged in?)
add_action( 'wp_ajax_nopriv_lv_process_rma', 'lv_process_rma' );




/**
 *  Favorite Listing
 */
function mp_save_favorite_listing() {

	if ( isset( $_POST['listing_id'] ) ) {

		$listing_id      = $_POST['listing_id'];
		$listing_address = $_POST['listing_address'];
		$listing_url     = $_POST['listing_url'];
		$user_id         = $_POST['user_id'];

		global $wpdb;
		$prefix     = $wpdb->prefix;
		$table_name = $prefix . 'mp_favorite_listings';

		$favorite_query         = "SELECT * FROM `{$table_name}` WHERE `user_id` = '{$user_id}' AND `listing_id` = '{$listing_id}'";
		$query_favorite_listing = $wpdb->get_results( $favorite_query );

		if ( $query_favorite_listing ) {
			$entry_id              = $query_favorite_listing[0]->id;
			$favorite_query_delete = "DELETE FROM `{$table_name}` WHERE `id` = '{$entry_id}'";
			$wpdb->get_results( $favorite_query_delete );
		} else {

			$wpdb->insert( $table_name, array(
				'time'          => current_time( 'mysql' ),
				'user_id'       => $user_id,
				'listing_id'    => $listing_id,
				'listing_title' => $listing_address,
				'listing_url'   => $listing_url
			) );
		}
	}
}

//add_action( 'wp_ajax_mp_favorite_listing', 'mp_save_favorite_listing' );


/**
 *  Save Search
 */
function mp_save_search() {
	if ( isset( $_POST['search_request'] ) ) {

		$user_id    = $_POST['user_id'];
		$search_url = $_POST['search_request'];

		global $wpdb;
		$prefix     = $wpdb->prefix;
		$table_name = $prefix . 'mp_saved_searches';

		$saved_search_query = "SELECT * FROM `{$table_name}` WHERE `user_id` = '{$user_id}' AND `search_url` = '{$search_url}'";
		$query_saved_search = $wpdb->get_results( $saved_search_query );

		if ( $query_saved_search ) {
			$entry_id           = $query_saved_search[0]->id;
			$save_search_delete = "DELETE FROM `{$table_name}` WHERE `id` = '{$entry_id}'";
			$wpdb->get_results( $save_search_delete );
		} else {

			$wpdb->insert( $table_name, array(
				'time'       => current_time( 'mysql' ),
				'user_id'    => $user_id,
				'search_url' => $search_url
			) );
		}
	}
}

//add_action( 'wp_ajax_mp_save_search', 'mp_save_search' );


/**
 *  Send Listing Agent Email - @todo get code from CCG?
 */
function mp_send_listing_agent_email() {


	if ( isset( $_POST['mp_email_listing_agent_click'] ) ) {

		$user_name    = $_POST['user_name'];
		$user_phone   = $_POST['user_phone'];
		$user_email   = $_POST['user_email'];
		$user_comment = $_POST['user_comment'];
		$agent_email  = $_POST['agent_email'];

		$send_emails = new mp_send_email( $user_email, $user_name, $user_phone, $user_comment, $agent_email );
		$send_emails->send_email();
		//wp_die('email sent! ' . $agent_email);
	}
}

// should work whether or not logged in
//add_action( 'wp_ajax_mp_send_listing_agent_email', 'mp_send_listing_agent_email' );
//add_action( 'wp_ajax_nopriv_mp_send_listing_agent_email', 'mp_send_listing_agent_email' );


/**
 *  User Delete Listing
 */
function mp_user_delete_listing() {

	if ( isset( $_POST['listing_id'] ) ) {

		$listing_id = $_POST['listing_id'];

		if ( wp_delete_post( $listing_id ) ) {
			wp_die( true );
		} else {
			wp_die( false );
		}
	}
}

//add_action( 'wp_ajax_mp_user_delete_listing', 'mp_user_delete_listing' );

