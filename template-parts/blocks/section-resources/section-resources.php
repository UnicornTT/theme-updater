<?php
/**
 * Section Resources Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @var $block
 */


$block_style = hmt_get_block_style_name( $block );


$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'section section-resources';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$section_top_padding_type = get_field( 'section_top_padding_type' );
$section_bottom_padding_type = get_field( 'section_bottom_padding_type' );

if( $section_top_padding_type && !empty($section_top_padding_type) ) {
	$section_top_padding = 'section-top-padding--' . $section_top_padding_type;
} else {
	$section_top_padding = 'section-top-padding--default';
}
if ( $section_bottom_padding_type && !empty($section_bottom_padding_type) ) {
	$section_bottom_padding = 'section-bottom-padding--' . $section_bottom_padding_type;
} else {
	$section_bottom_padding = 'section-bottom-padding--default';
}

$class_name .= ' section-resources--style-v1' . $block_style;
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

$section_title = get_field( 'resources_section_title' );

$categories = array_filter( get_categories(), function ( $category ) {
	return $category->count > 0;
} );

$tags = array_filter( get_tags(), function ( $tag ) {
	return $tag->count > 0;
} );

$posts = hmt_get_post_list( 5 );
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( $posts && !empty( $section_title ) && $section_title ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">

		<?php
			get_template_part( 'parts/resources/section-background', '', [
				'class_name' => 'section-resources__bg',
				'field_prefix' => 'resources_section_background',
				'field_id' => ''
			] );
		?>

		<div class="section__body">
			<div class="section-resources__content">
				<div class="container">
					<div class="section-resources__header">
						<?php if ( $section_title ) : ?>
							<div class="section-title section-title--style1 section-resources__title">
								<h2>
									<?= esc_html( $section_title ) ?>
								</h2>
							</div>
						<?php endif; ?>
					</div>

					<div class="section-resources__main">
						<div class="section-resources__controls">
							<div class="search-block">
								<form name="resources-search" method="get" action="<?= get_home_url() ?>">
									<div class="form-group form-group-search">
										<label for="resources-search-input" class="form-label sr-only">
											<?= esc_html( __( 'Search', THEME_TEXTDOMAIN ) ) ?>
										</label>

										<input type="text" class="form-control" name="s" id="resources-search-input" placeholder="<?= esc_attr( __( 'Search', THEME_TEXTDOMAIN ) ) ?>">

										<button class="search-btn js-resources-search" type="submit" aria-label="<?= esc_attr( __( 'Go search', THEME_TEXTDOMAIN ) ) ?>">
											<span class="icon icon-wrap">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-search.svg' ); ?>
											</span>
										</button>
									</div>
								</form>
							</div>

							<?php if ( $categories ) : ?>
								<div class="categories-block">
									<form name="resources-categories" method="post" action="">
										<div class="form-group form-group--select">
											<label for="resources-categories-select" class="form-label sr-only">
												<?= esc_html( __( 'Categories', THEME_TEXTDOMAIN ) ) ?>
											</label>

											<div class="select">
												<div class="filter-content-resource">
													<div class="new-select">Categories:	<?= esc_html( __( 'All', THEME_TEXTDOMAIN ) ) ?></div>

													<div class="icon-arrow">
														<span class="icon-wrap">
															<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-chevron-right-menu.svg" ); ?>
														</span>
													</div>
												</div>

												<div class="new-select__list" style="display: none;">
													<div class="scrollbar-outer">
														<div class="new-select__content">
															<div class="new-select__item" data-value="all">
																<span>
																	<?= esc_html( __( 'All', THEME_TEXTDOMAIN ) ) ?>
																</span>
															</div>
															<?php foreach ( $categories as $category ) : ?>
																<div class="new-select__item" data-value="<?= $category->term_id ?>">
																	<span>
																		<?= esc_html( $category->name ) ?>
																	</span>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
												</div>

												<select name="resources-categories-select" id="resources-categories-select" class="form-select resources-categories-select d-none">
													<option value="all">
														<?= esc_html( __( 'All', THEME_TEXTDOMAIN ) ) ?>
													</option>

													<?php foreach ( $categories as $category ) : ?>
														<option value="<?= $category->term_id ?>">
															<?= esc_html( $category->name ) ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</form>
								</div>
							<?php endif ?>

							<?php if ( $tags ) : ?>
								<div class="filter-block">
									<div class="dropdown">
										<button type="button" class="resources-filter dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="resources-filter__icon icon icon-wrap" aria-hidden="true">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-filter.svg" ); ?>
											</span>

											<span class="resources-filter__text">
												<?= esc_html( __( 'Tags', THEME_TEXTDOMAIN ) ) ?>
											</span>

											<span class="resources-filter__arrow icon icon-wrap" aria-hidden="true">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-next.svg" ); ?>
											</span>
										</button>

										<div class="dropdown-menu dropdown-menu-right">
											<div class="dropdown-menu__inner">
												<div class="scrollbar-outer">
													<div class="dropdown-menu__content">
														<?php foreach ( $tags as $tag ) : ?>
															<a data-tag-id="<?= esc_attr( $tag->term_id ) ?>" data-tag-name="<?= esc_attr( $tag->name ) ?>" href="javascript:void(0);" class="dropdown-item js-resources-tag-select">
																<?= esc_html( $tag->name ) ?>
															</a>
														<?php endforeach; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif ?>

							<div class="filter-results-block">
								<div class="template d-none">
									<li class="list-item filter-tag" tabindex="0">
										<div class="filter-tag__text"></div>

										<button class="filter-tag__remove" aria-label="<?= esc_attr( __( 'Remove', THEME_TEXTDOMAIN ) ) ?>">
											<span class="icon icon-wrap" aria-hidden="true">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
											</span>
										</button>
									</li>
								</div>

								<ul class="list list--unstyled filter-tags-list"></ul>
							</div>
						</div>

						<div class="section-resources__grid-wrapper">
							<div class="section-resources__grid">
								<?php foreach ( $posts as $index => $article ) : ?>
									<?php
										$post_title = get_the_title( $article );
										$post_description = wp_trim_words( get_field( 'single_post_description', $article->ID ), 40 );
										$post_thumbnail_id = get_post_thumbnail_id( $article );

										$post_author_ID = $article->post_author;
										$post_author_name = null;
										$post_author_page_link = null;

										$post_author_name = 'by ' . get_the_author_meta( 'display_name', $post_author_ID );
										$post_author_page_link = get_author_posts_url( $post_author_ID );

										$post_date = get_the_date( 'F jS, Y', $article );
										$post_link = get_the_permalink( $article );
									?>
									<div class="section-resources__cell">
										<div class="resources-card <?= esc_attr( $index == 0 ? 'resources-card--full' : '' ) ?>" tabindex="0">
											<div class="resources-card__bg">
												<?php if ( $post_thumbnail_id ) : ?>
													<div class="background-img">
														<picture>
															<img src="<?= esc_url( wp_get_attachment_image_url( $post_thumbnail_id, 'section-background-mobile' ) ); ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $post_thumbnail_id ) ); ?>">
														</picture>
													</div>
												<?php endif; ?>
											</div>

											<div class="resources-card__content">
												<div class="resources-card__body">
													<?php if ( $post_title ) : ?>
														<a href="<?= esc_url( $post_link ); ?>" class="resources-card__title section-title">
															<h3>
																<?= esc_html( $post_title ); ?>
															</h3>
														</a>
													<?php endif; ?>

													<?php if ( $post_description ) : ?>
														<div class="resources-card__description text-content">
															<?= apply_filters( 'the_content', $post_description ) ?>
														</div>
													<?php endif; ?>

													<div class="resources-card__date">
														<?php if ( $post_date ) : ?>
															<span>
																<?= esc_html( $post_date . ' |' ); ?>
															</span>
														<?php endif; ?>

														<?php if ( $post_author_name ) : ?>
															<a class="resources-card__author" href="<?= esc_url( $post_author_page_link ); ?>" aria-label="<?= esc_attr( __( 'View ' . $post_author_name . ' profile', THEME_TEXTDOMAIN ) ); ?>">
																<?= esc_html( $post_author_name ); ?>
															</a>
														<?php endif; ?>
													</div>
												</div>

												<div class="resources-card__button-wrapper">
													<a href="<?= esc_url( $post_link ); ?>" class="button button-bordered button-bordered-white resources-card__button">
														<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="loader" aria-hidden="true">
								<div class="loader-default">
									<div class="bar"></div>
									<div class="bar"></div>
									<div class="bar"></div>
									<div class="bar"></div>
								</div>
							</div>
						</div>

						<div class="section-resources__button-wrapper">
							<a href="<?= esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="button button-bordered section-resources__button">
								<?= esc_html( __( 'View All', THEME_TEXTDOMAIN ) ) ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>