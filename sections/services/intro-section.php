<?php

$query_id = get_queried_object_id();
$service_header = get_the_title( $query_id );
$service_content = get_field( 'service_page_description', $query_id );

$service_gallery = get_field( 'service_gallery', $query_id );

$background_type = get_field( 'service_page_background_bg_type' );

$button_type = get_field( 'service_action_button_type' );
$button_text = get_field( 'service_action_button_text' ) ?? __( 'Contact Us', THEME_TEXTDOMAIN );
?>

<section class="section section-service-intro alignfull">
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-service-intro__bg',
		'field_prefix' => 'service_page_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="container">
			<div class="section-service-intro__content">
				<div class="section-service-intro__header">
					<?php if ( !empty( $service_gallery ) && count( $service_gallery ) ): ?>
						<?php if ( count( $service_gallery ) == 1 ) : ?>
							<?php $service_media = $service_gallery[0]['service_media_group']['service_media_type']; ?>
								<div class="section-service-intro__image">
									<div class="background-img">
										<?php if ( $service_media['media_type'] == 'image' ) : ?>
											<picture>
												<source srcset="<?= esc_html( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" media="(min-width: 1280px)">
												<source srcset="<?= esc_html( wp_get_attachment_image_url( $service_media['image'], 'section-background-tablet' ) ) ?>" media="(max-width: 1279px)">
												<source srcset="<?= esc_html( wp_get_attachment_image_url( $service_media['image'], 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
												<img src="<?= esc_html( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $service_media['image'] ) ) ?>">
											</picture>
										<?php elseif ( $service_media['media_type'] == 'video' ) : ?>
											<?php
												$preview_video_url = wp_get_attachment_url( $service_media['video'] );
												$preview_video_poster = wp_get_attachment_image_url( $service_media['video_poster'], 'full-hd' );
											?>
											<video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( $preview_video_poster ) ?>" loop="loop">
												<source src="<?= esc_url( $preview_video_url ) ?>" type="video/mp4">
											</video>
										<?php endif; ?>
									</div>
									<?php
										$service_media = $service_gallery[0]['service_media_group'];
										$popup_video_type = $service_media['video_type'] ?? '';
										$popup_video_src = $service_media['video_file'] ?? '';
										$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $service_media['youtube_link'] );
										$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
									?>
									<?php if ( $popup_video_enabled ) : ?>
										<a href="#modal-video-service-intro-0" class="button-play button-play--small" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>">
											<span class="button-play__icon">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
											</span>
										</a>
									<?php endif; ?>
								</div>
						<?php else : ?>
							<div class="section-service-intro__gallery-wrapper">
								<div class="swiper section-service-intro__gallery js-section-service-intro">
									<div class="swiper-wrapper">
										<?php foreach ( $service_gallery as $i => $item_gallery ) : ?>
											<?php $service_media = $item_gallery['service_media_group']['service_media_type']; ?>
											<div class="swiper-slide">
												<div class="section-service-intro__gallery-item">
													<div class="background-img">
														<?php if ( $service_media['media_type'] == 'image' ) : ?>
															<picture>
																<source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" media="(min-width: 1025px)">
																<source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-tablet' ) ) ?>" media="(max-width: 1024px)">
																<source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-mobile' ) ) ?>" media="(max-width: 575px)">
																<img src="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $item_gallery['service_media_group']['service_media_image'] ) ) ?>">
															</picture>
														<?php elseif ( $service_media['media_type'] == 'video' ) : ?>
															<?php
																$preview_video_url = wp_get_attachment_url( $service_media['video'] );
																$preview_video_poster = wp_get_attachment_image_url( $service_media['video_poster'], 'full-hd' );
															?>
															<video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( $preview_video_poster ) ?>" loop="loop">
																<source src="<?= esc_url( $preview_video_url ) ?>" type="video/mp4">
															</video>
														<?php endif; ?>
													</div>

													<?php
														$service_media = $item_gallery['service_media_group'];
														$popup_video_type = $service_media['video_type'] ?? '';
														$popup_video_src = $service_media['video_file'] ?? '';
														$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $service_media['youtube_link'] );
														$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
													?>
													<?php if ( $popup_video_enabled ) : ?>
														<a href="#modal-video-service-intro-<?= $i ?>" class="button-play button-play--small" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>">
															<span class="button-play__icon">
																<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
															</span>
														</a>
													<?php endif; ?>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<div class="swiper section-service-intro__gallery-thumbs js-section-service-intro-thumbs">
									<div class="swiper-wrapper">
										<?php foreach ( $service_gallery as $index => $item_gallery ): ?>
											<?php $service_media = $item_gallery['service_media_group']['service_media_type']; ?>
											<?php 
												if ( $service_media['media_type'] == 'image' ){
													$service_image = $service_media['image'];
												}elseif($service_media['media_type'] == 'video'){
													$service_image = $service_media['video_poster'];
												}
											?>
												<div class="swiper-slide" data-slide-index="<?= esc_attr( $index ) ?>">
													<div class="section-service-intro__gallery-thumbs-item">
														<div class="background-img">
															<?= wp_get_attachment_image( $service_image, 'thumbs-desktop', false, ['alt' => esc_html( __( 'logo', THEME_TEXTDOMAIN ) )] ); ?>
															<?php
																$service_media = $item_gallery['service_media_group'];
																$popup_video_type = $service_media['video_type'] ?? '';
																$popup_video_src = $service_media['video_file'] ?? '';
																$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $service_media['youtube_link'] );
																$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
															?>
															<?php if ( $popup_video_enabled ) : ?>
																<a class="button-play button-play--small">
																	<span class="button-play__icon">
																		<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
																	</span>
																</a>
															<?php endif; ?>
														</div>
													</div>
												</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<div class="section-service-intro__main">
					<div class="section-title section-title--style6 section-service-intro__subtitle">
						<h2>
							<?= esc_html( __( 'Service page', THEME_TEXTDOMAIN ) ) ?>
						</h2>
					</div>

					<?php if ( $service_header ) : ?>
						<div class="section-title section-title--style1 section-service-intro__title">
							<h1>
								<?= esc_html( $service_header ) ?>
							</h1>
						</div>
					<?php endif; ?>

					<?php if ( !empty( $service_content ) ) : ?>
						<div class="section-service-intro__text">
							<div class="text-content">
								<?= wpautop( $service_content ) ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="section-service-intro__footer">
					<?php if ( $button_type !== 'empty' ) : ?>
						<?php if ( $button_type == 'modal' ) : ?>
							<?php
								$button_modal = get_field( 'service_action_button_modal' ) ?? '';
								\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $button_modal );
							?>
							<a href="#<?= esc_attr( $button_modal ) ?>" data-toggle="modal" class="button button-primary section-service-intro__button">
								<?= esc_html( $button_text ) ?>
							</a>

						<?php elseif ( $button_type == 'link' ) : ?>
							<?php
								$button_link = get_field( 'service_action_button_link' );
							?>
							<?php if ( !empty( $button_link ) ) : ?>
								<?php
									$button_text = $button_link['title'] ? $button_link['title'] : '';
									$url = $button_link['url'] ? $button_link['url'] : '';
									$target = $button_link['target'] ? $button_link['target'] : '_self';
									$hash = '#';
								?>
								<a href="<?= esc_url( $url ); ?>" target="<?= esc_attr( $target ); ?>" class="button button-primary section-service-intro__button <?= (strpos( $url, $hash ) !== false) ? esc_attr( 'js-scroll-link' ) : ''; ?>">
									<?= $button_text ? esc_html( $button_text ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Video popups -->
	<?php if ( !empty( $service_gallery ) && count( $service_gallery ) ): ?>
		<?php foreach ( $service_gallery as $i => $item_gallery ): ?>
				<?php
				$service_media = $item_gallery['service_media_group'];
				$popup_video_type = $service_media['video_type'] ?? '';
				$popup_video_src = $service_media['video_file'] ?? '';
				$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $service_media['youtube_link'] );
				$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;

				$popup_video_enabled = false;

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

				if ( $popup_video_enabled ) {
					get_template_part(
						'template-parts/popups/popup',
						'video',
						[
							'popup_id' => 'modal-video-service-intro-' . $i,
							'popup_video_youtube_player_id' => 'modal-video-player-service-intro' . $i,
							'popup_video_type' => $popup_video_type,
							'popup_video_poster_id' => $popup_video_poster,
							'popup_video_src_id' => $popup_video_src,
							'popup_video_youtube_id' => $popup_video_youtube_id,
						]
					);
				}
				?>
		<?php endforeach; ?>
	<?php endif; ?>
</section>