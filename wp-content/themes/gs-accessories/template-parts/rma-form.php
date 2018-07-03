<?php

/**
* Get current user values...
*/
$user = wp_get_current_user();
$user_id = $user->ID;
$meta = get_user_meta($user_id);
$first_name = $meta['first_name'];
$last_name = $meta['last_name'];
$email_address = $user->user_email;
$acf_id = 'user_' . $user_id;
$phone_number = get_field('phone_number', $acf_id);
$company = get_field('company', $acf_id);
$address = get_field('address', $acf_id);
$city = get_field('city', $acf_id);
$state = get_field('state', $acf_id);
$zip = get_field('zip', $acf_id);


/**
 *  Form to register new users
 */
$regular_inputs = array(
	array( 'First Name', 1, 'text', $first_name[0] ),
	array( 'Last Name', 1, 'text', $last_name[0] ),
	array( 'Company Name', 1, 'text', $company ),
	array( 'Email Address', 1, 'text', $user->user_email ),
	array( 'Phone Number', 1, 'text', $phone_number ),
	array('Address', 1, 'text', $address),
	array('City', 1, 'text', $city),
	array('State', 1, 'text', $state),
	array('Zip', 1, 'text', $zip),
	//array('Message', 1, 'textarea'),
);
?>

<div class="registration-form-wrapper form-wrap">
    <form method="post" name="registration-form">
        <div class="form-area-top regular-inputs">
			<?php foreach ( $regular_inputs as $input ) {
				if ( $input[1] ) {
					$req = '<span class="required">*</span>';
				} else {
					$req = '';
				}
				$input_title = $input[0];
				$input_title  = str_replace( array(',', ' #'), '', $input_title );
				$input_name  = strtolower( str_replace( ' ', '_', $input_title ) );
				$value = $input[3];
				?>
                <div class="registration-input-wrap <?php echo $input[2]; ?> <?php echo strtolower($input[0]); ?>">
                    <label class="<?php echo $input_name; ?>"><?php echo $input_title; ?><?php echo $req; ?></label>
                    <?php if ( $input[2] == 'textarea' ) { ?>
						<textarea name="<?php echo $input_name; ?>"  class="<?php echo $input_name; ?>"></textarea>
                    <?php } else { ?>
                    <input type="<?php echo $input[2]; ?>" name="<?php echo $input_name; ?>"  class="<?php echo $input_name; ?>" value="<?php echo $value; ?>"/>
                    <?php } ?>
                </div>
			
			<?php } ?>

			<input type="hidden" class="logged_in_user_id" value="<?php echo $user_id; ?>" />

        </div>

        

        <?php
        $return_items = 6; // @todo make this a constant - then you can reference that in form
                           // submission...

        for ( $i = 1; $i < $return_items; ++$i ) {
        ?>

        <h4 class="return-items-header">Return Item #<?php echo $i; ?></h4>

		<div class="form-area-top return-details-wrap">
			<div class="registration-input-wrap rma-quantity">
				<label>Quantity</label>
				<input name="item_quantity_<?php echo $i; ?>" type="number" />
			</div>
			<div class="registration-input-wrap rma-name">
				<label>Item Name</label>
				<input name="item_name_<?php echo $i; ?>" type="text" />
			</div>
			<div class="registration-input-wrap rma-price">
				<label>Unit Price</label>
				<input name="item_price_<?php echo $i; ?>" type="text" />
			</div>
			<div class="registration-input-wrap rma-serial">
				<label>Serial Number</label>
				<input name="item_serial_<?php echo $i; ?>" type="text" />
			</div>
			
			<div class="registration-input-wrap rma-po-number">
				<label>PO Number</label>
				<input name="item_po_number_<?php echo $i; ?>" type="text" />
			</div>

			<div class="registration-input-wrap rma-date">
				<label>Date Purchased</label>
				<input name="item_date_<?php echo $i; ?>"  type="text" />
			</div>
			<div class="registration-input-wrap textarea">
				<label>Return Problem Description</label>
				<textarea name="item_description_<?php echo $i; ?>"></textarea>
			</div>
		</div>

		<?php } ?>

        <button type="submit" class="gs-button" id="rma-form-submit">Submit</button>
    </form>
</div>