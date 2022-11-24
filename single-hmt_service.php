<?php
add_action( 'wp_enqueue_scripts', 'hmt_enqueue_styles_service_intro' );
function hmt_enqueue_styles_service_intro() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-intro-service-styles', THEME_ASSETS_URL . '/theme/css/blocks/section-intro-service.css' );
}

add_action( 'wp_enqueue_scripts', 'hmt_enqueue_scripts_service_intro' );
function hmt_enqueue_scripts_service_intro() {
	wp_enqueue_script( THEME_TEXTDOMAIN . '-section-intro-service-scripts', THEME_ASSETS_URL . '/theme/js/blocks/section-service-intro.js', array('jquery'), false, true );
}

?>

<?php get_header() ?>

<?php get_template_part( 'sections/services/intro', 'section' ); ?>

<?php the_content() ?>

<?php get_footer() ?>