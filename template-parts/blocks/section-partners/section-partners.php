<?php
/**
 * Section Logo Block Template.
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

$class_name = 'section section-partners';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$partners = get_field( 'partners' );

?>
<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>

	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>

<?php elseif( isset( $partners ) && is_array( $partners ) ) : ?>
	<section postId="<?= get_the_ID(); ?>" id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<div class="section-partners__content-wrapper">
			<div class="section-partners__content">
				<div class="slider-partners__wrap">
					<div class="slider-partners js-partners-infiniteslide">
						<?php foreach ( $partners as $key => $value ) : ?>
							<?php
							$link = $value['link'] ?? '';
							?>
							<?php if ( isset( $value['dark_theme_logo'] ) && isset( $value['light_theme_logo'] ) && $value['dark_theme_logo'] && $value['light_theme_logo'] ) : ?>
								<div class="slide">
									<?php if ( !empty( $link ) ) : ?>
										<?php
											$title = $link['title'] ?? '';
											$target = $link['target'] ?: '_self';
											$url = $link['url'] ?: '';
										?>
										<a
											href="<?= esc_url( $url ) ?>" class="slide-partner"
											target="<?= esc_attr( $target ) ?>"
											aria-label="<?= esc_attr( $title ) ?>"
										>
											<span class="slide-partner__inner">
												<?= wp_get_attachment_image( $value['dark_theme_logo'], 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $value['dark_theme_logo'] ) ), 'class' => 'light'] ); ?>
												<?= wp_get_attachment_image( $value['light_theme_logo'], 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $value['light_theme_logo'] ) ), 'class' => 'dark'] ); ?>
											</span>
										</a>
									<?php else : ?>
										<div class="slide-partner">
											<div class="slide-partner__inner">
												<?= wp_get_attachment_image( $value['dark_theme_logo'], 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $value['dark_theme_logo'] ) ), 'class' => 'light'] ); ?>
												<?= wp_get_attachment_image( $value['light_theme_logo'], 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $value['light_theme_logo'] ) ), 'class' => 'dark'] ); ?>
											</div>
										</div>

									<?php endif; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>