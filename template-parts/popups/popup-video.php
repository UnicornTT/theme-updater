<?php
/**
 * @param $popup_id
 * @param $popup_video_type
 * @param $popup_video_poster_id
 * @param $popup_video_src_id
 * @param $popup_video_youtube_id
 * @param $popup_video_youtube_player_id
 * @var $args ;
 */

extract( $args );

?>
<?php if ( isset( $popup_video_type ) && $popup_video_type !== 'empty') : ?>
	<div
		id="<?= isset( $popup_id ) ? esc_attr( $popup_id ) : '' ?>"
		class="modal fade modal-video"
		tabindex="-1"
		role="dialog" aria-hidden="true"
	>
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
					<!-- Self-hosted Video-->
					<?php if ( $popup_video_type === 'self_hosted' ) : ?>
						<div class="video-wrapper self-hosted-video">
							<?php if ( isset( $popup_video_src_id ) ) : ?>
								<video
									disablePictureInPicture
									controls playsinline
									poster="<?= isset( $popup_video_poster_id ) ? esc_url( wp_get_attachment_image_url( $popup_video_poster_id, 'full-hd' ) ) : '' ?>"
									loop="loop"
								>
									<source
										src="<?= esc_url( wp_get_attachment_url( $popup_video_src_id ) ) ?>"
										type="video/mp4"
									>
								</video>
							<?php endif; ?>
						</div>
					<?php endif ?>

					<!-- YouTube Video-->
					<?php if ( $popup_video_type === 'youtube' ) : ?>
						<div class="video-responsive">
							<div
								id="<?= isset( $popup_video_youtube_player_id ) ? esc_attr( $popup_video_youtube_player_id ) : '' ?>"
								data-video-id="<?= isset( $popup_video_youtube_id ) ? esc_attr( $popup_video_youtube_id ) : '' ?>"
								class="js-youtube-player"
							></div>
						</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>