<?php

$section_id = 'value-prop';

$section_title = get_field( 'value_prop_section_title' );
$value_props = get_field( 'value_prop_section_props' );
?>

<?php if( is_array($value_props) && $value_props[0]): ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-value-prop__bg',
		'field_prefix' => 'value_prop_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-value-prop__content">
			<div class="container">
				<div class="section-value-prop__header">
					<?php if ( isset( $section_title ) && $section_title ): ?>
						<div class="section-title section-title--style1 section-value-prop__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					<?php endif; ?>
				</div>

				<div class="section-value-prop__props-container">
					<?php foreach ( $value_props as $key => $value ) : ?>
						<?php extract( $value ) ?>
						<?php if( $img && $name && $description ): ?>
							<div class="props-card">
								<div class="props-card__main-content">
									<?php if ( isset( $img ) && $img ) : ?>
										<div class="props-card__logo icon-wrap">
											<?php
											$img_url = esc_url( wp_get_attachment_image_url( $img ) );
											$img_alt = esc_attr( hmt_get_attachment_image_alt( $img ) );
											?>
											<?= hmt_is_svg( $img ) ? hmt_get_svg_inline( wp_get_attachment_image_url( $img, 'thumbs-mobile' ) ) : "<img src=\"{$img_url}\" alt=\"{$img_alt}\" />"; ?>
										</div>
									<?php endif; ?>

									<?php if ( isset( $name ) && $name ) : ?>
										<div class="props-card__title props-card__title--main section-title">
											<h3>
												<?= esc_html( $name ) ?>
											</h3>
										</div>
									<?php endif; ?>
								</div>

								<div class="props-card__full">
									<div class="props-card__full-body">

										<div class="scrollbar-outer scrollbar--fade">
											<div class="props-card__full-content">
												<?php if ( isset( $name ) && $name ) : ?>
													<div class="props-card__title props-card__title--full-title section-title section-title--style5">
														<h3>
															<?= esc_html( $name ) ?>
														</h3>
													</div>
												<?php endif; ?>

												<?php if ( isset( $description ) && $description ) : ?>
													<div class="props-card__description text-content">
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
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>