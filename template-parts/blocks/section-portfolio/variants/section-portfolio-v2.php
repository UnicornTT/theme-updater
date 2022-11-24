<?php
/**
 * @var $args array
 */

$block_id = $args['block_id'];
$section_title = get_field( 'portfolio_section_title' );
$section_type = get_field( 'portfolio_type' );

if ( get_field( 'portfolio_type' ) === 'categories' && !empty( get_field( 'portfolio_projects_categories' ) ) ) {
	$terms_ids = [];

	$categories = get_field( 'portfolio_projects_categories' );

	if ( !empty($categories) && is_array($categories) ) {
		$project_categories = array_filter( $categories, function ($category) {
			return $category->count > 0;
		} );

		array_unshift( $project_categories, 'all' );

		foreach ( $project_categories as $category ) {
			if ( $category !== 'all' ) {
				$terms_ids[] = $category->term_id;
			}
		}

		$project_list = get_posts( array(
			'post_type' => 'hmt_project',
			'status' => 'publish',
			'numberposts' => - 1,
			'posts_per_page' => - 1,
			'suppress_filters' => true,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'hmt_project_category',
					'field' => 'term_id',
					'terms' => $terms_ids
				)
			)
		) );
	}
} else {
	$project_list = get_field( 'portfolio_projects' );
}

$projects_image_gallery = [];

?>

