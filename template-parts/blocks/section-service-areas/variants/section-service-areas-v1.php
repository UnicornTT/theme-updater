<?php

/**
 * @var $args array
 */

$block_id = $args['block_id'];
$section_title = get_field( 'service_areas_section_title' );
$section_type = get_field( 'service_areas_type' );

if ( $section_type == 'categories' && !empty( get_field( 'service_areas_categories' ) ) ) {
	$terms_ids = [];

	$service_areas_categories = array_filter( (array)get_field( 'service_areas_categories' ), function ( $category ) {
		return $category->count > 0;
	} );

	array_unshift( $service_areas_categories, 'all' );

	foreach ( $service_areas_categories as $category ) {
		if ( $category != 'all' ) {
			$terms_ids[] = $category->term_id;
		}
	}

	$service_areas_list = get_posts( array(
		'post_type' => 'hmt_service_area',
		'status' => 'publish',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'suppress_filters' => true,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'hmt_service_area_category',
				'field' => 'term_id',
				'terms' => $terms_ids
			)
		),
	) );

} else {
	$service_areas_list = get_field( 'service_areas' );

}

$service_areas_list_json = [];

foreach ( $service_areas_list as $key => $project ) {
	$id = $block_id . '-' . $project->post_name;
	$gallery = [];

	$project_title = $project->post_title;
	$project_lat = get_field( 'service_areas_location', $project->ID ) ? (string)get_field( 'service_areas_location', $project->ID )['lat'] : '';
	$project_lng = get_field( 'service_areas_location', $project->ID ) ? (string)get_field( 'service_areas_location', $project->ID )['lng'] : '';

	if ( !$project_title || !$project_lat || !$project_lng ) {
		unset( $service_areas_list[$key] );
		continue;
	}

	$categories = [];
	$project_taxonomies = wp_get_post_terms( $project->ID, 'hmt_service_area_category' );

	foreach ( $project_taxonomies as $project_taxonomy ) {
		array_push( $categories, $project_taxonomy->slug );
	}
	array_push( $categories, 'all' );

	$service_areas_list_json[] = [
		'id' => $id,
		'title' => $project_title,
		'coordinates' => [
			'lat' => $project_lat,
			'lng' => $project_lng,
		],
		'category' => $categories,
	];
}

// Throw variables from back to front end.
$theme_vars = array(
	'data' => $service_areas_list_json,
);
?>

