<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package GS_Accessories
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class('gs-accessories'); ?>>
	<div class="grid-y main-grid-wrap">
	<!-- <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gs-accessories' ); ?></a> -->

	<header id="masthead" class="site-header cell">

	 <div class="max-width-wrap">

		<div class="grid-x">

			<div class="cell medium-6">

				<div class="site-branding">

				<?php if ( $img_url = get_field('site_logo', 'option') ) { ?>

					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo $img_url; ?>" />
					</a>

					<?php } else { ?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'title' ); ?>
							</a>
						</h1>
					<?php } ?>

				</div><!-- .site-branding -->

			</div>

			<div class="cell medium-6">

			<nav id="site-navigation-custom" class="main-navigation-custom">

				<ul id="first_name" class="menu">

					<li><a href="/products">Products</a></li>
					<li><a href="/about">About</a></li>
					<li><a href="/contact">Contact</a></li>

					<?php if ( ! MP_LOGGED_IN_ID ) { ?>
						<li><a href="/contact">Log In</a></li>
					<?php } else { ?>

					<li><a href="/place-your-order">Place Order</a></li>
					<li><a href="/cart">Cart</a></li>

					<?php } ?>

				</ul>




				<?php
					// wp_nav_menu( array(
					// 	'theme_location' => 'menu-1',
					// 	'menu_id'        => 'primary-menu',
					// ) );
				?>
			</nav><!-- #site-navigation -->

			</div>

		</div>
		</div>

	</header><!-- #masthead -->

	<div class="main-content-wrap cell">