<?php if ( $project_list ) : ?>
	<div class="section__body">
		<div class="section-portfolio__content js-font-adjustment" data-fj-min="20">
			<div class="container">
				<div class="row no-gutters">
					<div class="section-portfolio__header section-header">
						<?php if ( $section_title ) : ?>
							<div class="section-header__title section-title section-title--style1">
								<h2 class="js-font-title">
									<?= esc_html( $section_title ) ?>
								</h2>
							</div>
						<?php endif; ?>

						<div class="section-header__nav">
							<div class="portfolio-nav <?= $section_type === 'categories' ? 'collapse show' : '' ?>">
								<div class="d-none d-md-block">
									<div class="swiper swiper-container js-portfolio-navigation-slider">
										<div class="swiper-wrapper">
											<?php if ( $section_type === 'projects' ) : ?>
												<?php
												$project_chunks = array_chunk( $project_list, 10, true );
												?>
												<?php foreach ( $project_chunks as $project_chunk ) : ?>
													<div class="swiper-slide">
														<ul
															class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--projects"
															role="tablist"
														>
															<?php foreach ( $project_chunk as $key => $project ) : ?>
																<?php
																$id = $block_id . '-' . $project->post_name;
																?>
																<li class="list-item nav-item" role="presentation">
																	<a
																		href="#tab-project-<?= esc_attr( $id ) ?>"
																		data-project="tab-project-<?= esc_attr( $id ) ?>"
																		class="link nav-link list-link js-list-link <?= esc_attr( !$key ? 'active' : '' ) ?>"
																		data-toggle="tab" role="tab"
																		aria-selected="<?= esc_attr( !$key ? 'true' : '' ) ?><?= esc_attr( $key ? 'false' : '' ) ?>"
																	>
																		<?= esc_html( $project->post_title ) ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>

											<?php if ( $section_type === 'categories' ) : ?>
												<?php
												$project_categories_chunks = array_chunk( $project_categories, 10, true );
												?>
												<?php foreach ( $project_categories_chunks as $chunk_key => $project_categories_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--categories">
															<?php foreach ( $project_categories_chunk as $category ) : ?>
																<?php if ( $category === 'all' ) : ?>
																	<li class="list-item list-item--has-child nav-item">
																		<a
																			href="javascript: void(0);"
																			class="link list-link active js-portfolio-subnav-opened"
																			data-filter="all"
																		>
																			<span class="link-text">
																				<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
																			</span>

																			<span class="link-total-number">
																				(<?= count( (array)$project_list ) ?>)&nbsp;&rsaquo;
																			</span>
																		</a>
																	</li>
																	<?php continue; endif; ?>


																<li class="list-item list-item--has-child nav-item">
																	<a
																		href="javascript: void(0);"
																		class="link list-link js-portfolio-subnav-opened"
																		data-filter="<?= esc_attr( $category->slug ) ?>"
																	>
																		<?php if ( $category->name ) : ?>
																			<span class="link-text">
																				<?= esc_html( $category->name ) ?>&nbsp;&rsaquo;
																			</span>
																		<?php endif; ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>

										<div class="swiper-controls swiper-controls--fraction">
											<button class="swiper-button-prev">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</button>

											<button class="swiper-button-next">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</button>

											<div class="swiper-pagination"></div>
										</div>
									</div>
								</div>

								<div class="d-block d-md-none">
									<div class="swiper swiper-container js-portfolio-navigation-slider">
										<div class="swiper-wrapper">
											<?php if ( $section_type === 'projects' ): ?>
												<?php
												$project_chunks = array_chunk( $project_list, 4, true );
												?>
												<?php foreach ( $project_chunks as $project_chunk ) : ?>
													<div class="swiper-slide">
														<ul
															class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--projects"
															role="tablist"
														>
															<?php foreach ( $project_chunk as $key => $project ) : ?>
																<?php
																$id = $block_id . '-' . $project->post_name;
																?>
																<li class="list-item nav-item" role="presentation">
																	<a
																		href="#tab-project-<?= esc_html( $id ) ?>"
																		class="link nav-link list-link js-list-link <?= esc_attr( !$key ? 'active' : '' ) ?>"
																		data-project="tab-project-<?= esc_attr( $id ) ?>"
																		data-toggle="tab" role="tab"
																		aria-selected="<?= esc_attr( !$key ? 'true' : '' ) ?><?= esc_attr( $key ? 'false' : '' ) ?>"
																	>
																		<?= esc_html( $project->post_title ) ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>

											<?php if ( $section_type === 'categories' ): ?>
												<?php
												$project_categories_chunks = array_chunk( $project_categories, 4, true );
												?>
												<?php foreach ( $project_categories_chunks as $chunk_key => $project_categories_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--categories">
															<?php foreach ( $project_categories_chunk as $category ) : ?>
																<?php if ( $category === 'all' ) : ?>
																	<li class="list-item list-item--has-child nav-item">
																		<a
																			href="javascript: void(0);"
																			class="link list-link active js-portfolio-subnav-opened"
																			data-filter="all"
																		>
																				<span class="link-text">
																					<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
																				</span>

																			<span
																				class="link-total-number"
																			>(<?= count( (array)$project_list ) ?>)</span>

																			<span
																				class="icon icon-wrap link__icon"
																				aria-hidden="true"
																			>
																				<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
																			</span>
																		</a>
																	</li>
																	<?php continue; endif; ?>


																<li class="list-item list-item--has-child nav-item">
																	<a
																		href="javascript: void(0);"
																		class="link list-link js-portfolio-subnav-opened"
																		data-filter="<?= esc_attr( $category->slug ) ?>"
																	>
																		<span class="link-text">
																			<?= esc_html( $category->name ) ?>
																		</span>

																		<span
																			class="icon icon-wrap link__icon"
																			aria-hidden="true"
																		>
																			<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
																		</span>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>

										<div class="swiper-controls swiper-controls--fraction">
											<button class="swiper-button-prev">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</button>

											<button class="swiper-button-next">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</button>

											<div class="swiper-pagination"></div>
										</div>
									</div>
								</div>
							</div>

							<?php if ( $section_type === 'categories' ): ?>
								<div class="portfolio-subnav collapse">
									<div class="portfolio-subnav__back">
										<div class="portfolio-subnav__title section-title section-title--style5">
											<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
										</div>

										<button
											class="button button-bordered button-bordered-white portfolio-subnav__button js-portfolio-subnav-back"
											aria-label="<?= esc_html( __( 'Back to categories', THEME_TEXTDOMAIN ) ) ?>"
										>
											<span class="icon icon-wrap button__icon" aria-hidden="true">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
											</span>

											<span class="button__text">
												<?= esc_html( __( 'Back', THEME_TEXTDOMAIN ) ) ?>
											</span>
										</button>
									</div>

									<div class="scrollbar-outer">
										<ul
											class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--projects"
											role="tablist"
										>
											<?php foreach ( $project_list as $key => $project ) : ?>
												<?php
												$filter_cats = '';
												$project_taxonomies = wp_get_post_terms( $project->ID, 'hmt_project_category' );

												foreach ( $project_taxonomies as $project_taxonomy ) {
													$filter_cats .= $project_taxonomy->slug . ' ';
												}

												$id = $block_id . '-' . $project->post_name;
												?>
												<li
													class="list-item nav-item visible"
													data-filter-values="all <?= esc_attr( $filter_cats ) ?>"
													role="presentation"
												>
													<a
														href="#tab-project-<?= esc_attr( $id ) ?>"
														class="link nav-link list-link <?= esc_attr( !$key ? 'active' : '' ) ?>"
														data-toggle="tab" role="tab"
														aria-selected="<?= esc_attr( !$key ? 'true' : '' ) ?><?= esc_attr( $key ? 'false' : '' ) ?>"
													>
														<?= esc_html( $project->post_title ) ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="section-portfolio__body">
						<div class="tab-content">
							<?php foreach ( $project_list as $key => $project ) : ?>
								<?php
								$id = $block_id . '-' . $project->post_name;
								$attachment_images = get_field( 'project_gallery', $project->ID );
								$projects_image_gallery['modal-portfolio-' . $id] = $attachment_images;
								$image_ids = get_field( 'project_gallery', $project->ID );
								$project_description = wpautop( get_field( 'project_description', $project->ID ) );
								$first_image_id = !empty( $image_ids[0] ) ? $image_ids[0] : null;
								?>
								<div
									class="tab-pane fade <?= esc_attr( !$key ? 'active show' : '' ) ?>"
									id="tab-project-<?= esc_attr( $id ) ?>" role="tabpanel"
								>
									<div class="portfolio-card portfolio-card--full">
										<div class="portfolio-card__img">
											<div class="background-img">
												<picture>
													<source
														srcset="<?= esc_url( wp_get_attachment_image_url( $first_image_id, 'section-background-4k' ) ) ?>"
														media="(min-width: 1920px)"
													>
													<source
														srcset="<?= esc_url( wp_get_attachment_image_url( $first_image_id, 'section-background-desktop' ) ) ?>"
														media="(min-width: 1280px)"
													>
													<source
														srcset="<?= esc_url( wp_get_attachment_image_url( $first_image_id, 'section-background-tablet' ) ) ?>"
														media="(max-width: 1279px)"
													>
													<source
														srcset="<?= esc_url( wp_get_attachment_image_url( $first_image_id, 'section-background-mobile' ) ) ?>"
														media="(max-width: 767px)"
													>
													<img
														src="<?= esc_url( wp_get_attachment_image_url( $first_image_id, 'section-background-desktop' ) ) ?>"
														alt="<?= esc_url( hmt_get_attachment_image_alt( $first_image_id ) ); ?>"
													>
												</picture>
											</div>
										</div>

										<div class="portfolio-card__content">
											<?php if ( !empty( $project->post_title ) ) : ?>
												<div class="portfolio-card__title section-title section-title--style3">
													<h3>
														<?= esc_html( $project->post_title ) ?>
													</h3>
												</div>
											<?php endif; ?>

											<?php if ( !empty( $project_description ) ) : ?>
												<div class="portfolio-card__body">
													<div class="scrollbar-outer">
														<div class="portfolio-card__description text-content">
															<?= wpautop( $project_description ) ?>
														</div>
													</div>
												</div>
											<?php endif; ?>

											<div class="portfolio-card__button-wrapper">
												<button
													class="button button-primary portfolio-card__button"
													data-target="#modal-portfolio-<?= esc_attr( $id ) ?>"
													data-toggle="modal"
												>
													<?= esc_html( __( 'View Gallery', THEME_TEXTDOMAIN ) ) ?>
												</button>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if( $projects_image_gallery ): ?>
		<!-- Image Gallery Popup -->
		<?php
		get_template_part(
			'template-parts/popups/popup',
			'image-gallery',
			[
				'gallery_list' => $projects_image_gallery
			]
		);
		?>
		<!-- End Image Gallery Popup -->
	<?php endif; ?>
<?php endif ?>
