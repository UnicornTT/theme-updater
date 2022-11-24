<?php
/**
 * @var $args ;
 */

$block_id = $args['block_id'];

$section_title = get_field( 'our_team_section_title' );
$our_team_group = (array)get_field( 'our_team_section_group' );

$action_button = get_field( 'our_team_section_action_button' );
if($action_button){
	$action_button_type = $action_button['type'] ?? '';
	$action_button_text = $action_button['text'] ?? '';
}
?>

<?php if ( $our_team_group[0] ) : ?>

	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-our-team__bg',
		'field_prefix' => 'our_team_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-our-team__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-title section-title--style1 section-our-team__title">
						<h2>
							<?= esc_html( $section_title ) ?>
						</h2>
					</div>
				<?php endif; ?>

				<div class="section-our-team__teams-wrapper">
					<div class="section-our-team__slider-team-bullets-wrapper">
						<div class="swiper section-our-team__slider-team-bullets js-team-bullets">
							<div class="swiper-wrapper">
								<?php foreach ( $our_team_group as $index => $team ): ?>
									<div
										class="swiper-slide"
										data-slide-index="<?= esc_attr( $block_id . '_' . $index ) ?>"
									>
										<?= esc_html( $team['title'] ) ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="section-our-team__slider-team-bullets-navigation">
							<button class="swiper-button swiper-button-prev">
								<span>
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
								</span>
							</button>

							<button class="swiper-button swiper-button-next">
								<span>
									<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left-bold.svg' ); ?>
								</span>
							</button>
						</div>
					</div>

					<div class="swiper section-our-team__slider-team-miltimple js-team-multiple-slider">
						<div class="swiper-wrapper">
							<?php foreach ( $our_team_group as $group ) : ?>
								<?php
								$section_gallery = $group['gallery'];
								?>
								<div class="swiper-slide">
									<div class="section-our-team__main-content-wrapper">
										<div class="section-our-team__text-content-wrapper">
											<div
												class="section-title section-title--style3 section-our-team__text-title"
											>
												<h2>
													<?= esc_html( $group['title'] ) ?>
												</h2>
											</div>

											<div class="section-our-team__description text-content">
												<?= apply_filters( 'the_content', $group['description'] ) ?>
											</div>

											<?php if( $action_button_type ): ?>
												<?php if ( 'link' === $action_button_type ) : ?>
													<?php
													$action_button_link = $action_button['link'] ?? '';
													$button_text = $action_button_link['title'] ?: '';
													$url = $action_button_link['url'] ?: '';
													$target = $action_button_link['target'] ?: '_self';
													?>
													<a
														href="<?= esc_url( $url ); ?>" target="<?= esc_attr( $target ); ?>"
														class="button button-bordered section-our-team__button"
													>
														<?= $button_text ? esc_html( $button_text ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
													</a>

												<?php elseif ( 'modal' === $action_button_type ) : ?>
													<?php
													$action_button_modal = get_field( 'our_team_section_action_button_modal' ) ?? '';
													\Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $action_button_modal );
													?>
													<a
														href="#<?= esc_attr( $action_button_modal ) ?>" data-toggle="modal"
														class="button button-bordered section-our-team__button"
													>
														<?= esc_html( $action_button_text ) ?>
													</a>
												<?php endif; ?>
											<?php endif; ?>
										</div>

										<div class="section-our-team__media-wrapper">
											<?php if ( !empty( $section_gallery ) && count( $section_gallery ) ): ?>
												<?php if ( count( $section_gallery ) === 1 ) : ?>
													<div class="section-our-team__image">
														<div class="background-img">
															<?= wp_get_attachment_image( $section_gallery[0], 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $section_gallery[0] ) )] ); ?>
														</div>
													</div>
												<?php else: ?>

													<div class="section-our-team__gallery-wrapper">
														<div
															class="swiper section-our-team__gallery js-section-our-team"
														>
															<div class="swiper-wrapper">
																<?php foreach ( $section_gallery as $item_gallery ): ?>
																	<div class="swiper-slide">
																		<div class="section-our-team__gallery-item">
																			<div class="background-img">
																				<?= wp_get_attachment_image( $item_gallery, 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $item_gallery ) )] ); ?>
																			</div>
																		</div>
																	</div>
																<?php endforeach; ?>
															</div>
														</div>

														<div
															class="swiper section-our-team__gallery-thumbs js-section-our-team-thumbs"
														>
															<div class="swiper-wrapper">
																<?php foreach ( $section_gallery as $i => $item_gallery ): ?>
																	<div
																		class="swiper-slide"
																		data-slide-index="<?= esc_attr( $i ) ?>"
																	>
																		<div
																			class="section-our-team__gallery-thumbs-item"
																		>
																			<div class="background-img">
																				<?= wp_get_attachment_image( $item_gallery, 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $item_gallery ) )] ); ?>
																			</div>
																		</div>
																	</div>
																<?php endforeach; ?>
															</div>
														</div>
													</div>

													<div class="section-our-team__slider-nav">
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

														<div class="section-our-team__slider-pagination"></div>
													</div>
												<?php endif; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>
