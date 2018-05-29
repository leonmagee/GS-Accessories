<?php
/**
 * GS Accessories functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package GS_Accessories
 */

date_default_timezone_set('America/Los_Angeles');

/**
* Define Constants
*/
define('MOQ_WHOLESALER', 1000);
define('MOQ_DEALER', 100);

$email_wrap = '<div style="margin: 15px; border: 8px double #E4E4E4; border-radius: 10px; padding: 10px 30px; background-color: #FFF; font-size: 18px"><div style="text-align: center;"><div style="padding: 10px;"><img src="https://mygsaccessories.com/wp-content/uploads/2017/12/gs-accessories-logo.png" style="max-width: 350px"/></div>';

define('GSA_EMAIL_WRAP', $email_wrap);

 /**
  * Add Files
  **/
 require_once('lib/helper-functions.php');
 require_once('lib/generate-custom-post-type.php');
 function mm_register_post_types() {
 	md_create_wp_cpt::create_post_type( 'accessories', 'Accessory', 'Accessories', 'accessories', 'smartphone' );
 	md_create_wp_cpt::create_post_type( 'orders', 'Order', 'Orders', 'orders', 'cart' );
 	md_create_wp_cpt::create_post_type( 'coupons', 'Coupon', 'Coupons', 'coupon', 'tag' );
 }
 add_action( 'init', 'mm_register_post_types' );

 require_once('lib/shopping_cart.php');
 require_once('lib/process-form-submission.php');
 require_once('lib/output-modal-login.php');
 require_once('lib/lv-register-user.php');
 require_once('lib/lv-send-email-misc.php');
 require_once('lib/lv-ajax.php');
 require_once('lib/rest-endpoints.php');

 session_start();

/**
* test user meta
*/
//add_user_meta( $user_id, $meta_key, $meta_value, $unique ); 
//add_user_meta(8, 'tin_ein_or_ssn', 'testingzzz');


/**
 * Constants
 * @todo move to different file
 */
if ( is_user_logged_in() ) {
	$user    = wp_get_current_user(); // @todo search for this to replace with constant
	$user_id = $user->ID;
	$first_name = get_user_meta($user_id, 'first_name', true);
	$last_name = get_user_meta($user_id, 'last_name', true);
	define('LV_LOGGED_IN_NAME', $first_name . ' ' . $last_name);
	define( 'LV_LOGGED_IN_ID', $user_id );
} else {
	define( 'LV_LOGGED_IN_ID', false );
}

function restricted_page() {
	if ( ! is_user_logged_in() ) {
		wp_redirect('/');
		exit;
	}
	// if ( (! is_user_logged_in()) || (! current_user_can('edit_posts')) ) {
	// 	wp_redirect('/');
	// 	exit;
	// }
}

function unrestricted_page() {
	if ( is_user_logged_in() ) {
		wp_redirect('/');
		exit;
	}
}



if ( ! function_exists( 'gs_accessories_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function gs_accessories_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on GS Accessories, use a find and replace
		 * to change 'gs-accessories' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'gs-accessories', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'gs-accessories' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'gs_accessories_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'gs_accessories_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gs_accessories_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'gs_accessories_content_width', 640 );
}
add_action( 'after_setup_theme', 'gs_accessories_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gs_accessories_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'gs-accessories' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'gs-accessories' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'gs_accessories_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function gs_accessories_scripts() {
	wp_enqueue_style( 'gs-accessories-style', get_stylesheet_uri() );

	wp_enqueue_script( 'gs-accessories-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'gs-accessories-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_style( 'font-awesome', get_template_directory_uri() . '/vendor/font-awesome/css/font-awesome.min.css', array() );
	
	wp_enqueue_style( 'font-awesome' );

	wp_register_style( 'foundation-css', get_template_directory_uri() . '/vendor/foundation/css/foundation.min.css', '', '1.0.1' );

	wp_enqueue_style( 'foundation-css' );

	wp_register_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.1.13', true );

	wp_enqueue_script( 'custom-js');

	wp_register_script( 'foundation-js', get_template_directory_uri() . '/vendor/foundation/js/vendor/foundation.min.js', '', '1.0.1' );

	wp_register_script( 'foundation-init-js', get_template_directory_uri() . '/vendor/foundation/js/app.js', array('jquery','foundation-js'), '1.0.2', true );

	wp_enqueue_script( 'foundation-init-js' );

	
	wp_register_style( 'gs-accessories-styles', get_template_directory_uri() . '/assets/css/main.min.css', '', '1.0.28' );
	
	wp_enqueue_style( 'gs-accessories-styles' );
}
add_action( 'wp_enqueue_scripts', 'gs_accessories_scripts' );



/**
* Enqueue admin scripts and styles
*/
function gs_accessories_admin_scritps() {

	wp_register_script( 'custom-admin-js', get_template_directory_uri() . '/js/custom-admin.js', array('jquery'), '1.1.9', true );

	wp_enqueue_script( 'custom-admin-js');

	wp_register_style( 'gs-accessories-admin-styles', get_template_directory_uri() . '/assets/css/admin.min.css', '', '1.1.5' );
	
	wp_enqueue_style( 'gs-accessories-admin-styles' );
}
add_action('admin_enqueue_scripts', 'gs_accessories_admin_scritps');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add ACF Theme Options Page
 */
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page( array(
		'page_title' => 'Theme General Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false,
		'icon_url'   => 'dashicons-admin-settings',
		'position'   => 4
	) );
}

/**
* Add Image Sizes
*/
function gs_accessories_image_sizes() {
	add_image_size('cats_image', 500, 250, true);
	add_image_size('accessory_image', 800, 800, true);
}

add_action('init', 'gs_accessories_image_sizes');



/**
 * Redirect non admin users to front page
 */

add_action( 'admin_init', 'non_admin_users_redirect' );

function non_admin_users_redirect() {

	if ( ! current_user_can( 'level_5' ) ) {

		wp_redirect( site_url() );
	}
}


/**
* Disable Admin Bar for all users
*/
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if (!is_admin()) {
		show_admin_bar(false);
	}
// if (!current_user_can('administrator') && !is_admin()) {
//   show_admin_bar(false);
// }
}

