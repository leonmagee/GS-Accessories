<?php
/**
 * GS Accessories functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package GS_Accessories
 */

 /**
  * Add Files
  **/
 require_once('lib/helper-functions.php');
 require_once('lib/generate-custom-post-type.php');
 function mm_register_post_types() {
 	md_create_wp_cpt::create_post_type( 'accessories', 'Accessory', 'Accessories', 'accessories', 'smartphone' );
 }
 add_action( 'init', 'mm_register_post_types' );

 require_once('lib/shopping_cart.php');
 require_once('lib/process-form-submission.php');
 require_once('lib/output-modal-login.php');
 require_once('lib/lv-register-user.php');
 require_once('lib/lv-send-email-misc.php');
 require_once('lib/lv-ajax.php');

 session_start();



/**
 * Constants
 * @todo move to different file
 */
if ( is_user_logged_in() ) {
	$user    = wp_get_current_user(); // @todo search for this to replace with constant
	$user_id = $user->ID;
	$first_name = get_user_meta($user_id, 'first_name', true);
	$last_name = get_user_meta($user_id, 'last_name', true);
	//var_dump($first_name . ' ' . $last_name);
	define('LV_LOGGED_IN_NAME', $first_name . ' ' . $last_name);
	define( 'LV_LOGGED_IN_ID', $user_id );
} else {
	//var_dump('user not logged in');
	define( 'LV_LOGGED_IN_ID', false );
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
}
add_action( 'wp_enqueue_scripts', 'gs_accessories_scripts' );

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
 * Enqueue scripts and styles.
 */
function gs_accessories_custom_scripts() {
	
		wp_register_style( 'font-awesome', get_template_directory_uri() . '/vendor/font-awesome/css/font-awesome.min.css', array() );
	
		wp_enqueue_style( 'font-awesome' );
	
		// $google_font_black_ops_one = 'https://fonts.googleapis.com/css?family=Black+Ops+One';
	
		// //$google_font_exo = 'https://fonts.googleapis.com/css?family=Exo:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i';
	
		// wp_register_style( 'google-fonts-black-ops-one', $google_font_black_ops_one, '', '1.0.1' );
	
		// wp_enqueue_style( 'google-fonts-black-ops-one' );

	wp_register_style( 'foundation-css', get_template_directory_uri() . '/vendor/foundation/css/foundation.min.css', '', '1.0.1' );

	wp_enqueue_style( 'foundation-css' );

		//wp_register_script( 'isotope-js', get_template_directory_uri() . '/vendor/isotope/isotope.js', '', '3.0.5', true );

	wp_register_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0.2', true );

		//wp_register_script( 'isotope-custom-js', get_template_directory_uri() . '/js/isotope.js', array('jquery', 'isotope-js'), '1.0.2', true );

	wp_enqueue_script( 'custom-js');


	wp_register_script( 'foundation-js', get_template_directory_uri() . '/vendor/foundation/js/vendor/foundation.min.js', '', '1.0.1' );

	wp_register_script( 'foundation-init-js', get_template_directory_uri() . '/vendor/foundation/js/app.js', array('jquery','foundation-js'), '1.0.2', true );

	wp_enqueue_script( 'foundation-init-js' );

	
	wp_register_style( 'gs-accessories-styles', get_template_directory_uri() . '/assets/css/main.min.css', '', '1.0.1' );
	
	wp_enqueue_style( 'gs-accessories-styles' );

	// if ( is_page('Products') ) {
	// 	wp_register_script( 'isotope-js', get_template_directory_uri() . '/vendor/isotope/isotope.js', '', '3.0.5', true );

	// 	wp_register_script( 'isotope-custom-js', get_template_directory_uri() . '/js/isotope.js', array('jquery', 'isotope-js'), '1.0.2', true );

	// 	wp_enqueue_script( 'isotope-custom-js');
	// }
}

add_action( 'wp_enqueue_scripts', 'gs_accessories_custom_scripts' );


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
	
	
