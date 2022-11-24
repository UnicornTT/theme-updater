<?php

function hmt_convert_path_to_url( $path ) {
	if ( stripos( $path, '://' ) === false ) {
		$url = str_replace(
			array(
				ABSPATH,
				DIRECTORY_SEPARATOR
			),
			array(
				trailingslashit( get_bloginfo( 'url' ) ),
				'/'
			),
			$path
		);

		return $url;
	}

	return $path;
}


function hmt_convert_url_to_path( $url ) {
	if ( stripos( $url, '://' ) !== false ) {
		$path = str_replace( get_bloginfo( 'url' ), ABSPATH, $url );

		return $path;
	}

	return $url;
}


function hmt_sanitize_phone_number_for_href( $phone ) {
	return preg_replace( '~[^0-9]~', '', $phone );
}


function hmt_maybe_add_target_attr_to_link( $link ) {
	$link_parts = parse_url( $link );
	$site_link_parts = parse_url( get_bloginfo( 'url' ) );

	if ( $link_parts['host'] != $site_link_parts['host'] ) {
		return 'target="_blank"';
	}
}


/**
 * @return string dark|light
 */
function hmt_get_current_theme_color(): string {
	$current_theme_name = $_COOKIE['theme_color_mode'] ?? get_field( 'theme_color', 'option' );
	switch ( $current_theme_name ) {
		case 'dark':
		case 'light':
			break;
		default:
			$current_theme_name = 'dark';
	}

	return $current_theme_name;
}


/**
 * @param string $name dark|light
 */
function hmt_set_current_theme_color( string $name ) {
	switch ( $name ) {
		case 'dark':
		case 'light':
			break;
		default:
			$name = 'dark';
	}

	setcookie( 'theme_color_mode', $name );
}


/**
 * @return bool True if theme is dark
 */
function hmt_is_dark_theme(): bool {
	return 'dark' === hmt_get_current_theme_color();
}


/**
 * @return bool True if theme is light
 */
function hmt_is_light_theme(): bool {
	return 'light' === hmt_get_current_theme_color();
}


/**
 * @param mixed $id Image ID
 *
 * @return bool
 */
function hmt_is_svg_image( $id ): bool {
	return false !== strstr( get_post_mime_type( $id ), 'image/svg' );
}