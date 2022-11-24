<?php
/**
 * Widget Search.
 * @param array $block The block settings and attributes.
 * @var $block
 */


$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = '';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php else : ?>
	<div class="widget widget_search widget--search">
		<form id="searchform" method="get" action="<?= esc_url( get_home_url() ) ?>">
			<div>
				<label id="s-nf-label-field" class="">
					<?= esc_html( __( 'Search', THEME_TEXTDOMAIN ) ) ?>
				</label>

				<input
					id="s-nf-field" type="text" class="search-field" name="s"
					placeholder="<?= esc_attr( __( 'Search', THEME_TEXTDOMAIN ) ); ?>" value=""
					aria-label="<?= esc_attr( __( 'Search', THEME_TEXTDOMAIN ) ) ?>" data-uw-rm-form="fx"
				>

				<button
					type="submit" value="<?= esc_html( __( 'Search', THEME_TEXTDOMAIN ) ) ?>"
					data-uw-rm-form="submit" aria-label="button" data-uw-rm-empty-ctrl=""
				>
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-resources-search.svg' ); ?>
				</button>
			</div>
		</form>
	</div>
<?php endif; ?>