/**
* Change User Role Names
*/
// function wps_change_role_name() {
// 	die('working');
// global $wp_roles;
// var_dump($wp_roles);
// if ( ! isset( $wp_roles ) )
// 	$wp_roles = new WP_Roles();
// 	$wp_roles->roles[‘contributor’][‘name’] = ‘Owner’;
// 	$wp_roles->role_names[‘contributor’] = ‘Owner’;
// }
// add_action(‘init’, ‘wps_change_role_name’);


function change_wp_role_names() {
	global $wp_roles;
	//if ( ! isset( $wp_roles ) ) {
	$wp_roles = new WP_Roles();
	$wp_roles->roles['subscriber']['name'] = 'New Signup';
	$wp_roles->role_names['subscriber'] = 'New Signup';
	$wp_roles->roles['contributor']['name'] = 'Retailer';
	$wp_roles->role_names['contributor'] = 'Retailer';
	$wp_roles->roles['author']['name'] = 'Wholesaler';
	$wp_roles->role_names['author'] = 'Wholesaler';
	//}
}

add_action('init', 'change_wp_role_names');

/**
* Let Editors Manage Users (run only once)
*/
function isa_editor_manage_users() {

	if ( get_option( 'isa_add_cap_editor_once' ) != 'done' ) {

        // let editor manage users

        $edit_editor = get_role('editor'); // Get the user role
        $edit_editor->add_cap('edit_users');
        $edit_editor->add_cap('list_users');
        $edit_editor->add_cap('promote_users');
        $edit_editor->add_cap('create_users');
        $edit_editor->add_cap('add_users');
        $edit_editor->add_cap('delete_users');

        update_option( 'isa_add_cap_editor_once', 'done' );
    }

}
add_action( 'init', 'isa_editor_manage_users' );


function isa_pre_user_query($user_search) {

	$user = wp_get_current_user();

	if ( ! current_user_can( 'manage_options' ) ) {

		global $wpdb;

		$user_search->query_where = 
		str_replace('WHERE 1=1', 
			"WHERE 1=1 AND {$wpdb->users}.ID IN (
			SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
			WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
			AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
			$user_search->query_where
		);

	}
}

add_action('pre_user_query','isa_pre_user_query');



/**
* Orders Meta Box 
* @todo move to different file
* @todo I need to separate out this so we have one form area to re-send the user email
* and another to send the shipping / notification email - I should also validate to make sure there 
* are at least the tracking number or the shipping service to let the email be submitted?
*/

function custom_meta_box_markup() { 

	global $post;
	$post_id = $post->ID;
	$user_email = get_field('customer_email', $post_id); 
	$admin_email = get_option('admin_email');
	$icon_url = get_site_url() . '/wp-admin/images/loading.gif'; ?>

	<input type="hidden" name="gsa-hidden-post-id" value="<?php echo $post_id; ?>" />

	<div class="gsa-email-control-wrap">
		<div class="form-group border">
			<div class="item">
				<label>Re-Send Customer Email</label>
			</div>
			<div class="item">
				<input name="gsa-email-address-user" placeholder="Email Address" value="<?php echo $user_email ; ?>" />
			</div>
			<div class="item buttons-flex">
				<a id="send-email-user" class="flex-item button button-primary">Send Email</a>
				<img class="flex-item gsa_spinner" src="<?php echo $icon_url; ?>" />
				<div class="flex-item callout success">Email Sent!</div>
				<div class="flex-item callout alert">Email Not Sent!</div>
			</div>
		</div>
		<div class="form-group border">
			<div class="item">
				<label>Re-Send Admin Email</label>
			</div>
			<div class="item">
				<input name="gsa-email-address-admin" placeholder="Email Address" value="<?php echo $admin_email ; ?>" />
			</div>
			<div class="item buttons-flex">
				<a id="send-email-admin" class="flex-item button button-primary">Send Email</a>
				<img class="flex-item gsa_spinner" src="<?php echo $icon_url; ?>" />
				<div class="flex-item callout success">Email Sent!</div>
				<div class="flex-item callout alert">Email Not Sent!</div>
			</div>
		</div>
		<div class="form-group">
			<div class="item">
				<label>Send Tracking Number / Shipping Service Email</label>
			</div>
			<div class="item">
				<input name="gsa-email-address-user-tracking" placeholder="Email Address" value="<?php echo $user_email ; ?>" />
			</div>
			<h4>Tracking Number</h4>
			<div class="item">
				<input name="gsa-tracking-number" />
			</div>
			<h4>Shipping Service</h4>
			<div class="item">
				<input name="gsa-shipping-service" />
			</div>
			<div class="item buttons-flex">
				<a id="send-email-user-tracking" class="flex-item button button-primary">Send Email</a>
				<img class="flex-item gsa_spinner" src="<?php echo $icon_url; ?>" />
				<div class="flex-item callout success">Email Sent!</div>
				<div class="flex-item callout alert">Email Not Sent!</div>
			</div>
		</div>
	</div>
	<?php }

	function order_email_meta_box() {

		add_meta_box(
			"gsa-email-meta-box", 
			"Email Settings", 
			"custom_meta_box_markup", 
			"orders", 
			"normal", 
			"low", 
			null
		);
	}

	add_action("add_meta_boxes", "order_email_meta_box");







	
	
