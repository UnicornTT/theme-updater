<?php

// Edit these variables' values, they're used for the WP admin panel, login page branding and for the email templates.
$theme_logo_raster_colorful_url = get_field( 'logo_header_single_dark', 'option' );
$theme_logo_raster_white_url = get_field( 'logo_header_single_light', 'option' );
$theme_logo_icon_url = get_field( 'logo_icon', 'option' );

if ( $theme_logo_raster_colorful_url ) {
	define( 'THEME_LOGO_RASTER_COLORFUL_URL', wp_get_attachment_url( $theme_logo_raster_colorful_url ) );
} else {
	define( 'THEME_LOGO_RASTER_COLORFUL_URL', get_bloginfo( 'stylesheet_directory' ) . '/assets/branding/img/default-logo-colorful.png' );
}

if ( $theme_logo_raster_white_url ) {
	define( 'THEME_LOGO_RASTER_WHITE_URL', wp_get_attachment_url( $theme_logo_raster_white_url ) );
} else {
	define( 'THEME_LOGO_RASTER_WHITE_URL', get_bloginfo( 'stylesheet_directory' ) . '/assets/branding/img/default-logo-white.png' );
}

if ( $theme_logo_icon_url ) {
	define( 'THEME_LOGO_ICON_URL', wp_get_attachment_url( $theme_logo_icon_url ) );

	if ( hmt_is_svg( $theme_logo_icon_url ) ) {
		define( 'THEME_FAVICON_LOGO_ICON_URL', hmt_get_svg_as_colorized_png( wp_get_attachment_url( $theme_logo_icon_url ) ) );
	} else {
		define( 'THEME_FAVICON_LOGO_ICON_URL', wp_get_attachment_url( $theme_logo_icon_url ) );
	}
} else {
	define( 'THEME_LOGO_ICON_URL', get_bloginfo( 'stylesheet_directory' ) . '/assets/branding/img/default-logo-icon.png' );
}

define( 'HARBINGER_MARKETING_LOGO_PATH', THEME_ASSETS_PATH . '/branding/img/harbinger-marketing-logo.svg' );
define( 'HARBINGER_MARKETING_WEBSITE_URL', 'https://harbingermarketing.com/' );