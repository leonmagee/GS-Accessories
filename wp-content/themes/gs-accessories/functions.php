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
 	md_create_wp_cpt::create_post_type( 'rmas', 'RMA', 'RMA', 'rmas', 'image-rotate' );
 }
 add_action( 'init', 'mm_register_post_types' );

 require_once('lib/shopping_cart.php');
 require_once('lib/process-form-submission.php');
 require_once('lib/output-modal-login.php');
 require_once('lib/lv-register-user.php');
 require_once('lib/lv-send-email-misc.php');
 require_once('lib/lv-ajax.php');
 require_once('lib/rest-endpoints.php');

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

	wp_register_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.2.1', true );

	wp_enqueue_script( 'custom-js');

	wp_register_script( 'foundation-js', get_template_directory_uri() . '/vendor/foundation/js/vendor/foundation.min.js', '', '1.0.1' );

	wp_register_script( 'foundation-init-js', get_template_directory_uri() . '/vendor/foundation/js/app.js', array('jquery','foundation-js'), '1.0.2', true );

	wp_enqueue_script( 'foundation-init-js' );

	
	wp_register_style( 'gs-accessories-styles', get_template_directory_uri() . '/assets/css/main.min.css', '', '1.2.2' );
	
	wp_enqueue_style( 'gs-accessories-styles' );
}
add_action( 'wp_enqueue_scripts', 'gs_accessories_scripts' );

