<?php
/**
 * Section Progression Chart Block Template.
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 * @var $block
 */

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$section_top_padding_type = get_field( 'section_top_padding_type' );
$section_bottom_padding_type = get_field( 'section_bottom_padding_type' );

if( $section_top_padding_type && !empty($section_top_padding_type) ) {
	$section_top_padding = 'section-top-padding--' . $section_top_padding_type;
} else {
	$section_top_padding = 'section-top-padding--default';
}
if ( $section_bottom_padding_type && !empty($section_bottom_padding_type) ) {
	$section_bottom_padding = 'section-bottom-padding--' . $section_bottom_padding_type;
} else {
	$section_bottom_padding = 'section-bottom-padding--default';
}

$class_name = 'section section-progression-chart';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block[''] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$section_title = get_field( 'progression_chart_section_title' );
$section_description = get_field( 'progression_chart_section_description' );

$progression_chart = get_field( 'progression_chart_faq' );

$button_type = get_field( 'progression_chart_section_action_button_type' );
$button_text = get_field( 'progression_chart_section_action_button_text' ) ?? __( 'Join Our Team', THEME_TEXTDOMAIN );

$popup_id = wp_generate_uuid4();

$section_media_block = get_field( 'progression_chart_media_group' );
if($section_media_block){
	$section_media = $section_media_block['progression_chart_media_type'] ?? '';

	$popup_video_type = $section_media_block['video_type'] ?? '';
	$popup_video_src = $section_media_block['video_file'] ?? '';
	$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $section_media_block['youtube_link'] );
	$popup_image_poster = $section_media_block['image_poster'] ?? '';
	$popup_id = wp_generate_uuid4();
	$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id ||
		$popup_video_type === 'self_hosted' && $popup_video_src;
}
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !empty( $section_title ) && $section_title && !empty( $section_description ) && $section_description ) : ?>
	<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class_name ); ?>">
		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-progression-chart__bg',
			'field_prefix' => 'progression_chart_section_background',
			'field_id' => ''
		] );
		?>

		<div class="section__body">
			<div class="section-progression-chart__content">
				<div class="container">
					<div class="section-progression-chart__content-wrapper">
						<div class="row justify-content-between">
							<div class="col-12 col-xl-6">
								<div class="section-progression-chart__main">
									<div class="section-progression-chart__header">
										<div
											class="section-title section-title--style1 section-progression-chart__title"
										>
											<h2>
												<?= esc_html( $section_title ) ?>
											</h2>
										</div>
										<div class="section__description section-progression-chart__description">
											<?= apply_filters( 'the_content', $section_description ) ?>
										</div>
									</div>

									<?php if( $section_media ): ?>
										<div class="section-progression-chart__media-container">
											<div class="section-progression-chart__media-wrapper">
												<div class="section-progression-chart__media">
													<?php if ( $section_media['media_type'] === 'image' ) : ?>
														<div class="background-img">
															<picture>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-desktop' ) ) ?>"
																	media="(min-width: 1025px)"
																>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-tablet' ) ) ?>"
																	media="(max-width: 1024px)"
																>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-mobile' ) ) ?>"
																	media="(max-width: 575px)"
																>
																<img
																	src="<?= esc_url( wp_get_attachment_image_url( $section_media['image'], 'section-background-desktop' ) ) ?>"
																	alt="<?= esc_attr( hmt_get_attachment_image_alt( $section_media['progression_chart_media_image'] ) ) ?>"
																>
															</picture>
														</div>
													<?php elseif ( $section_media['media_type'] === 'video' ) : ?>
														<div class="background-img">
															<?php
															$preview_video_url = wp_get_attachment_url( $section_media['video'] );
															$preview_video_poster = wp_get_attachment_image_url( $section_media['video_poster'], 'full-hd' );
															?>
															<video
																disablePictureInPicture
																muted playsinline autoplay
																poster="<?= $preview_video_poster ?>" loop="loop"
															>
																<source src="<?= esc_url($preview_video_url) ?>" type="video/mp4">
															</video>
														</div>
													<?php endif; ?>

													<?php if ( $popup_video_enabled ) : ?>
														<a
															href="#modal-video-progression-chart-<?= esc_attr( $popup_id ) ?>"
															class="button-play button-play--small" data-toggle="modal"
															aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>"
														>
															<span class="button-play__icon">
																<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
															</span>
														</a>
													<?php endif; ?>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<div class="col-12 col-xl-6">
								<?php if ( !empty( $progression_chart['progression_chart_section_faq'] ) && $progression_chart['progression_chart_section_faq'] ) : ?>
									<div class="section-progression-chart__accordion">
										<div class="faq-accordion">
											<div class="faq-accordion__body">
												<?php foreach ( $progression_chart['progression_chart_section_faq'] as $key => $value ) : ?>
													<?php extract( $value ); ?>
													<div class="faq-item <?= $key == 0 ? esc_attr( 'opened' ) : '' ?>">
														<div class="faq-item__header">
															<?php if ( isset( $progression_chart_title ) && $progression_chart_title ) : ?>
																<div class="faq-item__title section-title">
																	<h4>
																		<?= esc_html( $progression_chart_title ) ?>
																	</h4>
																</div>
															<?php endif; ?>

															<button
																class="faq-item__button js-faq-item-toggler"
																aria-label="<?= esc_attr( __( 'Toggle Question', THEME_TEXTDOMAIN ) ) ?>"
															>
																<span class="icon-wrap" aria-hidden="true">
																	<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-plus.svg' ); ?>
																</span>
															</button>
														</div>

														<?php if ( isset( $progression_chart_description ) && $progression_chart_description ) : ?>
															<div
																class="faq-item__body collapse <?= $key == 0 ? esc_attr( 'show' ) : '' ?>"
															>
																<div class="faq-item__answer text-content">
																	<div class="faq-item__answer-wrapper">
																		<?= apply_filters( 'the_content', $progression_chart_description ) ?>
																	</div>
																</div>
															</div>
														<?php endif; ?>
													</div>
												<?php endforeach; ?>
											</div>

											<?php if ( $button_type !== 'empty' ) : ?>
												<div class="faq-accordion__controls">
													<?php if ( $button_type == 'modal' ) : ?>
														<?php
															$button_modal = get_field( 'progression_chart_section_action_button_modal' ) ?? '';
															\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $button_modal );
														?>
														<div class="faq-accordion__button-wrapper">
															<a
																href="#<?= esc_attr( $button_modal ) ?>"
																data-toggle="modal"
																class="button button-bordered button-bordered-white-dark faq-accordion__button"
															>
																<?= esc_html( $button_text ) ?>
															</a>
														</div>

													<?php elseif ( $button_type == 'link' ) : ?>
														<?php
															$button_link = get_field( 'progression_chart_section_action_button_link' );
														?>
														<?php if ( !empty( $button_link ) ) : ?>
															<?php
																$button_text = $button_link['title'] ? $button_link['title'] : '';
																$url = $button_link['url'] ? $button_link['url'] : '';
																$target = $button_link['target'] ? $button_link['target'] : '_self';
															?>
															<div class="faq-accordion__button-wrapper">
																<a
																	href="<?= esc_url( $url ); ?>"
																	target="<?= esc_attr( $target ); ?>"
																	class="button button-bordered button-bordered-white-dark faq-accordion__button"
																>
																	<?= $button_text ? esc_html( $button_text ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
																</a>
															</div>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		if ( $popup_video_enabled ) {
			get_template_part(
				'template-parts/popups/popup',
				'video',
				[
					'popup_id' => 'modal-video-progression-chart-' . $popup_id,
					'popup_video_youtube_player_id' => 'modal-video-player-progression-chart-' . $popup_id,
					'popup_video_type' => $popup_video_type,
					'popup_video_poster' => $popup_image_poster,
					'popup_video_src_id' => $popup_video_src,
					'popup_video_youtube_id' => $popup_video_youtube_id,
				]
			);
		}
		?>
	</section>
<?php endif; ?>
