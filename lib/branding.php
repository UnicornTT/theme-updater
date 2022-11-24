<?php

// Change the WP login page logo link
add_filter( 'login_headerurl', 'hmt_custom_wp_login_logo_link' );
function hmt_custom_wp_login_logo_link() {
	return get_bloginfo( 'url' );
}


// Create CSS variable with the theme logo on the WP login page
add_action( 'login_head', 'hmt_custom_login_screen_logo' );
function hmt_custom_login_screen_logo() {
	?>

	<style type="text/css">
		:root {
			--theme-logo-raster-colorful-url: url('<?php echo THEME_LOGO_RASTER_COLORFUL_URL ?>');
		}
	</style>

	<?php
}


// Enqueue the branding styles
add_action( 'admin_enqueue_scripts', 'hmt_enqueue_branding_styles' );
add_action( 'wp_enqueue_scripts', 'hmt_enqueue_branding_styles' );
add_action( 'login_head', 'hmt_enqueue_branding_styles' );
function hmt_enqueue_branding_styles() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-branding-styles', THEME_ASSETS_URL . '/branding/css/styles.css' );
}


// Admin bar logo
add_action( 'add_admin_bar_menus', 'hmt_replace_admin_bar_logo' );
function hmt_replace_admin_bar_logo() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu' );
	add_action( 'admin_bar_menu', 'hmt_custom_admin_bar_logo' );
}

function hmt_custom_admin_bar_logo( $wp_admin_bar ) {
	$wp_admin_bar->add_menu( array(
		'id' => 'theme-logo',
		'title' => '<img src="' . THEME_LOGO_ICON_URL . '" alt="" >',
		'href' => home_url( '/' ),
		'meta' => array(
			'title' => get_bloginfo( 'name' )
		),
	) );
}

// Admin footer Harbinger Marketing logo
add_filter( 'admin_footer_text', 'hmt_custom_admin_footer_text' );
function hmt_custom_admin_footer_text() {
	?>

	<span id="footer-thankyou">
		<?= esc_html( __( 'Developed by', THEME_TEXTDOMAIN ) ) ?>

		<a href="<?= HARBINGER_MARKETING_WEBSITE_URL ?>" title="<?= esc_html( __( 'Harbinger Marketing', THEME_TEXTDOMAIN ) ) ?>" target="_blank">
			<?= hmt_get_svg_inline( HARBINGER_MARKETING_LOGO_PATH ) ?>
		</a>
	</span>

	<?php
}