<?php
/**
 * @var $args array
 */

use Harbinger_Marketing\BirdEye_Reviews;

$block_id = $args['block_id'];
$title = get_field( 'testimonials_section_block_title' );

$testimonials_type = get_field( 'testimonials_section_type' );
$testimonials_type_video = $testimonials_type[ 'testimonials_section_reviews_type_video' ];

$testimonials_reviews_textual_type = $testimonials_type[ 'testimonials_section_reviews_textual_type' ];
$testimonials_type_textual = $testimonials_type[ 'testimonials_section_reviews_type_textual' ];

$show_content = true;
if ( $testimonials_reviews_textual_type == 'manual' ) {
	$show_content = !empty( $testimonials_type_textual );
}

$birdeye_business_id = get_field( 'birdeye_business_id', 'option' );
$birdeye_api_key = get_field( 'birdeye_api_key', 'option' );
$BirdEye_Reviews = new BirdEye_Reviews( $birdeye_business_id, $birdeye_api_key );
$reviews = $BirdEye_Reviews->get_reviews( 15, true, false );
?>

<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-testimonials__bg',
		'field_prefix' => 'testimonials_section_block_background',
		'field_id' => ''
	] );
?>

<?php if ( $testimonials_type_video && $testimonials_reviews_textual_type && $show_content ) : ?>
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
					<?php if ( $testimonials_reviews_textual_type == 'manual' && $testimonials_type_textual ) : ?>
						<div class="section-testimonials__default-testimonials">
							<div class="swiper swiper-container js-swiper-testimonials-default">
								<div class="swiper-wrapper">
									<?php foreach ( $testimonials_type_textual as $review ) : ?>
										<div class="swiper-slide">
											<div class="testimonials-card testimonials-card--accent">
												<div class="testimonials-card__header">
													<div class="testimonials-card__source">
														<span class="icon-wrap" aria-hidden="true">
															<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-comment.svg' ); ?>
														</span>
													</div>

													<div class="testimonials-card__info">
														<?php if ( isset( $review->post_title ) && $review->post_title ) : ?>
															<div class="testimonials-card__author section-title section-title--style3">
																<?= esc_html( $review->post_title ) ?>
															</div>
														<?php endif; ?>

														<div class="testimonials-card__rating">
															<?php $rating = get_field( 'testimonials_section_review_rating', $review->ID ) ?>
															<div class="star-rating" aria-label="<?= $rating ?> star">
																<?php for ( $i = 0; $i < $rating; ++$i ) : ?>
																	<span class="star-rating__icon icon-wrap" aria-hidden="true">
																		<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-star-red.svg' ); ?>
																	</span>
																<?php endfor; ?>
															</div>
														</div>

														<div class="testimonials-card__date">
															<?= get_field( 'testimonials_section_review_date', $review->ID ) ?>
														</div>
													</div>
												</div>

												<div class="testimonials-card__body">
													<div class="scrollbar-outer">
														<div class="testimonials-card__description text-content">
															<?= wpautop( get_field( 'testimonials_section_review_text', $review->ID ) ) ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="swiper-controls">
								<button class="swiper-button-prev">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</button>

								<button class="swiper-button-next">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</button>

								<div class="swiper-pagination"></div>
							</div>
						</div>
					<?php endif ?>

					<?php if ( $testimonials_reviews_textual_type == 'bird_eye' && count( $reviews ) > 0 ) : ?>
						<div class="section-testimonials__default-testimonials">
							<div class="swiper swiper-container js-swiper-testimonials-default">
								<div class="swiper-wrapper">
									<?php foreach ( $reviews as $review ) : ?>
										<div class="swiper-slide">
											<div class="testimonials-card testimonials-card--accent">
												<div class="testimonials-card__header">
													<div class="testimonials-card__source">
														<span class="icon-wrap" aria-hidden="true">
															<?php
																$source_type = strtolower( $review->sourceType );

																if ( $source_type !== 'google' && $source_type !== 'facebook' ) {
																	$source_type = 'birdeye';
																}
															?>
															<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-{$source_type}.svg" ); ?>
														</span>
													</div>

													<div class="testimonials-card__info">
														<div class="testimonials-card__author section-title section-title--style3">
															<?= esc_html( $review->reviewer->firstName . ' ' . $review->reviewer->lastName ); ?>
														</div>

														<div class="testimonials-card__rating">
															<div class="star-rating" aria-label="<?= esc_attr( $review->rating ) ?> star">
																<?php for ( $i = 0; $i < $review->rating; ++$i ) : ?>
																	<span class="star-rating__icon icon-wrap" aria-hidden="true">
																		<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-star-red.svg' ); ?>
																	</span>
																<?php endfor; ?>
															</div>
														</div>

														<div class="testimonials-card__date">
															<?= date_format( date_create( $review->reviewDate ), 'F jS, Y' ); ?>
														</div>
													</div>
												</div>

												<?php if ( isset( $review->comments ) && $review->comments ) : ?>
													<div class="testimonials-card__body">
														<div class="scrollbar-outer">
															<div class="testimonials-card__description text-content">
																<?= wpautop( $review->comments ) ?>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="swiper-controls">
								<button class="swiper-button-prev">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</button>

								<button class="swiper-button-next">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</button>

								<div class="swiper-pagination"></div>
							</div>
						</div>
					<?php endif ?>

					<?php if ( $testimonials_type_video ) : ?>
						<div class="section-testimonials__video-testimonials">
							<?php
								$first_video = $testimonials_type_video[0];
								$section_media = get_field( 'testimonials_section_media_type', $first_video->ID );
							?>

							<div class="testimonials-card testimonials-card--video">
								<a href="#modal-video-testimonial-<?= esc_attr( $block_id ) ?>-0" class="testimonials-card__video" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video Testimonial', THEME_TEXTDOMAIN ) ) ?>">
									<div class="background-img">
										<?php if( $section_media && is_array($section_media) ): ?>
											<?php if ( $section_media['media_type'] === 'image' ) : ?>
												<picture>
													<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-tablet' ) ); ?>" media="(min-width: 1025px)">
													<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-mobile' ) ); ?>" media="(max-width: 1024px)">
													<img src="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-tablet' ) ); ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $section_media['image'] ) ); ?>">
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
										<?php endif ?>
									</div>

									<span class="icon-wrap">
										 <?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
									</span>
								</a>
							</div>

							<?php if ( isset( $testimonials_type_video[1] ) ) : ?>
								<div class="gallery-wrapper">
									<div class="swiper swiper-container js-swiper-testimonials-gallery">
										<div class="swiper-wrapper">
											<?php foreach ( array_slice( $testimonials_type_video, 1 ) as $key => $video ) : ?>
												<?php
													$section_media = get_field( 'testimonials_section_media_type', $video->ID ) ?? '';
												?>
												<?php if( $section_media && is_array($section_media) ):
													if($section_media['media_type'] == 'image'){
														$video_image_poster = $section_media['image'];
													}elseif($section_media['media_type'] == 'video'){
														$video_image_poster = $section_media['video_poster'];
													}
												?>
													<div class="swiper-slide">
														<div class="testimonials-card testimonials-card--video testimonials-card--video--small">
															<a href="#modal-video-testimonial-<?= esc_attr( $block_id . '-' . ($key + 1) ) ?>" class="testimonials-card__video" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video Testimonial', THEME_TEXTDOMAIN ) ) ?>">
																<div class="background-img">
																	<picture>
																		<source srcset="<?= esc_url( wp_get_attachment_image_url( $video_image_poster, 'section-background-tablet' ) ) ?>" media="(min-width: 1025px)">
																		<source srcset="<?= esc_url( wp_get_attachment_image_url( $video_image_poster, 'section-background-mobile' ) ) ?>" media="(max-width: 1024px)">
																		<img src="<?= esc_url( wp_get_attachment_image_url( $video_image_poster, 'section-background-tablet' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $video_image_poster ) ); ?>">
																	</picture>
																</div>

																<span class="icon-wrap">
																	<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
																</span>
															</a>
														</div>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
									</div>

									<div class="swiper-controls">
										<button class="swiper-button-prev">
											<span class="icon icon-wrap">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</span>
										</button>

										<button class="swiper-button-next">
											<span class="icon icon-wrap">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</span>
										</button>
									</div>
								</div>
							<?php endif ?>
						</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>

	<?php if( $testimonials_type_video ): ?>
		<!-- Video Popup -->
		<?php
		foreach ( $testimonials_type_video as $key => $video ) {
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
	<?php endif; ?>
<?php endif ?>