<?php if ( $service_areas_list ) : ?>
	<div class="section__body">
		<div class="section-service-areas__content">
			<div class="container">
				<div class="row no-gutters">
					<div class="section-service-areas__header section-header">
						<?php if ( $section_title ) : ?>
							<div class="section-header__title section-title section-title--style1">
								<h2>
									<?= esc_html( $section_title ) ?>
								</h2>
							</div>
						<?php endif; ?>

						<div class="section-header__nav">
							<div class="service-areas-nav <?= esc_attr( $section_type == 'categories' ? 'collapse show' : '' ) ?>">
								<div class="d-none d-md-block">
									<div class="swiper swiper-container js-service-areas-navigation-slider">
										<div class="swiper-wrapper">
											<?php if ( $section_type == 'service_areas' ) : ?>
												<?php
												$service_areas_chunks = array_chunk( $service_areas_list, 10, true );
												?>
												<?php foreach ( $service_areas_chunks as $service_areas_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs service-areas-menu service-areas-menu--projects">
															<?php foreach ( $service_areas_chunk as $key => $service_area ) : ?>
																<?php
																	$id = $block_id . '-' . $service_area->post_name;
																	$lat = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['lat'] : '';
																	$lng = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['lng'] : '';
																?>
																<li class="list-item nav-item visible" data-filter-values="all">
																	<a
																		href="javascript: void(0);"
																		class="link nav-link list-link js-change-map-project <?= esc_attr( !$key ? 'active' : '' ) ?>"
																		data-project="<?= esc_attr( $id ) ?>"
																		data-coordinates-lat="<?= esc_attr( $lat ) ?>"
																		data-coordinates-lng="<?= esc_attr( $lng ) ?>"
																	>
																		<?= esc_html( $service_area->post_title ) ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>

											<?php elseif ( $section_type == 'categories' && !empty( $service_areas_categories ) ) : ?>
												<?php
													$service_areas_chunks = array_chunk( $service_areas_categories, 10, true );
												?>

												<?php foreach ( $service_areas_chunks as $chunk_key => $service_areas_categories_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs service-areas-menu service-areas-menu--categories">
															<?php foreach ( $service_areas_categories_chunk as $category ) : ?>
																<?php if ( $category == 'all' ) : ?>
																	<li class="list-item list-item--has-child nav-item">
																		<a href="javascript: void(0);" class="link list-link active js-service-areas-subnav-opened" data-filter="all">
																			<span class="link-text">
																				<?= esc_attr( __( 'All', THEME_TEXTDOMAIN ) ) ?>
																			</span>

																			<span class="link-total-number">(<?= count( (array)$service_areas_list ) ?>)</span>

																			<span class="icon icon-wrap link__icon" aria-hidden="true">
																				<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
																			</span>
																		</a>
																	</li>
																	<?php continue;
																endif; ?>

																<li class="list-item list-item--has-child nav-item">
																	<a href="javascript: void(0);" class="link list-link js-service-areas-subnav-opened" data-filter="<?= esc_attr( $category->slug ) ?>">
																		<?php if ( !empty( $category->name ) ) : ?>
																			<span class="link-text">
																				<?= esc_html( $category->name ) ?>
																			</span>
																		<?php endif; ?>

																		<span class="icon icon-wrap link__icon" aria-hidden="true">
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

										<?php if ( ($service_areas_chunks && count( $service_areas_chunks ) > 1)) : ?>
											<div class="swiper-controls swiper-controls--fraction">
												<button class="swiper-button-prev">
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
												</button>

												<button class="swiper-button-next">
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
												</button>

												<div class="swiper-pagination"></div>
											</div>
										<?php endif; ?>
									</div>
								</div>

								<div class="d-block d-md-none">
									<div class="swiper swiper-container js-service-areas-navigation-slider">
										<div class="swiper-wrapper">
											<?php if ( $section_type == 'service_areas' ) : ?>
												<?php
													$service_areas_chunks = array_chunk( $service_areas_list, 4, true );
												?>
												<?php foreach ( $service_areas_chunks as $service_areas_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs service-areas-menu service-areas-menu--projects">
															<?php foreach ( $service_areas_chunk as $key => $service_area ) : ?>
																<?php
																	$id = $block_id . '-' . $service_area->post_name;
																	$lat = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['lat'] : '';
																	$lng = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['lng'] : '';
																?>
																<li class="list-item nav-item visible" data-filter-values="all">
																	<a
																		href="javascript: void(0);"
																		class="link nav-link list-link js-change-map-project <?= esc_attr( !$key ? 'active' : '' ) ?>"
																		data-project="<?= esc_attr( $id ) ?>"
																		data-coordinates-lat="<?= esc_attr( $lat ) ?>"
																		data-coordinates-lng="<?= esc_attr( $lng ) ?>"
																	>
																		<?= esc_html( $service_area->post_title ) ?>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endforeach; ?>

											<?php elseif ( $section_type == 'categories' && !empty( $service_areas_categories ) ) : ?>
												<?php
													$service_areas_categories_chunks = array_chunk( $service_areas_categories, 4, true );
												?>
												<?php foreach ( $service_areas_categories_chunks as $chunk_key => $service_areas_categories_chunk ) : ?>
													<div class="swiper-slide">
														<ul class="list list--unstyled nav nav-tabs service-areas-menu service-areas-menu--categories">
															<?php foreach ( $service_areas_categories_chunk as $category ) : ?>
																<?php if ( $category == 'all' ) : ?>
																	<li class="list-item list-item--has-child nav-item">
																		<a href="javascript: void(0);" class="link list-link active js-service-areas-subnav-opened" data-filter="all">
																			<span class="link-text">
																				<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
																			</span>

																			<span class="link-total-number">(<?= count( (array)$service_areas_list ) ?>)</span>

																			<span class="icon icon-wrap link__icon" aria-hidden="true">
																				<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
																			</span>
																		</a>
																	</li>
																	<?php continue;
																endif; ?>

																<li class="list-item list-item--has-child nav-item">
																	<a href="javascript: void(0);" class="link list-link js-service-areas-subnav-opened" data-filter="<?= esc_attr( $category->slug ) ?>">
																		<?php if ( !empty( $category->name ) ) : ?>
																			<span class="link-text">
																				<?= esc_html( $category->name ) ?>
																			</span>
																		<?php endif; ?>

																		<span class="icon icon-wrap link__icon" aria-hidden="true">
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

										<?php if ( ($service_areas_categories_chunks && count( $service_areas_categories_chunks ) > 1) || ($service_areas_chunks && count( $service_areas_chunks ) > 1) ) : ?>
											<div class="swiper-controls swiper-controls--fraction">
												<button class="swiper-button-prev">
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
												</button>

												<button class="swiper-button-next">
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
												</button>

												<div class="swiper-pagination"></div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<div class="service-areas-subnav collapse">
								<div class="service-areas-subnav__back">
									<div class="service-areas-subnav__title section-title section-title--style5">
										<?= esc_html( __( 'All categories', THEME_TEXTDOMAIN ) ) ?>
									</div>

									<button class="button button-bordered button-bordered-white service-areas-subnav__button js-service-areas-subnav-back" aria-label="<?= esc_html( __( 'Back to categories', THEME_TEXTDOMAIN ) ) ?>">
										<span class="icon icon-wrap button__icon" aria-hidden="true">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
										</span>

										<span class="button__text">
											<?= esc_html( __( 'Back', THEME_TEXTDOMAIN ) ) ?>
										</span>
									</button>
								</div>

								<div class="scrollbar-outer">
									<ul class="list list--unstyled nav nav-tabs service-areas-menu service-areas-menu--projects">
										<?php foreach ( $service_areas_list as $key => $service_area ) : ?>
											<?php
												$filter_cats = '';
												$service_area_taxonomies = wp_get_post_terms( $service_area->ID, 'hmt_service_area_category' );

												foreach ( $service_area_taxonomies as $service_area_taxonomy ) {
													$filter_cats .= $service_area_taxonomy->slug . ' ';
												}

												$id = $block_id . '-' . $service_area->post_name;

												$lat = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['lat'] : '';
												$lng = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['lng'] : '';
											?>
											<li class="list-item nav-item visible" data-filter-values="all <?= esc_html( $filter_cats ) ?>">
												<?php if ( isset( $service_area->post_title ) && $service_area->post_title ) : ?>
													<a href="javascript: void(0);" class="link nav-link list-link <?= esc_attr( !$key ? 'active' : '' ) ?> js-change-map-project" data-project="<?= esc_attr( $id ) ?>" data-coordinates-lat="<?= esc_attr( $lat ) ?>" data-coordinates-lng="<?= esc_attr( $lng ) ?>">
														<?= esc_html( $service_area->post_title ) ?>
													</a>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="section-service-areas__map">
			<div class="service-areas-map js-service-areas-map" id="service-areas-map-<?= esc_html( $block_id ) ?>"></div>
		</div>

		<script type="text/javascript">
			window['sitePath'] = '<?= THEME_ASSETS_URL ?>';
			window['themeVars_' + '<?= $block_id ?>'] = JSON.parse('<?php echo json_encode( $theme_vars ); ?>');
		</script>
	</div>
<?php endif ?>