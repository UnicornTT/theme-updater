<?php
/**
 * @var $args ;
 */

$block_id = $args['block_id'];

$project_list = $args['project_list'] ?? [];
$service_list = $args['service_list'] ?? [];

?>
<?php foreach ( $project_list as $project ) : ?>
	<?php
	$project_title = get_the_title( $project );
	$project_description = get_field( 'project_description', $project->ID );
	$project_gallery_image_list = get_field( 'project_gallery', $project->ID ) ?? [];
	?>
	<?php if ( isset( $project_gallery_image_list[0] ) ) : ?>
		<div
			class="modal fade modal-our-work" id="modal-our-work-<?= esc_attr( $project->ID ) ?>" tabindex="-1"
			role="dialog" aria-hidden="true"
		>
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button
							type="button" class="btn modal-close svg-icon" data-dismiss="modal"
							aria-label="<?= esc_attr( __( 'Close popup', THEME_TEXTDOMAIN ) ) ?>"
						>
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
						</button>
					</div>

					<div class="modal-body">
						<div class="our-work-gallery">
							<?php if ( $project_title ) : ?>
								<div class="our-work-gallery__title section-title section-title--style3">
									<h3>
										<?= esc_html( $project_title ) ?>
									</h3>
								</div>
							<?php endif; ?>

							<div class="swiper our-work-gallery__main js-our-work-gallery">
								<div class="swiper-wrapper">
									<?php foreach ( $project_gallery_image_list as $image_id ) : ?>
										<?php
										$image_url = wp_get_attachment_image_url( $image_id, 'full-hd' );
										$project_image_desktop_url = wp_get_attachment_image_url( $image_id, 'section-background-tablet' );
										$project_image_tablet_url = wp_get_attachment_image_url( $image_id, 'section-background-mobile' );
										$project_image_mobile_url = wp_get_attachment_image_url( $image_id, 'thumbs-desktop' );
										$image_alt = hmt_get_attachment_image_alt( $image_id );
										?>
										<div class="swiper-slide">
											<div class="our-work-gallery__main-item">
												<div class="background-img">
													<picture>
														<source
															srcset="<?= esc_url( $project_image_desktop_url ) ?>"
															media="(min-width: 1025px)"
														>
														<source
															srcset="<?= esc_url( $project_image_tablet_url ) ?>"
															media="(max-width: 1024px)"
														>
														<source
															srcset="<?= esc_url( $project_image_mobile_url ) ?>"
															media="(max-width: 575px)"
														>
														<img
															src="<?= esc_url( $image_url ) ?>"
															alt="<?= esc_attr( $image_alt ) ?>"
														>
													</picture>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="swiper our-work-gallery__thumbs js-our-work-gallery-thumbs">
								<div class="swiper-wrapper">
									<?php foreach ( $project_gallery_image_list as $i => $image_id ) : ?>
										<?php
										$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
										$image_alt = hmt_get_attachment_image_alt( $image_id );
										?>
										<div class="swiper-slide" data-slide-index="<?= $i ?>">
											<div class="our-work-gallery__thumbs-item">
												<div class="background-img">
													<?= wp_get_attachment_image( $image_id, 'section-background-desktop', false, ['alt' => $image_alt] ); ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<?php if ( $project_description ) : ?>
								<div class="our-work-gallery__description text-content">
									<?= apply_filters( 'the_content', $project_description ) ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
<?php endforeach; ?>

<?php foreach ( $service_list as $service ) : ?>
	<?php
	$service_title = get_the_title( $service );
	$service_description = get_field( 'service_page_short_description', $service->ID );
	$service_gallery = get_field( 'service_gallery', $service->ID );
	$service_media = $service_gallery[0]['service_media_group']['service_media_type'];
	$service_current_gallery = $service_gallery[0]['service_media_group'] ?? '';
	$service_background_media_type = $service_current_gallery['service_media_background_bg_type'] ?? '';
	?>
	<?php if ( isset( $service_gallery[0] ) ) : ?>
		<div
			class="modal fade modal-our-work" id="modal-services-<?= esc_attr( $block_id . '-' . $service->ID ) ?>"
			tabindex="-1" role="dialog" aria-hidden="true"
		>
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button
							type="button" class="btn modal-close svg-icon" data-dismiss="modal"
							aria-label="<?= esc_html( __( 'Close popup', THEME_TEXTDOMAIN ) ) ?>"
						>
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
						</button>
					</div>

					<div class="modal-body">
						<div class="our-work-gallery">
							<?php if ( $service_title ) : ?>
								<div class="our-work-gallery__title section-title section-title--style3">
									<h3>
										<?= esc_html( $service_title ) ?>
									</h3>
								</div>
							<?php endif; ?>

							<div class="swiper our-work-gallery__main js-our-work-gallery">
								<div class="swiper-wrapper">
									<?php foreach ( $service_gallery as $item ) : ?>
										<?php
										$service_current_gallery = $item['service_media_group'];
										$service_media = $service_current_gallery['service_media_type'];
										?>
										<div class="swiper-slide">
											<div class="our-work-gallery__main-item">
												<div class="background-img">
													<?php if ( $service_media['media_type'] == 'image' ) : ?>
														<picture>
															<source
																srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'full-hd' ) ) ?>"
																media="(min-width: 1280px)"
															>
															<source
																srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-tablet' ) ) ?>"
																media="(max-width: 1279px)"
															>
															<source
																srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-mobile' ) ) ?>"
																media="(max-width: 767px)"
															>
															<img
																src="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'full-hd' ) ) ?>"
																alt="<?= esc_attr( hmt_get_attachment_image_alt( $service_media['image'] ) ); ?>"
															>
														</picture>
													<?php elseif ( $service_media['media_type'] == 'video' ) : ?>
														<?php
														$service_background_video_poster = wp_get_attachment_image_url( $service_media['video_poster'], 'medium' );
														$service_background_video_url = wp_get_attachment_url( $service_media['video'] );
														?>
														<video
															disablePictureInPicture muted playsinline autoplay
															poster="<?= esc_url( $service_background_video_poster ) ?>"
															loop="loop"
														>
															<source
																src="<?= esc_url( $service_background_video_url ) ?>"
																type="video/mp4"
															>
														</video>
													<?php endif ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="swiper our-work-gallery__thumbs js-our-work-gallery-thumbs">
								<div class="swiper-wrapper">
									<?php foreach ( $service_gallery as $item ) : ?>
										<?php
										$service_current_gallery = $item['service_media_group'];
										$service_media = $service_current_gallery['service_media_type'];
										?>
										<div class="swiper-slide">
											<div
												class="our-work-gallery__thumbs-item <?= $service_media['media_type'] === 'video' ? 'our-work-gallery__thumbs-item--video' : ''; ?>"
											>
												<div class="background-img">
													<?= wp_get_attachment_image( $service_media['image'], 'section-background-desktop', false, ['alt' => $image_alt] ); ?>

													<?php if ( $service_media['media_type'] === 'video' ) : ?>
														<div class="icon icon-wrap">
															<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
														</div>
													<?php endif ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<?php if ( $service_description ) : ?>
								<div class="our-work-gallery__description text-content">
									<?= apply_filters( 'the_content', $service_description ) ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
<?php endforeach; ?>
