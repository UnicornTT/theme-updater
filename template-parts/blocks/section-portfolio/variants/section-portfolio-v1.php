<?php
/**
 * @var $args array
 */

$block_id = $args['block_id'];
$section_title = get_field( 'portfolio_section_title' );
$section_type = get_field( 'portfolio_type' );

if ( get_field( 'portfolio_type' ) === 'categories' && !empty( get_field( 'portfolio_projects_categories' ) ) ) {
	$terms_ids = [];
	$category = get_field( 'portfolio_projects_categories' );

	$project_categories = array_filter( $category, function ($category) {
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
		'post_status' => 'public',
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
		),
	) );

} else {
	$project_list = get_field( 'portfolio_projects' ) ?? [];
}

$project_list_json = [];
$projects_image_gallery = [];

foreach ( $project_list as $key => $project ) {
	$id = $block_id . '-' . $project->post_name;

	// -------- Images --------
	$image_ids = get_field( 'project_gallery', $project->ID );
	$projects_image_gallery['modal-portfolio-' . $id] = $image_ids;

	// -------- Position Coords --------
	$project_lat = get_field( 'project_location', $project->ID ) ? (string)get_field( 'project_location', $project->ID )['lat'] : '';
	$project_lng = get_field( 'project_location', $project->ID ) ? (string)get_field( 'project_location', $project->ID )['lng'] : '';

	if ( !$project_lat || !$project_lng ) {
		unset( $project_list[$key] );
		continue;
	}

	// -------- Marker Layout --------
	$layout = hmt_template_part_with_params( 'template-parts/blocks/section-portfolio/marker/section-portfolio-map-marker', [
		'project_location_ID' => $id,
		'project_image_IDs' => $image_ids,
		'project_title' => $project->post_title,
		'project_description' => get_field( 'project_description', $project->ID ),
	] );

	// -------- Categories --------
	$categories = [];
	$project_taxonomies = wp_get_post_terms( $project->ID, 'hmt_project_category' );

	foreach ( $project_taxonomies as $project_taxonomy ) {
		$categories[] = $project_taxonomy->slug;
	}
	$categories[] = 'all';

	// -------- Final Array --------
	$project_list_json[] = [
		'id' => $id,
		'coordinates' => [
			'lat' => $project_lat,
			'lng' => $project_lng,
		],
		'category' => $categories,
		'layout' => urlencode( $layout )
	];
}

