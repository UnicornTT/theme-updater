<?php

add_action( 'admin_head', 'hmt_custom_css_variables' );
function hmt_custom_css_variables() {
	$color_black = get_field( 'color_dark', 'option' );
	$color_white = get_field( 'color_light', 'option' );
	$color_accent_1 = get_field( 'color_accent_1', 'option' );
	$color_accent_2 = get_field( 'color_accent_2', 'option' );

	/// This solution is hot FIX for ACF BUG, when ACF return color string instead array.
	if ( is_string( $color_black ) || is_string( $color_white ) || is_string( $color_accent_1 ) || is_string( $color_accent_2 ) ) {
		return '';
	}

	ob_start()
	?>
	<style>
		:root {
			--color-black: rgb(<?= $color_black['red'] ?>, <?= $color_black['green'] ?>, <?= $color_black['blue'] ?>);
			--color-black-RGB: <?= $color_black['red'] ?>, <?= $color_black['green'] ?>,<?= $color_black['blue'] ?>;

			--color-white: rgb(<?= $color_white['red'] ?>, <?= $color_white['green'] ?>, <?= $color_white['blue'] ?>);
			--color-white-RGB: <?= $color_white['red'] ?>, <?= $color_white['green'] ?>,<?= $color_white['blue'] ?>;

			--color-accent-1: rgb(<?= $color_accent_1['red'] ?>, <?= $color_accent_1['green'] ?>, <?= $color_accent_1['blue'] ?>);
			--color-accent-1-RGB: <?= $color_accent_1['red'] ?>, <?= $color_accent_1['green'] ?>,<?= $color_accent_1['blue'] ?>;

			--color-accent-2: rgb(<?= $color_accent_2['red'] ?>, <?= $color_accent_2['green'] ?>, <?= $color_accent_2['blue'] ?>);
			--color-accent-2-RGB: <?= $color_accent_2['red'] ?>, <?= $color_accent_2['green'] ?>,<?= $color_accent_2['blue'] ?>;
		}
	</style>
	<?php
	$content = ob_get_clean();

	echo $content;
}


/***
 * Custom Admin Styles
 ***/
add_action( 'admin_enqueue_scripts', 'hmt_admin_enqueue_styles' );
function hmt_admin_enqueue_styles() {
	wp_enqueue_script( THEME_TEXTDOMAIN . '-related', get_template_directory_uri() . '/assets/theme/js/blocks/section-related.js', array('jquery'), '', true );
	wp_enqueue_style( THEME_TEXTDOMAIN . '-styles', get_template_directory_uri() . '/assets/admin/css/editor-style.css' );
	wp_enqueue_style( THEME_TEXTDOMAIN . '-admin-style', get_template_directory_uri() . '/assets/admin/css/styles.css' );
}


/***
 * Custom Editor Assets
 ***/
add_action( 'enqueue_block_editor_assets', 'hmt_block_editor_assets', 10, 0 );
function hmt_block_editor_assets() {
	wp_enqueue_script( THEME_TEXTDOMAIN . '-vendor', get_template_directory_uri() . '/assets/theme/js/vendor.js', array('jquery'), '', true );
	wp_enqueue_script( THEME_TEXTDOMAIN . '-main', get_template_directory_uri() . '/assets/theme/js/main.js', array('jquery'), '', true );
	wp_enqueue_style( 'block_editor_css', get_bloginfo( 'stylesheet_directory' ) . '/assets/admin/css/editor-style.css' );
}


/***
 * ACF Radio Images (for blocks' layout variants)
 ***/
/* TODO: Remove. When ACF initialization will be in PHP there is no sense in this. */
//add_filter('acf/load_field', 'hmt_acf_load_field');
//function hmt_acf_load_field( $field ) {
//
//    do_action( 'qm/debug', $field );
//
//    if ( str_starts_with( $field['name'], 'block_layout_' ) ) {
//        foreach ( $field['choices'] as $value => $label ) {
//            $block_name = explode('block_layout_', $field['name'])[1];
//            $preview_image_filename = 'block-preview-'. $block_name . '-'. $value .'.jpg';
//            $field['choices'][$value] = '<img src="'. get_template_directory_uri() . '/assets/admin/img/block-previews/'. $preview_image_filename .'" class="layout-preview">';
//        }
//    }
//
//    return $field;
//}


/***
 ** Registering scripts & styles
 ***/
add_action( 'init', 'hmt_register_scripts_styles' );
function hmt_register_scripts_styles() {
	wp_register_script( THEME_TEXTDOMAIN . '-swiper', get_template_directory_uri() . '/assets/theme/js/vendor/swiper-bundle.min.js', [], '', true );
	wp_register_script( THEME_TEXTDOMAIN . '-scrollbar', get_template_directory_uri() . '/assets/theme/js/vendor/scrollbar.min.js', [], '', true );
}

