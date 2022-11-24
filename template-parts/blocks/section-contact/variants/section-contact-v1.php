<?php
/**
 * Section Our Newsletters.
 *
 * @var $args
 */

$is_preview = $args['is_preview'];
$contact_form = get_field( 'contact_form' );
$section_title = get_field( 'contact_section_title' );
$section_description = get_field( 'contact_section_description' );

$ninja_form = get_field('cloneable_ninja_form_selector');
$is_default_ninja_form = $ninja_form['use_default_form'] ?? true;

$ninja_form_id = !$is_default_ninja_form && !empty($ninja_form['ninja_form_id_selector'])
	? $ninja_form['ninja_form_id_selector']
	: get_field( 'contact_us_form_id', 'option' );
?>

<?php if($ninja_form_id): ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-contact-us__bg',
		'field_prefix' => 'contact_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="container">
			<div class="section-contact-us__content">
				<div class="section-contact-us__header">
					<?php if ( $section_title ) : ?>
						<div class="section-title section-title--style1 section-contact-us__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					<?php endif; ?>

					<?php if ( $section_description ) : ?>
						<div class="section__description section-contact-us__description">
							<?= apply_filters( 'the_content', $section_description ) ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $section_title || $section_description ) : ?>
					<div class="section-contact-us__form">
						<?php if ( empty( $is_preview ) ) : ?>
							<?php if ( !empty( $ninja_form_id ) ): ?>
								<?= do_shortcode( "[ninja_form id={$ninja_form_id}]" ) ?>
							<?php endif ?>
						<?php else : ?>
							<div class="section-contact-us__form-example">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/contact_us_example_v1.svg' ) ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>