<?php
/**
 * ACF Filter: Insert Ninja Form IDs into special all selectors with 'form_id' ending.
 * The selector must be called selector_name**_form_id**
 */
function hmt_acf_ninja_form_universal_select_field( $field ) {
	// This filter use hmt_get_ninja_forms_selection_list to get list of Ninja Forms
	if ( !function_exists('hmt_get_ninja_forms_selection_list') ) {
		return $field;
	}

	if ( empty($field['name']) || false === strpos($field['name'], '_form_id') ) {
		return $field;
	}

	$field['choices'] = hmt_get_ninja_forms_selection_list();

	return $field;
}
add_filter('acf/load_field/type=select', 'hmt_acf_ninja_form_universal_select_field');


/**
 * Disable ACF WYSIWYG wpautop filter
 */
function hmt_acf_wysiwyg_field_remove_auto_p() {
	remove_filter('acf_the_content', 'wpautop' );
}
add_action('acf/init', 'hmt_acf_wysiwyg_field_remove_auto_p');


/**
 * Add new ACF WYSIWYG style
 */
add_filter( 'acf/fields/wysiwyg/toolbars', 'hmt_initialize_custom_wysiwyg_styles' );
function hmt_initialize_custom_wysiwyg_styles( $toolbars ) {
	$toolbars['Limited'] = array();
	$toolbars['Limited'][1] = array('bold', 'italic', 'underline', 'strikethrough', 'bullist', 'numlist', 'link', 'unlink', 'wp_more', 'undo', 'redo');

	unset( $toolbars['Basic'] );

	return $toolbars;
}


/**
 * Remove media buttons in Limited WYSIWYG style
 */
add_filter( 'acf/get_valid_field', 'hmt_remove_wysiwyg_media' );
function hmt_remove_wysiwyg_media( $field ) {
	if ( $field['type'] === 'wysiwyg' && $field['toolbar'] === 'limited' ) {
		$field['media_upload'] = 0;
	}

	return $field;
}