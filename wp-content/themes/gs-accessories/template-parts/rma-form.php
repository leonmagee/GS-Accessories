<?php


require_once(__DIR__ . '/../lib/rma-product.php');

// lets get all the orders place by this user

$args = array(
        'post_type' => 'orders', 
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'user_id',
                'value' => LV_LOGGED_IN_ID
            ),
            array(
                'key' => 'paid',
                'value' => 'Completed'
            )
        ),
    );

$order_query = new WP_Query($args);

$products_array = [];
$imei_sn_array = [];

if ( $order_query->have_posts() ) {
    while( $order_query->have_posts() ) {
        global $post;
        $order_query->the_post(); 
        $date = get_the_date();
        $order_id = $post->ID;
        if ( ! $coupon_percent = get_field('coupon_percent') ) {
            $coupon_percent = 'N/A';
        } 

        $total_charge = get_field('total_charge');

        $entries = get_field('product_entries');

        if($entries) {

	        foreach( $entries as $entry ) {

	            $product_name = $entry['product_name'];
	            $product_id = $entry['product_id'];
	            $unit_cost = $entry['unit_cost'];

	            $products_array[] = new rma_product(
	                $product_name,
	                $date,
	                $order_id,
	                $unit_cost,
	                $coupon_percent
	            );
	        }
	    } 

        if($imei_sn_numbers = get_field('imei__serial_numbers')) {

	        foreach( $imei_sn_numbers as $imei_sn ) {

	            $imei_sn_array[] = [
	            	'sn' => $imei_sn['imei__serial_number'],
	            	'name' => $imei_sn['product_name']
	            ];
	        }
	    }
    }
}

//var_dump($imei_sn_array);


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

<?php if (count($products_array) ) { ?>

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
				<input name="item_quantity_<?php echo $i; ?>" type="number" min="1" />
			</div>
			<div class="registration-input-wrap rma-name">
				<label>Item Name</label>


            <select name="item_name_<?php echo $i; ?>" id="rma-item_<?php echo $i; ?>">
            	<option></option>
                <?php foreach($products_array as $spp) { ?>
                    <option 
                    purchase_date="<?php echo $spp->purchase_date; ?>"
                    po_number="<?php echo $spp->po_number; ?>"
                    updated_cost="<?php echo $spp->updated_cost; ?>"
                    ><?php echo $spp->product_name; ?></option>
                <?php } ?>
            </select>

			</div>
			<div class="registration-input-wrap rma-price">
				<label>Unit Price</label>
				<input disabled name="item_price_<?php echo $i; ?>" type="text" />
			</div>

			<div class="registration-input-wrap rma-serial">
				<label>IMEI or S/N</label>
				<?php if (count($imei_sn_array)) { ?>
			    <select id="imei_select" name="item_serial_<?php echo $i; ?>" id="item_serial">
					<option>N/A</option>
					<?php foreach($imei_sn_array as $item) { ?>
				  		<option class="non" value="<?php echo $item['sn']; ?>"><?php echo $item['sn']; ?></option>
					<?php } ?>
<!-- 			      <option class="editable" value="">Enter Text</option>
 -->			    </select>
			    <input class="editOption" style="display:none;"></input>
				<?php } else { ?>
					<input name="item_serial" id="item_serial" type="text" />
				<?php } ?>
			</div>


			<div class="registration-input-wrap rma-po-number">
				<label>PO Number</label>
				<input disabled name="item_po_number_<?php echo $i; ?>" type="text" />
			</div>

			<div class="registration-input-wrap rma-date">
				<label>Date Purchased</label>
				<input disabled name="item_date_<?php echo $i; ?>"  type="text" />
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
<?php } else { ?>
		<p>You need to have a saved order to be able to usbmit an RMA.</p>
		<br /><br /><br /.
<?php } ?>