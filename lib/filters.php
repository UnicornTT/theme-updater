<?php

add_filter( 'wp_nav_menu_objects', 'hmt_menu_hash_filter', 10, 2 );
function hmt_menu_hash_filter( $menu_items_list, $menu_args ) {
	foreach ( $menu_items_list as &$menu_item ) {
		if ( !is_admin() ) {
			$anchor_block = get_field( 'autoscroll_function_anchor_block', $menu_item->ID );

			if ( $anchor_block !== '' ) {
				$menu_item->url = trim( $menu_item->url, '/' );
				$menu_item->url .= '#' . $anchor_block;
				$menu_item->classes[] = 'js-scroll-link';
			}

			// Disable menu item highlight
			if ( $menu_item->current ) {
				if ( $anchor_block !== '' ) {
					$menu_item->classes = array_filter( $menu_item->classes, function ( $class_name ) {
						return false === strstr( $class_name, 'current' );
					} );
					$menu_item->current = false;
				}
			}
		}
	}

	return $menu_items_list;
}


add_filter( 'upload_mimes', 'hmt_upload_allow_types', 10, 2 );
function hmt_upload_allow_types( $mimes ) {
	$mimes['json'] = 'application/json';
	$mimes['webp'] = 'image/webp';

	// unset( $mimes['mp4a'] );
	return $mimes;
}


add_filter( 'acf/settings/show_admin', 'hmt_acf_show_admin' );
function hmt_acf_show_admin( $show ) {
	if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
		return true;
	}

	return false;
}


add_filter( 'acf/load_field/name=widget_footer_menu', 'hmt_wd_nav_menus_load', 10, 2 );
function hmt_wd_nav_menus_load( $field ) {
	$menus = wp_get_nav_menus();

	if ( !empty( $menus ) ) {

		foreach ( $menus as $menu ) {
			$field['choices'][$menu->slug] = $menu->name;
		}

	}

	return $field;
}

add_filter( 'acf/load_field/name=mec_events_shortcode_name', 'hmt_get_mec_shortcodes', 10, 2 );
function hmt_get_mec_shortcodes( $field ) {
	$shortcodes = get_posts( [
		'post_type' => 'mec_calendars',
	] );

	$field['choices'] = [];
	foreach ( $shortcodes as $shortcode ) {
		$field['choices'][$shortcode->post_name] = $shortcode->post_title;
	}

	return $field;
}


add_filter( 'acf/load_field/name=widget_footer_contact_form_id', 'hmt_get_ninja_forms', 10, 2 );
add_filter( 'acf/load_field/name=request_consultation_form_id', 'hmt_get_ninja_forms', 10, 2 );
add_filter( 'acf/load_field/name=contact_us_form_id', 'hmt_get_ninja_forms', 10, 2 );
add_filter( 'acf/load_field/name=job_application_form_id', 'hmt_get_ninja_forms', 10, 2 );
add_filter( 'acf/load_field/name=subscribe_newsletters_form_id', 'hmt_get_ninja_forms', 10, 2 );
function hmt_get_ninja_forms( $field ) {
	$forms = Ninja_Forms()->form()->get_forms();

	foreach ( $forms as $form ) {
		$field['choices'][$form->get_id()] = $form->get_setting( 'title' );
	}

	return $field;
}


add_filter( 'next_posts_link_attributes', 'hmt_next_posts_link_attributes', 10, 1 );
function hmt_next_posts_link_attributes( $attributes ) {
	global $wp_query;

	return 'class="button button-bordered section-resources__button load_more_posts" data-max-pages="' . $wp_query->max_num_pages . '"';
}


add_filter( 'use_block_editor_for_posts', 'hmt_posts_disable_gutenberg', 10, 2 );
function hmt_posts_disable_gutenberg( $current_status, $post_type ) {
	if ( $post_type === 'post' ) return false;
	return $current_status;
}

add_filter( 'block_categories_all', function( $categories, $post ) {
	$widget_index = 0;
	foreach( $categories as $i=>$category ){
		if($category['slug'] == 'widgets'){
			$widget_index = $i;
			break;
		}
	}
	
	if( $widget_index ){
		$start_categories = array_slice($categories, 0, $widget_index, true);
		$end_categories = array_slice($categories, $widget_index , count($categories)-1, true);
		foreach($end_categories as $i=>$category){
			$end_categories[$i+1] = $category;
		}
		$new_categories = array_merge( $start_categories, $end_categories);
		$new_categories[$widget_index+1] =  array( 'slug'  => 'theme-widgets', 'title' => 'Theme Widgets');

		return $new_categories;
	}else{
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'theme-widgets',
					'title' => 'Theme Widgets',
				),
			)
		);
	}
}, 10, 2 );