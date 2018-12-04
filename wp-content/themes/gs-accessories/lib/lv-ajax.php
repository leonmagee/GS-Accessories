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

		//wp_die($form_type);

		if ( isset( $_POST['username'] ) ) {
			$form_type 	   = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS );
			$username      = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS );
			$first_name    = filter_input( INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$last_name     = filter_input( INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS );
			$email_address = filter_input( INPUT_POST, 'email_address', FILTER_SANITIZE_SPECIAL_CHARS );
			$password      = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS );
			$company      = filter_input( INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS );
			$phone_number = filter_input( INPUT_POST, 'phone_number', FILTER_SANITIZE_SPECIAL_CHARS );
			$tin_ein_or_ssn = filter_input( INPUT_POST, 'tin_ein_or_ssn', FILTER_SANITIZE_SPECIAL_CHARS );

			$address = filter_input( INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS );
			$city = filter_input( INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS );
			$state = filter_input( INPUT_POST, 'state', FILTER_SANITIZE_SPECIAL_CHARS );
			$zip = filter_input( INPUT_POST, 'zip', FILTER_SANITIZE_SPECIAL_CHARS );


			if ( $form_type === 'reseller' ) {
				if ( ! ( $first_name && $last_name && $email_address && $password && $company && $phone_number && $tin_ein_or_ssn && $address && $city && $state && $zip ) ) {
					wp_die('fill_all_fields');
				}
			} else {
				if ( ! ( $first_name && $last_name && $email_address && $password && $phone_number && $address && $city && $state && $zip ) ) {
					wp_die('fill_all_fields');
				}
			}



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


			$ref_agent_id = get_user_meta($user_id, 'referring_agent', true);
			$ref_agent_email = false;
			if ( $ref_agent_id ) {
				$user_object = get_userdata($ref_agent_id);
				$ref_agent_email = $user_object->user_email;
			}




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

			// $item_quantity = filter_input( INPUT_POST, 'item_quantity', FILTER_SANITIZE_SPECIAL_CHARS );
			// $item_name = filter_input( INPUT_POST, 'item_name', FILTER_SANITIZE_SPECIAL_CHARS );
			// $item_price = filter_input( INPUT_POST, 'item_price', FILTER_SANITIZE_SPECIAL_CHARS );
			// $item_serial = filter_input( INPUT_POST, 'item_serial', FILTER_SANITIZE_SPECIAL_CHARS );
			// $item_po_number = filter_input( INPUT_POST, 'item_po_number', FILTER_SANITIZE_SPECIAL_CHARS );
			// $item_date = filter_input( INPUT_POST, 'item_date', FILTER_SANITIZE_SPECIAL_CHARS );
			// $item_description = filter_input( INPUT_POST, 'item_description', FILTER_SANITIZE_SPECIAL_CHARS );

			if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {

				wp_die( 'invalid_email_address' );

			} else {

				//Create Post to record order
				
				$rma_title = $first_name . ' ' . $last_name . ' - RMA - ' . date("F j, Y, g:i a");

				$args = array(
					'post_title' => $rma_title,
					'post_type' => 'rmas',
					'post_status' => 'publish'
				);

				$new_rma_id = wp_insert_post($args);

				$rma_number = 'GSA-RMA-' . $new_rma_id;

				update_field( 'rma_number', $rma_number, $new_rma_id);
				update_field( 'rma_status', 'Pending', $new_rma_id);
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


				$product_details = '';

				for ( $i = 0; $i < 5; $i++ ) {

					if($item_name[$i]) {

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

						$product_details .= '<div>
							<h3 style="margin-bottom: 8px"><span style="color: #32b79d">Item #' . ($i + 1) . '</span></h3>
							<div><span style="color: #32b79d">Quantity:</span> <strong>' . $item_quantity[$i] . '</strong></div>
							<div><span style="color: #32b79d">Item Name:</span> <strong>' . $item_name[$i] . '</strong></div>
							<div><span style="color: #32b79d">Unit Price:</span> <strong>' . $item_price[$i] . '</strong></div>
							<div><span style="color: #32b79d">IMEI or S/N:</span> <strong>' . $item_serial[$i] . '</strong></div>
							<div><span style="color: #32b79d">PO Number:</span> <strong>' . $item_po_number[$i] . '</strong></div>
							<div><span style="color: #32b79d">Date Purchased:</span> <strong>' . $item_date[$i] . '</strong></div>
							<div><span style="color: #32b79d">Description:</span> <strong>' . $item_description[$i] . '</strong></div>
						</div>';
					}
				}

				// $product_details = '';

				// $item_data = array(
				// 	'quantity' => $item_quantity,
				// 	'item_name' => $item_name,
				// 	'unit_price' => $item_price,
				// 	'serial_number' => $item_serial,
				// 	'po_number' => $item_po_number,
				// 	'date_purchased' => $item_date,
				// 	'return_problem_description' => $item_description
				// );

				// update_field( 'return_item_1', $item_data, $new_rma_id);

				// $product_details .= '<div>
				// 	<h3 style="margin-bottom: 8px"><span style="color: #32b79d">Return Item</span></h3>
				// 	<div><span style="color: #32b79d">Quantity:</span> <strong>' . $item_quantity . '</strong></div>
				// 	<div><span style="color: #32b79d">Item Name:</span> <strong>' . $item_name . '</strong></div>
				// 	<div><span style="color: #32b79d">Unit Price:</span> <strong>' . $item_price . '</strong></div>
				// 	<div><span style="color: #32b79d">IMEI or S/N:</span> <strong>' . $item_serial . '</strong></div>
				// 	<div><span style="color: #32b79d">PO Number:</span> <strong>' . $item_po_number . '</strong></div>
				// 	<div><span style="color: #32b79d">Date Purchased:</span> <strong>' . $item_date . '</strong></div>
				// 	<div><span style="color: #32b79d">Description:</span> <strong>' . $item_description . '</strong></div>
				// </div>';

				// send admin email
				$admin_email = get_option('admin_email');

				// send email to admin
				$admin_intro = '<div><span style="color: #32b79d">RMA Number: </span> <strong>' . $rma_number . '</strong><br /><span style="color: #32b79d">RMA submitted by</span> <strong>' . $first_name . ' ' . $last_name . '</strong><br /><span style="color: #32b79d">Company:</span> <strong>' . $company_name . '</strong><br /><span style="color: #32b79d">Address:</span> <strong>' . $address . '</strong><br /><strong>' . $city . ', ' . $state . ' ' . $zip . '</strong><br /><span style="color: #32b79d">Email:</span> <strong>' . $email_address . '</strong><br />';

				if ( $ref_agent_email ) {
					
					$to = array($admin_email, $ref_agent_email, 'leonmagee33@gmail.com');

				} else {

					$to = array($admin_email, 'leonmagee33@gmail.com');
				}

				$email_wrap = GSA_EMAIL_WRAP;
				$email_wrap_close = '</div>';

				$subject = 'GS Accessories RMA';
				$body_admin = $admin_intro . $product_details;
				$body_final_admin = $email_wrap . $body_admin . $email_wrap_close;
				$headers = array('Content-Type: text/html; charset=UTF-8');

				wp_mail( $to, $subject, $body_final_admin, $headers );


				$customer_email_text = '<div>Thank you for submitting your RMA request. You will receive another email once we have reviewed your request.</div>';

				$body_final_customer = $email_wrap . $customer_email_text . $email_wrap_close;

				//$to = array($admin_email, 'leonmagee33@gmail.com');

				wp_mail( $email_address, $subject, $body_final_customer, $headers );

			}
		}
	} 
}

/**
 *  Ajax Action Hooks - references name of JS function
 */
add_action( 'wp_ajax_lv_process_rma', 'lv_process_rma' ); //@todo remove this? (redirect if logged in?)
add_action( 'wp_ajax_nopriv_lv_process_rma', 'lv_process_rma' );





