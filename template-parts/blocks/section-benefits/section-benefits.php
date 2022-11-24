<?php

/**
 * Section Benefits Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
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

$class_name = 'section section-benefits';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$section_title = get_field( 'benefits_section_title' );
$benefits = get_field( 'benefits_section_items' );
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !empty( $section_title ) && $section_title && $benefits ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">

		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-benefits__bg',
			'field_prefix' => 'benefits_section_background',
			'field_id' => ''
		] );
		?>

		<div class="section__body">
			<div class="section-benefits__content">
				<div class="container">
					<div class="section-benefits__header">
						<div class="section-title section-title--style1 section-benefits__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>

					<div class="section-benefits__main">
						<div class="row service-align js-horizontal-scroll">
							<?php if ( !empty( $benefits ) && is_array( $benefits ) ) : ?>
								<?php foreach ( $benefits as $benefit ) : ?>
									<?php extract( $benefit ) ?>
									<?php if ( !empty( $benefit_content_icon ) && !empty( $benefit_title) ): ?>
										<div class="col service-grid">
											<div class="service">
												<div class="service__main-content">
													<?php if ( !empty( $benefit_content_icon ) ) : ?>
														<?php if ( hmt_is_svg($benefit_content_icon) ) : ?>
															<?php
																$icon_url = wp_get_attachment_image_url( $benefit_content_icon );
															?>
															<div class="service__logo icon-wrap">
																<?= hmt_get_svg_inline( $icon_url ); ?>
															</div>
														<?php endif; ?>
													<?php endif; ?>

													<?php if ( !empty( $benefit_title ) ) : ?>
														<div class="service__title service__title--main section-title">
															<h3>
																<?= esc_html( $benefit_title ) ?>
															</h3>
														</div>
													<?php endif; ?>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
