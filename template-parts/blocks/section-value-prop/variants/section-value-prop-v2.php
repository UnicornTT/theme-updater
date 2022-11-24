<?php
/**
 * Section Value Prop V2.
 *
 * @var $block
 */

$section_id = 'value-prop';

$section_title = get_field( 'value_prop_section_title' );
$value_props = get_field( 'value_prop_section_props' );
$link = get_field( 'value_prop_section_button_link' );
?>

<?php if ( is_array($value_props) && $value_props[0] ) : ?>
	<?php if( $value_props[0]['img'] && $value_props[0]['name'] && $value_props[0]['description'] ): ?>
		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-value-prop__bg',
			'field_prefix' => 'value_prop_section_background',
			'field_id' => ''
		] );
		?>
		
		<div class="section__body">
			<div class="section-value-prop__content js-value-prop-font js-font-adjustment" data-fj-min="24">
				<div class="container">
					<div class="value-prop">
						<div class="value-prop__content tab-content">
							<?php foreach ( $value_props as $index => $value ) : ?>
								<?php extract( $value ) ?>
								<?php if( $img && $name && $description ): ?>
									<div class="tab-pane fade<?= esc_attr( $index == 0 ? " active show" : "" ) ?>" id="tab-pane-<?= esc_attr( $index ) ?>" role="tabpanel" aria-labelledby="tab-link-<?= esc_attr( $index ) ?>">

										<div class="value-prop__info-header">
											<?php if ( isset( $section_title ) && $section_title ) : ?>
												<div class="section-title--style6 value-prop__section-title" aria-hidden="true">
													<h2>
														<?= esc_html( $section_title ) ?>
													</h2>
												</div>
											<?php endif; ?>

											<?php if ( isset( $name ) && $name ) : ?>
												<div class="section-title--style1 value-prop__title js-font-title">
													<?= esc_html( $name ) ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="value-prop__info-wrapper">
											<div class="value-prop__info-main">
												<div class="scrollbar-outer">
													<div class="value-prop__info">
														<?php if ( isset( $description ) && $description ) : ?>
															<div class="text-content">
																<?= wpautop( $description ) ?>
															</div>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>

							<?php if ( !empty( $link ) ) : ?>
								<?php
									$url = $link['url'] ?? '';
									$link_title = $link['title'] ?? '';
									$target = $link['target'] ? $link['target'] : '_self';
									$class = $url == '#' ? 'js-scroll-link' : '';
								?>
								<div class="value-prop__info-footer">
									<a href="<?= esc_url( $url ) ?>" target="<?= esc_attr( $target ) ?>" class="button button-bordered button-bordered-white-black value-prop__button <?= esc_attr( $class ) ?>">
										<?= $link_title ? esc_html( $link_title ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
									</a>
								</div>
							<?php endif ?>
						</div>

						<div class="value-prop__selector">
							<div class="value-prop__list">
								<div class="scrollbar-outer">
									<div class="value-prop__items-wrapper nav nav-pills" role="tablist">
										<?php foreach ( $value_props as $index => $value ) : ?>
											<?php extract( $value ) ?>
											<?php if( $img && $name && $description ): ?>
												<a class="value-prop__item <?= esc_attr( $index == 0 ? "active" : "" ) ?>" data-title="<?= esc_attr( $value['name'] ) ?>" data-toggle="tab" href="#tab-pane-<?= esc_attr( $index ) ?>" id="tab-link-<?= esc_attr( $index ) ?>" role="tab" aria-controls="tab-pane-<?= esc_attr( $index ) ?>" <?= esc_attr( $index == 0 ? 'aria-selected=true' : 'aria-selected=false' ) ?>>
													<?php if ( isset( $img ) && $img ) : ?>
														<div class="value-prop__item-logo">
															<?php
																$img_url = esc_url( wp_get_attachment_image_url( $img, 'thumbs-mobile' ) );
																$img_alt = esc_attr( hmt_get_attachment_image_alt( $img ) );
															?>
															<?= hmt_is_svg( $img ) ? hmt_get_svg_inline( wp_get_attachment_image_url( $img ) ) : "<img src=\"{$img_url}\" alt=\"{$img_alt}\" />"; ?>
														</div>
													<?php endif; ?>

													<?php if ( isset( $name ) && $name ) : ?>
														<div class="value-prop__item-title">
															<?= esc_html( $name ) ?>
														</div>
													<?php endif; ?>
												</a>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif ?>
