<?php
/**
 * Section Events Hero Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$block_style = hmt_get_block_style_name( $block );

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
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

$class_name = 'section section-events-hero';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$class_name .= ' section-events-hero--style-v1' . $block_style;
$section_title = get_field( 'events_hero_section_title' );
$section_description = get_field( 'events_hero_section_description' );
$button_text = get_field( 'events_hero_section_button_text' );
$button_page = get_field( 'events_hero_section_button_link' );

$calendar_class_name = $section_top_padding . ' ' . $section_bottom_padding;

if ( $button_page ) {
	$button_link = $button_page['url'];
}

$shortcode_name = get_field( 'mec_events_shortcode_name' );
$shortcode = get_posts( [
	'post_type' => 'mec_calendars',
	'name' => $shortcode_name
] );

if($shortcode){
	if( is_array(get_post_meta($shortcode[0]->ID, 'skin')) ){
		$shortcode_template = get_post_meta($shortcode[0]->ID, 'skin')[0];
	}
}

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( $shortcode && !empty( $section_title ) && $section_title ): ?>
	<?php if ( isset($shortcode_template) && $shortcode_template == 'monthly_view' ): ?>
		<section class="section section-calendar-events alignfull <?= esc_attr( $calendar_class_name ); ?>">
			<?php
				get_template_part( 'parts/resources/section-background', '', [
					'class_name' => 'section-calendar-events__bg',
					'field_prefix' => 'events_hero_section_background',
					'field_id' => ''
				] );
			?>

			<div class="section__body">
				<div class="container">
					<div class="section-calendar-events__content">
						<div class="calendar">
							<?php if ( $section_title ) : ?>
								<div class="calendar__header">
									<div class="section-title section-title--style1 calendar__title">
										<h2>
											<?= esc_html( $section_title ) ?>
										</h2>
									</div>
								</div>

								<?php if ( empty( $is_preview ) ) : ?>
									<?php if ( !empty( $shortcode[0]->ID ) ): ?>
										<div class="calendar__content">
											<?= do_shortcode( '[MEC id="' . $shortcode[0]->ID . '"]' ) ?>
										</div>
									<?php endif ?>
								<?php else : ?>
									<div class="calendar__calendar-example">
										<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/event-calendar-example.svg' ) ?>
									</div>
								<?php endif; ?>

								<div class="calendar__card">
									<div class="calendar__card-date">
										<span class="text-date"></span>
									</div>

									<div class="calendar__card-events"></div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php elseif($shortcode_template == 'grid'): ?>
		<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
			<?php
			get_template_part( 'parts/resources/section-background', '', [
				'class_name' => 'section-events-hero__bg',
				'field_prefix' => 'events_hero_section_background',
				'field_id' => ''
			] );
			?>

			<div class="section__body">
				<div class="section-events-hero__content">
					<div class="container">
						<div class="section-events-hero__header-container">
							<div class="section-events-hero__header">
								<?php if ( $section_title ) : ?>
									<div class="section-title section-title--style1 section-events-hero__title">
										<h2>
											<?= esc_html( $section_title ) ?>
										</h2>
									</div>
								<?php endif; ?>

								<?php if ( $section_description ) : ?>
									<div class="section-description section-events-hero__description text-content">
										<?= apply_filters( 'the_content', $section_description ) ?>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( !empty( $button_link ) ) : ?>
								<a
									href="<?= !empty( $button_link ) ? esc_url( $button_link ) : '' ?>"
									target="<?= !empty( $button_page['target'] ) ? $button_page['target'] : '' ?>"
									class="button button-bordered section-events-hero__button"
								>
									<?= esc_html( $button_page['title'] ) ?>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<?= do_shortcode( '[MEC id="' . $shortcode[0]->ID . '"]' ) ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endif; ?>