<?php
/**
 * Section About Us.
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

$class_name = 'section section-about-us';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$content_image = get_field( 'about_us_section_content_image' );
$section_title = get_field( 'about_us_section_title' );
$section_description = get_extended( get_field( 'about_us_section_description' ) );
$button_type = get_field( 'about_us_section_button_type' );
$button_text = get_field( 'about_us_section_button_text' );
$button_link = get_field( 'about_us_section_button_link' );
$section_media_block = get_field( 'about_us_section_media_group' );
$section_media = $section_media_block['about_us_section_media_type'] ?? '';
$popup_video_type = $section_media_block['video_type'] ?? '';
$popup_video_src = $section_media_block['video_file'] ?? '';
$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $section_media_block['youtube_link'] );
$popup_video_poster = $section_media_block['image_poster'] ?? '';
$popup_id = wp_generate_uuid4();
$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !empty( $section_title ) && $section_title ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-about-us__bg',
			'field_prefix' => 'about_us_section_background',
			'field_id' => ''
		] );
		?>

		<div class="section__body">
			<div class="container">
				<div class="section-about-us__content">
					<div class="section-about-us__header">
						<div class="section-title section-title--style1 section-about-us__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>

					<div class="section-about-us__main">
						<div class="section-about-us__image">
							<div class="section-about-us__image-inner">
								<?php if ( !empty( $section_media ) && isset( $section_media['media_type'] ) ) : ?>
									<?php if ( $section_media['media_type'] === 'video' ) : ?>
										<?php
										$preview_video_url = wp_get_attachment_url( $section_media['video'] );
										$preview_video_poster = wp_get_attachment_image_url( $section_media['video_poster'], 'full-hd' );
										?>
										<video
											disablePictureInPicture muted playsinline autoplay poster="<?= $preview_video_poster ?>" loop="loop"
										>
											<source src="<?= $preview_video_url ?>" type="video/mp4">
										</video>
									<?php elseif ( $section_media['media_type'] === 'image' ) : ?>
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
									<?php endif; ?>
								<?php endif; ?>

								<?php if ( $popup_video_enabled ) : ?>
									<a
										href="#modal-video-about-us-<?= esc_attr( $popup_id ) ?>"
										class="button-play button-play--small"
										data-toggle="modal"
										aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>"
									>
										<span class="button-play__icon">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
										</span>
									</a>
								<?php endif ?>
							</div>
						</div>

						<div class="section-about-us__text">
							<?php
								if ( $button_type === 'show_more' && $section_description['extended'] ) {
									$description_class = 'visible';
								} else {
									$description_class = 'full';
								}
							?>
							<?php if ( $section_description ) : ?>
								<div class="section-about-us__description-full text-content">
									<?= wpautop( $section_description['main'] ) ?>

									<?php if ( $button_type !== 'show_more' && $section_description['extended'] ) : ?>
										<?= wpautop( $section_description['extended'] ) ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( $button_type ) : ?>
								<?php if ( $button_type === 'link' ) : ?>
									<?php if ( !empty( $button_link ) ) : ?>
										<?php
											$button_text = $button_link['title'] ? $button_link['title'] : '';
											$url = $button_link['url'] ? $button_link['url'] : '';
											$target = $button_link['target'] ? $button_link['target'] : '_self';
											$hash = '#';
										?>
										<div class="section-about-us__button-wrapper">
											<a
												href="<?= esc_url( $url ); ?>" target="<?= esc_attr( $target ) ?>"
												class="button button-bordered button-bordered-white-dark section-about-us__button <?= (strpos( $url, $hash ) !== false) ? esc_attr( 'js-scroll-link' ) : ''; ?>"
											>
												<?= $button_text ? esc_html( $button_text ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
											</a>
										</div>
									<?php endif ?>
								<?php endif ?>

								<?php if ( $button_type === 'show_more' ) : ?>
									<?php
									$collapse_id = wp_generate_uuid4();
									?>
									<div
										class="collapse section-about-us__description-hidden"
										id="about-us-collapse-<?= esc_attr( $collapse_id ) ?>"
									>
										<div class="collapse-content text-content">
											<?= wpautop( $section_description['extended'] ) ?>
										</div>
									</div>

									<?php if ( $section_description['extended'] && $button_text ) : ?>
										<div class="section-about-us__button-wrapper">
											<button
												class="button button-bordered button-bordered-white-dark section-about-us__button collapsed"
												data-target="#about-us-collapse-<?= esc_attr( $collapse_id ) ?>"
												data-toggle="collapse"
												aria-label="<?= esc_attr( __( 'Toggle hidden content', THEME_TEXTDOMAIN ) ) ?>"
											>
												<span class="hide">
													<?= esc_html( $button_text ) ?>
												</span>

												<span class="show">
													<?= esc_html( __( 'Hide', THEME_TEXTDOMAIN ) ) ?>
												</span>
											</button>
										</div>
									<?php endif ?>
								<?php endif ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if ( $popup_video_enabled ) : ?>
			<?php
			$popup_video_id = "modal-video-about-us-{$popup_id}";
			$popup_video_youtube_player_id = "modal-video-player-about-us-{$popup_id}";

			get_template_part(
				'template-parts/popups/popup',
				'video',
				[
					'popup_id' => $popup_video_id,
					'popup_video_youtube_player_id' => $popup_video_youtube_player_id,
					'popup_video_type' => $popup_video_type,
					'popup_video_poster_id' => $popup_video_poster,
					'popup_video_src_id' => $popup_video_src,
					'popup_video_youtube_id' => $popup_video_youtube_id,
				]
			);
			?>
		<?php endif; ?>
	</section>
<?php endif; ?>
