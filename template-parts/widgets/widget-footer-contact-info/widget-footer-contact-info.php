<?php
/**
 * Widget Contact Info.
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
	<?php
	$title = get_field( 'widget_footer_menu_column_title' );
	$phone_number = get_field( 'phone_number', 'option' );
	$address = get_field( 'address', 'option' );
	$address_link = get_field( 'address_link', 'option' );
	$working_hours = get_field( 'working_hours', 'option' );
	$socials = get_field( 'social', 'option' );
	$widget_footer_margins = get_field( 'widget_footer_margins' );
	?>
	<!--<div class="col-12 col-lg-3 footer-contact-info">-->
	<div class="col-12 col-md-auto footer-contact-info <?php if ( $widget_footer_margins['left_margin'] == 'auto' ) : ?>ml-auto<?php endif; ?> <?php if ( $widget_footer_margins['right_margin'] == 'auto' ) : ?>mr-auto<?php endif; ?>">
		<div class="widget widget--contact">
			<?php if ( !empty( $title ) ) : ?>
				<div class="section-title section-title--style5 title">
					<h2>
						<?= esc_html( $title ) ?>
					</h2>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $working_hours ) && $working_hours ): ?>
				<div class="page-footer__work-time">
					<span class="icon icon-wrap">
						<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-clock.svg' ); ?>
					</span>

					<ul class="list-container">
						<?php foreach ( $working_hours as $working_hour ) : ?>
							<?php extract( $working_hour ) ?>
							<li class="item">
								<?php if ( !empty( $days ) ) : ?>
									<span class="item-day">
										<?= esc_html( $days . ' ' ) ?>
									</span>
								<?php endif; ?>

								<?php if ( !empty( $hours ) ) : ?>
									<span class="item-time">
										<?= esc_html( $hours ) ?>
									</span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $address ) ): ?>
				<address class="page-footer__address">
					<span class="icon icon-wrap">
						<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-position-marker.svg' ); ?>
					</span>

					<?php if ( !empty( $address_link ) ) : ?>
						<a
							href="<?= esc_url( $address_link ) ?>" target="_blank" class="address-link"
							aria-label="link-address"
						>
							<?= esc_html( $address ) ?>
						</a>
					<?php endif; ?>
				</address>
			<?php endif; ?>

			<?php if ( !empty( $phone_number ) ): ?>
				<span class="page-footer__phone">
					<span class="icon icon-wrap">
						<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-phone-footer.svg' ); ?>
					</span>

					<a
						href="tel:<?= esc_attr( hmt_sanitize_phone_number_for_href( $phone_number ) ) ?>"
						class="phone-number"
					>
						<?= esc_html( $phone_number ) ?>
					</a>
				</span>
			<?php endif; ?>

			<?php if ( !empty( $socials ) && is_array( $socials ) ): ?>
				<div class="page-footer__social">
					<?php foreach ( $socials as $social ): ?>
						<?php
						extract( $social );
						$target = (!empty( $link ) && hmt_check_external_url( $link )) ? 'target="_blank"' : ''
						?>
						<a
							href="<?= !empty( $link ) ? esc_url( $link ) : 'javascript:void(0)' ?>" <?= esc_attr( $target ) ?>
							class="social-item"
						>
							<span class="icon icon-wrap">
								<?php if ( !empty( $icon ) ) : ?>
									<?= hmt_get_svg_inline( wp_get_attachment_image_url( $icon ) ); ?>
								<?php endif; ?>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif;

?>
