<?php
/**
 *  Form to register new users
 *
 *  array( 'Label', 'required(boolean)' );
 */
$regular_inputs = array(
	array( 'First Name', 1, 'text' ),
	array( 'Last Name', 1, 'text' ),
	array( 'Company Name', 1, 'text' ),
	array( 'Username', 1, 'text' ),
	array( 'Email Address', 1, 'text' ),
	//array( 'Email Repeat', 1 ),
	array( 'Password', 1, 'text' ),
	//array( 'Password Repeat', 1 ),
	array( 'Phone Number', 1, 'text' ),
	//array( 'TIN, EIN or SSN #', 1, 'text'),
	array('Address', 1, 'text'),
	array('City', 1, 'text'),
	array('State', 1, 'text'),
	array('Zip', 1, 'text')
);
//$social_media_inputs_inputs = array(
//	'Facebook',
//	'Twitter',
//	'Google Plus',
//	'Pinterest',
//	'YouTube',
//	'Linkedin',
//	'Instagram'
//);
?>
<div class="registration-form-wrapper form-wrap">
    <form method="post" name="registration-form">
        <div class="form-area-top">
			<?php foreach ( $regular_inputs as $input ) {
				if ( $input[1] ) {
					$req = '<span class="required">*</span>';
				} else {
					$req = '';
				}
				$input_title = $input[0];
				$input_title  = str_replace( array(',', ' #'), '', $input_title );
				$input_name  = strtolower( str_replace( ' ', '_', $input_title ) );
				?>
                <div class="registration-input-wrap <?php echo $input[2]; ?>">
                    <label class="<?php echo $input_name; ?>"><?php echo $input_title; ?><?php echo $req; ?></label>
                    <?php if ( $input[2] == 'textarea' ) { ?>
						<textarea name="<?php echo $input_name; ?>"  class="<?php echo $input_name; ?>"></textarea>
                    <?php } else { ?>
                    <input type="<?php echo $input[2]; ?>" name="<?php echo $input_name; ?>"  class="<?php echo $input_name; ?>"/>
                    <?php } ?>
                </div>
			<?php } ?>
        </div>
        <button type="submit" class="gs-button" id="register-new-user-submit">Submit</button>
    </form>
</div>