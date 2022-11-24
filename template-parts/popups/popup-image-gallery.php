<?php
/**
 * @var $args ;
 */

$gallery_list = $args['gallery_list'] ?? '';
?>
<?php foreach ( $gallery_list as $modal_id => $gallery ) : ?>
	<div class="modal fade modal-gallery-default" id="<?= esc_attr( $modal_id ) ?>" tabindex="-1" role="dialog"
	     aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-gallery">
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
					<div class="modal-gallery">
						<div class="swiper modal-gallery__main js-modal-gallery">
							<div class="swiper-wrapper">
								<?php if ( is_array( $gallery ) ): ?>
									<?php foreach ( $gallery as $image_id ) : ?>
										<?php
										$project_image_desktop_url = wp_get_attachment_image_url( $image_id, 'section-background-tablet' );
										$project_image_tablet_url = wp_get_attachment_image_url( $image_id, 'section-background-mobile' );
										$image_alt = hmt_get_attachment_image_alt( $image_id );
										?>
										<div class="swiper-slide">
											<div class="modal-gallery__item modal-gallery__item--img">
												<div class="background-img">
													<picture>
														<source
															srcset="<?= esc_url( $project_image_desktop_url ) ?>"
															media="(min-width: 1280px)"
														>
														<source
															srcset="<?= esc_url( $project_image_tablet_url ) ?>"
															media="(max-width: 1079px)"
														>
														<img
															src="<?= esc_url( $project_image_desktop_url ) ?>"
															alt="<?= esc_attr( $image_alt ) ?>"
														>
													</picture>
												</div>
											</div>
										</div>

									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="swiper-controls swiper-controls--fraction">
							<button class="swiper-button-prev">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</button>

							<button class="swiper-button-next">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</button>

							<div class="swiper-pagination"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
