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
require_once 'lib/helper-functions.php';

require_once 'lib/extended-cpts/extended-cpts.php';

// add_action( 'init', function() {
//     register_extended_post_type( 'accessories', [

//         # Add the post type to the site's main RSS feed:
//         'show_in_feed' => true,

//         # Show all posts on the post type archive:
//         'archive' => [
//             'nopaging' => true,
//         ],
//         'menu_icon' => 'dashicons-smartphone',
//         // 'taxonomy' => array(
//         //     'categories' => array(
//         //         'taxonomy' => 'category',
//         //     ),
//         // ),
//         'admin_cols' => array(
//             'categories' => array(
//                 'taxonomy' => 'category',
//             ),
//             'post_date' => array(
//                 'title'      => 'Accessory',
//                 'post_field' => 'post_date',
//                 'default'      => 'DESC'
//             ),
//         ),
//         'admin_filters' => [
//             'categories' => [
//                 'taxonomy' => 'category',
//             ]
//         ],
//     ], [

//         # Override the base names used for labels:
//         'singular' => 'Accessory',
//         'plural'   => 'Accessories',
//         'slug'     => 'accessories',
//     ] );
// } );

// add_action( 'init', function() {
//     register_extended_taxonomy( 'category', 'accessories', [], [

//         'singular' => 'Category',
//         'plural'   => 'Categories',
//         'slug'     => 'categories',
//     ] );
// } );

add_action('init', function () {
    register_extended_post_type('orders', [

        # Add the post type to the site's main RSS feed:
        'show_in_feed' => true,

        # Show all posts on the post type archive:
        'archive' => [
            'nopaging' => true,
        ],

        //Add some custom columns to the admin screen:
        'admin_cols' => [
            'order_status' => [
                'title' => 'Order Status',
                'meta_key' => 'paid',
            ],
            'order_type' => [
                'title' => 'Order Type',
                'meta_key' => 'order_type',
            ],
            'total_charge' => [
                'title' => 'Total Charge',
                'meta_key' => 'total_charge',
            ],
            'post_date' => array(
                'title' => 'Order Placed',
                'post_field' => 'post_date',
                'default' => 'DESC',
            ),
        ],
        'menu_icon' => 'dashicons-cart',

        # Add a dropdown filter to the admin screen:
        'admin_filters' => [
            'order_status' => [
                'title' => 'Order Status',
                'meta_key' => 'paid',
            ],
        ],

    ], [

        # Override the base names used for labels:
        'singular' => 'Order',
        'plural' => 'Orders',
        'slug' => 'orders',

    ]);

});

add_action('init', function () {
    register_extended_post_type('coupons', [

        # Add the post type to the site's main RSS feed:
        'show_in_feed' => true,

        # Show all posts on the post type archive:
        'archive' => [
            'nopaging' => true,
        ],
        'admin_cols' => [
            'couon_percent' => [
                'title' => 'Coupon Percent',
                'meta_key' => 'discount_percent',
            ],
        ],
        'menu_icon' => 'dashicons-tag',
    ], [

        # Override the base names used for labels:
        'singular' => 'Coupon',
        'plural' => 'Coupons',
        'slug' => 'coupon',
    ]);
});

add_action('init', function () {
    register_extended_post_type('rmas', [

        # Add the post type to the site's main RSS feed:
        'show_in_feed' => true,

        # Show all posts on the post type archive:
        'archive' => [
            'nopaging' => true,
        ],

        //Add some custom columns to the admin screen:
        'admin_cols' => [
            'rma_status' => [
                'title' => 'RMA Status',
                'meta_key' => 'rma_status',
            ],
            'rma_number' => [
                'title' => 'RMA Number',
                'meta_key' => 'rma_number',
            ],
            'post_date' => array(
                'title' => 'RMA Requested',
                'post_field' => 'post_date',
                'default' => 'DESC',
            ),
        ],
        'menu_icon' => 'dashicons-image-rotate',

        # Add a dropdown filter to the admin screen:
        'admin_filters' => [
            'rma_status' => [
                'title' => 'RMA Status',
                'meta_key' => 'rma_status',
            ],
        ],
    ], [
        # Override the base names used for labels:
        'singular' => 'RMA',
        'plural' => 'RMA',
        'slug' => 'rmas',

    ]);

});

require_once 'lib/generate-custom-post-type.php';

function mm_register_post_types()
{
    md_create_wp_cpt::create_post_type('accessories', 'Accessory', 'Accessories', 'accessories', 'smartphone');
    // md_create_wp_cpt::create_post_type( 'orders', 'Order', 'Orders', 'orders', 'cart' );
    // md_create_wp_cpt::create_post_type( 'coupons', 'Coupon', 'Coupons', 'coupon', 'tag' );
    // md_create_wp_cpt::create_post_type( 'rmas', 'RMA', 'RMA', 'rmas', 'image-rotate' );
}
add_action('init', 'mm_register_post_types');

require_once 'lib/shopping_cart.php';
require_once 'lib/process-form-submission.php';
require_once 'lib/output-modal-login.php';
require_once 'lib/lv-register-user.php';
require_once 'lib/lv-send-email-misc.php';
require_once 'lib/lv-ajax.php';
require_once 'lib/rest-endpoints.php';

