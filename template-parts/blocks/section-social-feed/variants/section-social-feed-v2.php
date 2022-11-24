<?php
/**
 * @var $args ;
 */

$feed_type = get_field( 'social_feed_section_content_type' );
$feed = [];

if ( 'instagram' === $feed_type ) {
	$instagram = \Harbinger_Marketing\Instagram_Media::get_instance();
	$feed = $instagram ? $instagram->get()->data : null;
} elseif ( 'manual' === $feed_type ) {
	$feed = get_field( 'social_feed_section' );
}

if( 'manual' === $feed_type && is_array($feed) ){
	$has_media = $feed[0]['media_url'];
}elseif('instagram' === $feed_type){
	$has_media = true;
}
?>

<?php if( $feed && $has_media): ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-social-feed__bg',
			'field_prefix' => 'social_feed_section_background',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<?php if ( $feed ) : ?>
			<div class="section-social-feed__slider-wrap">
				<div class="section-social-feed__slider js-social-feed-infiniteslide">
					<?php foreach ( $feed as $post ) : ?>
						<?php
							extract( (array)$post );

							if ( isset( $media_type ) && $media_type != 'IMAGE' ) continue;

							$url_link = (isset( $permalink ) && $permalink) ? $permalink : '';
							$target = hmt_check_external_url( $url_link ) ? 'target="_blank"' : '';
						?>
						<div class="section-social-feed__slide-wrap">
							<div class="section-social-feed__slide">
								<?php if ( $url_link ) : ?>
									<a href="<?= esc_url( $url_link ) ?>" <?= esc_attr( $target ) ?> class="section-social-feed__slide-link">

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
								<?php if ( !$url_link ) : ?>
									</div>

								<?php else : ?>
									</a>

								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif ?>
	</div>
<?php endif; ?>
