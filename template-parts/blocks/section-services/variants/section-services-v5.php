<?php

/**
 * @var $args
 */

$initial_services = $args['services'];
$page_id = get_queried_object_id();

$section_title = get_field( 'services_section_title' );
$section_subtitle = get_field( 'services_section_subtitle' );

if(is_array($initial_services)){
	$services = array_filter( $initial_services ?? [], function ( $service ) use ( $page_id ) {
		$service_gallery = get_field( 'service_gallery', $service->ID );
		$service_media = $service_gallery[0]['service_media_group']['service_media_type'];
		$service_gallery_first_img_id = null;

		if ( $service_media['media_type'] == 'image' ) {
			$service_gallery_first_img_id = $service_media['image'];
		} elseif ( $service_media['media_type'] == 'video' ) {
			$service_gallery_first_img_id = $service_media['video_poster'];
		}

		return $service->ID != $page_id && $service_gallery_first_img_id;
	} );
}

?>

<?php if ( !empty( $services ) ) : ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-services__bg',
			'field_prefix' => 'services_section',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="section-services__content">
			<div class="container">
				<div class="section-services__header">
					<?php if ( $section_title ) : ?>
						<div class="section-title section-title--style1 section-services__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					<?php endif; ?>

					<?php if ( $section_subtitle ) : ?>
						<div class="section-title section-title--style5 section-services__subtitle">
							<h2>
								<?= esc_html( $section_subtitle ) ?>
							</h2>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="swiper section-services__slider js-services-slider">
				<div class="swiper-wrapper">
					<?php foreach ( $services as $service ) : ?>
						<?php
							$service_link = get_permalink( $service->ID );
							$service_title = get_the_title( $service );
							$excerpt = wp_trim_words( get_the_excerpt( $service ), 40 );
							$excerpt_custom = get_field( 'service_page_description', $service->ID );
							$service_short_description = $excerpt ?: wp_trim_words( get_extended($excerpt_custom)['main'], 40 );
							$service_gallery = get_field( 'service_gallery', $service->ID );
							$service_media = $service_gallery[0]['service_media_group']['service_media_type'];
							$service_gallery_first_img_id = null;

							if ($service_media['media_type']){
								if ( $service_media['media_type'] == 'image' ) {
									$service_gallery_first_img_id = $service_media['image'];
								} elseif ( $service_media['media_type'] == 'video' ) {
									$service_gallery_first_img_id = $service_media['video_poster'];
								}

								$service_image_desktop_url = wp_get_attachment_image_url( $service_gallery_first_img_id, 'section-background-tablet' );
								$service_image_tablet_url = wp_get_attachment_image_url( $service_gallery_first_img_id, 'section-background-mobile' );
								$service_image_alt = hmt_get_attachment_image_alt( $service_gallery_first_img_id );
							}
						?>

						<div class="swiper-slide">
							<div class="work-card">
								<div class="work-card__img">
									<?php if ($service_media['media_type']): ?>
										<div class="background-img">
											<picture>
												<source srcset="<?= esc_url( $service_image_desktop_url ) ?>" media="(min-width: 1025px)">
												<source srcset="<?= esc_url( $service_image_tablet_url ) ?>" media="(max-width: 1024px)">
												<img src="<?= esc_url( $service_image_desktop_url ) ?>" alt="<?= esc_attr( $service_image_alt ) ?>">
											</picture>
										</div>
									<?php endif; ?>
								</div>

								<?php if ( $service_title ) : ?>
									<div class="work-card__title work-card__title--main section-title section-title--style5">
										<h3>
											<?= esc_html( $service_title ) ?>
										</h3>
									</div>
								<?php endif; ?>

								<div class="work-card__full">
									<div class="work-card__full-body">
										<div class="scrollbar-outer">
											<div class="work-card__full-content">
												<?php if ( $service_title ) : ?>
													<div class="work-card__title section-title section-title--style5">
														<h3>
															<?= esc_html( $service_title ) ?>
														</h3>
													</div>
												<?php endif; ?>

												<?php if ( $service_short_description ) : ?>
													<div class="work-card__description text-content">
														<?= apply_filters( 'the_content', $service_short_description ) ?>
													</div>
												<?php endif; ?>
											</div>
										</div>

										<div class="work-card__button-wrapper">
											<a class="button button-bordered button-bordered-white work-card__button" href="<?= esc_url( $service_link ) ?>">
												<?= esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ) ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="container">
					<div class="swiper-controls swiper-controls--circle">
						<button class="swiper-button-prev">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
						</button>

						<button class="swiper-button-next">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>


