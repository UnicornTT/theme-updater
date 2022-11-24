<?php
/**
 * @var $args ;
 */

$equipment = $args['equipment'];
$equipment_image = get_field( 'equipment_image', $equipment->ID );
$equipment_header = get_field( 'equipment_header', $equipment->ID );
$equipment_description = get_field( 'equipment_description', $equipment->ID );

$section_media = get_field( 'equipment_media_group', $equipment->ID );
$media_type = $section_media['media_type'];
$background_type = $section_media['equipment_popup_background_bg_type'] ?? '';
$equipment_video_prev = $args['popup_video_poster'];
$popup_video_enabled = $args['popup_video_enabled'];
$popup_id = $args['popup_id'];
$popup_video_id = $args['popup_video_id'];
?>

<?php if ( $media_type !== 'none' ) : ?>
	<div
		class="modal fade modal-equipment-card" id="<?= esc_attr( $popup_id ) ?>" tabindex="-1" role="dialog"
		aria-hidden="true"
	>
		<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-equipment-full">
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
					<div class="equipment-popup-card equipment-popup-card--full">
						<div class="equipment-popup-card__content">
							<div class="equipment-popup-card__img">
								<div class="background-img">
									<picture>
										<img
											src="<?= esc_url( wp_get_attachment_image_url( $equipment_image, 'section-background-mobile' ) ) ?>"
											alt="<?= esc_attr( hmt_get_attachment_image_alt( $equipment_image ) ) ?>"
										>
									</picture>
								</div>
							</div>

							<div class="equipment-popup-card__body">
								<div class="scrollbar-outer">
									<?php if ( $equipment_header ) : ?>
										<div class="section-title section-title--style5 equipment-popup-card__title">
											<h3>
												<?= esc_html( $equipment_header ) ?>
											</h3>
										</div>
									<?php endif; ?>

									<?php if ( $equipment_description ) : ?>
										<div class="equipment-popup-card__description text-content">
											<?= apply_filters( 'the_content', $equipment_description ) ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<?php if ( $section_media['media_type'] != 'none' ): ?>
							<div class="equipment-popup-card__video">
								<div class="background-img">
									<?php if ( $section_media['media_type'] === 'image' ) : ?>
										<?php
										$equipment_video_prev = $section_media['image'];
										?>
										<picture>
											<source
												srcset="<?= esc_url( wp_get_attachment_image_url( $equipment_video_prev, 'section-background-desktop' ) ) ?>"
												media="(min-width: 1280px)"
											>
											<source
												srcset="<?= esc_url( wp_get_attachment_image_url( $equipment_video_prev, 'section-background-mobile' ) ) ?>"
												media="(max-width: 1279px)"
											>
											<img
												src="<?= esc_url( wp_get_attachment_image_url( $equipment_video_prev, 'section-background-desktop' ) ) ?>"
												alt="<?= esc_attr( hmt_get_attachment_image_alt( $equipment_video_prev ) ) ?>"
											>
										</picture>

									<?php elseif ( $section_media['media_type'] === 'video' ) : ?>
										<?php
										$preview_video_url = wp_get_attachment_url( $section_media['video'] );
										$preview_video_poster = wp_get_attachment_image_url( $section_media['video_poster'], 'full-hd' );
										?>
										<video
											disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( $preview_video_poster ) ?>"
											loop="loop"
										>
											<source src="<?= esc_url( $preview_video_url ) ?>" type="video/mp4">
										</video>

									<?php endif ?>
								</div>

								<?php if ( $popup_video_enabled ) : ?>
									<div class="equipment-popup-card__button-wrapper">
										<button
											data-target="#<?= esc_attr( $popup_video_id ) ?>"
											class="button-play button-play--small js-open-equipment-video"
											aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>"
										>
											<span class="button-play__icon">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
											</span>
										</button>
									</div>
								<?php endif ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>

	<div
		class="modal fade modal-equipment-card" id="<?= esc_attr( $popup_id ) ?>" tabindex="-1" role="dialog"
		aria-hidden="true"
	>
		<div class="modal-dialog modal-dialog-centered modal-md">
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
					<div class="equipment-popup-card">
						<div class="equipment-popup-card__content">
							<div class="equipment-popup-card__img">
								<div class="background-img">
									<picture>
										<img
											src="<?= esc_url( wp_get_attachment_image_url( $equipment_image, 'section-background-mobile' ) ) ?>"
											alt="<?= esc_attr( hmt_get_attachment_image_alt( $equipment_image ) ) ?>"
										>
									</picture>
								</div>
							</div>

							<div class="equipment-popup-card__body">
								<div class="scrollbar-outer">
									<?php if ( $equipment_header ) : ?>
										<div class="section-title section-title--style5 equipment-popup-card__title">
											<h3>
												<?= esc_html( $equipment_header ) ?>
											</h3>
										</div>
									<?php endif; ?>

									<?php if ( $equipment_description ) : ?>
										<div class="equipment-popup-card__description text-content">
											<?= apply_filters( 'the_content', $equipment_description ) ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>
