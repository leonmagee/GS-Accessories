<?php

/**
 * Class mp_output_modal_login
 *
 */
class mp_output_modal_login {

	public $shortcode;
	public $link_id;
	public $form_title;

	public function __construct( $link_id, $form_title, $link_reg = null ) {

		$this->link_id    = $link_id;
		$this->form_title = $form_title;
		$this->link_reg   = $link_reg;
	}

	public function output_modal() { ?>

        <div id="<?php echo $this->link_id; ?>" class="reveal" data-reveal>
			<?php if ( $this->link_reg ) { ?>
                <h2 class="sign-up-modal-title" id="modalTitle"><?php echo $this->form_title; ?> or <a
                            href="<?php echo site_url(); ?>/register-account">Sign Up for New Account</a></h2>
			<?php } else { ?>
                <h2 id="modalTitle"><?php echo $this->form_title; ?></h2>
			<?php } ?>
            <div class="form-wrapper">

				<?php
				/**
				 * Login Form
				 */
				?>


                <section class="mp_loginForm">
					<?php
					global $user_login;

					// In case of a login error.
					if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
                        <div class="aa_error">
                            <p><?php _e( 'FAILED: Try again!', 'AA' ); ?></p>
                        </div>
						<?php
					endif;

					// If user is already logged in.
					if ( is_user_logged_in() ) : ?>

                        <div class="mp_logout">
                            Hello <?php echo $user_login; ?>
                            </br>
                            You are already logged in.
                        </div>
                        <a id="wp-submit" href="<?php echo wp_logout_url(); ?>" title="Logout">Logout</a>
						<?php
					// If user is not logged in.
					else:

						if ( is_ssl() ) {
							$scheme = 'https';
						} else {
							$scheme = 'http';
						}

						// Login form arguments.
						$args = array(
							'echo'           => true,
							'redirect'       => home_url( '/', $scheme ), // will work w/ ssl
							'form_id'        => 'loginform',
							'label_username' => __( 'Username' ),
							'label_password' => __( 'Password' ),
							'label_remember' => __( 'Remember Me' ),
							'label_log_in'   => __( 'Log In' ),
							'id_username'    => 'user_login',
							'id_password'    => 'user_pass',
							'id_remember'    => 'rememberme',
							'id_submit'      => 'wp-submit',
							'remember'       => true,
							'value_username' => null,
							'value_remember' => true
						);

						// Calling the login form.
						wp_login_form( $args );

					endif;
					?>

                </section>


            </div>
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

	<?php }
}