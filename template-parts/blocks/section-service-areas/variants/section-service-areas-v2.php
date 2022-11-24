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

foreach ( $service_areas_list as $key => $service_area ) {
	$id = $block_id . '-' . $service_area->post_name;
	$gallery = [];

	$service_area_title = $service_area->post_title;
	$service_area_lat = get_field( 'service_areas_location', $service_area->ID ) ? (string)get_field( 'service_areas_location', $service_area->ID )['lat'] : '';
	$service_area_lng = get_field( 'service_areas_location', $service_area->ID ) ? (string)get_field( 'service_areas_location', $service_area->ID )['lng'] : '';

	if ( !$service_area_title || !$service_area_lat || !$service_area_lng ) {
		unset( $service_areas_list[$key] );
		continue;
	}

	$service_areas_list_json[] = [
		'id' => $id,
		'title' => $service_area_title,
		'coordinates' => [
			'lat' => $service_area_lat,
			'lng' => $service_area_lng,
		],
	];
}

// Throw variables from back to front end.
$theme_vars = array(
	'data' => $service_areas_list_json,
);

if ( !function_exists( 'getTermName' ) ) {
	function getTermName( $term ) {
		return $term->name;
	}
}

?>

<?php if ( $service_areas_list ) : ?>

	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-seo-content__bg',
		'field_prefix' => 'service_areas_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-service-areas__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-service-areas__header">
						<div class="section-title section-title--style1 section-service-areas__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>
				<?php endif; ?>

				<div class="section-service-areas__body">
					<?php if ( $section_type == 'categories' ) : ?>
						<div class="section-service-areas__filter">
							<div class="section-service-areas__filter-list js-horizontal-scroll">
								<?php foreach ( $service_areas_categories as $key => $value ) : ?>
									<?php if ( $value == 'all' ) {
										$title = __( 'All', THEME_TEXTDOMAIN );
									} else {
										$title = $value->name;
									}
									?>
									<div class="section-service-areas__filter-item-wrapper">
										<button class="section-service-areas__filter-item js-filter<?= $value == 'all' ? ' active' : '' ?>" data-filter="<?= esc_attr( $title ) ?>">
											<?= esc_html( $title ) ?>
										</button>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="section-service-areas__slider js-service-areas__slider swiper">
						<div class="swiper-wrapper">
							<?php foreach ( $service_areas_list as $key => $service_area ) : ?>
								<?php
									$terms = get_the_terms( $service_area->ID, 'hmt_service_area_category' );
									$terms = implode( ";", array_map( "getTermName", $terms ) );
									$address = get_field( 'service_areas_location', $service_area->ID ) ? get_field( 'service_areas_location', $service_area->ID )['address'] : '';
									$lat = get_field( 'service_areas_location', $service_area->ID ) ? (string)get_field( 'service_areas_location', $service_area->ID )['lat'] : '';
									$lng = get_field( 'service_areas_location', $service_area->ID ) ? (string)get_field( 'service_areas_location', $service_area->ID )['lng'] : '';
									$add_custom_link = get_field( 'service_areas_add_custom_link', $service_area->ID );
									$custom_url = get_field( 'service_areas_link', $service_area->ID );
									$link = "https://maps.google.com/?q={$address}";
									if($add_custom_link && !empty($custom_url)) {
										$link = $custom_url;
									}
								?>
								<div class="swiper-slide js-filter-item" data-filter-item="<?= esc_attr( $terms ) ?>">
									<div class="service-card">
										<?php if ( !empty( $service_area->post_title ) ) : ?>
											<div class="service-card__header">
                                                <span class="service-card__header-icon">
                                                    <?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-position-marker-areas.svg' ); ?>
                                                </span>

												<?= esc_html( $service_area->post_title ) ?>
											</div>
										<?php endif; ?>

										<?php if ( $address ) : ?>
											<div class="service-card__data">
												<a class="service-card__address" href="<?= esc_url( $link ) ?>" target="_blank">
													<?= esc_html( $address ) ?>
												</a>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="section-service-areas__slider-nav">
							<button class="swiper-button swiper-button-prev">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</button>

							<div class="swiper-pagintaion"></div>

							<button class="swiper-button swiper-button-next">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>