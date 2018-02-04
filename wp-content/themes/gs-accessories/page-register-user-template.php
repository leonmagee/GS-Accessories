<?php
/**
 * Template Name: Register User
 * @package GS_Accessories
 */
unrestricted_page();

get_template_part( 'template-parts/spinner' );
get_header(); ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="max-width-wrap">
                <header class="entry-header">
                    <h1 class="entry-title">Register Your Account</h1>
                </header>
                <div class="mp-update-success success callout">
                    Thank you for signing up with GS Wireless. We greatly appreciate your business. Your account is currently pending under review by GS Wireless administration. Please allow 24-48 business hours to process your request. Once your account has been approved, you will then be able to log-in and enjoy your benefits.
                </div>
                <div class="mp-required-fields callout alert">Plese fill out all required fields.</div>
                <div class="register-user-email-taken callout alert">
                    That email address is already taken! <a href="#">Login</a> - <a href="#">ForgotYour Password</a>
                </div>
				<?php get_template_part( 'template-parts/registration-form' ); ?>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->
<?php get_footer();
