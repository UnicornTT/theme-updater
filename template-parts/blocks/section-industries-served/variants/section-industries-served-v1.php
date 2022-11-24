<?php
/**
 * @var $args
 */

$id = $args['block']['id'];

$section_title = get_field( 'industries_served_section_title' );
$filter_posts_type = get_field( 'industries_served_filter_posts' );

if ( $filter_posts_type === 'all' ) {
	$items = get_posts( array(
		'post_type' => 'hmt_industry',
		'posts_per_page' => - 1,
	) );
} else {
	$items = get_field( 'industries_served_selected_posts_type' );
}
?>

<?php if ( $items && is_array( $items ) ) : ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-industries-served__bg',
		'field_prefix' => 'industries_served_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-industries-served__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-industries-served__header sr-only">
						<div class="section-title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>
				<?php endif; ?>

				<div class="section-industries-served__main">
					<div class="section-industries-served__nav">
						<?php if ( is_array( $items ) ) : ?>
							<ul class="list list--unstyled nav nav-list js-horizontal-scroll" role="tablist">
								<?php foreach ( $items as $key => $item ) : ?>
									<?php
									$id_part = $id . '-' . $key;
									?>
									<li class="nav-item" role="presentation">
										<a
											class="nav-link button-nav <?= $key == 0 ? 'active' : '' ?>"
											id="sis-tablink-<?= esc_attr( $id_part ) ?>"
											data-toggle="tab"
											href="#sis-tab-<?= esc_attr( $id_part ) ?>"
											role="tab"
											aria-controls="sis-tab-<?= esc_attr( $id_part ) ?>"
											aria-selected="true"
										>
											<span>
												<?= esc_html( $item->post_title ) ?>
											</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

					<div class="section-industries-served__tabs">
						<div class="tab-content">
							<?php if ( is_array( $items ) ) : ?>
								<?php foreach ( $items as $key => $item ) :
									$excerpt = wp_trim_words( get_the_excerpt( $item ), 75 );
									$excerpt_custom = get_field( 'industries_served_description', $item->ID );
									$short_description = $excerpt ?: wp_trim_words( get_extended($excerpt_custom)['main'], 75 );
									$media = get_field( 'industries_media_group', $item->ID );
									$media_background_type = $media['industries_media_media_type'] ?? '';
									$media_image = '';
									$id_attr = $id . '-' . $key;
									?>
									<div
										class="tab-pane fade <?php if ( $key == 0 ) echo 'show active' ?>"
										id="sis-tab-<?= esc_attr( $id_attr ) ?>" role="tabpanel"
										aria-labelledby="sis-tablink-<?= esc_attr( $id_attr ) ?>"
									>
										<div class="industries-card">
											<div class="industries-card__img">
												<div class="background-img">
													<?php if ( $media_background_type === 'image' ) :
														$media_image = $media['industries_media_image'] ?? '';
														?>
														<picture>
															<source
																srcset="<?= esc_url( wp_get_attachment_image_url( $media_image, 'section-background-tablet' ) ) ?>"
																media="(min-width: 768px)"
															>
															<source
																srcset="<?= esc_url( wp_get_attachment_image_url( $media_image, 'section-background-mobile' ) ) ?>"
																media="(max-width: 767px)"
															>
															<img
																src="<?= esc_url( wp_get_attachment_image_url( $media_image, 'section-background-desktop' ) ) ?>"
																alt="<?= esc_attr( hmt_get_attachment_image_alt( $media_image ) ) ?>"
															>
														</picture>
													<?php elseif ( $media_background_type === 'video' ) :
														$background_video_poster = wp_get_attachment_image_url( $media['industries_media_video_poster'], 'full' );
														$background_video_url = wp_get_attachment_url( $media['industries_media_video'] );
														?>
														<video
															disablePictureInPicture
															muted
															playsinline
															autoplay
															poster="<?= esc_url( $background_video_poster ) ?>"
															loop="loop"
														>
															<source
																src="<?= esc_url( $background_video_url ) ?>"
																type="video/mp4"
															>
														</video>
													<?php endif ?>
												</div>

												<?php if( !empty($media) && is_array($media) ): ?>
													<?php
													$popup_video_type = $media['industries_popup_video_video_type'] ?? '';
													$popup_video_poster = $media['industries_popup_video_image_poster'] ?? '';
													$popup_video_src = $media['industries_popup_video_video_file'] ?? '';
													$popup_video_youtube_id = isset( $media['industries_popup_video_youtube_link'] ) ?
														hmt_get_youtube_video_id_from_url( $media['industries_popup_video_youtube_link'] ) : '';

													$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id ||
														$popup_video_type === 'self_hosted' && $popup_video_src;
													if ( $popup_video_enabled ) :
														?>
														<a
															href="#modal-video-<?= esc_attr( $id_attr ) ?>"
															class="button-play button-play--small"
															data-toggle="modal"
															aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>"
														>
															<span class="button-play__icon">
																<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
															</span>
														</a>
													<?php endif ?>
												<?php endif; ?>
											</div>

											<div class="industries-card__content js-font-adjustment" data-fj-min="13">
												<?php if ( $section_title ) : ?>
													<div
														class="industries-card__subtitle section-title section-title--style6"
														aria-hidden="true"
													>
														<?= esc_html( $section_title ) ?>
													</div>
												<?php endif; ?>

												<?php if ( $item->post_title ) : ?>
													<div
														class="industries-card__title section-title section-title--style1"
													>
														<h3 class="js-font-title">
															<?= esc_html( $item->post_title ) ?>
														</h3>
													</div>
												<?php endif; ?>

												<div class="industries-card__description text-content">
													<?= apply_filters( 'the_content', $short_description ) ?>
												</div>

												<div class="industries-card__button-wrapper">
													<a
														href="<?= esc_url( get_permalink( $item->ID ) ) ?>"
														class="button button-bordered industries-card__button"
													>
														<?= esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ) ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	if ( is_array( $items ) ) {
		foreach ( $items as $key => $item ) {
			$id_attr = $id . '-' . $key;
			$media = get_field( 'industries_media_group', $item->ID );

			if( !empty($media) && is_array($media) ){
				$popup_video_type = $media['industries_popup_video_video_type'] ?? '';
				$popup_video_poster = $media['industries_popup_video_image_poster'] ?? '';
				$popup_video_src = $media['industries_popup_video_video_file'] ?? '';
				$popup_video_youtube_id = isset( $media['industries_popup_video_youtube_link'] ) ?
					hmt_get_youtube_video_id_from_url( $media['industries_popup_video_youtube_link'] ) : '';

				$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id ||
					$popup_video_type === 'self_hosted' && $popup_video_src;
				if ( $popup_video_enabled ) {
					get_template_part(
						'template-parts/popups/popup',
						'video',
						[
							'popup_id' => 'modal-video-' . $id_attr,
							'popup_video_youtube_player_id' => 'modal-video-player-' . $id_attr,
							'popup_video_type' => $popup_video_type,
							'popup_video_poster_id' => $popup_video_poster,
							'popup_video_src_id' => $popup_video_src,
							'popup_video_youtube_id' => $popup_video_youtube_id,
						]
					);
				}
			}
		}
	}
	?>
<?php endif; ?>