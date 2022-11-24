<?php
/**
 * Widget Logo and Button.
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

$home_url = get_home_url();

$action_button_type = get_field( 'footer_section_action_button_type' );
$action_button_text = get_field( 'footer_section_action_button_text' );
$widget_footer_margins = get_field( 'widget_footer_margins' );

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php else : ?>
	<!--<div class="col-12 col-md-4 col-xl-3 mr-auto footer-contact-link">-->
	<div class="col-12 col-md-auto footer-contact-link <?php if ( $widget_footer_margins['left_margin'] == 'auto' ) : ?>ml-auto<?php endif; ?> <?php if ( $widget_footer_margins['right_margin'] == 'auto' ) : ?>mr-auto<?php endif; ?>">
		<div class="widget widget--logo">
			<a
				href="<?= esc_url( $home_url ) ?>" class="logo-link"
				aria-label="<?= esc_attr( __( 'link-to-home', THEME_TEXTDOMAIN ) ) ?>"
			>
				<?php
				$logo_footer_type = get_field( 'logo_variation', 'option' );
				?>
				<?php if ( $logo_footer_type === 'single' ) : ?>
					<?php
					$logo_footer_id = get_field( 'logo_header', 'option' );
					?>
					<?php if ( $logo_footer_id ) : ?>
						<span class="logo-container">
							<?php if ( hmt_is_svg_image( $logo_footer_id ) ) : ?>
								<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_footer_id ) ); ?>
							<?php else : ?>
								<?= wp_get_attachment_image( $logo_footer_id, 'section-background-desktop', false, ['alt' => esc_attr( __( 'Logo', THEME_TEXTDOMAIN ) )] ); ?>
							<?php endif ?>
						</span>
					<?php endif; ?>
				<?php elseif ( $logo_footer_type === 'multiple' ) : ?>
					<?php
					$logo_footer_dark_id = get_field( 'logo_header_single_dark', 'option' );
					$logo_footer_Light_id = get_field( 'logo_header_single_light', 'option' );
					?>
					<?php if ( $logo_footer_dark_id && $logo_footer_Light_id ) : ?>
						<span class="logo-container dark">
							<?php if ( hmt_is_svg_image( $logo_footer_dark_id ) ) : ?>
								<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_footer_dark_id ) ); ?>
							<?php else : ?>
								<?= wp_get_attachment_image( $logo_footer_dark_id, 'section-background-desktop', false, ['alt' => esc_attr( __( 'Logo Dark', THEME_TEXTDOMAIN ) )] ); ?>
							<?php endif ?>
						</span>
						<span class="logo-container light">
							<?php if ( hmt_is_svg_image( $logo_footer_Light_id ) ) : ?>
								<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_footer_Light_id ) ); ?>
							<?php else : ?>
								<?= wp_get_attachment_image( $logo_footer_Light_id, 'section-background-desktop', false, ['alt' => esc_attr( __( 'Logo Light', THEME_TEXTDOMAIN ) )] ); ?>
							<?php endif ?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</a>
			<div class="button-wrapper">
				<?php if ( 'link' === $action_button_type ) : ?>
					<?php
					$button_link = get_field( 'footer_section_action_button_link' );
					?>
					<?php if ( !empty( $button_link ) ) : ?>
						<?php
							$button_title = $button_link['title'] ? $button_link['title'] : '';
							$url = $button_link['url'] ? $button_link['url'] : '';
							$target = $button_link['target'] ? $button_link['target'] : '_self';
						?>
						<a
							href="<?= esc_url( $url ) ?>"
							target="<?= esc_attr( $target ); ?>"
							class="button button-bordered page-footer__button"
							aria-label="<?= esc_attr( $button_title ) ?>"
						>
							<?= esc_html( $button_title ) ?>
						</a>
					<?php endif; ?>
				<?php elseif ( 'modal' === $action_button_type ) : ?>
					<?php
						$action_button_modal = get_field( 'footer_section_action_button_modal' ) ?? '';
						\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $action_button_modal );
					?>
					<a
						href="#<?= esc_html( $action_button_modal ) ?>"
						data-toggle="modal"
						class="button button-bordered page-footer__button"
						aria-label="<?= esc_attr( __( 'Open Form', THEME_TEXTDOMAIN ) ) ?>"
					>
						<?= esc_attr( $action_button_text ) ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>