<?php
/**
 * Google Maps Marker Template.
 *
 * @param string $project_location_ID
 * @param string $project_image_IDs
 * @param string $project_title
 * @param string $project_description
 *
 * @var $args
 */


$project_location_ID = $args['project_location_ID'];
$project_image_IDs = $args['project_image_IDs'];
$project_title = $args['project_title'];
$project_description = $args['project_description'];
?>

<div class="infowindow" id="infowindow-<?= esc_html( $project_location_ID ) ?>">
	<div class="portfolio-card portfolio-card--map">
		<div class="portfolio-card__header">
			<?php if ( count( $project_image_IDs ) == 1 ) : ?>
				<a
					href="#modal-portfolio-<?= esc_attr( $project_location_ID ) ?>"
					class="portfolio-card__img js-open-modal"
					data-toggle="modal"
				>
					<img
						src="<?= esc_url( wp_get_attachment_image_url( $project_image_IDs[0], 'thumbs-desktop' ) ) ?>"
						alt="<?= esc_attr( hmt_get_attachment_image_alt( $project_image_IDs[0] ) ) ?>"
					>
				</a>

			<?php else : ?>
				<div class="column-left">
					<div class="swiper swiper-container js-swiper-map-main">
						<div class="swiper-wrapper">
							<?php foreach ( $project_image_IDs as $image_main ) : ?>
								<div class="swiper-slide">
									<a
										href="#modal-portfolio-<?= esc_attr( $project_location_ID ) ?>"
										class="portfolio-card__img js-open-modal"
										data-toggle="modal"
									>
										<img
											src="<?= esc_url( wp_get_attachment_image_url( $image_main, 'thumbs-desktop' ) ) ?>"
											alt="<?= esc_attr( hmt_get_attachment_image_alt( $image_main ) ) ?>"
										>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="column-right">
					<div class="swiper swiper-container js-swiper-map-thumbs">
						<div class="swiper-wrapper">
							<?php foreach ( $project_image_IDs as $image_thumb ) : ?>
								<div class="swiper-slide">
									<div class="portfolio-card__img portfolio-card__img--small">
										<img
											src="<?= esc_url( wp_get_attachment_image_url( $image_thumb, 'thumbs-desktop' ) ) ?>"
											alt="<?= esc_attr( hmt_get_attachment_image_alt( $image_thumb ) ) ?>"
										>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>			
		</div>

		<div class="portfolio-card__content">
			<?php if ( !empty( $project_title ) ) : ?>
				<div class="portfolio-card__title section-title section-title--style5">
					<h3>
						<?= esc_html( $project_title ) ?>
					</h3>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $project_description ) ) : ?>
				<div class="portfolio-card__body">
					<div class="scrollbar-outer">
						<div class="portfolio-card__description text-content">
							<?= apply_filters( 'the_content', $project_description ) ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="swiper-controls swiper-controls--fraction">
				<button class="swiper-button-prev">
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
				</button>

				<button class="swiper-button-next">
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
				</button>

				<div class="swiper-pagination"></div>
			</div>
		</div>
	</div>
</div>
