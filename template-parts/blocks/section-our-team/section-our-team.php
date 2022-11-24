<?php
/**
 * Section Our Team Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$block_style = get_field( 'our_team_section_choose-variants' );

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'section section-our-team';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
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

$class_name .= ' section-our-team--style-' . $block_style;
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;
$section_title = get_field( 'our_team_section_title' );
$section_description = get_field( 'our_team_section_description' );
$our_team_group = (array)get_field( 'our_team_section_group' );

$is_content = $block_style === 'v1' && !empty( $section_title ) && $section_title && $section_description && !empty( $section_description ) ||
	$block_style === 'v2' && !empty( $section_title ) && $section_title && isset( $our_team_group[0] )
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( $is_content ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<?php if ( $block_style === 'v1' ) : ?>
			<?php get_template_part( 'template-parts/blocks/section-our-team/variants/section-our-team-v1', '', ['block_id' => $block['id']] ); ?>
		<?php elseif ( $block_style === 'v2' ) : ?>
			<?php get_template_part( 'template-parts/blocks/section-our-team/variants/section-our-team-v2', '', ['block_id' => $block['id']] ); ?>
		<?php endif; ?>
	</section>
<?php endif; ?>