// Throw variables from back to front end.
$theme_vars = array(
	'data' => $project_list_json,
);

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
							<div
								class="portfolio-nav <?= esc_attr( $section_type === 'categories' ? 'collapse show' : '' ) ?>"
							>
								<div class="d-none d-md-block">
									<div class="swiper swiper-container js-portfolio-navigation-slider">
										<div class="swiper-wrapper">
											<?php if ( $section_type === 'projects' ) : ?>
												<?php
													$project_chunks = array_chunk( $project_list, 10, true );
												?>
												<?php foreach ( $project_chunks as $project_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--projects">
															<?php foreach ( $project_chunk as $key => $project ) : ?>
																<?php
																	$id = $block_id . '-' . $project->post_name;
																	$lat = get_field( 'project_location', $project->ID ) ? get_field( 'project_location', $project->ID )['lat'] : '';
																	$lng = get_field( 'project_location', $project->ID ) ? get_field( 'project_location', $project->ID )['lng'] : '';
																?>
																<li
																	class="list-item nav-item visible"
																	data-filter-values="all"
																>
																	<a
																		href="javascript: void(0);"
																		class="link nav-link list-link js-change-single-map-project <?= esc_attr( !$key ? 'active' : '' ) ?>"
																		data-project="<?= esc_attr( $id ) ?>"
																		data-coordinates-lat="<?= esc_attr( $lat ) ?>"
																		data-coordinates-lng="<?= esc_attr( $lng ) ?>"
																	>
																		<?= esc_html( $project->post_title ) ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>

											<?php elseif ( $section_type === 'categories' && !empty( $project_categories ) ) : ?>
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
																			href="javascript:void(0);"
																			class="link list-link js-portfolio-subnav-opened"
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
																	<?php continue; ?>
																<?php endif; ?>


																<li class="list-item list-item--has-child nav-item">
																	<a
																		href="javascript:void(0);"
																		class="link list-link js-portfolio-subnav-opened"
																		data-filter="<?= esc_attr( $category->slug ) ?>"
																	>
																		<span class="link-text">
																			<?= esc_html( $category->name ) ?>&nbsp;&rsaquo;
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

								<div class="d-block d-md-none">
									<div class="swiper swiper-container js-portfolio-navigation-slider">
										<div class="swiper-wrapper">
											<?php if ( $section_type === 'projects' ) : ?>
												<?php
													$project_chunks = array_chunk( $project_list, 4, true );
												?>
												<?php foreach ( $project_chunks as $project_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--projects">
															<?php foreach ( $project_chunk as $key => $project ) : ?>
																<?php
																	$id = $block_id . '-' . $project->post_name;
																	$lat = get_field( 'project_location', $project->ID ) ? get_field( 'project_location', $project->ID )['lat'] : '';
																	$lng = get_field( 'project_location', $project->ID ) ? get_field( 'project_location', $project->ID )['lng'] : '';
																?>
																<li
																	class="list-item nav-item visible"
																	data-filter-values="all"
																>
																	<a
																		href="javascript:void(0);"
																		class="link nav-link list-link js-change-map-project <?= !$key ? 'active' : '' ?>"
																		data-project="<?= esc_attr( $id ) ?>"
																		data-coordinates-lat="<?= esc_attr( $lat ) ?>"
																		data-coordinates-lng="<?= esc_attr( $lng ) ?>"
																	>
																		<?= esc_html( $project->post_title ) ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>
											<?php elseif ( $section_type === 'categories' ) : ?>
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
																			href="javascript:void(0);"
																			class="link list-link js-portfolio-subnav-opened"
																			data-filter="all"
																		>
																			<span class="link-text">
																				<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
																			</span>

																			<span class="link-total-number">
																				(<?= count( (array)$project_list ) ?>)
																			</span>

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
																		<?php if ( $category->name ) : ?>
																			<span class="link-text">
																				<?= esc_html( $category->name ) ?>
																			</span>
																		<?php endif; ?>

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

							<?php if ( $section_type === 'categories' ) : ?>
								<div class="portfolio-subnav collapse">
									<div class="portfolio-subnav__back">
										<div class="portfolio-subnav__title section-title section-title--style5">
											<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
										</div>

										<button class="button button-bordered button-bordered-white portfolio-subnav__button js-portfolio-subnav-back" aria-label="<?= esc_html( __( 'Back to categories', THEME_TEXTDOMAIN ) ) ?>">
											<span class="icon icon-wrap button__icon" aria-hidden="true">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
											</span>

											<span class="button__text">
												<?= esc_html( __( 'Back', THEME_TEXTDOMAIN ) ) ?>
											</span>
										</button>
									</div>

									<div class="scrollbar-outer">
										<ul class="list list--unstyled nav nav-tabs portfolio-menu portfolio-menu--projects">
											<?php foreach ( $project_list as $key => $project ) : ?>
												<?php
													$filter_cats = '';
													$project_taxonomies = wp_get_post_terms( $project->ID, 'hmt_project_category' );

													foreach ( $project_taxonomies as $project_taxonomy ) {
														$filter_cats .= $project_taxonomy->slug . ' ';
													}

													$id = $block_id . '-' . $project->post_name;

													$lat = get_field( 'project_location', $project->ID ) ? get_field( 'project_location', $project->ID )['lat'] : '';
													$lng = get_field( 'project_location', $project->ID ) ? get_field( 'project_location', $project->ID )['lng'] : '';
												?>
												<li
													class="list-item nav-item visible"
													data-filter-values="all <?= $filter_cats ?>"
												>
													<a
														href="javascript: void(0);"
														class="link nav-link list-link js-change-map-project <?= esc_attr( !$key ? 'active' : '' ) ?>"
														data-project="<?= esc_html( $id ) ?>"
														data-coordinates-lat="<?= esc_html( $lat ) ?>"
														data-coordinates-lng="<?= esc_html( $lng ) ?>"
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
				</div>
			</div>
		</div>

		<div class="section-portfolio__map">
			<div class="portfolio-map js-portfolio-map" id="portfolio-map-<?= esc_attr( $block_id ) ?>"></div>
		</div>

		<script type="text/javascript">
			window['sitePath'] = '<?= THEME_ASSETS_URL ?>';
			window['themeVars_' + '<?= $block_id ?>'] = JSON.parse('<?= json_encode( $theme_vars ); ?>');
		</script>
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
