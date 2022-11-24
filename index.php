<?php
$page_for_posts_id = get_option( 'page_for_posts' );

add_action( 'wp_enqueue_scripts', 'hmt_enqueue_styles_resources' );
function hmt_enqueue_styles_resources() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-resources-styles', get_bloginfo( 'stylesheet_directory' ) . '/assets/theme/css/blocks/section-resources.css' );
}

add_action( 'wp_enqueue_scripts', 'hmt_enqueue_scripts_resources' );
function hmt_enqueue_scripts_resources() {
	wp_enqueue_script( THEME_TEXTDOMAIN . '-section-resources-scripts', get_bloginfo( 'stylesheet_directory' ) . '/assets/theme/js/blocks/section-resources.js', array('jquery'), false, true );
}

global $wp_query;
$paged = get_query_var( 'paged' );
$max_num_pages = ceil( (count( $wp_query->posts )) / 9 );

$queried_object = get_queried_object();

get_header()
?>

<section id="resources" class="section section-resources section-resources--style-v2">
	<div class="section__bg section-resources__bg" aria-hidden="true">
		<div class="background-img">
		</div>
	</div>

	<div class="section__body">
		<div class="section-resources__content">
			<div class="container">
				<div class="section-resources__header">
					<div class="section-title section-title--style1 section-resources__title">
						<h2>
							<?php if ( is_search() ): ?>
								<?= esc_html( __( 'Search for: ', THEME_TEXTDOMAIN ) . get_search_query() ); ?>
							<?php elseif ( is_category() ): ?>
								<?= esc_html( __( 'Category: ', THEME_TEXTDOMAIN ) . get_queried_object()->name ); ?>
							<?php elseif ( is_tag() ): ?>
								<?= esc_html( __( 'Tag: ', THEME_TEXTDOMAIN ) . get_queried_object()->name ); ?>
							<?php else: ?>
								<?= esc_html( get_the_title( get_option( 'page_for_posts', true ) ) ); ?>
							<?php endif; ?>
						</h2>
					</div>
				</div>

				<div class="section-resources__main">
					<div class="section-resources__controls d-none d-md-block">
						<div class="controls-wrapper">
							<aside class="sidebar default-wp-widgets-container">
								<?php dynamic_sidebar( 'resources-sidebar' ); ?>
							</aside>
						</div>
					</div>

					<div class="section-resources__article">
						<div id="resources-grid" class="section-resources__grid">
							<?php
								$key = 0;
							?>
							<?php if ( have_posts() ) : ?>
								<?php while ( have_posts() ) : $key++ ?>
									<?php
										the_post();
										$short_description = wp_trim_words( get_field( 'single_post_description', get_the_ID() ), 40 );
									?>
									<div class="section-resources__cell">
										<div class="resources-card <?= $key == 1 ? 'resources-card--full' : '' ?>" tabindex="0">
											<div class="resources-card__bg">
												<div class="background-img">
													<picture>
														<source srcset="<?= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'resources-desktop' ) ) ?>" media="(min-width: 1280px)">
														<source srcset="<?= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'resources-mobile' ) ) ?>" media="(max-width: 1279px)">
														<img src="<?= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'resources-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( get_post_thumbnail_id( get_the_ID() ) ) ); ?>">
													</picture>
												</div>
											</div>

											<div class="resources-card__content">
												<div class="resources-card__body">
													<a href="<?= esc_url( get_permalink() ) ?>" class="resources-card__title section-title">
														<h3>
															<?= esc_html( the_title() ) ?>
														</h3>
													</a>

													<?php if ( !empty( $short_description ) ) : ?>
														<div class="resources-card__description text-content">
															<?= apply_filters( 'the_content', $short_description ) ?>
														</div>
													<?php endif; ?>

													<div class="resources-card__date">
														<span><?= esc_html( get_the_date( 'M jS, Y' ) ); ?> |</span>

														<a class="resources-card__author" href="<?= get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>">
															<?= esc_html( __( 'by ', THEME_TEXTDOMAIN ) . get_the_author() ) ?>
														</a>
													</div>
												</div>

												<div class="resources-card__button-wrapper">
													<a href="<?= esc_url( get_permalink() ) ?>" class="button button-bordered button-bordered-white resources-card__button">
														<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php endwhile; ?>
							<?php endif; ?>

							<?php if ( !have_posts() ) : ?>
								<div class="search-empty">
									<?php if ( is_search() ): ?>
										<?= esc_html( __( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', THEME_TEXTDOMAIN ) ) ?>
									<?php elseif ( is_category() ): ?>
										<?= esc_html( __( 'Sorry, but there are no posts in the "' . get_queried_object()->name . '" category. Please try again later.', THEME_TEXTDOMAIN ) ) ?>
									<?php elseif ( is_tag() ): ?>
										<?= esc_html( __( 'Sorry, but there are no posts tagged as "' . get_queried_object()->name . '". Please try again later.', THEME_TEXTDOMAIN ) ) ?>
									<?php else: ?>
										<?= esc_html( __( 'Sorry, but there are no posts to show at the moment. Please try again later.', THEME_TEXTDOMAIN ) ) ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( true ) : ?>
							<div class="section-resources__button-wrapper">
								<?= next_posts_link( __( 'Load More', THEME_TEXTDOMAIN ), $wp_query->max_num_pages ) ?>
							</div>
						<?php endif ?>
					</div>

					<div class="section-resources__controls section-resources__controls--mobile d-block d-md-none">
						<aside class="sidebar sidebar--mobile default-wp-widgets-container">
							<?php dynamic_sidebar( 'resources-sidebar' ); ?>
						</aside>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
	foreach ( parse_blocks( get_post_field( 'post_content', $page_for_posts_id ) ) as $block ) {
		echo render_block( $block );
	}
?>

<?php get_footer() ?>