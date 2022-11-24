<?php

use Harbinger_Marketing\Assets_Cache;
use Harbinger_Marketing\PDF_Generator;
use Harbinger_Marketing\Instagram_Media;
use Harbinger_Marketing\BirdEye_Reviews;
use Harbinger_Marketing\Modal_Action;

add_action( 'after_setup_theme', 'load_theme_dependencies', 1 );
function load_theme_dependencies() {
	require_once('vendor/autoload.php');

	require_once('lib/classes/ACF_Helper.php');

	require_once('config/constants.php');

	require_once('lib/checks.php');

	require_once('lib/helpers/generic.php');
	require_once('lib/helpers/geolocation.php');
	require_once('lib/helpers/media.php');
	require_once('lib/helpers/svg.php');
	require_once('lib/helpers/image.php');
	require_once('lib/helpers/video.php');
	require_once('lib/helpers/ninja-forms.php');
	require_once('lib/helpers/wp.php');
	require_once('lib/helpers/blocks.php');
	require_once('lib/helpers/calendar.php');

	require_once('lib/actions.php');
	require_once('lib/sidebars.php');
	require_once('lib/sitemap.php');
}


require_once('lib/functions-hmt.php');


/**
 * Registration of modal popups
 */
function hmt_init_modal_actions() {
	define( 'MODAL_SUCCESS', 'success-popup' );
	define( 'MODAL_REQUEST_CONSULTATION', 'request-consultation' );
	define( 'MODAL_REGISTRATION_ON_EVENT', 'registration-on-event' );
	define( 'MODAL_JOB_APPLICATION', 'job-application' );
	define( 'MODAL_OPEN_POSITION', 'open-position' );

	Modal_Action::register_modal_action( MODAL_SUCCESS, null, function () {
		get_template_part( 'template-parts/popups/popup-success' );
	} );
	Modal_Action::register_modal_action( MODAL_REQUEST_CONSULTATION, __( 'Request Consultation', THEME_TEXTDOMAIN ), function () {
		get_template_part( 'template-parts/popups/popup-request-consultation');
	} );
	Modal_Action::register_modal_action( MODAL_REGISTRATION_ON_EVENT, __( 'Registration on Event', THEME_TEXTDOMAIN ), function () {
		get_template_part( 'template-parts/popups/popup-registration-on-event');
	} );
	Modal_Action::register_modal_action( MODAL_JOB_APPLICATION, __( 'Job Application', THEME_TEXTDOMAIN ), function () {
		get_template_part( 'template-parts/popups/popup-job-application' );
	} );
	Modal_Action::register_modal_action( MODAL_OPEN_POSITION, __( 'Open Position', THEME_TEXTDOMAIN ), function () {
		get_template_part( 'template-parts/blocks/section-open-position/popup/section-open-position-popup' );
	} );

	Modal_Action::add_modal_action_to_render_list( MODAL_SUCCESS );
}
add_action( 'after_setup_theme', 'hmt_init_modal_actions' );


/**
 * Advanced Custom Fields theme support
 */
function hmt_init_acf() {
	require_once('lib/acf/theme-options.php');
	require_once('lib/acf/block-categories.php');
	require_once('lib/acf/blocks.php');
	require_once('lib/acf/filters.php');
}
add_action( 'after_setup_theme', 'hmt_init_acf' );


/**
 * Ninja forms theme support
 */
function hmt_init_ninja_forms() {
	if ( !class_exists('Ninja_Forms') ) {
		return;
	}

	require_once('lib/ninja-forms/filters.php');
}
add_action( 'after_setup_theme', 'hmt_init_ninja_forms' );

/**
 * Setup the initial state of support classes
 */
function hmt_init_classes() {
	Instagram_Media::init( get_field( 'instagram_app_id', 'option' ), get_field( 'instagram_app_secret', 'option' ), get_field( 'instagram_access_token', 'option' ) );
}
add_action( 'init', 'hmt_init_classes' );


/**
 * Initialize theme
 */
function hmt_init_theme() {
	require_once('config/constants-dependent.php');

	require_once('lib/branding.php');
	require_once('lib/post-types.php');
	require_once('lib/menus/menus.php');
	require_once('lib/shortcodes.php');
	require_once('lib/routes.php');
	require_once('lib/emails/emails.php');
	require_once('lib/filters.php');

	load_theme_textdomain( THEME_TEXTDOMAIN, get_stylesheet_directory() . '/languages' );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-units', array('rem', 'em', 'vh', 'vw') );

	require_once('lib/image-sizes.php');
}
add_action( 'init', 'hmt_init_theme', 1 );


/**
 * Include JS scripts
 */
function hmt_enqueue_scripts() {
	global $wp_query;
	wp_enqueue_script( THEME_TEXTDOMAIN . '-vendor', get_bloginfo( 'stylesheet_directory' ) . '/assets/theme/js/vendor.js', array('jquery'), false, true );
	wp_enqueue_script( THEME_TEXTDOMAIN . '-main', get_bloginfo( 'stylesheet_directory' ) . '/assets/theme/js/main.js', array('jquery'), false, true );
	//wp_enqueue_script( THEME_TEXTDOMAIN . '-section-service-intro', get_bloginfo('stylesheet_directory') . '/assets/theme/js/blocks/section-service-intro.js', array('jquery'), false, true );

	wp_localize_script( THEME_TEXTDOMAIN . '-vendor', 'themeVars', array(
		'ajaxurl' => '/wp-json/api/',
		'posts' => json_encode( $wp_query->query_vars ),
	) );
}
add_action( 'wp_enqueue_scripts', 'hmt_enqueue_scripts' );


/**
 * Include CSS
 */
function hmt_enqueue_styles() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style', get_bloginfo( 'stylesheet_directory' ) . '/assets/theme/css/styles.css' );
}
add_action( 'wp_enqueue_scripts', 'hmt_enqueue_styles' );


/**
 * Include Editor JS scripts
 */
function hmt_enqueue_editor_scripts() {
	wp_enqueue_script(
		THEME_TEXTDOMAIN . '-editor-block-variations',
		get_bloginfo( 'stylesheet_directory' ) . '/assets/admin/js/editor.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'jquery', THEME_TEXTDOMAIN . '-main' ),
		false,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'hmt_enqueue_editor_scripts' );


// Adding excerpt
add_post_type_support( 'hmt_industry', 'excerpt' );
add_post_type_support( 'hmt_service', 'excerpt' );
add_post_type_support( 'hmt_equipment_item', 'excerpt' );
add_post_type_support( 'hmt_project', 'excerpt' );

// History year field validate
function history_year_validate_value( $valid, $value, $field, $input_name ) {
	if ( $valid !== true ) {
		return $valid;
	}

	$started_year = intval('1800');
	$current_year = intval(date('Y'));
	$selected_year = intval($value);

	if ( ($selected_year > $current_year) || ($selected_year < $started_year) ) {
		return __( 'The specified year must be between 1800 and the current year.' );
	} else if ($value != $selected_year) {
		return __( 'Please enter a valid year.' );
	}
	return $valid;
}
add_filter('acf/validate_value/name=year', 'history_year_validate_value', 10, 4);



add_action( 'widget_init', 'my_unregister_widgets', 11 );
function my_unregister_widgets() {
    unregister_widget( 'WP_Widget_Pages' );
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget( 'WP_Widget_Links' );
    unregister_widget( 'WP_Widget_Categories' );
    unregister_widget( 'WP_Widget_Recent_Posts' );
    unregister_widget( 'WP_Widget_Search' );
    unregister_widget( 'WP_Widget_Tag_Cloud' );
}