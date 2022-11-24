<?php
/**
 * @var $args array
 */

$block_id = $args['block_id'];
$title = get_field( 'testimonials_section_block_title' );

$testimonials_type = get_field( 'testimonials_section_type' );
$testimonials_type_video_textual = $testimonials_type[ 'testimonials_section_reviews_type_video_textual' ];
?>

<?php if ( $testimonials_type_video_textual ) : ?>

	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-testimonials__bg',
		'field_prefix' => 'testimonials_section_block_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-testimonials__content">
			<div class="container">
				<?php if ( $title ) : ?>
					<div class="section-testimonials__header">
						<div class="section-title section-title--style1 section-testimonials__title">
							<h2>
								<?= esc_html( $title ) ?>
							</h2>
						</div>
					</div>
				<?php endif; ?>

                <div class="section-testimonials__main">
                    <div class="swiper swiper-container js-swiper-testimonials-custom">
                        <div class="swiper-wrapper">
                            <?php foreach ( $testimonials_type_video_textual as $key => $review ) : ?>
								<?php
									$section_media = get_field( 'testimonials_section_media_type', $review->ID );
								?>
								<?php if ( $section_media && is_array($section_media) ): ?>
									<div class="swiper-slide">
										<div class="testimonials-card testimonials-card--video testimonials-card--video--full">
											<a href="#modal-video-testimonial-<?= esc_attr( $block_id . '-' . $key ) ?>" class="testimonials-card__video" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video Testimonial', THEME_TEXTDOMAIN ) ) ?>">
												<div class="background-img">
													<?php if ($section_media['media_type']): ?>
														<?php if ( $section_media['media_type'] === 'image' ) : ?>
															<picture>
																<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'card-image-hige-desktop' ) ) ?>" media="(min-width: 768px)">
																<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
																<img src="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'card-image-hige-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $section_media['image'] ) ); ?>">
															</picture>
														<?php elseif ( $section_media['media_type'] === 'video' ) : ?>
															<?php
															$preview_video_url = wp_get_attachment_url( $section_media['video'] );
															$preview_video_poster = wp_get_attachment_image_url( $section_media['video_poster'], 'full-hd' );
															?>
															<video disablePictureInPicture muted playsinline autoplay poster="<?= $preview_video_poster ?>" loop="loop">
																<source src="<?= $preview_video_url ?>" type="video/mp4">
															</video>
														<?php endif ?>
													<?php endif; ?>
												</div>

												<span class="icon-wrap">
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
												</span>
											</a>

											<?php
												$date = get_field( 'testimonials_section_review_date', $review->ID );
												$review_text = get_field( 'testimonials_section_review_text', $review->ID );
											?>
											<div class="testimonials-card__content">
												<div class="testimonials-card__header">
													<?php if ( isset( $review->post_title ) ) : ?>
														<div class="testimonials-card__author section-title section-title--style3">
															<?= esc_html( $review->post_title ) ?>
														</div>
													<?php endif; ?>

													<?php if ( $date ) : ?>
														<div class="testimonials-card__date">
															<?= esc_html( $date ) ?>
														</div>
													<?php endif; ?>
												</div>

												<?php if ( $review_text ) : ?>
													<div class="testimonials-card__body">
														<div class="testimonials-card__scroll-wrapper">
															<div class="scrollbar-outer">
																<div class="testimonials-card__description text-content">
																	<?= wpautop( $review_text ) ?>
																</div>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="swiper-controls swiper-controls--fraction">
						<button class="swiper-button-prev">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
						</button>

						<button class="swiper-button-next">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
						</button>

						<div class="swiper-pagination"></div>
					</div>

					<button class="swiper-button-image swiper-button-image--prev js-swiper-testimonials-custom-prev" aria-label="<?= esc_attr( __( 'Prev', THEME_TEXTDOMAIN ) ) ?>">
						<span class="background-img" aria-hidden="true">
							<img src="" alt="">
						</span>

						<span class="swiper-button-image__info">
							<span class="swiper-button-image__icon icon-wrap" aria-hidden="true">
								<span class="desktop">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</span>

								<span class="mobile">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
								</span>
							</span>

							<span class="swiper-button-image__author section-title"></span>
						</span>
					</button>

					<button class="swiper-button-image swiper-button-image--next js-swiper-testimonials-custom-next" aria-label="<?= esc_attr( __( 'Next', THEME_TEXTDOMAIN ) ) ?>">
						<span class="background-img" aria-hidden="true">
							<img src="" alt="">
						</span>

						<span class="swiper-button-image__info">
							<span class="swiper-button-image__icon icon-wrap" aria-hidden="true">
								<span class="desktop">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</span>

								<span class="mobile">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
								</span>
							</span>

							<span class="swiper-button-image__author section-title"></span>
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Video Popup -->
	<?php
	foreach ( $testimonials_type_video_textual as $key => $video ) {
		get_template_part(
			'template-parts/popups/popup',
			'video',
			[
				'popup_id' => 'modal-video-testimonial-' . $block_id . '-' . $key,
				'popup_video_youtube_player_id' => 'modal-video-player-testimonial-' . $block_id . '-' . $key,
				'popup_video_type' => get_field( 'testimonials_section_video_video_type', $video->ID ),
				'popup_video_poster_id' => get_field( 'testimonials_section__video_video_image_poster', $video->ID ) ?? '',
				'popup_video_src_id' => get_field( 'testimonials_section_video_video_file', $video->ID ),
				'popup_video_youtube_id' => hmt_get_youtube_video_id_from_url( get_field( 'testimonials_section_video_youtube_link', $video->ID ) ),
			]
		);
	}
	?>
	<!-- End Video Popup -->
<?php endif ?>