/**
 * Start session to enable shopping cart functionality
 */
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
if (is_user_logged_in()) {
    $user = wp_get_current_user(); // @todo search for this to replace with constant
    $user_id = $user->ID;
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $hide_accessories = get_field('hide_accessories', 'user_' . $user_id);
    define('LV_LOGGED_IN_NAME', $first_name . ' ' . $last_name);
    define('LV_LOGGED_IN_ID', $user_id);
    define('LV_LOGGED_IN_EMAIL', $user->user_email);
    define('LV_HIDE_ACCESSORIES', $hide_accessories);
} else {
    define('LV_LOGGED_IN_ID', false);
    define('LV_HIDE_ACCESSORIES', false);
}

function restricted_page()
{
    if ((!is_user_logged_in())) { // get user role here and restrict?
        wp_redirect('/');
        exit;
    }
    // if ( (! is_user_logged_in()) || (! current_user_can('edit_posts')) ) {
    //     wp_redirect('/');
    //     exit;
    // }
}

function unrestricted_page()
{
    if (is_user_logged_in()) {
        wp_redirect('/');
        exit;
    }
}

if (!function_exists('gs_accessories_setup')):
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function gs_accessories_setup()
{
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on GS Accessories, use a find and replace
         * to change 'gs-accessories' to the name of your theme in all the template files.
         */
        load_theme_textdomain('gs-accessories', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'gs-accessories'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('gs_accessories_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }
endif;
add_action('after_setup_theme', 'gs_accessories_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gs_accessories_content_width()
{
    $GLOBALS['content_width'] = apply_filters('gs_accessories_content_width', 640);
}
add_action('after_setup_theme', 'gs_accessories_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gs_accessories_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'gs-accessories'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'gs-accessories'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'gs_accessories_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function gs_accessories_scripts()
{
    wp_enqueue_style('gs-accessories-style', get_stylesheet_uri());

    // behalf script
    //<script src="https://sdk.behalf.com/sdk/v4/behalf_payment_sdk.js" async></script>
    wp_enqueue_script('behalf', 'https://sdk.behalf.com/sdk/v4/behalf_payment_sdk.js', [], '20190727', true);

    wp_enqueue_script('gs-accessories-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);

    wp_enqueue_script('gs-accessories-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_register_style('font-awesome', get_template_directory_uri() . '/vendor/font-awesome/css/font-awesome.min.css', array());

    wp_enqueue_style('font-awesome');

    wp_register_style('foundation-css', get_template_directory_uri() . '/vendor/foundation/css/foundation.min.css', '', '1.0.1');

    wp_enqueue_style('foundation-css');

    wp_register_script('custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'behalf'), '1.2.31', true);

    wp_enqueue_script('custom-js');

    wp_register_script('foundation-js', get_template_directory_uri() . '/vendor/foundation/js/vendor/foundation.min.js', '', '1.0.1');

    wp_register_script('foundation-init-js', get_template_directory_uri() . '/vendor/foundation/js/app.js', array('jquery', 'foundation-js'), '1.0.2', true);

    wp_enqueue_script('foundation-init-js');

    wp_register_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', '', '1.1.1');

    wp_register_style('gs-accessories-styles', get_template_directory_uri() . '/assets/css/main.min.css', array('jquery-ui-css'), '1.2.46');

    wp_enqueue_style('gs-accessories-styles');
}
add_action('wp_enqueue_scripts', 'gs_accessories_scripts');

/**
 * Enqueue admin scripts and styles
 */
function gs_accessories_admin_scritps()
{

    wp_register_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', '', '1.1.1');

    wp_register_script('custom-admin-js', get_template_directory_uri() . '/js/custom-admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), '1.1.21', true);

    wp_enqueue_script('custom-admin-js');

    wp_register_style('gs-accessories-admin-styles', get_template_directory_uri() . '/assets/css/admin.min.css', array('jquery-ui-css'), '1.1.15');

    wp_enqueue_style('gs-accessories-admin-styles');
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
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add ACF Theme Options Page
 */
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
        'icon_url' => 'dashicons-admin-settings',
        'position' => 4,
    ));
}

/**
 * Add Image Sizes
 */
function gs_accessories_image_sizes()
{
    add_image_size('cats_image', 500, 250, true);
    add_image_size('accessory_image', 800, 800, true);
}

add_action('init', 'gs_accessories_image_sizes');

/**
 * Redirect non admin users to front page
 */

add_action('admin_init', 'non_admin_users_redirect');

function non_admin_users_redirect()
{

    /**
     * @todo this needs to effect Agents as well...
     * @todo test this for non -localhost site?
     */
    if ((!current_user_can('level_5')) && ($_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php')) {
        //if ( ! current_user_can( 'delete_others_pages' ) ) {

        wp_redirect(site_url());
        //exit;
    }
}

// add_action( 'admin_init', 'redirect_non_logged_users_to_specific_page' );

// function redirect_non_logged_users_to_specific_page() {

// if ( !is_user_logged_in() && is_page('add page slug or i.d here') && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {

// wp_redirect( 'http://www.example.dev/page/' );
//     exit;
// }

add_action('init', 'define_agent_constant');

function define_agent_constant()
{

    if (is_user_logged_in()) {

        $current_user = wp_get_current_user();
        $role_name = $current_user->roles[0];
        if ($role_name === 'um_agent') {
            define('AGENT_LOGGED_IN', true);
        } else {
            define('AGENT_LOGGED_IN', false);
        }
    } else {
        define('AGENT_LOGGED_IN', false);
    }
}

/**
 * Redirect Agents
 * @todo remove level_10 access - does this do anything??????
 */
add_action('pre_get_posts', 'agent_login_redirect');

function agent_login_redirect()
{

    if (is_user_logged_in()) {

        $current_user = wp_get_current_user();
        $role_name = $current_user->roles[0];
        //var_dump($role_name);
        if ($role_name === 'um_agent') {
            $current_page = sanitize_post($GLOBALS['wp_the_query']->get_queried_object());
            $slug = $current_page->post_name;
            if (($slug !== 'agent-admin') && ($slug !== 'register-user-agent')) {
                /**
                 * @todo also allow for registration page? reports page? any page available to Agent
                 */
                wp_redirect(site_url() . '/agent-admin');
                //exit;
            }
        }
    }
}

/**
 * Disable Admin Bar for all users
 */
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
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
//     die('working');
// global $wp_roles;
// var_dump($wp_roles);
// if ( ! isset( $wp_roles ) )
//     $wp_roles = new WP_Roles();
//     $wp_roles->roles[‘contributor’][‘name’] = ‘Owner’;
//     $wp_roles->role_names[‘contributor’] = ‘Owner’;
// }
// add_action(‘init’, ‘wps_change_role_name’);

function change_wp_role_names()
{
    global $wp_roles;
    //if ( ! isset( $wp_roles ) ) {
    $wp_roles = new WP_Roles();
    $wp_roles->roles['subscriber']['name'] = 'New Signup';
    $wp_roles->role_names['subscriber'] = 'New Signup';
    $wp_roles->roles['contributor']['name'] = 'Retailer';
    $wp_roles->role_names['contributor'] = 'Retailer';
    $wp_roles->roles['author']['name'] = 'Wholesaler';
    $wp_roles->role_names['author'] = 'Wholesaler';
}

add_action('init', 'change_wp_role_names');

/**
 * Let Editors Manage Users (run only once)
 */
function isa_editor_manage_users()
{

    if (get_option('isa_add_cap_editor_once') != 'done') {

        // let editor manage users

        $edit_editor = get_role('editor'); // Get the user role
        $edit_editor->add_cap('edit_users');
        $edit_editor->add_cap('list_users');
        $edit_editor->add_cap('promote_users');
        $edit_editor->add_cap('create_users');
        $edit_editor->add_cap('add_users');
        $edit_editor->add_cap('delete_users');

        update_option('isa_add_cap_editor_once', 'done');
    }

}
add_action('init', 'isa_editor_manage_users');

function isa_pre_user_query($user_search)
{

    $user = wp_get_current_user();

    if (!current_user_can('manage_options')) {

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

add_action('pre_user_query', 'isa_pre_user_query');

/**
 * Orders Meta Box
 */
function custom_meta_box_markup()
{

    global $post;
    $post_id = $post->ID;
    $user_email = get_field('customer_email', $post_id);
    $user_id = get_field('user_id', $post_id);
    $admin_email = get_option('admin_email');
    $icon_url = get_site_url() . '/wp-admin/images/loading.gif';
    ?>

	<input type="hidden" name="gsa-hidden-post-id" value="<?php echo $post_id; ?>" />
	<input type="hidden" name="gsa-user-id" value="<?php echo $user_id; ?>" />

	<div class="gsa-email-control-wrap">
		<div class="form-group border">
			<div class="item">
				<label>Re-Send Customer Email</label>
			</div>
			<div class="item">
				<input name="gsa-email-address-user" placeholder="Email Address" value="<?php echo $user_email; ?>" />
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
				<input name="gsa-email-address-admin" placeholder="Email Address" value="<?php echo $admin_email; ?>" />
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
				<input name="gsa-email-address-user-tracking" placeholder="Email Address" value="<?php echo $user_email; ?>" />
			</div>
			<h4>Tracking Number</h4>
			<div class="item">
				<input name="gsa-tracking-number" />
			</div>
			<h4>Shipping Service</h4>
			<div class="item">
				<select name="gsa-shipping-service" style="min-width: 350px">
					<option value="USPS">USPS</option>
					<option value="FedEx">FedEx</option>
					<option value="UPS">UPS</option>
					<option value="DHL">DHL</option>
				</select>
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

function order_email_meta_box()
{

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

/**
 * RMA Meta Box
 */
function custom_meta_box_markup_rma()
{

    global $post;
    $post_id = $post->ID;
    $user_email = get_field('email_address', $post_id);
    $icon_url = get_site_url() . '/wp-admin/images/loading.gif';
    $user_id = get_field('user_id', $post_id);
    ?>

	<input type="hidden" name="gsa-hidden-post-id" value="<?php echo $post_id; ?>" />
	<input type="hidden" name="gsa-user-id" value="<?php echo $user_id; ?>" />

	<div class="gsa-email-control-wrap">
		<div class="form-group border">
			<div class="item">
				<label>Customer Email</label>
			</div>
			<div class="item">
				<input name="gsa-email-address-user" placeholder="Email Address" value="<?php echo $user_email; ?>" />
			</div>
			<div class="item">
				<label>Message to Customer</label>
			</div>
			<div class="item">
				<textarea id="rma-message" name="rma-message"></textarea>
			</div>
			<div class="item buttons-flex">
				<a id="send-rma-email" class="flex-item button button-primary">Approve RMA</a>
				<a id="send-rma-email-reject" class="flex-item button button-secondary">Reject RMA</a>
				<a id="rma-original-email" class="flex-item button button-secondary">Re-Send Original Email</a>
				<a id="rma-custom-message" class="flex-item button button-secondary">Custom Message</a>
				<img class="flex-item gsa_spinner" src="<?php echo $icon_url; ?>" />
				<div class="flex-item callout success approve">RMA Approved!</div>
				<div class="flex-item callout success reject">RMA Rejected</div>
				<div class="flex-item callout success email-resend">RMA Email Re-Sent</div>
				<div class="flex-item callout success custom-message">RMA Custom Message Sent</div>
				<div class="flex-item callout alert">Email Not Sent!</div>
			</div>
		</div>

	</div>
<?php }

function rma_meta_box()
{

    add_meta_box(
        "gsa-email-meta-box",
        "RMA Settings",
        "custom_meta_box_markup_rma",
        "rmas",
        "normal",
        "low",
        null
    );
}

add_action("add_meta_boxes", "rma_meta_box");

/**
 * Inventory Report Admin Page
 */
add_action('admin_menu', 'inventory_report_admin_page');

function inventory_report_admin_page()
{

    add_menu_page('Inventory Report', 'Inventory', 'manage_options', 'current-inventory.php', 'inventory_admin_page', 'dashicons-chart-line', 6);
}

function inventory_admin_page()
{

    if (isset($_POST['update-inventory-sort'])) {
        $inventory_cat = $_POST['inventory-sort'];
    } else {
        $inventory_cat = '';
    }
    ?>
	<div class="wrap">
		<h2>Current Inventory</h2>

		<?php $cats = get_categories();?>

		<div class="sort-inventory-wrap">
			<form method="POST">
				<div>
					<h4 style="margin-bottom: 0">Sort Inventory</h4>
				</div>
				<div style="padding: 10px 0;">
					<input type="hidden" name="update-inventory-sort" />
					<select style="min-width: 200px;" name="inventory-sort">
						<option value="">All</option>
						<?php foreach ($cats as $cat) {
        $selected = '';
        if ($inventory_cat === $cat->slug) {
            $selected = 'selected="true"';
        }
        ?>
							<option <?php echo $selected; ?> value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
						<?php }?>
					</select>
				</div>
				<div>
					<input class="button button-primary" type="submit" value="Update"/>
				</div>
			</form>
		</div>

		<div class="accessory-inventory-wrap">

			<?php

    if ($inventory_cat) {
        $args = array(
            'post_type' => 'accessories',
            'category_name' => $inventory_cat,
        );
    } else {
        $args = array(
            'post_type' => 'accessories',
        );
    }

    $custom_query = new WP_Query($args);
    while ($custom_query->have_posts()) {
        $custom_query->the_post();

        ?>

				<?php $color_quantity = get_accessory_colors();
        if ($color_quantity) {?>
					<div class="accessory-inventory-item">
						<h3 style="margin-bottom: 7px; margin-left: 3px;"><?php the_title();?></h3>
						<table class="widefat fixed" cellspacing="0">
							<thead>
								<tr>
									<th>Color</th>
									<th>In Stock</th>
								</tr>
							</thead>
							<tbody>

								<?php foreach ($color_quantity as $item => $quantity) {?>
									<tr class="alternate">
										<td style="border: 1px solid #EEE"><?php echo $item; ?></td>
										<td style="border: 1px solid #EEE"><?php echo number_format($quantity); ?> Available</td>
									</tr>
								<?php }?>

							</tbody>
						</table>
					</div>

				<?php }
    }
    wp_reset_postdata();

    ?>

		</div>
	</div>
	<?php
}

//add_action('init', 'temp_update_order_value');

// function temp_update_order_value() {

//         /**
//         * Temp updating of Paid in Full to completed
//         */
//         $args = array(
//                 'post_type' => 'orders',
//                 'posts_per_page' => -1,
//                 'meta_query' => array(
//                     array(
//                         'key' => 'paid',
//                         'value' => 'Paid in Full'
//                     )
//                 ),
//             );

//         $temp_update_query = new WP_Query($args);

//         if ( $temp_update_query->have_posts() ) {

//             while( $temp_update_query->have_posts() ) {

//                 $temp_update_query->the_post();

//                 $field = get_field('paid');
//                 update_field('paid', 'Completed');

//                 }

//             }

// }

/**
 * Sales Report Admin Page
 */
add_action('admin_menu', 'sales_report_admin_page');

function sales_report_admin_page()
{

    add_menu_page('Sales Report', 'Sales', 'manage_options', 'current-sales.php', 'sales_admin_page', 'dashicons-chart-bar', 6);
}

function sales_admin_page()
{

    if (isset($_POST['change-month-year-admin'])) {

        $current_month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);

        //var_dump($current_month);
        $current_year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);

        // wp_redirect('/agent-admin?data_month=' . $month . '&data_year=' . $year);
        // exit;

        //$current_month = intval($_GET['data_month']);
        //$current_year = intval($_GET['data_year']);
        $monthName = DateTime::createFromFormat('!m', $current_month)->format('F');
        $display_date = $monthName . ' ' . $current_year;
    } else {
        $current_month = intval(date('n'));
        $current_year = intval(date('Y'));
        $display_date = 'for ' . date('F Y');
    }

    $gsa_date_rage_query = false;

    if (isset($_POST['change-date-range-admin'])) {
        $gsa_date_rage_query = true;
        $datepicker_start = filter_input(INPUT_POST, 'datepicker-start', FILTER_SANITIZE_SPECIAL_CHARS);
        $datepicker_end = filter_input(INPUT_POST, 'datepicker-end', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($datepicker_start && $datepicker_end) {
            $display_date = 'for ' . $datepicker_start . ' - ' . $datepicker_end;
        } elseif ($datepicker_start) {
            $display_date = 'for ' . $datepicker_start . ' - present';
        } elseif ($datepicker_end) {
            $display_date = 'through ' . $datepicker_end;
        }
    }

    ?>
	<div class="wrap gsa-sales-admin-page">

		<h2>Current Sales</h2>

		<div class="sales-report-wrap">

			<div class="forms-wrap">

				<div class="month-choice form-item-group">

					<div class="change-date-form">
						<?php

    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

    $years = array();
    $current_year_loop = intval(date('Y'));
    for ($i = 2018; $i <= $current_year_loop; $i++) {
        $years[] = $i;
    }?>

						<form class="change-date-form-inner" method="POST" action="#">

							<h5>Sort by Month, Year</h5>

							<div style="padding: 10px 0;">
								<input type="hidden" name="change-month-year-admin" />
								<div>
									<select name="month">
										<?php foreach ($months as $key => $month) {
        $month_val = ($key + 1);
        if ($month_val === intval($current_month)) {
            $selected = 'selected="true"';
        } else {
            $selected = '';
        }
        ?>
											<option <?php echo $selected; ?> value="<?php echo $month_val; ?>"><?php echo $month; ?></option>
										<?php }?>
									</select>
								</div>
								<div style="margin-top: 5px;">
									<select name="year">
										<?php foreach ($years as $year) {
        if (intval($year) === intval($current_year)) {
            $selected = 'selected="true"';
        } else {
            $selected = '';
        }
        ?>
											<option <?php echo $selected; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php }?>
									</select>
								</div>
							</div>

							<div>
								<button type="submit" class="button button-primary">Update</button>
							</div>
						</form>
					</div>
				</div>

				<div class="range-choice form-item-group">
					<h5>Sort by Date Range</h5>
					<form method="POST">
						<input type="hidden" name="change-date-range-admin" />
						<div class="date-range-input">
							<label>Starting Date:</label>
							<input type="text" name="datepicker-start" id="datepicker_start" />
						</div>
						<div class="date-range-input">
							<label>Ending Date:</label>
							<input type="text" name="datepicker-end" id="datepicker_end" />
						</div>

						<div>
							<button type="submit" class="button button-primary">Update</button>
						</div>

					</form>
				</div>

			</div>

			<div class="completed-orders-wrap">

				<?php if ($gsa_date_rage_query) {

        $args = array(
            'post_type' => 'orders',
            'posts_per_page' => -1,
            'date_query' => array(
                array(
                    'before' => $datepicker_end,
                    'after' => $datepicker_start,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'paid',
                    'value' => 'Completed',
                ),
            ),
        );
    } else {
        $args = array(
            'post_type' => 'orders',
            'posts_per_page' => -1,
            'date_query' => array(
                array(
                    'year' => $current_year,
                    'month' => $current_month,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'paid',
                    'value' => 'Completed',
                ),
            ),
        );
    }

    $order_query = new WP_Query($args);

    $total_payment = 0;
    if ($order_query->have_posts()) {
        while ($order_query->have_posts()) {
            global $post;
            $order_query->the_post();
            $date = get_the_date();
            $purchaser_id = get_field('user_id');
            $userdata = get_userdata($purchaser_id);
            $first = $userdata->user_firstname;
            $last = $userdata->user_lastname;
            $company = get_field('company', 'user_' . $purchaser_id);
            $order_id = $post->ID;
            $sub_total = get_field('sub_total');
            if (!$credit_applied = get_field('credit_applied')) {
                $credit_applied = 'N/A';
            } else {
                $credit_applied = '$' . number_format($credit_applied, 2);
            }
            if (!$coupon_percent = get_field('coupon_percent')) {
                $coupon_percent = 'N/A';
            } else {
                $coupon_percent = $coupon_percent . '%';
            }
            $total_charge = get_field('total_charge');
            ?>

						<div class="order-details-wrap">
							<table style="margin-top: 30px;" class="widefat fixed" cellspacing="0">
								<thead>
									<tr class="alternate">
										<th>PO Number</th>
										<th>Company</th>
										<th>Name</th>
										<th>Date</th>
										<th>Sub Total</th>
										<th>Credit Applied</th>
										<th>Coupon Percent</th>
										<th>Total Charge</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo 'GSA-Order-' . $order_id; ?></td>
										<td><?php echo $company; ?></td>
										<td><?php echo $first . ' ' . $last; ?></td>
										<td><?php echo $date; ?></td>
										<td><?php echo $sub_total; ?></td>
										<td><?php echo $credit_applied; ?></td>
										<td><?php echo $coupon_percent; ?></td>
										<td><?php echo $total_charge; ?></td>
									</tr>
								</tbody>
							</table>

							<div class="products-ordred" style="margin-top: 10px;">

								<table class="widefat fixed" cellspacing="0">
									<thead>
										<tr class="alternate">
											<th>Product</th>
											<th>Color</th>
											<th>Quantity</th>
											<th>Unit Cost</th>
											<th>Total Cost</th>
										</tr>
									</thead>
									<tbody>

										<?php $entries = get_field('product_entries');

            foreach ($entries as $entry) {
                $product_name = $entry['product_name'];
                $product_id = $entry['product_id'];
                $product_color = $entry['product_color'];
                $product_quantity = $entry['product_quantity'];
                $unit_cost = $entry['unit_cost'];
                $cost_total = $entry['cost_total'];
                $cost_actual = intval(str_replace(array('$', ','), '', $cost_total));
                $category_array = get_the_category($product_id);
                $cat_name = $category_array[0]->name;
                $cat_id = $category_array[0]->term_id;
                $payment = $cost_actual;
                $total_payment = ($total_payment + $payment);?>

											<tr class="product-table-items">
												<td><?php echo $product_name; ?></td>
												<td><?php echo $product_color; ?></td>
												<td><?php echo $product_quantity; ?></td>
												<td><?php echo $unit_cost; ?></td>
												<td><?php echo $cost_total; ?></td>
<!-- 											<td>$<?php echo number_format($payment, 2); ?></td>
-->										</tr>

<?php }?>

</tbody>

</table>

</div>

</div>

<?php }

    } else {?>
	<div class="gsa-no-orders-info">No orders for this period.</div>
<?php }
    wp_reset_postdata();
    ?>

<div class="gsa-agent-total-payment">
	<h3>Total Sales <?php echo $display_date; ?>: <span>$<?php echo number_format($total_payment, 2); ?></span></h3>

</div>

</div>

</div>

</div>
<?php
}








/**
 * Agent Report Admin Page
 */
add_action('admin_menu', 'agent_report_admin_page');

function agent_report_admin_page()
{

    add_menu_page('Agent Report', 'Agents', 'manage_options', 'agent-reports.php', 'agent_admin_page', 'dashicons-businessman', 6);
}

function agent_admin_page()
{

    // if (isset($_POST['change-month-year-admin'])) {

    //     $current_month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);

    //     //var_dump($current_month);
    //     $current_year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);

    //     // wp_redirect('/agent-admin?data_month=' . $month . '&data_year=' . $year);
    //     // exit;

    //     //$current_month = intval($_GET['data_month']);
    //     //$current_year = intval($_GET['data_year']);
    //     $monthName = DateTime::createFromFormat('!m', $current_month)->format('F');
    //     $display_date = $monthName . ' ' . $current_year;
    // } else {
    //     $current_month = intval(date('n'));
    //     $current_year = intval(date('Y'));
    //     $display_date = 'for ' . date('F Y');
    // }


        if ( isset($_GET['data_month'])) {
          $current_month = intval($_GET['data_month']);
          $current_year = intval($_GET['data_year']);
          $monthName = DateTime::createFromFormat('!m', $current_month)->format('F');
          $display_date = $monthName . ' ' . $current_year;

        } else {
          $current_month = intval(date( 'n' ));
          $current_year = intval(date( 'Y' ));
          $display_date = date('F Y');
        }
      ?>
	<div class="wrap gsa-sales-admin-page">

		<h2>Agent Report</h2>

		<div class="sales-report-wrap">

			<div class="forms-wrap">

				<div class="month-choice form-item-group">

					<div class="change-date-form">
						<?php

    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

    $years = array();
    $current_year_loop = intval(date('Y'));
    for ($i = 2018; $i <= $current_year_loop; $i++) {
        $years[] = $i;
    }?>

						<form class="change-date-form-inner" method="GET" action="#">

							<h5>Sort by Month, Year</h5>

							<div style="padding: 10px 0;">
                <input type="hidden" name="page" value="agent-reports.php"/>
								<div>
									<select name="data_month">
										<?php foreach ($months as $key => $month) {
        $month_val = ($key + 1);
        if ($month_val === intval($current_month)) {
            $selected = 'selected="true"';
        } else {
            $selected = '';
        }
        ?>
											<option <?php echo $selected; ?> value="<?php echo $month_val; ?>"><?php echo $month; ?></option>
										<?php }?>
									</select>
								</div>
								<div style="margin-top: 5px;">
									<select name="data_year">
										<?php foreach ($years as $year) {
        if (intval($year) === intval($current_year)) {
            $selected = 'selected="true"';
        } else {
            $selected = '';
        }
        ?>
											<option <?php echo $selected; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php }?>
									</select>
								</div>
							</div>

							<div>
								<button type="submit" class="button button-primary">Update</button>
							</div>
						</form>
					</div>
				</div>

      </div>



			<div class="completed-orders-wrap">
        <br />
        <h2>Agent Data for <?php echo $display_date; ?></h2>
      	<table style="margin-top: 15px;" class="widefat fixed" cellspacing="0">
								<thead>
									<tr class="alternate">
										<th>Agent Name</th>
										<th>Agent Email</th>
										<th>Agent ID</th>
										<th>Total Payment</th>
									</tr>
								</thead>
								<tbody>

        <?php


        $args = array(
          'role' => 'um_agent',
        );



        $agent_query = new WP_User_Query($args);
        $agents = $agent_query->get_results();
        $alternate_row = false;
        foreach($agents as $agent) {

					$current_user_id = 'user_' . $agent->ID;
					$category_payment_values = get_field('agent_percent', $current_user_id);
					$cat_percent_array = array();

          if($category_payment_values) {
            foreach( $category_payment_values as $item ) {
              $cat_percent_array[$item['category']] = intval($item['percent']);
            }
          }

						$args = array(
							'post_type' => 'orders',
							'posts_per_page' => -1,
							'date_query' => array(
								array(
									'year'  => $current_year,
									'month' => $current_month,
								),
							),
							'meta_query' => array(
								array(
									'key' => 'agent_id',
									'value' => $agent->ID,
								),
								array(
									'key' => 'paid',
									'value' => 'Completed'
								)
							),
						);

						$order_query = new WP_Query($args);

						$total_payment = 0;
						if ( $order_query->have_posts() ) {
							while( $order_query->have_posts() ) {

								$order_query->the_post();
								$date = get_the_date();
								$purchaser_id = get_field('user_id');
								$company = get_field('company', 'user_' . $purchaser_id);
								$userdata = get_userdata($purchaser_id);
								$first = $userdata->user_firstname;
                $last = $userdata->user_lastname;

                $entries = get_field('product_entries');

                foreach( $entries as $entry ) {
                  $product_name = $entry['product_name'];
                  $product_id = $entry['product_id'];
                  $product_color = $entry['product_color'];
                  $product_quantity = $entry['product_quantity'];
                  $unit_cost = $entry['unit_cost'];
                  $cost_total = $entry['cost_total'];
                  $cost_actual = intval(str_replace(array('$',','), '', $cost_total));
                  $category_array = get_the_category($product_id);
                  $cat_name = $category_array[0]->name;
                  $cat_id = $category_array[0]->term_id;
                  if ( isset($cat_percent_array[$cat_id]) ) {
                    if ( ! ( $payment_percent = $cat_percent_array[$cat_id]) ) {
                      $payment_percent = 0;
                    }
                  } else {
                    $payment_percent = 0;
                  }
                  $payment = ( $cost_actual * ( $payment_percent / 100 ) );
                  $total_payment = ( $total_payment + $payment );

                  }
                }
              }

          $user_data = get_userdata($agent->ID);
          if($alternate_row) {
            $alternate_row = false;
            $alt_style="alternate";
          } else {
            $alternate_row = true;
            $alt_style="";
          }
          ?>
          	<tr class="<?php echo $alt_style; ?>">
              <td><?php echo $user_data->display_name; ?></td>
              <td><?php echo $agent->user_email; ?></td>
              <td><?php echo $agent->ID; ?></td>
              <td><?php echo '$' . number_format($total_payment, 2); ?></td>
            </tr>

          <?php } ?>
				  </tbody>
        </table>

</div>

</div>

</div>
<?php
}












/**
 * Credit Admin Page
 */
add_action('admin_menu', 'credit_report_admin_page');

function credit_report_admin_page()
{

    add_menu_page('Credit Report', 'Credit', 'manage_options', 'credit-report.php', 'credit_admin_page', 'dashicons-id-alt', 6);
}

function credit_admin_page()
{

    if (isset($_POST['change-user-credit-value'])) {

        $user_id = filter_input(INPUT_POST, 'credit-user-id', FILTER_SANITIZE_SPECIAL_CHARS);
        $credit_value = filter_input(INPUT_POST, 'credit-new-value', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$credit_value) {
            $credit_value = 0;
        }

        update_field('credit_value', $credit_value, 'user_' . $user_id);

        // var_dump($user_id);
        // var_dump($credit_value);

        // die('working');

    }

    $user_details_array = array();

    $args = array('role__not_in' => array('administrator', 'editor', 'um_agent'));
    $user_query = new WP_User_Query($args);

    foreach ($user_query->get_results() as $user) {

        $user_info = array();

        if ($user->roles[0] === 'subscriber') {
            $user_info['type'] = 'New Signup';
        } elseif ($user->roles[0] === 'contributor') {
            $user_info['type'] = 'Retailer';
        } elseif ($user->roles[0] === 'author') {
            $user_info['type'] = 'Wholesaler';
        }
        $company = get_field('company', 'user_' . $user->ID);

        if (!($credit = get_field('credit_value', 'user_' . $user->ID))) {
            $credit = 0;
        }

        $user_data = get_userdata($user->ID);
        //var_dump($user_data->first_name);

        $user_info['id'] = $user->ID;
        $user_info['name'] = ($user_data->first_name . ' ' . $user_data->last_name);
        $user_info['company'] = $company;
        $user_info['credit'] = $credit;
        $user_info['email'] = $user->user_email;

        $user_details_array[] = $user_info;

    }

    wp_reset_postdata();

    ?>

	<div class="wrap gsa-sales-admin-page">

		<h2>Credit Report</h2>

		<div class="sales-report-wrap">

			<div class="forms-wrap credit">

				<div class="range-choice form-item-group">
					<h5>Change Credit Values</h5>
					<form method="POST">
						<input type="hidden" name="change-user-credit-value" />
						<div class="user-input form-item">
							<label>Select User</label>
							<select name="credit-user-id">
								<?php foreach ($user_details_array as $user) {?>
									<option value="<?php echo $user['id']; ?>"><?php echo $user['company'] . ' - ' . $user['name']; ?></option>
								<?php }?>
							</select>
						</div>
						<div class="credit-input form-item">
							<label>Credit Value</label>
							<input type="number" name="credit-new-value" min="0" placeholder="0" />
						</div>

						<div>
							<button type="submit" class="button button-primary">Update</button>
						</div>

					</form>
				</div>

			</div>

			<div class="completed-orders-wrap current-credit">

				<div class="order-details-wrap">

					<h2 style="margin-bottom: -15px; margin-top: 30px;">User Credit</h2>

					<table style="margin-top: 30px;" class="widefat fixed" cellspacing="0">
						<thead>
							<tr class="alternate">
								<th>Company</th>
								<th>Name</th>
								<th>Email</th>
								<th>Role</th>
								<th>Current Credit</th>
							</tr>
						</thead>
						<tbody>

							<?php

    foreach ($user_details_array as $user) {

        ?>
								<tr class="product-table-items">
									<td><?php echo $user['company']; ?></td>
									<td><?php echo $user['name']; ?></td>
									<td><?php echo $user['email']; ?></td>
									<td><?php echo $user['type']; ?></td>
									<td>$<?php echo number_format($user['credit'], 2); ?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>

				</div>

			</div>

		</div>

	</div>
	<?php
}

// function new_contact_methods( $contactmethods ) {
//     $contactmethods['phone'] = 'Phone Number';
//     return $contactmethods;
// }

// add_filter( 'user_contactmethods', 'new_contact_methods', 10, 1 );

function new_modify_user_table($column)
{
    // $column['phone'] = 'Phone';
    // return $column;
    $column['company'] = 'Company';
    return $column;
}

add_filter('manage_users_columns', 'new_modify_user_table');

function new_modify_user_table_row($val, $column_name, $user_id)
{
    switch ($column_name) {
        case 'company':
            //return get_the_author_meta( 'phone', $user_id );
            return get_field('company', 'user_' . $user_id);
            break;
        default:
    }
    return $val;
}
add_filter('manage_users_custom_column', 'new_modify_user_table_row', 10, 3);

// function add_course_section_filter( $which ) {

//     // create sprintf templates for <select> and <option>s
//     $st = '<select name="course_section_%s" style="float:none;"><option value="">%s</option>%s</select>';
//     $ot = '<option value="%s" %s>Section %s</option>';

//     // determine which filter button was clicked, if any and set section
//     $button = key( array_filter( $_GET, function($v) { return __( 'Filter' ) === $v; } ) );
//     $section = $_GET[ 'course_section_' . $button ] ?? -1;

//     // generate <option> and <select> code
//     $options = implode( '', array_map( function($i) use ( $ot, $section ) {
//         return sprintf( $ot, $i, selected( $i, $section, false ), $i );
//     }, range( 1, 3 ) ));
//     $select = sprintf( $st, $which, __( 'Course Section...' ), $options );

//     // output <select> and submit button
//     echo $select;
//     submit_button(__( 'Filter' ), null, $which, false);
// }
// add_action('restrict_manage_users', 'add_course_section_filter');

// function filter_users_by_course_section($query)
// {
//     global $pagenow;
//     if (is_admin() && 'users.php' == $pagenow) {
//         $button = key( array_filter( $_GET, function($v) { return __( 'Filter' ) === $v; } ) );
//         if ($section = $_GET[ 'course_section_' . $button ]) {
//             $meta_query = [['key' => 'courses','value' => $section, 'compare' => 'LIKE']];
//             $query->set('meta_key', 'courses');
//             $query->set('meta_query', $meta_query);
//         }
//     }
// }
// add_filter('pre_get_users', 'filter_users_by_course_section');
