<?php
/**
 * @var $args ;
 */

$block_id = $args['block_id'];

$section_title = get_field( 'leadership_section_title' );
$section_description = get_field( 'leadership_section_description' );
$leadership_group = (array)get_field( 'leadership_section_group' );
?>

<?php if ( is_array($leadership_group) && $leadership_group[0] ) : ?>

	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-leadership__bg',
		'field_prefix' => 'leadership_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-leadership__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-title section-title--style1 section-leadership__title">
						<h2>
							<?= esc_html( $section_title ) ?>
						</h2>
					</div>
				<?php endif ?>

				<?php if ( $section_description ) : ?>
					<div class="section-leadership__section-description text-content">
						<?= apply_filters( 'the_content', $section_description ) ?>
					</div>
				<?php endif ?>

				<?php if ( count( $leadership_group ) >= 1 ) : ?>
					<div class="section-leadership__slider-nav">
						<button class="swiper-button swiper-button-prev">
							<span class="desktop">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</span>

							<span class="mobile">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
							</span>
						</button>

						<button class="swiper-button swiper-button-next">
							<span class="desktop">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
							</span>

							<span class="mobile">
								<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
							</span>
						</button>

						<div class="section-leadership__slider-pagination"></div>
					</div>
				<?php endif ?>

				<div class="swiper section-leadership__slider js-leadership-slider">
					<div class="swiper-wrapper">
						<?php foreach ( $leadership_group as $index => $group ) : ?>
							<?php if ( $group ) : ?>
								<?php extract( $group ) ?>
								<?php if ( !empty( $photo ) && (!empty( $first_name ) || !empty( $last_name )) && !empty( $description ) && !empty( $position )) : ?>
									<div class="swiper-slide">
										<div class="person-card">
											<div class="person-card__img">
												<div class="background-img">
													<?= wp_get_attachment_image( $photo, 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $photo ) )] ); ?>
												</div>
											</div>

											<div class="person-card__text-main-wrapper">
												<div class="person-card__title person-card__title--main section-title section-title--style5">
													<h3>
														<?= esc_html( $first_name ) . ' ' . esc_html( $last_name ) ?>
													</h3>
												</div>

												<div class="person-card__subtitle person-card__subtitle--main section-title section-title--style6">
													<h4>
														<?= esc_html( $position ) ?>
													</h4>
												</div>
											</div>

											<div class="person-card__full">
												<div class="person-card__full-body">
													<div class="scrollbar-outer">
														<div class="person-card__full-content">
														<?php if ( !empty( $first_name ) ) : ?>
															<div class="person-card__title section-title section-title--style3">
																<h3>
																	<?= esc_html( $first_name ) . '<br>' . esc_html( $last_name ) ?>
																</h3>
															</div>
														<?php endif; ?>

														<?php if ( !empty( $position ) ) : ?>
															<div class="person-card__subtitle person-card__subtitle--additional section-title section-title--style6">
																<h4>
																	<?= esc_html( $position ) ?>
																</h4>
															</div>
														<?php endif; ?>

														<?php if ( !empty( $description ) ) : ?>
															<?php
																$extended_description = get_extended( $description );
																$main_text = trim($extended_description['main']);
															?>

															<?php if( $extended_description['extended'] ): ?>
																<div class="person-card__description text-content ellipsis">
																	<?=  apply_filters( 'the_content', $main_text ) ?>
																</div>
															<?php else: ?>
																<div class="person-card__description desktop text-content">
																	<?= hmt_truncate( apply_filters( 'the_content', $main_text ), 400, array('html' => true, 'ending' => '...') ) ?>
																</div>

																<div class="person-card__description tablet text-content">
																	<?= hmt_truncate( apply_filters( 'the_content', $main_text ), 100, array('html' => true, 'ending' => '...') ) ?>
																</div>

																<div class="person-card__description mobile text-content">
																	<?= hmt_truncate( apply_filters( 'the_content', $main_text ), 150, array('html' => true, 'ending' => '...') ) ?>
																</div>
															<?php endif; ?>

														<?php endif; ?>
													</div>
													</div>
												</div>

												<div class="person-card__full-footer">
													<a
														class="button button-bordered button-bordered-white person-card__button"
														role="button"
														data-toggle="modal"
														href="#modal-person-<?= esc_attr( $block_id . '-' . $index ) ?>"
													>
														<?= esc_html( __( 'View Bio', THEME_TEXTDOMAIN ) ) ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Team Member Popup -->
	<?php foreach ( $leadership_group as $index => $group ): ?>
		<?php
		get_template_part(
			'template-parts/blocks/section-leadership/popups/team-member-popup',
			'',
			[
				'popup_id' => 'modal-person-' . $block_id . '-' . $index,
				'team_member_info' => $group,
			]
		);
		?>
	<?php endforeach; ?>
	<!-- Team Member Popup End -->
<?php endif ?>
