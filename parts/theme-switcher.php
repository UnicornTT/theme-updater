<?php
$hmt_is_dark_theme = hmt_is_dark_theme();
$hmt_is_light_theme = hmt_is_light_theme();
?>

<div class="theme-switcher">
	<a href="javascript: void(0);" class="theme-switcher__item <?= esc_attr( $hmt_is_light_theme ? 'current' : '' ) ?>" data-theme="light" aria-label="<?= esc_attr( __( 'Light theme', THEME_TEXTDOMAIN ) ) ?>">
		<span class="icon icon-wrap">
			<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-sun.svg' ); ?>
		</span>
	</a>

	<a href="javascript: void(0);" class="theme-switcher__item <?= esc_attr( $hmt_is_dark_theme ? 'current' : '' ) ?>" data-theme="dark" aria-label="<?= esc_attr( __( 'Dark theme', THEME_TEXTDOMAIN ) ) ?>">
		<span class="icon icon-wrap">
			<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-moon.svg' ); ?>
		</span>
	</a>

	<span class="theme-switcher__indicator" aria-hidden="true"></span>
</div>