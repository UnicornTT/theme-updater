<?php
/**
 * Section Oen Position Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$block_style = get_field( 'open_position_section_choose-variants' );

$id = $block[ 'id' ];
if ( !empty( $block[ 'anchor' ] ) ) {
    $id = $block[ 'anchor' ];
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

$class_name = 'section section-open-position';
if ( !empty( $block[ 'className' ] ) ) {
    $class_name .= ' ' . $block[ 'className' ];
}
if ( !empty( $block[ 'align' ] ) ) {
    $class_name .= ' align' . $block[ 'align' ];
}

$class_name .= ' section-open-position--style-' . $block_style;
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

$section_title = get_field( 'open_position_section_title' );
$filter_vacancies_type = get_field( 'vacancy_filter' );
$vacancies_list = '';

if ( $filter_vacancies_type === 'all' ) {
	$vacancies_list = get_posts( array(
		'post_type' => 'hmt_vacancy',
		'posts_per_page' => - 1,
	) );
} else {
	$vacancies_list = get_field( 'select_vacancies' );
}

?>


<?php if ( isset( $block[ 'data' ][ 'block_preview_images' ] ) ) : ?>
    <?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', [ 'block' => $block ] ); ?>
<?php elseif ( $vacancies_list && !empty( $vacancies_list ) && !empty( $section_title ) && $section_title ) : ?>
    <section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
        <?php if ( $block_style == 'v1' ) : ?>
            <?= get_template_part( 'template-parts/blocks/section-open-position/variants/section-open-position-v1', '', [ 'blockId' => $id, 'vacancies_list' => $vacancies_list, 'isPreview' => $is_preview ] ); ?>
        <?php elseif ( $block_style == 'v2' ) : ?>
            <?= get_template_part( 'template-parts/blocks/section-open-position/variants/section-open-position-v2', '', [ 'blockId' => $id, 'vacancies_list' => $vacancies_list, 'isPreview' => $is_preview ] ); ?>
        <?php endif; ?>
    </section>

	<?php
		\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( MODAL_OPEN_POSITION );
		\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( MODAL_JOB_APPLICATION );
	?>
<?php endif; ?>