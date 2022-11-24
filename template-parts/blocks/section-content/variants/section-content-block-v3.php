<?php
/**
 * Section Content Block.
 *
 * @var $args
 */

$block_id = $args['block_id'];

$section_subtitle = get_field( 'content_block_section_kicker' );
$section_slider_section = get_field( 'content_block_section_type_slider' );
$section_slider = $section_slider_section['content_block_section_slider'] ?? '';
?>

<?php if (
	$section_subtitle
	&& !empty( $section_slider )
	&& !empty( $section_slider[0]['content_block_section_slider_title'] )
	&& !empty( $section_slider[0]['content_block_section_slider_description'] )
) : ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-content-block__bg',
			'field_prefix' => 'content_block_section_background',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="section-content-block__content js-content-block-slider js-font-adjustment" data-fj-min="34">
			<div class="container">
				<div class="row section-content-block__row">
					<div class="col-xl-6 col-md-8 col-12">
						<div class="section-content-block__left">
							<?php if ( $section_subtitle ) : ?>
								<div class="section-content-block__subtitle section-title section-title--style6">
									<?= esc_html( $section_subtitle ); ?>
								</div>
							<?php endif; ?>
							
							<?php if ( is_array( $section_slider ) ) : ?>
								<div class="swiper js-content-slider-info section-content-block__slider-info">
									<div class="swiper-wrapper">
										<?php foreach ( $section_slider as $slide ) : ?>
											<?php extract( $slide ); ?>
											<?php if ( !empty( $content_block_section_slider_title ) && !empty( $content_block_section_slider_description ) ) : ?>
												<div class="swiper-slide">
													<div class="section-content-block__header">
														<div
															class="section-title section-title--style2 section-content-block__title"
														>
															<h2 class="js-font-title">
																<?= esc_html( $content_block_section_slider_title ); ?>
															</h2>
														</div>
													</div>

													<div class="section-content-block__body">
														<div class="section-content-block__text-wrapper">
															<div class="scrollbar-outer">
																<div class="section-content-block__text text-content">
																	<?= apply_filters( 'the_content', $content_block_section_slider_description ); ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="col-xl-6 col-md-4 col-12">
						<div class="section-content-block__right">
							<?php if ( is_array( $section_slider ) ) : ?>
								<div class="swiper js-content-slider-cards section-content-block__slider-cards">
									<div class="swiper-wrapper">
										<?php $j = 0; ?>
										<?php foreach ( $section_slider as $i => $slide ) : ?>
											<?php
											$title = $slide['content_block_section_slider_title'];
											$description = $slide['content_block_section_slider_description'];
											?>
											<?php if ( !empty( $title ) && !empty( $description ) ) :
											$index = $j >= 10 ? ++$j : '0' . ++$j;
											?>
												<div class="swiper-slide" data-slide-index="<?= esc_attr( --$j ) ?>">
													<article class="article">
														<div class="article__top">
															<div class="article__index">
															<span>
																<?= esc_html( $index ); ?>
															</span>
															</div>
														</div>

														<?php if ( $title ) : ?>
															<div class="article__bottom">
																<div class="article__title">
																	<h3>
																		<?= esc_html( $title ); ?>
																	</h3>
																</div>
															</div>
														<?php endif; ?>
													</article>
												</div>
											<?php $j++ ?>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>