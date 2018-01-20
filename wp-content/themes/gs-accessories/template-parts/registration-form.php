<?php
/**
 *  Form to register new users
 *
 *  array( 'Label', 'required(boolean)' );
 */
$regular_inputs = array(
	array( 'First Name', 1 ),
	array( 'Last Name', 1 ),
	array( 'Username', 1 ),
	array( 'Email Address', 1 ),
	//array( 'Email Repeat', 1 ),
	array( 'Password', 1 ),
	//array( 'Password Repeat', 1 ),
	array( 'Phone Number', 0 ),
	array( 'Company Name', 0 )
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
<div class="registration-form-wrapper">
    <form method="post" name="registration-form">
        <div class="form-area-top">
			<?php foreach ( $regular_inputs as $input ) {
				if ( $input[1] ) {
					$req = '<span class="required">*</span>';
				} else {
					$req = '';
				}
				$input_title = $input[0];
				$input_name  = strtolower( str_replace( ' ', '_', $input_title ) );
				?>
                <div class="registration-input-wrap">
                    <label><?php echo $input_title; ?><?php echo $req; ?></label>
                    <input type="text" name="<?php echo $input_name; ?>"  class="<?php echo $input_name; ?>"/>
                </div>
			<?php } ?>
        </div>
        <input type="submit" class="submit-input" id="register-new-user-submit"/>
    </form>
</div>