<?php
/**
 * Section Intro Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$block_style = get_field( 'hero_section_choose_variants' );

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'section section-intro';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$class_name .= ' section-intro--style-' . $block_style;

$section_title = get_field( 'hero_section_title' );
$section_description = get_field( 'hero_section_description' );
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !!$section_title || !!$section_description ) : ?>
<?php
$parts_variants = ['v1', 'v2', 'v3'];
?>
	<?php if ( in_array( $block_style, $parts_variants ) ) : ?>
		<?php if ( !empty( $section_title ) && $section_title ) : ?>
			<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
				<?php get_template_part( 'template-parts/blocks/section-intro/variants/section-intro-' . $block_style ); ?>
			</section>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
