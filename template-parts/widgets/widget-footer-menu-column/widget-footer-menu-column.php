<?php
/**
 * Widget Menu.
 *
 * @param array $block The block settings and attributes.
 *
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
	<?php
	$title = get_field( 'widget_footer_menu_column_title' );
	$menu_slug = get_field( 'widget_footer_menu' );
	$widget_footer_margins = get_field( 'widget_footer_margins' );
	?>
	<!--<div class="col-6 col-md-3 col-lg-2 ml-auto footer-menu-column">-->
	<div class="col-6 col-md-auto footer-menu-column <?php if ( $widget_footer_margins['left_margin'] == 'auto' ) : ?>ml-auto<?php endif; ?> <?php if ( $widget_footer_margins['right_margin'] == 'auto' ) : ?>mr-auto<?php endif; ?>">
		<div class="widget widget--links">
			<?php if ( !empty( $title ) ) : ?>
				<div class="section-title section-title--style5 title">
					<h2>
						<?= esc_html( $title ) ?>
					</h2>
				</div>
			<?php endif; ?>

			<?php
			$main_menu_args = array(
				'menu' => $menu_slug,
				'container' => false,
				'walker' => new HMT_Walker_Nav_Menu(),
			);
			wp_nav_menu( $main_menu_args );
			?>
		</div>
	</div>
<?php endif;

?>


