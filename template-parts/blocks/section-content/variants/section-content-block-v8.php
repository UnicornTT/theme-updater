<?php
/**
 * Section Content Block.
 *
 * @var $args
 */

$block_id = $args['block_id'];

$section_title = get_field( 'content_block_section_title' );
$section_content_full = get_field( 'content_block_section_description' );
$section_content = get_extended( $section_content_full );

$section_content_show = isset( $section_content['main'] ) ? $section_content['main'] : '';
$section_content_hidden = isset( $section_content['extended'] ) ? $section_content['extended'] : '';
?>

<?php if ( $section_title && $section_content_show ) : ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-content-block__bg',
		'field_prefix' => 'content_block_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-content-block__content js-font-adjustment" data-fj-min="24">
			<div class="container">
				<div class="section-content-block__wrapper">
					<div class="section-content-block__header">
						<?php if ( $section_title ) : ?>
							<div class="section-title section-title--style1 section-content-block__title">
								<h2 class="js-font-title">
									<?= esc_html( $section_title ); ?>
								</h2>
							</div>
						<?php endif; ?>
					</div>

					<div class="section-content-block__body">
						<div class="section-content-block__text-wrapper">
							<?php if ( $section_content_full ) : ?>
								<div class="section-content-block__scroll">
									<div class="scrollbar-outer">
										<div class="section-content-block__text text-content">
											<?= apply_filters( 'the_content', $section_content_full ); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<div class="section-content-block__extended">
								<?php if ( $section_content_show ) : ?>
									<div class="section-content-block__description-visible text-content">
										<?= apply_filters( 'the_content', $section_content_show ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $section_content_hidden ) : ?>
									<?php
									$collapse_id = wp_generate_uuid4();
									?>
									<div
										class="collapse section-content-block__description-hidden js-sub-content-height"
										id="content-block-collapse-<?= esc_attr( $collapse_id ) ?>"
									>
										<div class="collapse-content text-content">
											<?= apply_filters( 'the_content', $section_content_hidden ); ?>
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
			</div>
		</div>
	</div>
<?php endif; ?>