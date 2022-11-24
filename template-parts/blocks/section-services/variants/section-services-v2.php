<?php
/**
 * @var $args
 */

$block_id = $args['block_id'];
$services = array_slice( $args['services'], 0, 8 );

$section_title = get_field( 'services_section_title' );
?>

<?php if ( !empty( $services ) ) : ?>
    <?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-services__bg',
			'field_prefix' => 'services_section',
			'field_id' => ''
		] );
    ?>

    <div class="section__body">
        <div class="section-services__content">
            <div class="container">
                <div class="section-services__header">
                    <div class="section-title section-title--style1 section-services__title">
                        <h2>
                            <?= esc_html( $section_title ) ?>
                        </h2>
                    </div>
                </div>

                <div class="section-services__main <?= count( $services ) <= 8 ? 'horizontal' : ''; ?> ">
                    <div class="section-services__services-accordeon accordeon <?= count( $services ) <= 8 ? 'accordeon--horizontal' : ''; ?> ">
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
                            <div class="service js-service-accordeon-item">
                                <div class="service__wrapper">
                                    <div class="service__spine <?= $key == 0 ? 'active' : '' ?>">
                                        <div class="service__bg service__spine-bg">
                                            <?php if ($service_media['media_type']): ?>
                                                <div class="background-img">
                                                    <?php
														$background_image = '';

														if ( $service_media['media_type'] == 'image' )
                                                            $background_image = $service_media['image'];
                                                        elseif ( $service_media['media_type'] == 'video' )
                                                            $background_image = $service_media['video_poster'];
                                                    ?>
                                                    <?php if ( $background_image ) : ?>
                                                        <picture>
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-4k' ) ) ?>" media="(min-width: 1280px)">
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-desktop' ) ) ?>" media="(max-width: 1279px)">
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-tablet' ) ) ?>" media="(max-width: 1024px)">
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-mobile' ) ) ?>" media="(max-width: 575px)">
                                                            <img src="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $background_image ) ); ?>">
                                                        </picture>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ( $service_title ) : ?>
                                            <span class="service__name">
                                                <?= esc_html( $service_title ) ?>
                                            </span>
                                        <?php endif; ?>

                                        <span class="service__index"></span>
                                    </div>

                                    <div class="service__info <?= $key == 0 ? 'open active' : ''; ?>">
                                        <div class="service__bg">
                                            <?php if ( isset( $service_current_gallery ) ) : ?>
                                                <div class="background-img">
                                                    <?php if ( $service_media['media_type'] == 'image' ) : ?>
                                                        <picture>
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-4k' ) ) ?>" media="(min-width: 1280px)">
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-desktop' ) ) ?>" media="(max-width: 1279px)">
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-tablet' ) ) ?>" media="(max-width: 1024px)">
                                                            <source srcset="<?= esc_url( wp_get_attachment_image_url( $service_media['image'], 'section-background-mobile' ) ) ?>" media="(max-width: 575px)">
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
                                                </div>
                                            <?php endif ?>
                                        </div>

                                        <div class="service__content-wrapper">
                                            <div class="service__content">
                                                <div class="service__index-bg"></div>

                                                <div class="service__body <?= $key == 0 ? 'active' : ''; ?>">
                                                    <?php if ( $service_title ) : ?>
                                                        <h3 class="service__content-title">
                                                            <?= esc_html( $service_title ) ?>
                                                        </h3>
                                                    <?php endif; ?>

                                                    <?php if ( $service_short_description ) : ?>
                                                        <div class="service__excerpt text-content">
															<?= esc_html( $service_short_description ) ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <a href="<?= esc_url( get_permalink( $service->ID ) ) ?>" class="service__content-button button button-bordered button-bordered-white">
                                                        <span>
                                                            <?= esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ) ?>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>

											<div class="service__play-button-wrapper">
												<?php if ( $popup_video_enabled ) : ?>
													<a href="#modal-video-services-<?= esc_attr( $block_id . '-' . $key ) ?>" class="button-play button-play--small" data-toggle="modal" aria-label="<?= esc_html( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>">
														<span class="button-play__icon">
															<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
														</span>
													</a>
												<?php endif; ?>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>