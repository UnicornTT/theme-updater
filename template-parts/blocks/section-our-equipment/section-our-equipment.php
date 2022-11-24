<?php
/**
 * Section Equipment.
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

$class_name = 'section section-our-equipment';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$equipment_section_title = get_field( 'equipment_section_title_key' );

$equipment_list = get_posts( array(
	'post_type' => 'hmt_equipment_item',
	'posts_per_page' => - 1,
) );

$filter_works_type = get_field( 'equipment_section_filter_posts' );

if ( $filter_works_type === 'latest' ) {
	$equipment_list = get_posts( array(
		'post_type' => 'hmt_equipment_item',
		'posts_per_page' => - 1,
	) );
} else {
	$equipment_list = get_field( 'equipment_section_selected_posts' );
}
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !empty( $equipment_list ) && is_array($equipment_list ) && !empty( $equipment_section_title ) && $equipment_section_title ) : ?>

	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">

		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-our-equipment__bg',
			'field_prefix' => 'equipment_section_background',
			'field_id' => ''
		] );
		?>

		<div class="section__body">
			<div class="section-our-equipment__content">
				<div class="container">
					<div class="section-our-equipment__header">
						<?php if ( $equipment_section_title ) : ?>
							<div class="section-title section-title--style1 section-our-equipment__title">
								<h2>
									<?= esc_html( $equipment_section_title ) ?>
								</h2>
							</div>
						<?php endif; ?>

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

					<?php if ( !empty( $equipment_list ) && $equipment_list ) : ?>
						<div class="swiper section-our-equipment__slider js-our-equipment-slider">
							<div class="swiper-wrapper">
								<?php $equipment_id = 0 ?>
								<?php foreach ( $equipment_list as $equipment ) : ?>
									<?php
									$equipment_image = get_field( 'equipment_image', $equipment->ID );
									$equipment_header = get_the_title( $equipment->ID );
									$equipment_description = get_field( 'equipment_description', $equipment->ID );
									$unique_equipment_id = $id . '-' . $equipment_id ++;
									?>
									<div class="swiper-slide">
										<div class="equipment-card" tabindex="0">
											<div class="equipment-card__img">
												<?php if ( $equipment_image ) : ?>
													<div class="background-img">
														<picture>
															<img
																src="<?= esc_url( wp_get_attachment_image_url( $equipment_image, 'section-background-mobile' ) ) ?>"
																alt="<?= esc_attr( hmt_get_attachment_image_alt( $equipment_image ) ) ?>"
															>
														</picture>
													</div>
												<?php endif; ?>
											</div>

											<?php if ( $equipment_header ) : ?>
												<div
													class="equipment-card__title equipment-card__title--main section-title section-title--style5"
												>
													<h3>
														<?= esc_html( $equipment_header ) ?>
													</h3>
												</div>
											<?php endif; ?>

											<div class="equipment-card__full">
												<div class="equipment-card__full-body">
													<div class="scrollbar-outer">
														<div class="equipment-card__full-content">
															<?php if ( $equipment_header ) : ?>
																<div
																	class="equipment-card__title section-title section-title--style3"
																>
																	<h3>
																		<?= esc_html( $equipment_header ) ?>
																	</h3>
																</div>
															<?php endif; ?>

															<div class="equipment-card__description text-content">
																<?= apply_filters( 'the_content', $equipment_description ) ?>
															</div>
														</div>
													</div>

													<div class="equipment-card__button-wrapper">
														<a
															class="button button-bordered button-bordered-white equipment-card__button"
															role="button" data-toggle="modal"
															href="#modal-equipment-card-<?= esc_attr( $unique_equipment_id ) ?>"
														>
															<?= esc_html( __( 'View More', THEME_TEXTDOMAIN ) ) ?>
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php if ( !empty( $equipment_list ) && is_array( $equipment_list ) ) : ?>
			<?php $equipment_id = 0 ?>
			<?php foreach ( $equipment_list as $equipment ) : ?>
				<?php

				$unique_equipment_id = $id . '-' . $equipment_id ++;
				$section_media_block = get_field( 'equipment_media_group', $equipment->ID );
				if($section_media_block){
					$section_popup = $section_media_block['equipment_popup_video'] ?? '';
					$popup_video_type = $section_popup['video_type'] ?? '';
					$popup_video_src = $section_popup['video_file'] ?? '';
					$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $section_popup['youtube_link'] );
					$popup_image_poster = $section_popup['image_poster'] ?? '';

					$popup_id = wp_generate_uuid4();

					$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id ||
						$popup_video_type === 'self_hosted' && $popup_video_src;
				}
				?>
				<!-- Check if equipment has video -->
				<?php if ( $popup_video_enabled ) : ?>
					<?php
					get_template_part(
						'template-parts/blocks/section-our-equipment/popups/section-our-equipment-video',
						'popup',
						[
							'popup_id' => 'modal-video-equipment-' . $unique_equipment_id,
							'popup_video_youtube_player_id' => 'modal-video-player-equipment-' . $unique_equipment_id,
							'popup_video_type' => $popup_video_type,
							'popup_video_poster' => $popup_image_poster,
							'popup_video_src' => $popup_video_src,
							'popup_video_youtube_id' => $popup_video_youtube_id,
						]
					);
					?>
				<?php endif; ?>

				<?php get_template_part(
					'template-parts/blocks/section-our-equipment/popups/section-our-equipment',
					'popup',
					[
						'popup_id' => 'modal-equipment-card-' . $unique_equipment_id,
						'popup_video_enabled' => $popup_video_enabled,
						'popup_video_id' => 'modal-video-equipment-' . $unique_equipment_id,
						'popup_video_poster' => $popup_image_poster,
						'equipment' => $equipment,
					]
				)
				?>
			<?php endforeach; ?>
		<?php endif; ?>
	</section>
<?php endif; ?>
