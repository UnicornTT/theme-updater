<?php
/**
 * @var $args array
 */

use Harbinger_Marketing\BirdEye_Reviews;

$block_id = $args['block_id'];
$title = get_field( 'testimonials_section_block_title' );

$testimonials_type = get_field( 'testimonials_section_type' );
$testimonials_reviews_textual_type = $testimonials_type[ 'testimonials_section_reviews_textual_type' ];
$testimonials_type_textual = $testimonials_type[ 'testimonials_section_reviews_type_textual' ];

$birdeye_business_id = get_field( 'birdeye_business_id', 'option' );
$birdeye_api_key = get_field( 'birdeye_api_key', 'option' );
$BirdEye_Reviews = new BirdEye_Reviews( $birdeye_business_id, $birdeye_api_key );
$reviews = $BirdEye_Reviews->get_reviews( 15, true, false );
$reviews_summary = $BirdEye_Reviews->get_reviews_summary();
$reviews_image_gallery = [];
?>


<?php if( ($testimonials_reviews_textual_type == 'manual' && $testimonials_type_textual) || ($testimonials_reviews_textual_type == 'bird_eye' && $reviews) ): ?>
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
				<div class="section-testimonials__header">
					<?php if ( $title ) : ?>
						<div class="section-title section-title--style1 section-testimonials__title">
							<h2>
								<?= esc_html( $title ) ?>
							</h2>
						</div>
					<?php endif; ?>

					<?php if ( $testimonials_reviews_textual_type == 'manual' ) : ?>
						<?php
							$rating_array = [];

							foreach ( $testimonials_type_textual as $review_key => $review ) {
								$rating_array[] = $rating = get_field( 'testimonials_section_review_rating', $review->ID );
							}

							$average_rating = number_format( array_sum( $rating_array ) / count( $rating_array ), 1, '.', '' );
						?>
						<div class="section-testimonials__rate-info">
							<div class="section-testimonials__star icon-wrap">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-star-stroke.svg' ); ?>
							</div>

							<div class="section-testimonials__middle-rate">
								<span class="section-testimonials__rate">
									<?= esc_html( $average_rating ) ?>
								</span>

								<span class="section-testimonials__number">
									(<?= count( $rating_array ) ?>)
								</span>
							</div>
						</div>
					<?php endif ?>

					<?php if ( $testimonials_reviews_textual_type == 'bird_eye' ) : ?>
						<div class="section-testimonials__rate-info">
							<div class="section-testimonials__star icon-wrap">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-star-stroke.svg' ); ?>
							</div>

							<div class="section-testimonials__middle-rate">
								<span class="section-testimonials__rate">
									<?= number_format( $reviews_summary->avgRating, 1, '.', '' ); ?>
								</span>

								<?php if ( isset( $reviews_summary->reviewCount ) && $reviews_summary->reviewCount ) : ?>
									<span class="section-testimonials__number">
										(<?= esc_html( $reviews_summary->reviewCount ) ?>)
									</span>
								<?php endif; ?>
							</div>
						</div>
					<?php endif ?>
				</div>
			</div>

			<?php if ( $testimonials_reviews_textual_type == 'manual' ) : ?>
				<div class="section-testimonials__main">
					<div class="swiper section-testimonials__slider js-swiper-reviews">
						<div class="swiper-wrapper">
							<?php foreach ( $testimonials_type_textual as $review_key => $review ) : ?>
								<div class="swiper-slide">
									<div class="testimonials-card">
										<div class="testimonials-card__header">
											<?php if ( isset( $review->post_title ) && $review->post_title ) : ?>
												<div class="testimonials-card__header-left">
													<div class="testimonials-card__name section-title section-title--style4">
														<h3>
															<?= esc_html( $review->post_title ) ?>
														</h3>
													</div>
												</div>
											<?php endif; ?>

											<div class="testimonials-card__header-right">
												<div class="testimonials-card__logo icon-wrap">
													<?php hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-comment.svg' ); ?>
												</div>

												<div class="testimonials-card__date">
													<?= get_field( 'testimonials_section_review_date', $review->ID ) ?>
												</div>
											</div>
										</div>

										<div class="testimonials-card__rating">
											<?php $rating = get_field( 'testimonials_section_review_rating', $review->ID ) ?>

											<div class="star-rating" aria-label="<?= esc_attr( $rating ) ?> star">
												<?php for ( $i = 0; $i < $rating; ++$i ) : ?>
													<span class="star-rating__icon icon-wrap" aria-hidden="true">
														<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-star-red.svg' ); ?>
													</span>
												<?php endfor; ?>
											</div>
										</div>

										<div class="testimonials-card__date testimonials-card__date--mobile">
											<?= get_field( 'testimonials_section_review_date', $review->ID ) ?>
										</div>

										<div class="testimonials-card__description-gradient">
											<div class="testimonials-card__description testimonials-card__description--scroll">
												<div class="scrollbar-outer">
													<div class="text-content">
														<?= get_field( 'testimonials_section_review_text', $review->ID ) ?>
													</div>
												</div>
											</div>
										</div>

										<?php
											$attachment_images = get_field( 'testimonials_section_review_attached_images', $review->ID );
										?>

										<?php if ( $attachment_images ) : ?>
											<?php $reviews_image_gallery['testimonials_section_review_images_' . $review_key . $block_id] = $attachment_images; ?>

											<div class="testimonials-card__gallery">
												<div class="swiper testimonials-card__reviews-slider js-swiper-reviews-gallery">
													<div class="swiper-wrapper">
														<?php foreach ( $attachment_images as $image_id ) : ?>
															<div class="swiper-slide">
																<a href="#<?= 'testimonials_section_review_images_' . $review_key . $block_id ?>" class="testimonials-card__gallery-image" data-toggle="modal">
																	<?= wp_get_attachment_image( $image_id, 'thumbs-mobile', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $image_id ) )] ); ?>
																</a>
															</div>
														<?php endforeach; ?>
													</div>
												</div>
											</div>
										<?php endif ?>
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
			<?php endif ?>

			<?php if ( $testimonials_reviews_textual_type == 'bird_eye' ) : ?>
				<div class="section-testimonials__main">
					<div class="swiper section-testimonials__slider js-swiper-reviews">
						<div class="swiper-wrapper">
							<?php foreach ( $reviews as $review ) : ?>
								<div class="swiper-slide">
									<div class="testimonials-card">
										<div class="testimonials-card__header">
											<div class="testimonials-card__header-left">
												<div class="testimonials-card__name section-title section-title--style4">
													<h3>
														<?= esc_html( $review->reviewer->firstName . ' ' . $review->reviewer->lastName ); ?>
													</h3>
												</div>
											</div>

											<div class="testimonials-card__header-right">
												<div class="testimonials-card__logo icon-wrap">
													<?php
													$source_type = strtolower( $review->sourceType );

													if ( $source_type !== 'google' && $source_type !== 'facebook' ) {
														$source_type = 'birdeye';
													}
													?>
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-{$source_type}.svg" ); ?>
												</div>

												<div class="testimonials-card__date">
													<?= date_format( date_create( $review->reviewDate ), 'F jS, Y' ); ?>
												</div>
											</div>
										</div>

										<div class="testimonials-card__rating">
											<div class="star-rating" aria-label="<?= $review->rating ?> star">
												<?php for ( $i = 0; $i < $review->rating; ++$i ) : ?>
													<span class="star-rating__icon icon-wrap" aria-hidden="true">
														<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-star-red.svg' ); ?>
													</span>
												<?php endfor; ?>
											</div>
										</div>

										<div class="testimonials-card__date testimonials-card__date--mobile">
											<?= date_format( date_create( $review->reviewDate ), 'F jS, Y' ); ?>
										</div>

										<?php if ( isset( $review->comments ) && $review->comments ) : ?>
											<div class="testimonials-card__description-gradient">
												<div class="testimonials-card__description testimonials-card__description--scroll">
													<div class="scrollbar-outer">
														<div class="text-content">
															<?= esc_html( $review->comments ) ?>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
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
			<?php endif ?>
		</div>
	</div>

	<?php if( $reviews_image_gallery ): ?>
		<!-- Image Gallery Popup -->
		<?php
		get_template_part(
			'template-parts/popups/popup',
			'image-gallery',
			[
				'gallery_list' => $reviews_image_gallery
			]
		);
		?>
		<!-- End Image Gallery Popup -->
	<?php endif; ?>
<?php endif; ?>