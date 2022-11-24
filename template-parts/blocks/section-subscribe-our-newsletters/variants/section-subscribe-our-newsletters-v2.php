<?php
/**
 * Section Our Newsletters.
 *
 * @var $args
 */

$is_preview = $args['is_preview'];

$section_title = get_field( 'subscribe_our_newsletters_section_title' );
$section_subtitle = get_field( 'subscribe_our_newsletters_section_subtitle' );

$ninja_form = get_field('cloneable_ninja_form_selector');
$is_default_ninja_form = $ninja_form['use_default_form'] ?? true;

$ninja_form_id = !$is_default_ninja_form && !empty($ninja_form['ninja_form_id_selector'])
	? $ninja_form['ninja_form_id_selector']
	: get_field( 'subscription_form_id', 'option' );
?>

<?php if( $ninja_form_id ): ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-subscribe-our-newsletters__bg',
		'field_prefix' => 'subscribe_our_newsletters_section_background',
		'field_id' => ''
	] );
	?>


	<div class="section__body">
		<div class="section-subscribe-our-newsletters__content">
			<div class="container">
				<div class="section-subscribe-our-newsletters__wrapper">
					<div class="section-subscribe-our-newsletters__header">
						<?php if ( $section_title ) : ?>
							<div class="section-title section-title--style1 section-subscribe-our-newsletters__title">
								<h2>
									<?= esc_html( $section_title ); ?>
								</h2>
							</div>
						<?php endif; ?>

						<?php if ( $section_subtitle ) : ?>
							<div class="section-subscribe-our-newsletters__description">
								<?= wpautop( $section_subtitle ); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="section-subscribe-our-newsletters__form">
						<?php if ( empty( $is_preview ) ) : ?>
							<?php if ( !empty( $ninja_form_id ) ) : ?>
								<?= do_shortcode( "[ninja_form id={$ninja_form_id}]" ); ?>
							<?php endif; ?>

						<?php else : ?>
							<div class="section-subscribe-our-newsletters__form-example">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/newsletters_example.svg' ) ?>
							</div>

						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>