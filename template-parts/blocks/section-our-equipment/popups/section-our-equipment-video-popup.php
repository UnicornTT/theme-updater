<?php
/**
 * @var $args ;
 */

$equipment_image = get_sub_field( 'equipment_image' );
$equipment_header = get_sub_field( 'equipment_header' );
$equipment_description = get_sub_field( 'equipment_description' );

$popup_id = $args['popup_id'];
$popup_video_type = $args['popup_video_type'];
$popup_video_poster_id = $args['popup_video_poster'];
$popup_video_src_id = $args['popup_video_src'];
$popup_video_youtube_id = $args['popup_video_youtube_id'];
$popup_video_youtube_player_id = $args['popup_video_youtube_player_id'];

?>
<?php if ( 'empty' !== $popup_video_type ) : ?>
	<div class="modal fade modal-video" id="<?= $popup_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-video">
			<div class="modal-content">
				<div class="modal-header">
					<button
						type="button"
						class="btn modal-close svg-icon"
						data-dismiss="modal"
						aria-label="<?= esc_attr( __( 'Close popup', THEME_TEXTDOMAIN ) ) ?>"
					>
						<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
					</button>
				</div>

				<div class="modal-body">
					<?php if ( $popup_video_type === 'self_hosted' ) : ?>
						<div class="video-wrapper self-hosted-video">
							<video
								disablePictureInPicture
								controls
								playsinline
								poster="<?= wp_get_attachment_image_url( $popup_video_poster_id, 'full-hd' ) ?>"
								loop="loop"
							>
								<source src="<?= wp_get_attachment_url( $popup_video_src_id ) ?>" type="video/mp4">
							</video>
						</div>
					<?php endif ?>

					<?php if ( $popup_video_type === 'youtube' ) : ?>
						<div class="video-responsive">
							<div
								id="<?= esc_attr( $popup_video_youtube_player_id ) ?>"
								data-video-id="<?= esc_attr( $popup_video_youtube_id ) ?>"
								class="js-youtube-player"
							>
							</div>
						</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>
