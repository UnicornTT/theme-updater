<?php
/**
 * Section Careers Block Template.
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 * @var $block
 */

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$section_top_padding_type = get_field( 'section_top_padding_type' );
$section_bottom_padding_type = get_field( 'section_bottom_padding_type' );

if( $section_top_padding_type && !empty($section_top_padding_type) ) {
	$section_top_padding = 'section-top-padding--' . $section_top_padding_type;
} else {
	$section_top_padding = 'section-top-padding--default';
}
if ( $section_bottom_padding_type && !empty($section_bottom_padding_type) ) {
	$section_bottom_padding = 'section-bottom-padding--' . $section_bottom_padding_type;
} else {
	$section_bottom_padding = 'section-bottom-padding--default';
}

$class_name = 'section section-careers';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$section_title = get_field( 'careers_section_title' );
$section_description = get_field( 'careers_section_description' );
$section_gallery = get_field( 'careers_gallery' );
$section_media_block = get_field( 'careers_section_media_group' );
$section_media = $section_media_block['careers_section_media_type'] ?? '';
$button_type = get_field( 'careers_section_action_button_type' );
$button_text = get_field( 'careers_section_action_button_text' ) ?? __( 'Join Our Team', THEME_TEXTDOMAIN );
$popup_id = wp_generate_uuid4();

$popup_video_type = $section_media_block['video_type'] ?? '';
$popup_video_src = $section_media_block['video_file'] ?? '';
$popup_video_youtube_id = isset( $section_media_block['youtube_link'] ) && !empty( $section_media_block['youtube_link'] ) ?
	hmt_get_youtube_video_id_from_url( $section_media_block['youtube_link'] ) :
	'';
$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
$popup_image_poster = $section_media_block['image_poster'] ?? '';

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !empty( $section_title ) && $section_title && $section_gallery) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-careers__bg',
			'field_prefix' => 'careers_section_background',
			'field_id' => ''
		] );
		?>

		<div class="section__body">
			<div class="section-careers__content">
				<div class="container">
					<div class="section-careers__content-wrapper">
						<div class="section-careers__slider-wrapper">
							<?php if ( $section_gallery && is_array( $section_gallery ) ) : ?>
								<div class="swiper section-careers__gallery js-section-careers">
									<div class="swiper-wrapper">
										<?php foreach ( $section_gallery as $item_gallery ) : ?>
											<div class="swiper-slide">
												<div class="section-careers__gallery-item">
													<div class="background-img">
														<picture>
															<img
																src="<?= esc_url( wp_get_attachment_image_url( $item_gallery, 'section-background-mobile' ) ) ?>"
																alt="<?= esc_attr( hmt_get_attachment_image_alt( $item_gallery ) ) ?>"
															>
														</picture>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<div class="section-careers__slider-nav">
									<button class="swiper-button swiper-button-prev">
										<span class="desktop">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
										</span>

										<span class="mobile">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
										</span>
									</button>

									<button class="swiper-button swiper-button-next">
										<span class="desktop">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
										</span>

										<span class="mobile">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
										</span>
									</button>

								    <div class="section-careers__slider-pagination"></div>
								</div>

							<?php endif; ?>
						</div>

						<div class="section-careers__text-content-wrapper">
							<div class="section-careers__text-content">
								<?php if ( $section_title ) : ?>
                                    <div class="section-title section-title--style1 section-careers__title">
                                        <h2>
                                            <?= esc_html( $section_title ) ?>
                                        </h2>
                                    </div>
								<?php endif; ?>
								<div class="section-careers__description text-content">
									<?= apply_filters( 'the_content', $section_description ) ?>
								</div>
								<?php if ( $button_type && $button_type !== 'empty' ) : ?>
									<?php if ( $button_type === 'link' ) : ?>
										<?php
										$button_link = get_field( 'careers_section_action_button_link' );
										?>
										<?php if ( !empty( $button_link ) ) : ?>
											<?php
											$button_text = $button_link['title'] ?? '';
											$url = $button_link['url'] ?? '';
											$target = $button_link['target'] ?? '_self';
											?>
											<a
												href="<?= esc_url( $url ); ?>" target="<?= esc_attr( $target ); ?>"
												class="button button-primary section-careers__button"
											>
												<?= $button_text ? esc_html( $button_text ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
											</a>
										<?php endif; ?>

									<?php elseif ( $button_type === 'modal' ) : ?>
										<?php
										$button_modal = get_field( 'careers_section_action_button_modal' ) ?? '';
										\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $button_modal );
										?>
                                        <?php if( $button_modal ): ?>
                                            <a
                                                href="#<?= esc_attr( $button_modal ) ?>" data-toggle="modal"
                                                class="button button-primary section-careers__button"
                                            >
                                                <?= esc_html( $button_text ) ?>
                                            </a>
                                        <?php endif; ?>

									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
						<?php if ( isset( $section_media['media_type'] ) && !empty( $section_media['media_type'] ) ) : ?>
							<div class="section-careers__media-wrapper">
								<div class="section-careers__media">
									<?php if ( $section_media['media_type'] === 'image' ) : ?>
										<div class="background-img">
											<picture>
												<source
													srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-desktop' ) ) ?>"
													media="(min-width: 1025px)"
												>
												<source
													srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-tablet' ) ) ?>"
													media="(max-width: 1024px)"
												>
												<source
													srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-mobile' ) ) ?>"
													media="(max-width: 575px)"
												>
												<img
													src="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-desktop' ) ) ?>"
													alt="<?= esc_attr( hmt_get_attachment_image_alt( $section_media['image'] ) ); ?>"
												>
											</picture>
										</div>

									<?php elseif ( $section_media['media_type'] === 'video' ) : ?>
										<div class="background-img">
											<?php
											$preview_video_url = wp_get_attachment_url( $section_media['video'] );
											$preview_video_poster = wp_get_attachment_image_url( $section_media['video_poster'], 'full-hd' );
											?>
											<video
												disablePictureInPicture muted playsinline autoplay
												poster="<?= esc_url( $preview_video_poster ) ?>" loop="loop"
											>
												<source src="<?= esc_url( $preview_video_url ) ?>" type="video/mp4">
											</video>
										</div>
									<?php endif; ?>

									<?php if ( $popup_video_enabled ) : ?>
										<a
											href="#modal-video-careers-<?= esc_attr( $popup_id ) ?>"
											class="button-play button-play--small" data-toggle="modal"
											aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>"
										>
										<span class="button-play__icon">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
										</span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<?php
		if ( $popup_video_enabled ) {
			get_template_part(
				'template-parts/popups/popup',
				'video',
				[
					'popup_id' => 'modal-video-careers-' . $popup_id,
					'popup_video_youtube_player_id' => 'modal-video-player-careers-' . $popup_id,
					'popup_video_type' => $popup_video_type,
					'popup_video_poster' => $popup_image_poster,
					'popup_video_src_id' => $popup_video_src,
					'popup_video_youtube_id' => $popup_video_youtube_id,
				]
			);
		}
		?>
	</section>
<?php endif; ?>
