<?php
$main_menu_button = get_field( 'main_menu_button', 'option' );
$button_type = $main_menu_button['type'] ?? '';
$button_text = $main_menu_button['text'] ?? '';

$is_have_button = $button_type === 'modal' || $button_type === 'link';
?>

<div class="main-menu">
	<div class="main-menu__inner<?= esc_attr( $is_have_button ? "" : " main-menu__inner--no-buttons" ) ?>">
		<div class="main-menu__controls">
			<a class="main-menu__back" href="javascript: void(0);" aria-label="<?= esc_attr( __( 'Back to main menu', THEME_TEXTDOMAIN ) ) ?>">
                <span class="icon icon-wrap">
                    <?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
                </span>

				<span class="label">
                    <?= esc_html( __( 'Back', THEME_TEXTDOMAIN ) ) ?>
                </span>
			</a>

			<div class="main-menu__widget">
				<?= get_template_part( 'parts/theme-switcher' ) ?>
			</div>
		</div>

		<div class="scrollbar-outer">
			<nav class="main-menu__menu">
				<?php
					$main_menu_args = array(
						'theme_location' => 'main-menu',
						'container' => false,
						'menu_id' => 'main-menu',
						'walker' => new HMT_Walker_Nav_Menu()
					);
					wp_nav_menu( $main_menu_args );
				?>
			</nav>
		</div>

		<div class="scroll-info__wrapper">
			<div class="scroll-info">
				<div class="scroll-info__text">
					<?= esc_html( __( 'scroll', THEME_TEXTDOMAIN ) ); ?>
				</div>

				<div class="scroll-info__icon">
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
				</div>
			</div>
		</div>

		<?php if ( $is_have_button ) : ?>
			<?php
				$button_modal = $main_menu_button['modal'] ?? '';
				\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $button_modal );
			?>
			<div class="main-menu__button-wrap">
				<?php if ( $button_type == 'link' ) : ?>
					<?php
						$button_link = $main_menu_button['main_menu_button_link'] ?? '';
					?>
					<?php if ( !empty( $button_link ) ) : ?>
						<?php
							$button_title = $button_link['title'] ? $button_link['title'] : '';
							$url = $button_link['url'] ? $button_link['url'] : '';
							$target = $button_link['target'] ? $button_link['target'] : '_self';
						?>
						<a href="<?= esc_url( $url ) ?>" target="<?= esc_attr( $target ) ?>" class="button button--main-menu main-menu__button">
							<?= esc_html( $button_title ) ?>
						</a>
					<?php endif; ?>

				<?php elseif ( $button_type == 'modal' ) : ?>
					<?php
						$hash = $main_menu_button['modal'] ?? '';
					?>
					<a href="#<?= esc_attr( $hash ); ?>" data-toggle="modal" class="button button--main-menu main-menu__button">
						<?= esc_html( $button_text ) ?>
					</a>

				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div><!-- .main-menu -->

<div class="main-menu-overlay" aria-hidden="true"></div>