/**
* Enqueue admin scripts and styles
*/
function gs_accessories_admin_scritps() {

	wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', '', '1.1.1');

	wp_register_script( 'custom-admin-js', get_template_directory_uri() . '/js/custom-admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), '1.1.9', true );

	wp_enqueue_script( 'custom-admin-js');

	wp_register_style( 'gs-accessories-admin-styles', get_template_directory_uri() . '/assets/css/admin.min.css', array('jquery-ui-css'), '1.1.5' );
	
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

	/**
	* @todo this needs to effect Agents as well... 
	* @todo test this for non -localhost site?
	*/
	if ( ! current_user_can( 'level_5' ) ) {
	//if ( ! current_user_can( 'delete_others_pages' ) ) {

		wp_redirect( site_url() );
		//exit;
	}
}

/**
* Redirect Agents
* @todo remove level_10 access - does this do anything??????
*/
add_action( 'pre_get_posts', 'agent_login_redirect' );

function agent_login_redirect() {

	$current_user   = wp_get_current_user();
	$role_name      = $current_user->roles[0];
    //var_dump($role_name);
	if ( $role_name === 'um_agent' ) {
		$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		$slug = $current_page->post_name;
		if ( ( $slug !== 'agent-admin' ) && ( $slug !== 'register-user-agent') ) {
    		/**
    		* @todo also allow for registration page? reports page? any page available to Agent
    		*/
    		define('AGENT_LOGGED_IN', true);
    		wp_redirect( site_url() . '/agent-admin' );
			//exit;
    	}
    } else {
    	define('AGENT_LOGGED_IN', false);
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


/**
* Inventory Report Admin Page
*/
add_action( 'admin_menu', 'inventory_report_admin_page' );

function inventory_report_admin_page() {

	add_menu_page( 'Inventory Report', 'Inventory', 'manage_options', 'current-inventory.php', 'inventory_admin_page', 'dashicons-chart-line', 6  );
}

function inventory_admin_page(){

	if ( isset($_POST['update-inventory-sort'])) {
		$inventory_cat = $_POST['inventory-sort'];
	} else {
		$inventory_cat = '';
	}
	?>
	<div class="wrap">
		<h2>Current Inventory</h2>

		<?php $cats = get_categories(); ?>

		<div class="sort-inventory-wrap">
			<form method="POST">
				<div>
					<h4 style="margin-bottom: 0">Sort Inventory</h4>
				</div>
				<div style="padding: 10px 0;">
					<input type="hidden" name="update-inventory-sort" />
					<select style="min-width: 200px;" name="inventory-sort">
						<option value="">All</option>
						<?php foreach( $cats as $cat ) { 
							$selected = '';
							if ( $inventory_cat === $cat->slug ) {
								$selected = 'selected="true"';
							}
							?>
							<option <?php echo $selected; ?> value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<input class="button button-primary" type="submit" value="Update"/>
				</div>
			</form>
		</div>

		<div class="accessory-inventory-wrap">

			<?php

			if ( $inventory_cat ) {
				$args = array(
					'post_type' => 'accessories',
					'category_name' => $inventory_cat
				);
			} else {
				$args = array(
					'post_type' => 'accessories'
				);
			}

			$custom_query = new WP_Query($args);
			while( $custom_query->have_posts() ) {
				$custom_query->the_post();

				?>

				<?php $color_quantity = get_accessory_colors();
				if ( $color_quantity ) { ?>
					<div class="accessory-inventory-item">
						<h3 style="margin-bottom: 7px; margin-left: 3px;"><?php the_title(); ?></h3>
						<table class="widefat fixed" cellspacing="0">
							<thead>
								<tr>
									<th>Color</th>
									<th>In Stock</th>
								</tr>
							</thead>
							<tbody>

								<?php foreach( $color_quantity as $item => $quantity ) { ?>
									<tr class="alternate">
										<td style="border: 1px solid #EEE"><?php echo $item; ?></td>
										<td style="border: 1px solid #EEE"><?php echo $quantity; ?> Available</td>
									</tr>
								<?php } ?>

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





/**
* Inventory Report Admin Page
*/
add_action( 'admin_menu', 'sales_report_admin_page' );

function sales_report_admin_page() {

	add_menu_page( 'Sales Report', 'Sales', 'manage_options', 'current-sales.php', 'sales_admin_page', 'dashicons-chart-bar', 6  );
}

function sales_admin_page(){

	if ( isset($_POST['change-month-year-admin'])) {

		$current_month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);

		//var_dump($current_month);
		$current_year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);

		// wp_redirect('/agent-admin?data_month=' . $month . '&data_year=' . $year);
		// exit;


		//$current_month = intval($_GET['data_month']);
		//$current_year = intval($_GET['data_year']);
		$monthName = DateTime::createFromFormat('m', $current_month)->format('F');
		$display_date = $monthName . ' ' . $current_year;
	}
	else {
		$current_month = intval(date( 'n' ));
		$current_year = intval(date( 'Y' ));
		$display_date = 'for ' . date('F Y');
	}

	$gsa_date_rage_query = false;

	if ( isset($_POST['change-date-range-admin'])) {
		$gsa_date_rage_query = true;
		$datepicker_start = filter_input(INPUT_POST, 'datepicker-start', FILTER_SANITIZE_SPECIAL_CHARS);
		$datepicker_end = filter_input(INPUT_POST, 'datepicker-end', FILTER_SANITIZE_SPECIAL_CHARS);
		if ( $datepicker_start && $datepicker_end ) {
			$display_date = 'for ' . $datepicker_start . ' - ' . $datepicker_end;
		} elseif( $datepicker_start ) {
			$display_date = 'for ' . $datepicker_start . ' - present';
		} elseif( $datepicker_end ) {
			$display_date = 'through ' . $datepicker_end;
		}
	}


	?>
	<div class="wrap gsa-sales-admin-page">

		<h2>Current Sales</h2>

		<div class="sales-report-wrap">

			<div>
				<h4 class="section-title">Report Date</h4>
			</div>

			<div class="forms-wrap">
				
				<div class="month-choice form-item-group">

					<div class="change-date-form">
						<?php 

						$months = array('January','February','March','April','May','June','July','August','September','October','November','December'); 

						$years = array();
						$current_year = intval(date('Y'));
						for ( $i = 2018; $i <= $current_year; $i++ ) {
							$years[] = $i;
						} ?>

						<form class="change-date-form-inner" method="POST" action="#">

							<h5>Sort by Month</h5>

							<div style="padding: 10px 0;">
								<input type="hidden" name="change-month-year-admin" />
								<div>
									<select name="month">
										<?php foreach( $months as $key => $month ) {
											$month_val = ( $key + 1); 
											if ( $month_val === intval($current_month) ) {
												$selected = 'selected="true"';
											} else {
												$selected = '';
											}
											?>
											<option <?php echo $selected; ?> value="<?php echo $month_val; ?>"><?php echo $month; ?></option>
										<?php } ?>
									</select>
								</div>
								<div style="margin-top: 5px;">
									<select name="year">
										<?php foreach( $years as $year ) { ?>
											<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php } ?>
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

				<?php if ( $gsa_date_rage_query ) {

					$args = array(
						'post_type' => 'orders', 
						'posts_per_page' => -1,
						'date_query' => array(
							array(
								'before'  => $datepicker_end,
								'after' => $datepicker_start,
							),
						),
						'meta_query' => array(
							array(
								'key' => 'paid',
								'value' => 'Paid in Full'
							)
						),
					);
				} else {
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
								'key' => 'paid',
								'value' => 'Paid in Full'
							)
						),
					);
				}


				$order_query = new WP_Query($args);

				$total_payment = 0;
				if ( $order_query->have_posts() ) {
					while( $order_query->have_posts() ) {
						global $post;
						$order_query->the_post(); 
						$date = get_the_date();
						$purchaser_id = get_field('user_id');
						$userdata = get_userdata($purchaser_id);
						$first = $userdata->user_firstname;
						$last = $userdata->user_lastname;
						$order_id = $post->ID;
						?>

						<style>
						.product-table-items:not(:nth-child(1)) td {
							border-top: 1px solid #EEE;
						}
					</style>

					<div class="order-details-wrap">
						<table style="margin-top: 30px;" class="widefat fixed" cellspacing="0">
							<thead>
								<tr class="alternate">
									<th>Order ID</th>
									<th>Retailer Name</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $order_id; ?></td>
									<td><?php echo $first . ' ' . $last; ?></td>
									<td><?php echo $date; ?></td>
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
										<th>Total Cost</th>
										<th>Payment</th>
									</tr>
								</thead>
								<tbody>

									<?php $entries = get_field('product_entries'); 

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
										$payment = $cost_actual;
										$total_payment = ( $total_payment + $payment ); ?>

										<tr class="product-table-items">	
											<td><?php echo $product_name; ?></td>
											<td><?php echo $product_color; ?></td>
											<td><?php echo $product_quantity; ?></td>
											<td><?php echo $cost_total; ?></td>
											<td>$<?php echo number_format( $payment, 2); ?></td>
										</tr>

									<?php } ?>

								</tbody>

							</table>

						</div>

					</div>

				<?php } 

			} else { ?>
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



