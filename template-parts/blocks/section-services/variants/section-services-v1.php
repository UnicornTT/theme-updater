<?php
/**
 * @var $args
 */

$block_id = $args['block_id'];
$services = $args['services'];

$section_title = get_field( 'services_section_title' );
?>

<?php if ( !empty( $services ) ) : ?>
    <div class="section__body">
        <div class="section-services__content">
            <div class="tab-content">
                <?php foreach ( $services as $key => $service ) : ?>
                    <?php
                        $service_title = $service->post_title;
                        $excerpt = wp_trim_words( get_the_excerpt( $service ), 40 );
                        $excerpt_custom = get_field( 'service_page_description', $service->ID );
                        $service_short_description = $excerpt ?: wp_trim_words( get_extended($excerpt_custom)['main'], 40 );
                        $service_gallery = get_field( 'service_gallery', $service->ID );
                        $service_media = $service_gallery[0]['service_media_group']['service_media_type'];
                        $service_current_gallery = $service_gallery[0]['service_media_group'];
                        $popup_video_type = $service_current_gallery['video_type'] ?? '';
                        $popup_video_src = $service_current_gallery['video_file'] ?? '';
                        $popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $service_current_gallery['youtube_link'] );
                        $popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
                    ?>
                    <div
						class="tab-pane fade js-initialize <?= $key == 0 ? 'show active js-initialize-active' : '' ?>"
						id="service-<?= esc_attr( $block_id . '-' . $key ) ?>"
						role="tabpanel"
						aria-labelledby="service-tab-<?= esc_attr( $block_id . '-' . $key ) ?>"
					>
                        <div class="service">
                            <div class="service__bg" aria-hidden="true">
                                <div class="background-img">
                                    <?php if ($service_media['media_type']): ?>
                                        <?php if ( $service_media['media_type'] == 'image' ) : ?>
                                            <picture>
                                                <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-4k' ) ) ?>" media="(min-width: 1920px)">
                                                <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" media="(min-width: 1280px)">
                                                <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-tablet' ) ) ?>" media="(max-width: 1279px)">
                                                <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
                                                <img src="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $service_media['image'] ) ); ?>">
                                            </picture>

                                        <?php elseif ( $service_media['media_type'] == 'video' ) : ?>
                                            <?php
                                                $service_background_video_poster = wp_get_attachment_image_url( $service_media['video_poster'], 'medium' );
                                                $service_background_video_url = wp_get_attachment_url( $service_media['video'] );
                                            ?>
                                            <video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( $service_background_video_poster ) ?>" loop="loop">
                                                <source src="<?= esc_url( $service_background_video_url ) ?>" type="video/mp4">
                                            </video>

                                        <?php endif ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="service__play">
                                <?php if ( $popup_video_enabled) : ?>
                                    <a href="#modal-video-services-<?= esc_attr( $block_id . '-' . $key ) ?>" class="button-play button-play--small" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>">
                                        <span class="button-play__icon">
                                            <?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
                                        </span>
                                    </a>
                                <?php endif ?>
                            </div>

                            <div class="service__content">
                                <?php if ( $section_title ) : ?>
                                    <div class="section-title section-title--style6 service__title">
                                        <h2>
                                            <?= esc_html( $section_title ) ?>
                                        </h2>
                                    </div>
                                <?php endif; ?>

                                <div class="service__main">
                                    <?php if ( $service_title ) : ?>
                                        <div class="section-title section-title--style3 service__title">
                                            <h3>
                                                <?= esc_html( $service_title ) ?>
                                            </h3>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $service_short_description ) : ?>
                                        <div class="scrollbar-outer">
                                            <div class="text-content service__description">
                                                <?= apply_filters( 'the_content', $service_short_description ) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="service__button-wrapper">
                                    <a href="<?= esc_url( get_permalink( $service->ID ) ) ?>" class="button button-bordered service__button" aria-label="<?= esc_attr( __( 'link-to-service', THEME_TEXTDOMAIN ) ) ?>">
                                        <?= esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="section-services__nav">
				<div class="scrollbar-outer">
					<ul class="section-services__tab nav" role="tablist">
						<?php foreach ( $services as $key => $service ) : ?>
							<?php
								$service_title = $service->post_title;
							?>
							<li class="nav-item">
								<a
									class="nav-link <?= esc_attr( $key == 0 ? 'active' : '' ) ?>"
									id="service-tab-<?= esc_attr( $block_id . '-' . $key ) ?>"
									href="#service-<?= esc_attr( $block_id . '-' . $key ) ?>"
									data-toggle="pill"
									role="tab"
									aria-controls="service-<?= esc_attr( $block_id . '-' . $key ) ?>"
									aria-selected="<?= $key == 0 ? 'true' : 'false' ?>"
									aria-label="<?= esc_attr( $service_title ) ?>"
								>
									<!-- Add class active and aria-selected="true" only for first item -->
									<?php if ( $service_title ) : ?>
										<span class="nav-text">
											<span class="text-content">
												<?= esc_html( $service_title ) ?>
											</span>
										</span>
									<?php endif; ?>

									<span class="icon icon-wrap">
										<?= hmt_get_svg_inline( wp_get_attachment_image_url( get_field( 'service_page_icon', $service->ID ) ) ); ?>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
            </div>
        </div>
    </div>
<?php endif; ?>
