<?php
/**
 * Widget Form.
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
	$socials = get_field( 'social', 'option' );
	$title = get_field( 'widget_footer_menu_column_title' );
	$contact_form_id = get_field( 'widget_footer_contact_form_id' );
	$widget_footer_margins = get_field( 'widget_footer_margins' );
	?>
	<!--<div class="col-12 col-md-5 col-xl-4 col-xxm-5 footer-contact-form">-->
	<div class="col-12 col-md-auto footer-contact-form <?php if ( $widget_footer_margins['left_margin'] == 'auto' ) : ?>ml-auto<?php endif; ?> <?php if ( $widget_footer_margins['right_margin'] == 'auto' ) : ?>mr-auto<?php endif; ?>">
		<div class="widget widget--form">
			<?php if ( !empty( $title ) ) : ?>
				<div class="section-title section-title--style5 title">
					<h2>
						<?= esc_html( $title ) ?>
					</h2>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $contact_form_id ) ): ?>
				<div class="page-footer__email-form">
					<?= do_shortcode( "[ninja_form id={$contact_form_id}]" ) ?>
				</div>
			<?php endif ?>

			<?php if ( !empty( $socials ) && is_array( $socials ) ) : ?>
				<div class="page-footer__social">
					<?php foreach ( $socials as $social ) : ?>
						<?php extract( $social ) ?>
						<a href="<?= !empty( $link ) ? esc_url( $link ) : '' ?>" target="_blank" class="social-item">
							<?php if ( !empty( $icon ) ) : ?>
								<span class="icon icon-wrap">
									<?= hmt_get_svg_inline( wp_get_attachment_image_url( $icon ) ); ?>
								</span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif;

?>


