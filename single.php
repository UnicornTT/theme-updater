<?php
add_action( 'wp_enqueue_scripts', 'hmt_enqueue_styles_resources' );
function hmt_enqueue_styles_resources() {
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-resources-styles', THEME_ASSETS_URL . '/theme/css/blocks/section-resources.css' );
	wp_enqueue_style( THEME_TEXTDOMAIN . '-style-related-styles', THEME_ASSETS_URL . '/theme/css/blocks/section-related.css' );
}

add_action( 'wp_enqueue_scripts', 'hmt_enqueue_scripts_resources' );
function hmt_enqueue_scripts_resources() {
	wp_enqueue_script( THEME_TEXTDOMAIN . '-section-related-scripts', THEME_ASSETS_URL . '/theme/js/blocks/section-related.js', array('jquery'), false, true );
}

$queried_object = get_queried_object();

$current_categories = get_the_category();
$current_tags = get_the_tags();

$author_id = $post->post_author;
$author_name = get_the_author_meta( 'display_name', $author_id );
$author_page_link = get_author_posts_url( $author_id );
$description = get_field( 'single_post_description' );

$categories = get_categories( [
	'hide_empty' => true,
] );

$tags = get_tags( [
	'hide_empty' => true,
] );

get_header() ?>

<section id="resource-article" class="section section-resources section-resources--style-v2">
	<div class="section__bg section-resources__bg" aria-hidden="true">
		<div class="background-img">
			<?php wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), 'section-background-desktop', false, ['alt' => hmt_get_attachment_image_alt( get_post_thumbnail_id( get_the_ID() ) )] ); ?>
		</div>
	</div>

	<div class="section__body">
		<div class="section-resources__content">
			<div class="container">
				<div class="section-resources__header">
					<div class="section-resources__category-wrapper">
						<?php if ( $current_categories ): ?>
							<?php foreach ( $current_categories as $category ): ?>
								<a href="<?= get_term_link( $category ) ?>" class="section-resources__category">
									<?= esc_html( $category->name ) ?>
								</a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="section-resources__author">
						<span>
							<?= esc_html( __( 'Author', THEME_TEXTDOMAIN ) ) ?>:
						</span>

						<a href="<?= $author_page_link; ?>" class="author-link" aria-label="<?= esc_attr( __( 'View ' . $author_name . ' profile', THEME_TEXTDOMAIN ) ); ?>">
							<?= esc_html( $author_name ); ?>
						</a>
					</div>

					<div class="section-title section-title--style3 section-resources__title">
						<h2>
							<?= esc_html( the_title() ) ?>
						</h2>
					</div>

					<div class="section-resources__post-info">
						<div class="section-resources__post-date">
							<div class="post-date">
								<div class="post-date__icon icon icon-wrap" aria-hidden="true">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-calendar.svg' ); ?>
								</div>

								<div class="post-date__text">
									<?= esc_html( get_the_date( 'M jS, Y' ) ); ?>
								</div>
							</div>
						</div>

						<?php if ( $current_tags ) : ?>
							<div class="section-resources__post-tags">
								<ul class="list list--unstyled post-tags">
									<?php foreach ( $current_tags as $tag ) : ?>
										<li class="post-tags-item">
											<a href="<?= esc_url( get_term_link( $tag->term_id ) ) ?>" class="post-tags-link">
												<?= esc_html( $tag->name ) ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif ?>
					</div>
				</div>

				<div class="section-resources__main">
					<article class="section-resources__article article-block">
						<div class="article-block__header-img">
							<?= wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), 'section-background-desktop', false, ['alt' => hmt_get_attachment_image_alt( get_post_thumbnail_id( get_the_ID() ) )] ); ?>
						</div>

						<?php if ( !empty( $description ) ) : ?>
							<div class="article-block__body text-content">
								<?= apply_filters( 'the_content', $description ); ?>
							</div>
						<?php endif; ?>

						<div class="article-block__footer">
							<?php
								$prev_post_id = get_adjacent_post( false, '', true )->ID;
								$next_post_id = get_adjacent_post( false, '', false )->ID;
							?>
							<a href="<?= esc_url( get_permalink( $prev_post_id ) ) ?>" class="article-link article-link--back <?= !$prev_post_id ? 'disabled' : '' ?>">
								<span class="article-link__icon icon icon-wrap" aria-hidden="true">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</span>

								<span class="article-link__text">
									<?= esc_html( __( 'Back', THEME_TEXTDOMAIN ) ) ?>
								</span>
							</a>

							<a href="<?= esc_url( get_permalink( $next_post_id ) ) ?>" class="article-link article-link--next <?= !$next_post_id ? 'disabled' : '' ?>">
								<span class="article-link__text">
									<?= esc_html( __( 'Next', THEME_TEXTDOMAIN ) ) ?>
								</span>

								<span class="article-link__icon icon icon-wrap" aria-hidden="true">
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
								</span>
							</a>
						</div>
					</article>

					<div class="section-resources__controls">
						<div class="controls-wrapper">
							<aside class="sidebar default-wp-widgets-container">
								<?php dynamic_sidebar( 'posts-sidebar' ); ?>
							</aside>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?= get_template_part( 'template-parts/blocks/section-related/section', 'related' ); ?>

<?php get_footer() ?>