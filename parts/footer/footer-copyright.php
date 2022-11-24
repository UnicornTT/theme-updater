<?php
$logo_footer_id = get_field( 'logo_header', 'option' );
?>

<div class="page-footer__copyright-container">
	<div class="block-left">
		<?php foreach ( wp_get_nav_menu_items('copyright-menu') as $key => $item ) : ?>

			<a href="<?= $item->url ?>" class="item-sitemap" aria-label="<?= esc_attr( __( 'link-sitemap', THEME_TEXTDOMAIN ) ) ?>">
				<?= esc_html( __(  $item->title, THEME_TEXTDOMAIN ) ) ?>
			</a>

		<?php endforeach; ?>
	</div>

	<div class="block-right">
		<div class="item-copyright">
			<?= esc_html( date( "Y" ) . __( ' © Harbinger Marketing', THEME_TEXTDOMAIN ) ) ?>
		</div>

		<div class="item-design">
			<span class="text">
				<?= esc_html( __( 'Design & Marketed by', THEME_TEXTDOMAIN ) ) ?>
			</span>

			<a href="https://harbingermarketing.com/" class="design-link" target="_blank" aria-label="<?= esc_attr( __( 'link-harbinger', THEME_TEXTDOMAIN ) ) ?>">
				<?php if ( $logo_footer_id ) : ?>
					<span class="icon icon-wrap">
						<?php if ( hmt_is_svg_image( $logo_footer_id ) ) : ?>
							<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_footer_id ) ); ?>
						<?php endif ?>
					</span>
				<?php endif; ?>
			</a>
		</div>
	</div>
</div>