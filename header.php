<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ) ?>"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="HandheldFriendly" content="true"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

	<!-- Preloader styles -->
	<style>
		.preloader {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: 200000;
			transition: background-color ease-in-out .3s;
		}

		.theme-light .preloader {
			background-color: var(--color-white);
		}

		.theme-light .preloader .dark {
			display: none;
		}

		.theme-light .preloader .light {
			display: block;
		}

		.theme-dark .preloader {
			background-color: var(--color-black);
		}

		.theme-dark .preloader .dark {
			display: block;
		}

		.theme-dark .preloader .light {
			display: none;
		}

		.loader-default {
			display: inline-block;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-top: -40px;
			margin-left: -40px;
			width: 80px;
			height: 80px;
		}

		.loader-default .bar {
			box-sizing: border-box;
			display: block;
			position: absolute;
			width: 64px;
			height: 64px;
			margin: 8px;
			border: 8px solid var(--color-accent-1);
			border-radius: 50%;
			animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
			border-color: var(--color-accent-1) transparent transparent transparent;
		}

		.loader-default .bar:nth-child(1) {
			animation-delay: -0.45s;
		}

		.loader-default .bar:nth-child(2) {
			animation-delay: -0.3s;
		}

		.loader-default .bar:nth-child(3) {
			animation-delay: -0.15s;
		}

		@keyframes lds-ring {
			0% {
				transform: rotate(0deg);
			}

			100% {
				transform: rotate(360deg);
			}
		}

		.loader-gif {
			position: absolute;
			top: 50%;
			left: 50%;
			width: 250px;
			height: 250px;
			line-height: 0;
			transform: translate(-50%, -50%);
		}

		.loader-gif img {
			width: 100%;
			height: 100%;
			object-fit: contain;
		}

		.loader-lottie,
		.loader-video {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		.loader-video {
			width: 250px;
			height: auto;
		}

		.loader-video video {
			width: 100%;
			height: auto;
		}
	</style>

	<link rel="apple-touch-icon" sizes="180x180" href="<?= THEME_FAVICON_LOGO_ICON_URL ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= THEME_FAVICON_LOGO_ICON_URL ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= THEME_FAVICON_LOGO_ICON_URL ?>">
	<link rel="manifest" href="<?= THEME_ASSETS_URL ?>/theme/img/favicons/site.webmanifest">
	<meta name="theme-color" content="<?= THEME_BRAND_COLOR_MAIN ?>">

	<?php if ( get_field( 'preloader_type', 'option' ) == 'lottie' ) : ?>
		<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
	<?php endif ?>

	<?php wp_head() ?>

	<?php hmt_custom_css_variables(); ?>
</head>

<body <?php body_class( 'scroll-off theme-' . hmt_get_current_theme_color() ) ?>>
<?php wp_body_open() ?>

<div class="preloader" aria-hidden="true">
	<?php if ( get_field( 'preloader_type', 'option' ) == 'default' ) : ?>
		<div class="loader-default">
			<div class="bar"></div>
			<div class="bar"></div>
			<div class="bar"></div>
			<div class="bar"></div>
		</div>
	<?php endif ?>

	<?php if ( get_field( 'preloader_type', 'option' ) == 'video' ) : ?>
		<div class="loader-video">
			<?php if ( hmt_get_current_theme_color() == 'light' ) : ?>
				<div class="video-wrapper light">
					<video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( wp_get_attachment_url( get_field( 'video_preloader_light_poster', 'option' ) ) ); ?>" loop="loop" preload="auto">
						<source src="<?= esc_url( wp_get_attachment_url( get_field( 'video_preloader_light', 'option' ) ) ); ?>" type="video/mp4">
					</video>
				</div>

			<?php else : ?>
				<div class="video-wrapper dark">
					<video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( wp_get_attachment_url( get_field( 'video_preloader_dark_poster', 'option' ) ) ); ?>" loop="loop" preload="auto">
						<source src="<?= esc_url( wp_get_attachment_url( get_field( 'video_preloader_dark', 'option' ) ) ); ?>" type="video/mp4">
					</video>
				</div>
			<?php endif ?>
		</div>
	<?php endif ?>

	<?php if ( get_field( 'preloader_type', 'option' ) == 'gif' ) : ?>
		<div class="loader-gif">
			<?php if ( hmt_get_current_theme_color() == 'light' ) : ?>
				<div class="gif-wrapper light">
					<?= wp_get_attachment_image( get_field( 'gif_preloader_light', 'option' ), 'thumbs-desktop', false, ['alt' => esc_attr( __( 'Loading...', THEME_TEXTDOMAIN ) )] ); ?>
				</div>

			<?php else : ?>
				<div class="gif-wrapper dark">
					<?= wp_get_attachment_image( get_field( 'gif_preloader_dark', 'option' ), 'thumbs-desktop', false, ['alt' => esc_attr( __( 'Loading...', THEME_TEXTDOMAIN ) )] ); ?>
				</div>
			<?php endif ?>
		</div>
	<?php endif ?>

	<?php if ( get_field( 'preloader_type', 'option' ) == 'lottie' ) : ?>
		<?php if ( hmt_get_current_theme_color() == 'light' ) : ?>
			<div class="loader-lottie light">
				<lottie-player src="<?= esc_url( wp_get_attachment_url( get_field( 'lottie_preloader_light', 'option' ) ) ); ?>" background="transparent" speed="1" style="width: 250px; height: 250px;" loop autoplay></lottie-player>
			</div>

		<?php else : ?>
			<div class="loader-lottie dark">
				<lottie-player src="<?= esc_url( wp_get_attachment_url( get_field( 'lottie_preloader_dark', 'option' ) ) ); ?>" background="transparent" speed="1" style="width: 250px; height: 250px;" loop autoplay></lottie-player>
			</div>
		<?php endif ?>
	<?php endif ?>
</div>

<?php if (!is_404()) : ?>
<div class="main-wrapper" id="main-wrapper">
	<?php
		$header_layout = get_field( 'main_menu_type', 'option' );
		/* TODO: Header templates (with/without menus) */
		if ( $header_layout == 1 ) {
			get_template_part( 'parts/header/header-v1' );
		} elseif ( $header_layout == 2 ) {
			get_template_part( 'parts/header/header-v2' );
		}
	?>
<?php endif; ?>