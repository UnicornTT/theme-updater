<?php
/**
 * Widget Media.
 *
 * @param array $block The block settings and attributes.
 *
 * @var $block
 */


$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = '';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php else : ?>
	<?php
	$title = get_field( 'widget_footer_media_title' );
	$media_block = get_field( 'widget_footer_media_group' );
	$widget_footer_margins = get_field( 'widget_footer_margins' );
	$popup_id = wp_generate_uuid4();

	$popup_video_type = $media_block['video_type'];
	$popup_video_src = $media_block['video_file'];
	$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $media_block['youtube_link'] );

	$popup_video_enabled = false;
	$popup_image_poster = $section_media_block['image_poster'] ?? '';

	if ( $popup_video_type == 'youtube' ) {
		if ( $popup_video_youtube_id ) {
			$popup_video_enabled = true;
		}
	}

	if ( $popup_video_type == 'self_hosted' ) {
		if ( $popup_video_src ) {
			$popup_video_enabled = true;
		}
	}
	?>
	<!--<div class="col-12 col-md-6 col-lg-4 footer-media">-->
	<div class="col-12 col-md-auto footer-media <?php if ( $widget_footer_margins['left_margin'] == 'auto' ) : ?>ml-auto<?php endif; ?> <?php if ( $widget_footer_margins['right_margin'] == 'auto' ) : ?>mr-auto<?php endif; ?>">
		<div class="widget widget--media">
			<?php if ( !empty( $title ) ) : ?>
				<div class="section-title section-title--style5 title">
					<h2>
						<?= esc_html( $title ) ?>
					</h2>
				</div>
			<?php endif; ?>

			<div class="footer-media__content">
				<?php if ( $media_block['media_type'] === 'image' ) : ?>
					<div class="background-img">
						<picture>
							<source
								srcset="<?= esc_url( wp_get_attachment_image_url( $media_block['image'], 'section-background-desktop' ) ) ?>"
								media="(min-width: 1025px)"
							>
							<source
								srcset="<?= esc_url( wp_get_attachment_image_url( $media_block['image'], 'section-background-tablet' ) ) ?>"
								media="(max-width: 1024px)"
							>
							<source
								srcset="<?= esc_url( wp_get_attachment_image_url( $media_block['image'], 'section-background-mobile' ) ) ?>"
								media="(max-width: 575px)"
							>
							<img
								src="<?= esc_url( wp_get_attachment_image_url( $media_block['image'], 'section-background-desktop' ) ) ?>"
								alt="<?= esc_attr( hmt_get_attachment_image_alt( $media_block['widget_footer_video_media_image'] ) ) ?>"
							>
						</picture>
					</div>
				<?php elseif ( $media_block['media_type'] === 'video' ) : ?>
					<div class="background-img">
						<?php
						$preview_video_url = wp_get_attachment_url( $media_block['video'] );
						$preview_video_poster = wp_get_attachment_image_url( $media_block['video_poster'], 'full-hd' );
						?>
						<video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( $preview_video_poster ) ?>" loop="loop">
							<source src="<?= esc_url( $preview_video_url ) ?>" type="video/mp4">
						</video>
					</div>
				<?php endif; ?>

				<?php if ( $popup_video_enabled ) : ?>
					<a
						href="#modal-video-footer-media-widget-<?= esc_attr( $popup_id ) ?>"
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

		<?php
		if ( $popup_video_enabled ) {
			get_template_part(
				'template-parts/popups/popup',
				'video',
				[
					'popup_id' => 'modal-video-footer-media-widget-' . $popup_id,
					'popup_video_youtube_player_id' => 'modal-video-player-footer-media-widget-' . $popup_id,
					'popup_video_type' => $popup_video_type,
					'popup_video_poster' => $popup_image_poster,
					'popup_video_src_id' => $popup_video_src,
					'popup_video_youtube_id' => $popup_video_youtube_id,
				]
			);
		}
		?>
	</div>
<?php endif;

?>


