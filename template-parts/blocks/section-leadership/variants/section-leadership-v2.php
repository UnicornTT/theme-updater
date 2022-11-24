<?php
/**
 * @var $args ;
 */

$block_id = $args['block_id'];

$section_title = get_field( 'leadership_section_title' );
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
				<div class="section-leadership__container">
					<div class="section-leadership__description-wrapper">
						<?php if ( $section_title ) : ?>
							<div class="section-title section-title--style6 section-leadership__subtitle">
								<h2>
									<?= esc_html( $section_title ) ?>
								</h2>
							</div>
						<?php endif ?>

						<div class="section-leadership__content-slider-wrapper">
							<div class="swiper js-leadership-slider-v2">
								<div class="swiper-wrapper">
									<?php foreach ( $leadership_group as $index => $group ) : ?>
										<?php
										extract( $group );
										$extended_description = get_extended( $description );
										?>
									
										<div class="swiper-slide">
											<?php if ( !empty( $first_name ) || !empty( $last_name ) ) : ?>
												<div class="section-title section-title--style1 section-leadership__person-name">
													<h2>
														<?= esc_html( $first_name ) . (esc_html( $last_name ) ? ' ' : '') . esc_html( $last_name ) ?>
													</h2>
												</div>
											<?php endif; ?>

											<?php if ( !empty( $position ) ) : ?>
												<div class="section-title section-title--style5 section-leadership__person-position">
													<h4>
														<?= esc_html( $position ) ?>
													</h4>
												</div>
											<?php endif; ?>

											<?php if ( !empty( $description ) ) : ?>
												<div class="section-leadership__description">
													<div class="text-content">
														<?= wp_trim_words($description, 50) ?>
													</div>
												</div>
											<?php endif; ?>

											<div class="section-leadership__button-wrapper">
												<a
													class="button button-bordered button-bordered-white section-leadership__button"
													role="button"
													data-toggle="modal"
													href="#modal-person-<?= esc_attr( $block_id . '-' . $index ) ?>"
												>
													<?= esc_html( __( 'View Bio', THEME_TEXTDOMAIN ) ) ?>
												</a>
											</div>
										</div>
									<?php endforeach; ?>
								</div>

								<div class="swiper-controls swiper-controls--fraction section-leadership__slider-nav">
									<button class="swiper-button-prev">
										<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
									</button>

									<button class="swiper-button-next">
										<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
									</button>

									<div class="swiper-pagination section-leadership__slider-pagination"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="section-leadership__media-slider-wrapper">
						<div class="swiper js-leadership-slider-media">
							<div class="swiper-wrapper">
								<?php foreach ( $leadership_group as $group ): ?>
									<div class="swiper-slide">
										<div class="background-img">
											<picture>
												<img
													src="<?= esc_url( wp_get_attachment_image_url( $group['photo'], 'card-image-hige-desktop' ) ) ?>"
													alt="<?= esc_attr( hmt_get_attachment_image_alt( $group['photo'] ) ) ?>"
												>
											</picture>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
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
<?php endif; ?>
