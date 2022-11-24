<?php

add_action('init','hide_events_post_type', 10);
function hide_events_post_type(){
	if( post_type_exists('mec-events') ){
		$events_post_type = get_post_type_object('mec-events');
		$events_post_type->public = false;
		$events_post_type->publicly_queryable = false;

		if( taxonomy_exists('mec_category') ){
			$events_categories = get_taxonomy('mec_category');
			$events_categories->public = false;
			$events_categories->publicly_queryable = false;
		}
	}
	
}


add_action('admin_menu', 'remove_events', 10);
function remove_events() {
	$enabled_events = get_field('global_enable_events', 'option');
	if( $enabled_events != 'enabled' ){
		remove_menu_page('mec-intro');
		if( post_type_exists('mec-events') ){
			unregister_post_type( 'mec-events' );
		}
	}
}


add_action('init','remove_posts', 10);
function remove_posts(){
	$enabled_posts = get_field('global_enable_posts', 'option');
 	if( $enabled_posts != 'enabled' ){
		remove_menu_page('edit.php');
		if( post_type_exists('post') ){
			$posts_type = get_post_type_object('post');
			$posts_type->public = false;
			$posts_type->publicly_queryable = false;
			$posts_type->show_in_menu = false;
			$posts_type->has_archive = false;
			if( taxonomy_exists('post_tag') ){
				$post_tag = get_taxonomy('post_tag');
				$post_tag->public = false;
				$post_tag->publicly_queryable = false;
			}
		}
	}
}


add_action( 'init', 'hmt_session_init' );
function hmt_session_init() {
	if ( !session_id() ) {
		session_start();
	}
}


add_action( 'wp_head', 'hmt_location_filter' );
function hmt_location_filter() {
	if( taxonomy_exists('hmt_location') ){
		$terms = get_the_terms( get_queried_object_id(), 'hmt_location' );

		if ( $terms ) {
			hmt_set_current_location( $terms[0] );
		}

		if ( is_tax( 'hmt_location' ) ) {
			hmt_set_current_location( get_queried_object() );
		}

		// set default location
		if ( is_front_page() ) {
			$_SESSION['location'] = '';
		}
	}
}


add_action( 'acf/init', 'hmt_my_acf_init' );
function hmt_my_acf_init() {
	$google_api_key = get_field( 'google_api_key', 'option' );
	acf_update_setting( 'google_api_key', $google_api_key );
}


add_action( 'pre_get_posts', 'hmt_check_search_auery' );
function hmt_check_search_auery() {
	global $wp_query;
	if ( get_search_query() ) {
		$wp_query->set( 'post_type', 'post' );
		$wp_query->set( 'orderby', 'date' );
		$wp_query->set( 'order', 'DESC' );
	} elseif ( $wp_query->is_search ) {
		$wp_query->set( 'post_type', 'post' );
	}
}


add_action( 'acf/validate_save_post', 'hmt_validate_save_post', 5 );
function hmt_validate_save_post() {

	// bail early if no $_POST
	$acf = false;
	foreach ( $_POST as $key => $value ) {
		if ( strpos( $key, 'acf' ) === 0 ) {
			if ( !empty( $_POST[$key] ) ) {
				acf_validate_values( $_POST[$key], $key );
			}
		}
	}
}