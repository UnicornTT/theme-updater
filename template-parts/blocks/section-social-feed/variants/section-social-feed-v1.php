<?php
/**
 * @var $args ;
 */

$section_title = get_field( 'social_feed_section_title' );
$socials = get_field( 'social', 'option' );

$feed_type = get_field( 'social_feed_section_content_type' );
$initial_feed = [];
$feed = [];

if ( 'instagram' === $feed_type ) {
	$instagram = \Harbinger_Marketing\Instagram_Media::get_instance();
	$initial_feed = $instagram ? $instagram->get()->data : null;
} elseif ( 'manual' === $feed_type ) {
	$initial_feed = get_field( 'social_feed_section' );
}

if ( !empty( $initial_feed ) && count( $initial_feed ) ) {
	$feed = $initial_feed;

	while ( count( $feed ) < 6 || count( $feed ) % 2 !== 0 ) {
		$feed = array_merge( $feed, $initial_feed );
	}
}
if( 'manual' === $feed_type && is_array($initial_feed) ){
	$has_media = $initial_feed[0]['media_url'];
}elseif('instagram' === $feed_type){
	$has_media = true;
}
?>

<?php if( $feed && $has_media && $section_title ): ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-social-feed__bg',
			'field_prefix' => 'social_feed_section_background',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="container">
			<div class="section-social-feed__header">
				<div class="section-title section-title--style1 section-social-feed__title">
					<h2>
						<?= esc_html( $section_title ) ?>
					</h2>
				</div>

				<?php if ( !empty( $socials ) && $socials ) : ?>
					<div class="section-social-feed__block-icons">
						<?php foreach ( $socials as $social ): ?>
							<?php extract( $social ) ?>

							<?php if ( isset( $icon ) ) : ?>
								<a href="<?= isset( $link ) ? esc_url( $link ) : 'javascript:void(0)' ?>" target="_blank" class="section-social-feed__icons">
									<span class="icon icon-wrap">
										<?= hmt_get_svg_inline( wp_get_attachment_image_url( $icon ) ); ?>
									</span>
								</a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( !empty( $feed ) ) : ?>
			<div class="section-social-feed__slider-wrap">
				<div class="swiper section-social-feed__slider js-social-feed-slider">
					<div class="swiper-wrapper">
						<?php foreach ( $feed as $post ) : ?>
							<?php
								extract( (array)$post );

								if ( isset( $media_type ) && $media_type != 'IMAGE' ) continue;

								$url_link = (isset( $permalink ) && $permalink) ? $permalink : '';
								$target = hmt_check_external_url( $url_link ) ? 'target="_blank"' : '';
							?>
							<div class="swiper-slide section-social-feed__slide-wrap">
								<div class="section-social-feed__slide">
									<?php if ($url_link) : ?>
									<a href="<?= esc_url( $url_link ) ?>" <?= $target ?> class="section-social-feed__slide-link">
										<?php else : ?>
										<div class="section-social-feed__slide-link">
											<?php endif; ?>
											<?php if ( isset( $media_url ) && $media_url ) : ?>
												<div class="section-social-feed__slide-image">
													<div class="background-img">
														<?php
															$link = wp_http_validate_url( $media_url ) ? esc_url( $media_url ) : wp_get_attachment_image_url( $media_url, 'large' );
															$alt = wp_http_validate_url( $media_url ) ? (isset( $caption ) ? esc_attr( $caption ) : __( 'Social Title', THEME_TEXTDOMAIN )) : hmt_get_attachment_image_alt( $media_url );
														?>
														<img src="<?= $link ?>" alt="<?= $alt ?>">
													</div>
												</div>
											<?php endif; ?>

											<div class="section-social-feed__full">
												<div class="section-social-feed__full-container js-font-adjustment" data-fj-min="18" data-fj-size-factor="1.8">
													<?php if ( $feed_type === 'instagram' ) : ?>
														<span class="social-name">
															<?= __( 'Instagram', THEME_TEXTDOMAIN ) ?>
														</span>
													<?php elseif ( isset( $label ) && $label ) : ?>
														<span class="social-name">
															<?= esc_html( $label ) ?>
														</span>
													<?php endif; ?>

													<?php if ( isset( $caption ) && $caption ) : ?>
														<span class="social-nickname js-font-title">
															<?= esc_html( $caption ) ?>
														</span>
													<?php endif; ?>
												</div>
											</div>
											<?php if (!$url_link) : ?>
										</div>
										<?php else : ?>
									</a>
								<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="swiper-controls swiper-controls--circle">
					<button class="swiper-button-prev">
						<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M26.9173 17H7.08398" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
							<path d="M17.0006 26.9168L7.08398 17.0002L17.0006 7.0835" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</button>

					<button class="swiper-button-next">
						<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M26.9173 17H7.08398" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
							<path d="M17.0006 26.9168L7.08398 17.0002L17.0006 7.0835" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</button>
				</div>
			</div>
		<?php endif ?>
	</div>
<?php endif; ?>