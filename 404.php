<?php

if ( $_SERVER['REQUEST_URI'] !== '/404' ) {
	header( "HTTP/1.1 301 Moved Permanently" );
	header( "Location: /404" );
}

$text = get_field( 'page_404_text', 'option' );
$description = get_field( 'page_404_description', 'option' );
$background_image =  get_field( 'page_404_background', 'option' );

get_header() ?>

	<div class="main-wrapper" id="main-wrapper">
		<section id="<?= esc_attr('404') ?>" class="section section-page-404">
			<?php if ( !empty( $background_image ) ) : ?>
				<div class="section__bg section-page-404__bg" aria-hidden="true">
					<div class="background-img">
						<picture>
							<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-4k' ) ) ?>" media="(min-width: 1920px)">
							<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-desktop' ) ) ?>" media="(min-width: 1280px)">
							<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-tablet' ) ) ?>" media="(max-width: 1279px)">
							<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
							<img src="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $background_image ) ) ?>">
						</picture>
					</div>
				</div>
			<?php endif; ?>

			<div class="section__body">
				<div class="container">
					<div class="section-page-404__content-wrapper">
						<div class="section-page-404__content">
							<div class="section-page-404__error">
								<span class="error-number">
									<?= esc_html( __( '404', THEME_TEXTDOMAIN ) ) ?>
								</span>
							</div>

							<?php if ( !empty( $text ) ) : ?>
								<div class="section-page-404__message">
									<span class="message-text">
										<?= esc_html( $text ) ?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ( !empty( $description ) ) : ?>
								<div class="section-page-404__description">
									<div class="description-text text-content">
										<?= apply_filters( 'the_content', $description ) ?>
									</div>
								</div>
							<?php endif; ?>

							<div class="section-page-404__button-wrapper">
								<a class="button button-bordered button-bordered-white section-page-404__button" role="link" href="<?= esc_url( home_url() ) ?>">
									<?= esc_html( __( 'Go to Homepage', THEME_TEXTDOMAIN ) ) ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

<?php get_footer() ?>