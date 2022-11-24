<?php
/**
 * Section Content Block.
 *
 * @var $args
 */

$block_id = $args['block_id'];

$section_title = get_field( 'content_block_section_title' );
$section_subtitle = get_field( 'content_block_section_kicker' );

// Left Content
$content_left_full = get_field( 'content_block_section_content' );
$content_left = get_extended( $content_left_full );

$content_left_show = isset( $content_left['main'] ) ? $content_left['main'] : '';
$content_left_hidden = isset( $content_left['extended'] ) ? $content_left['extended'] : '';

// Right Content
$content_right_full = get_field( 'content_block_section_content_full' );
$content_right = get_extended( $content_right_full );

$content_right_show = isset( $content_right['main'] ) ? $content_right['main'] : '';
$content_right_hidden = isset ( $content_right['extended'] ) ? $content_right['extended'] : '';
?>

<?php if ( $section_title && $section_subtitle && $content_left_full && $content_right_full ) : ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-content-block__bg',
		'field_prefix' => 'content_block_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-content-block__content js-font-adjustment" data-fj-min="25">
			<div class="container">
				<div class="row">
					<div class="col-xl-5 col-12">
						<div class="section-content-block__left">
							<div class="section-content-block__header">
								<?php if ( $section_subtitle ) : ?>
									<div class="section-content-block__subtitle section-title section-title--style6">
										<?= esc_html( $section_subtitle ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $section_title ) : ?>
									<div class="section-title section-title--style1 section-content-block__title">
										<h2 class="js-font-title">
											<?= esc_html( $section_title ); ?>
										</h2>
									</div>
								<?php endif; ?>
							</div>

							<?php if ($content_left_full) : ?>
								<div class="section-content-block__body">
									<div class="section-content-block__left-scroll">
										<div class="scrollbar-outer">
											<div class="section-content-block__text text-content">
												<?= apply_filters( 'the_content', $content_left_show ); ?>
												<?= apply_filters( 'the_content', $content_left_hidden ); ?>
											</div>
										</div>
									</div>

									<div class="section-content-block__left-extended">
										<?php if ( $content_left_show ) : ?>
											<div class="section-content-block__description-visible text-content">
												<?= apply_filters( 'the_content', $content_left_show ); ?>
											</div>
										<?php endif; ?>

										<?php if ( $content_left_hidden ) : ?>
											<?php
											$collapse_id = wp_generate_uuid4();
											?>
											<div
												class="collapse section-content-block__description-hidden js-sub-content-height"
												id="content-block-collapse-<?= esc_attr( $collapse_id ) ?>"
											>
												<div class="collapse-content text-content">
													<?= apply_filters( 'the_content', $content_left_hidden ); ?>
												</div>
											</div>

											<div class="section-content-block__button-wrapper">
												<button
													class="button button-bordered button-bordered-white-dark section-content-block__button collapsed"
													data-target="#content-block-collapse-<?= esc_attr( $collapse_id ) ?>"
													data-toggle="collapse"
													aria-label="<?= esc_attr( __( 'Toggle hidden content', THEME_TEXTDOMAIN ) ) ?>"
												>
													<span class="hide">
														<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
													</span>

													<span class="show">
														<?= esc_html( __( 'Hide', THEME_TEXTDOMAIN ) ) ?>
													</span>
												</button>
											</div>
										<?php endif ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $content_right_full ) : ?>
						<div class="col-xl-7 col-12">
							<div class="section-content-block__right">
								<?php
								get_template_part( 'parts/resources/section-background', '', [
									'class_name' => 'section-content-block__right-bg',
									'field_prefix' => 'content_block_section_background',
									'field_id' => ''
								] );
								?>

								<div class="section-content-block__full-content">
									<div class="section-content-block__right-scroll">
										<div class="section-content-block__cover">
											<div class="scrollbar-outer">
												<div class="section-content-block__text-cover text-content">
													<?= apply_filters( 'the_content', $content_right_show ); ?>
													<?= apply_filters( 'the_content', $content_right_hidden ); ?>
												</div>
											</div>
										</div>
									</div>

									<div class="section-content-block__right-extended">
										<?php if ( $content_right_show ) : ?>
											<div class="section-content-block__description-visible text-content">
												<?= apply_filters( 'the_content', $content_right_show ); ?>
											</div>
										<?php endif; ?>

										<?php if ( $content_right_hidden ) : ?>
											<?php
											$collapse_id = wp_generate_uuid4();
											?>
											<div
												class="collapse section-content-block__description-hidden js-sub-content-height"
												id="content-block-collapse-<?= esc_attr( $collapse_id ) ?>"
											>
												<div class="collapse-content text-content">
													<?= apply_filters( 'the_content', $content_right_hidden ); ?>
												</div>
											</div>

											<div class="section-content-block__button-wrapper">
												<button
													class="button button-bordered button-bordered-white-dark section-content-block__button collapsed"
													data-target="#content-block-collapse-<?= esc_attr( $collapse_id ) ?>"
													data-toggle="collapse"
													aria-label="<?= esc_attr( __( 'Toggle hidden content', THEME_TEXTDOMAIN ) ) ?>"
												>
													<span class="hide">
														<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
													</span>

													<span class="show">
														<?= esc_html( __( 'Hide', THEME_TEXTDOMAIN ) ) ?>
													</span>
												</button>
											</div>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>