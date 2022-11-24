<?php
/**
 * @var $args
 */

$block_id = $args['block_id'];

// Action Buttons Props
$section_button = get_field( 'seo_content_section_button_group' );
$button_type = $section_button[ 'seo_content_section_button_type' ] ?? '';
$button_text = $section_button[ 'seo_content_section_button_text' ] ?? '';
$button_link = $section_button[ 'seo_content_section_button_link' ] ?? '';

// Section Props
$section_title = get_field( 'seo_content_section_title' );
$section_description = get_extended( get_field( 'seo_content_section_description' ) );
$section_content_image = get_field( 'seo_content_section_content_image' );

$section_media_block = get_field( 'seo_content_section_media_group' );

if($section_media_block){
	$section_media = $section_media_block['seo_content_section_media_type'] ?? '';
	$popup_video_type = $section_media_block['video_type'] ?? '';
	$popup_video_src = $section_media_block['video_file'] ?? '';
	$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $section_media_block['youtube_link'] );
	$popup_video_poster = $section_media_block['image_poster'] ?? '';
	$popup_id = wp_generate_uuid4();
	$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
}
?>

<?php
get_template_part( 'parts/resources/section-background', '', [
	'class_name' => 'section-seo-content__bg',
	'field_prefix' => 'seo_content_section_background',
	'field_id' => ''
] );
?>

<?php if ( $section_title && $section_description['main'] ) : ?>
	<div class="section__body">
        <div class="section-seo-content__content js-font-adjustment" data-fj-min="37">
            <div class="container">
                <div class="row justify-content-center justify-content-xl-start">
                    <div class="col-12 col-md-6">
                        <div class="seo-content__media">
							<?php if ( !empty( $section_media ) && isset( $section_media['media_type'] ) ) : ?>
								<div class="seo-content__media-picture background-img">
                                    <?php if ( $section_media['media_type'] === 'image' ) : ?>
	                                    <picture>
                                            <source
	                                            srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-tablet' ) ) ?>"
	                                            media="(min-width: 768px)"
                                            >
                                            <source
	                                            srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-mobile' ) ) ?>"
	                                            media="(max-width: 767px)"
                                            >
                                            <img
	                                            src="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-tablet' ) ); ?>"
	                                            alt="<?= esc_attr( hmt_get_attachment_image_alt( $section_media['image'] ) ) ?>"
                                            >
                                        </picture>
                                    <?php elseif ( $section_media['media_type'] === 'video' ) : ?>
	                                    <?php
	                                    $preview_video_url = wp_get_attachment_url( $section_media['video'] );
	                                    $preview_video_poster = wp_get_attachment_image_url( $section_media['video_poster'], 'full-hd' );
	                                    ?>
	                                    <video
											disablePictureInPicture
		                                    muted playsinline autoplay poster="<?= $preview_video_poster ?>" loop="loop"
	                                    >
                                            <source src="<?= $preview_video_url ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>
                                </div>
							<?php endif; ?>

	                        <?php if ( $popup_video_enabled ) : ?>
		                        <a
			                        href="#modal-video-seo-content-<?= esc_attr( $block_id ) ?>"
			                        class="button-play button-play--small" data-toggle="modal"
			                        aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>"
		                        >
									<span class="button-play__icon">
										<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
									</span>
								</a>
	                        <?php endif ?>
						</div>
					</div>

					<div class="col-12 col-md-6 align-self-center">
						<div class="seo-content__text-content">
							<div class="seo-content__title section-title section-title--style1">
								<h2 class="js-font-title">
									<?= esc_html( $section_title ); ?>
								</h2>
							</div>

							<div class="seo-content__text">
								<div class="seo-content__text-container">
									<?php
									if ( $button_type === 'show_more' && $section_description['extended'] ) {
										$description_class = 'visible';
									} else {
										$description_class = 'full';
									}
									?>
									<?php if ( $section_description ) : ?>
										<?php if ( $button_type !== 'show_more' && $section_description['extended'] ) : ?>
											<div class="seo-content__text-wrapper">
												<div class="seo-content__description text-content">
													<?= wpautop( $section_description['main'] ) ?>

													<?= wpautop( $section_description['extended'] ) ?>
												</div>
											</div>
										<?php elseif ( $button_type !== 'show_more' )  : ?>
											<div class="seo-content__text-wrapper">
												<div class="seo-content__description text-content">
													<?= wpautop( $section_description['main'] ) ?>
												</div>
											</div>
										<?php else : ?>
											<?php if ( $button_type && $button_type === 'show_more' ) : ?>
												<?php
												$collapse_id = wp_generate_uuid4();
												?>
												<div class="seo-content__description text-content">
													<?= wpautop( $section_description['main'] ) ?>
												</div>

												<div
													class="collapse seo-content__description-hidden"
													id="seo-content-collapse-<?= esc_attr( $collapse_id ) ?>"
												>
													<div class="collapse-content text-content">
														<?= wpautop( $section_description['extended'] ) ?>
													</div>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>

								<?php if ( $button_type ) : ?>
									<?php if ( $button_type === 'link' ) : ?>
										<?php if ( !empty( $button_link ) ) : ?>
											<?php
											$button_text = $button_link['title'] ? $button_link['title'] : '';
											$url = $button_link['url'] ? $button_link['url'] : '';
											$target = $button_link['target'] ? $button_link['target'] : '_self';
											$hash = '#';
											?>
											<div class="seo-content__button-wrapper">
												<a
													href="<?= esc_url( $url ); ?>" target="<?= esc_attr( $target ) ?>"
													class="button button-primary seo-content__button <?= (strpos( $url, $hash ) !== false) ? esc_attr( 'js-scroll-link' ) : ''; ?>"
												>
													<?= $button_text ? esc_html( $button_text ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
												</a>
											</div>
										<?php endif ?>
									<?php endif ?>

									<?php if ( $button_type === 'show_more' ) : ?>
										<?php if ( $section_description['extended'] && $button_text ) : ?>
											<div class="seo-content__button-wrapper">
												<button
													class="button button-primary seo-content__button collapsed"
													data-target="#seo-content-collapse-<?= esc_attr( $collapse_id ) ?>"
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
		</div>
	</div>

	<!-- Video Popup -->
	<?php
	if ( $popup_video_enabled ) {
		get_template_part(
			'template-parts/popups/popup',
			'video',
			[
				'popup_id' => "modal-video-seo-content-{$block_id}",
				'popup_video_youtube_player_id' => "modal-video-player-seo-content-{$block_id}",
				'popup_video_type' => $popup_video_type,
				'popup_video_poster' => $popup_video_poster,
				'popup_video_src_id' => $popup_video_src,
				'popup_video_youtube_id' => $popup_video_youtube_id,
			]
		);
	}
	?>
	<!-- End Video Popup -->
<?php endif; ?>
