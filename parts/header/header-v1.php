<?php
$title = get_bloginfo( 'title' );
?>
<header class="page-header page-header--v1">
	<div class="container">
		<div class="page-header__content">
			<a href="<?= esc_attr( home_url() ) ?>" class="page-header__logo" aria-label="<?= esc_attr( $title ) ?>">
				<?php $logo_header_type = get_field( 'logo_variation', 'option' ); ?>
				<?php if ( $logo_header_type == 'single' ) : ?>
					<?php
						$logo_header_id = get_field( 'logo_header', 'option' );
					?>
					<?php if ( $logo_header_id ) : ?>
						<span class="page-header__logo-inner">
							<?php if ( hmt_is_svg_image( $logo_header_id ) ) : ?>
								<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_header_id ) ); ?>

							<?php else : ?>
								<?= wp_get_attachment_image( $logo_header_id, 'thumbs-desktop', false, ['alt' => esc_attr( __( 'Logo', THEME_TEXTDOMAIN ) )] ); ?>

							<?php endif ?>
						</span>
					<?php endif; ?>

				<?php elseif ( $logo_header_type == 'multiple' ) : ?>
					<?php
						$logo_header_dark_id = get_field( 'logo_header_single_dark', 'option' );
						$logo_header_light_id = get_field( 'logo_header_single_light', 'option' );
					?>
					<?php if ( $logo_header_dark_id && $logo_header_light_id ) : ?>
						<span class="page-header__logo-inner">
							<span class="dark">
								<?php if ( hmt_is_svg_image( $logo_header_dark_id ) ) : ?>
									<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_header_dark_id ) ); ?>

								<?php else : ?>
									<?= wp_get_attachment_image( $logo_header_dark_id, 'thumbs-desktop', false, ['alt' => esc_attr( __( 'Logo Dark', THEME_TEXTDOMAIN ) )] ); ?>

								<?php endif ?>
							</span>

							<span class="light">
								<?php if ( hmt_is_svg_image( $logo_header_light_id ) ) : ?>
									<?= hmt_get_svg_inline( wp_get_attachment_url( $logo_header_light_id ) ); ?>

								<?php else : ?>
									<?= wp_get_attachment_image( $logo_header_light_id, 'thumbs-desktop', false, ['alt' => esc_attr( __( 'Logo Light', THEME_TEXTDOMAIN ) )] ); ?>

								<?php endif ?>
							</span>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</a>

			<div class="page-header__nav">
				<?php
					$main_menu_args = array(
						'theme_location' => 'main-menu',
						'container' => false,
						'menu_id' => 'desktop-menu',
						'walker' => new HMT_Walker_Nav_Menu()
					);
					wp_nav_menu( $main_menu_args );
				?>
			</div>

			<div class="page-header__right">
				<div class="page-header__widget theme-switcher-widget">
					<?= get_template_part( 'parts/theme-switcher' ) ?>
				</div>

				<?php $phone_number = get_field( 'phone_number', 'options' ); ?>
				<?php if ( $phone_number ) : ?>
					<div class="page-header__widget header-phone-widget">
						<a href="tel:<?= esc_attr( hmt_sanitize_phone_number_for_href( $phone_number ) ) ?>" class="page-header__phone" aria-label="<?= esc_attr( __( 'Call ', THEME_TEXTDOMAIN ) ) ?><?= esc_attr( hmt_sanitize_phone_number_for_href( $phone_number ) ) ?>">
							<span class="icon icon-wrap">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-phone.svg' ); ?>
							</span>

							<span class="label">
								<?= esc_html( $phone_number ) ?>
							</span>
						</a>
					</div>
				<?php endif; ?>

				<div class="page-header__widget menu-toggler-widget">
					<button class="menu-button page-header__menu-button" aria-label="<?= esc_attr( __( 'Toggle menu', THEME_TEXTDOMAIN ) ) ?>">
						<span class="icon icon-wrap icon-bar" aria-hidden="true">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-menu.svg' ); ?>
						</span>

						<span class="icon icon-wrap icon-close" aria-hidden="true">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-menu-close.svg' ); ?>
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</header>

<?php get_template_part( 'parts/main-menu' ) ?>
