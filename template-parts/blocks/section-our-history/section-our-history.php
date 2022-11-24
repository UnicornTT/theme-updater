<?php
/**
 * Section Our History.
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

$class_name = 'section section-our-history';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$section_title = get_field( 'our_history_section_title' );
$sorting = get_field( 'our_history_sorting' );

if ( $sorting == 'descending' ) {
	$history_list = hmt_array_sort( get_field( 'our_history_section_history' ), 'year', SORT_DESC );
} elseif ( $sorting == 'ascending' ) {
	$history_list = hmt_array_sort( get_field( 'our_history_section_history' ), 'year', SORT_ASC );
} else {
	$history_list = get_field( 'our_history_section_history' );
}
if(is_array($history_list)){
	$has_year = $history_list[0]['year'];
}
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( $section_title && $history_list && $has_year ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<div class="section__body">
			<div class="container">
				<div class="section-our-history__content">
					<?php if ( $section_title ) : ?>
						<div class="sr-only">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					<?php endif; ?>

					<?php if ( is_array( $history_list ) ) : ?>
						<div class="section-our-history__slider-wrapper">
							<div class="swiper swiper-container section-our-history__slider js-our-history-slider">
								<div class="swiper-wrapper">
									<?php foreach ( $history_list as $history ): ?>
										<?php if( $history['image'] && $history['year'] && $history['description'] ): ?>
											<div class="swiper-slide">
												<div class="our-history-card">
													<div class="our-history-card__media">
														<div class="background-img">
															<picture>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $history['image'], 'section-background-4k' ) ) ?>"
																	media="(min-width: 1920px)"
																>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $history['image'], 'section-background-desktop' ) ) ?>"
																	media="(min-width: 1280px)"
																>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $history['image'], 'section-background-tablet' ) ) ?>"
																	media="(max-width: 1279px)"
																>
																<source
																	srcset="<?= esc_url( wp_get_attachment_image_url( $history['image'], 'section-background-mobile' ) ) ?>"
																	media="(max-width: 767px)"
																>
																<img
																	src="<?= esc_url( wp_get_attachment_image_url( $history['image'], 'section-background-desktop' ) ); ?>"
																	alt="<?= esc_attr( hmt_get_attachment_image_alt( $history['image'] ) ); ?>"
																>
															</picture>
														</div>
													</div>

													<div class="our-history-card__body">
														<?php if ( $section_title ) : ?>
															<div
																class="our-history-card__subtitle section-title section-title--style6"
																aria-hidden="true"
															>
																<?= esc_html( $section_title ); ?>
															</div>
														<?php endif; ?>

														<?php if ( $history['year'] ) : ?>
															<div
																class="our-history-card__title section-title section-title--style1"
															>
																<h3>
																	<?= esc_html( $history['year'] ); ?>
																</h3>
															</div>
														<?php endif; ?>

														<?php if ( $history['description'] ) : ?>
															<div class="scrollbar-outer">
																<div class="our-history-card__description text-content">
																	<?= wpautop( $history['description'] ); ?>
																</div>
															</div>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>

						<div class="section-our-history__nav">
							<button class="swiper-button-prev">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</button>

							<button class="swiper-button-next">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</button>

							<div class="swiper swiper-container slider-rolldate js-slider-rolldate">
								<div class="swiper-wrapper">
									<?php foreach ( $history_list as $key => $history ): ?>
										<div class="swiper-slide">
											<div class="rolldate-year">
											<span class="rolldate-year__text">
												<?= esc_html( $history['year'] ); ?>
											</span>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
