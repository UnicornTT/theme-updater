<?php
/**
 * Template Name: Sitemap Page
 */

add_action( 'wp_enqueue_scripts', 'hmt_enqueue_styles_resources' );
function hmt_enqueue_styles_resources() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-resources-styles', THEME_ASSETS_URL . '/theme/css/blocks/section-resources.css' );
}

get_header(); ?>

<section class="section section-resources section-resources--style-v3">
	<div class="section__body">
		<div class="section-resources__content">
			<div class="container">
				<div class="section-resources__header">
					<div class="section-title section-title--style3 section-resources__title">
						<h2>
							<?= the_title() ?>
						</h2>
					</div>
				</div>

				<div class="section-resources__main">
					<article class="section-resources__article article-block">
						<div class="article-block__body">
							<div class="sitemap-nav">
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
						</div>
					</article>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
