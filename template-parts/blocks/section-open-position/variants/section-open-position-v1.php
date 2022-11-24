<?php

/**
 * @var $args array
 */

$block_id = $args['blockId'];
$vacancies_list = $args['vacancies_list'];
$section_title = get_field( 'open_position_section_title' );

$filter_vacancies_type = get_field( 'vacancy_filter' );

$vacancies_list_json = [];

foreach ( $vacancies_list as $vacancy ) {
	$id = $block_id . '-' . $vacancy->ID;
	$title = $vacancy->post_title;
	$general = get_field( 'vacancy_general_information', $vacancy->ID );
	$specifications = get_field( 'vacancy_specifications_group', $vacancy->ID );

	$specifications_filtered = [];

    if( $specifications ){
        foreach ( $specifications as $specification ) {
            $specifications_filtered[] = [
                'specification_title' => $specification['specification_title'],
                'specification_content' => urlencode( apply_filters( 'the_content', $specification['specification_description'] ) )
            ];
        }
    }

	$vacancies_list_json[] = [
		'id' => $id,
		'vacancy_id' => $vacancy->ID,
		'title' => $title,
		'general' => urlencode( apply_filters( 'the_content', $general ) ),
		'specifications' => $specifications_filtered
	];
}

$theme_vars = array(
	'data' => $vacancies_list_json,
);
?>

<?php if ( $vacancies_list && !empty( $vacancies_list ) ) : ?>
	<?php
        get_template_part( 'parts/resources/section-background', '', [
            'class_name' => 'section-open-position__bg',
            'field_prefix' => 'open_position_section_background',
            'field_id' => ''
        ] );
    ?>

	<div class="section__body">
        <div class="container">
            <div class="section-open-position__content">
                <div class="section-open-position__header">
                    <div class="section-title section-title--style1 section-open-position__title">
                        <h2>
                            <?= esc_html( $section_title ) ?>
                        </h2>
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

                <div class="swiper section-open-position__slider js-open-position-slider-v1">
                    <div class="swiper-wrapper">
                        <?php foreach ( $vacancies_list as $vacancy ) : 
                            $vacancy_name = get_the_title( $vacancy->ID );
                            $vacancy_image_url = get_field( 'vacancy_image', $vacancy->ID );
                            $vacancy_time = get_field( 'vacancy_work_time', $vacancy->ID );
                            $vacancy_location = get_field( 'vacancy_work_location', $vacancy->ID );
                            ?>
                            <div class="swiper-slide">
                                <div class="position-card">
                                    <div class="position-card__image">
                                        <div class="background-img">
                                            <picture>
                                                <img 
													src="<?= esc_url( wp_get_attachment_image_url( $vacancy_image_url, 'section-background-mobile' ) ) ?>" 
													alt="<?= esc_attr( hmt_get_attachment_image_alt( $vacancy_image_url ) ) ?>"
												>
                                            </picture>
                                        </div>
                                    </div>

                                    <div class="position-card__text-content">
                                        <div class="position-card__postion">
                                            <?= esc_html($vacancy_name) ?>
                                        </div>

                                        <div class="position-card__postion-info">
                                            <span class="position-card__postion-info-time">
                                                <?= esc_html($vacancy_time) ?>
                                            </span>

                                            <span class="position-card__postion-info-location">
                                                <?= esc_html($vacancy_location) ?>
                                            </span>
                                        </div>

                                        <button data-position-id="<?= $id = $block_id . '-' . $vacancy->ID; ?>" class="button button-primary position-card__button">
                                            <?= esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ) ?>
										</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

		<script type="text/javascript">
			window['themeVars_' + '<?= $block_id ?>'] = JSON.parse('<?= json_encode($theme_vars); ?>');
		</script>
    </div>
<?php endif; ?>
