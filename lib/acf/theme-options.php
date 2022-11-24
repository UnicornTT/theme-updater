<?php

if ( function_exists( 'acf_add_options_page' ) ) {
	hmt_register_acf_theme_settings();
	hmt_register_acf_general_settings();
	hmt_register_acf_header_settings();
	hmt_register_acf_contacts_settings();
}

function hmt_register_acf_theme_settings() {
	acf_add_options_page( array(
		'page_title' 	=> __( 'Theme General Settings', THEME_TEXTDOMAIN ),
		'menu_title' 	=> __( 'Theme Settings', THEME_TEXTDOMAIN ),
		'menu_slug' 	=> 'theme-settings',
		'capability'	=> 'edit_posts',
		'redirect' 		=> true
	) );
}

function hmt_register_acf_general_settings() {
	acf_add_options_sub_page( array(
		'page_title' 	=> __( 'General Settings', THEME_TEXTDOMAIN ),
		'menu_title' 	=> __( 'General', THEME_TEXTDOMAIN ),
		'menu_slug' 	=> 'theme-general-settings',
		'parent_slug' 	=> 'theme-settings',
	) );
}

function hmt_register_acf_header_settings() {
	acf_add_options_sub_page( array(
		'page_title' 	=> __( 'Header Settings', THEME_TEXTDOMAIN ),
		'menu_title' 	=> __( 'Header', THEME_TEXTDOMAIN ),
		'menu_slug' 	=> 'theme-header-settings',
		'parent_slug' 	=> 'theme-settings',
	) );
}

function hmt_register_acf_contacts_settings() {
	acf_add_options_sub_page( array(
		'page_title' 	=> __( 'Contact Settings', THEME_TEXTDOMAIN ),
		'menu_title' 	=> __( 'Contact', THEME_TEXTDOMAIN ),
		'menu_slug' 	=> 'theme-contacts-settings',
		'parent_slug' 	=> 'theme-settings',
	) );
}