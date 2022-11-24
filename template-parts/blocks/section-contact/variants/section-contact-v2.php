<?php
/**
 * Section Contact Block Template.
 *
 * @var $args
 */

$is_preview = $args['is_preview'];
$contact_form = get_field( 'contact_form' );
$contact_faq = get_field( 'contact_faq' );
$section_contact_title = get_field( 'contact_section_title' );
$section_contact_description = get_field( 'contact_section_description' );
$section_faq_title = $contact_faq['contact_section_faq_title'] ?? '';

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
				<div class="row justify-content-between">
					<div class="col-12 col-xl-6 justufy-content-between align-self-end">
						<div class="section-contact-us__main">
							<div class="section-contact-us__header">
								<?php if ( $section_contact_title ) : ?>
									<div class="section-title section-title--style1 section-contact-us__title">
										<h2>
											<?= esc_html( $section_contact_title ) ?>
										</h2>
									</div>
								<?php endif; ?>

								<?php if ( $section_contact_description ) : ?>
									<div class="section__description section-contact-us__description">
										<?= apply_filters( 'the_content', $section_contact_description ) ?>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( $section_contact_title || $section_contact_description ) : ?>
								<div class="section-contact-us__form">
									<?php if ( empty( $is_preview ) ) : ?>
										<?php if ( !empty( $ninja_form_id ) ): ?>
											<?= do_shortcode( "[ninja_form id={$ninja_form_id}]" ) ?>
										<?php endif ?>
									<?php else : ?>
										<div class="section-contact-us__form-example">
											<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/contact_us_example_v2.svg' ) ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="col-12 col-xl-5">
						<?php if ( isset( $contact_faq['contact_section_faq'] ) && !empty( $contact_faq['contact_section_faq'] ) ) : ?>
							<div class="section-contact-us__faq">
								<div class="faq-accordion">
									<?php if ( $section_faq_title ) : ?>
										<div class="section-title section-title--style5 faq-accordion__title">
											<h3>
												<?= esc_html( $section_faq_title ) ?>
											</h3>
										</div>
									<?php endif; ?>
									<?php if ( is_array( $contact_faq['contact_section_faq'] ) ) : ?>
										<div class="faq-accordion__body">
											<?php foreach ( $contact_faq['contact_section_faq'] as $key => $value ) : ?>
												<?php extract( $value ); ?>
												<?php if ( !empty( $faq_question ) && !empty( $faq_answer ) ) : ?>
													<div class="faq-item <?= $key == 0 ? esc_attr( 'opened' ) : '' ?>">
														<div class="faq-item__header">
															<div class="faq-item__title section-title">
																<h4>
																	<?= esc_html( $faq_question ) ?>
																</h4>
															</div>

															<button
																class="faq-item__button js-faq-item-toggler"
																aria-label="<?= esc_attr( __( 'Toggle question', THEME_TEXTDOMAIN ) ) ?>"
															>
																<span class="icon-wrap" aria-hidden="true">
																	<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-plus.svg' ); ?>
																</span>
															</button>
														</div>

														<div
															class="faq-item__body collapse <?= $key == 0 ? esc_attr( 'show' ) : '' ?>"
														>
															<div class="faq-item__answer text-content">
																<?= apply_filters( 'the_content', $faq_answer ) ?>
															</div>
														</div>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
