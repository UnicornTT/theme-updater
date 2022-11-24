<?php
/**
 * Section Services Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @var $args ;
 */

$block_id = $args['block_id'];
$section_title = get_field( 'our_process_section_title' );

$fields_faq = get_field( 'our_process_faq' );
$fields_list = $fields_faq['our_process_section_faq'] ?? [];
$fields = [];

foreach ( $fields_list as $value ) {
	array_push( $fields, [
		'title' => $value['our_process_title'],
		'text' => $value['our_process_description'],
		'icon_id' => $value['our_process_section_content_icon_v2'],
		'icon' => wp_get_attachment_image_url( $value['our_process_section_content_icon_v2'], 'thumbs-desktop' ),
	] );
}

$show_section = false;
foreach ( $fields as $index => $value ){
	extract( $value );
	if( $value['title'] && $value['icon_id'] && $value['icon'] && $value['text'] ){
		$show_section = true;
		break;
	}
}
?>

<?php if( !empty($fields) && $show_section ): ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-service-hero__bg',
			'field_prefix' => 'our_process_section_background',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="section-our-process__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-our-process__header">
						<div class="section-title section-title--style1 section-our-process__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>
				<?php endif; ?>

				<div class="section-our-process__main">
					<div class="row align-items-center justify-content-center">
						<?php foreach ( $fields as $index => $value ) : ?>
							<?php extract( $value ) ?>
							<?php if( !empty( $title ) && !empty( $icon_id ) && !empty( $icon ) && !empty( $text ) ): ?>
								<div class="col-12 col-xl-3 our-process-item" tabindex="0">
									<div class="our-process-card">
										<div class="our-process-card__index"></div>

										<div class="our-process-card__content">
											<?php if ( !empty( $icon_id ) && !empty( $icon ) ) : ?>
												<div class="our-process-card__icon">
													<div class="icon-wrap background-icon">
														<?php if ( hmt_is_svg( $icon_id ) ) : ?>
															<?= hmt_get_svg_inline( $icon ) ?>
														<?php endif ?>
													</div>
												</div>
											<?php endif; ?>

											<?php if ( !empty( $title ) ) : ?>
												<div class="our-process-card__title our-process-card__title--main section-title section-title--style5">
													<h3>
														<?= esc_html( $title ) ?>
													</h3>
												</div>
											<?php endif; ?>
										</div>

										<div class="our-process-card__full">
											<div class="our-process-card__full-body">
												<div class="scrollbar-outer">
													<div class="our-process-card__full-content">
														<?php if ( !empty( $title ) ) : ?>
															<div class="our-process-card__full-title section-title section-title--style3">
																<h3>
																	<?= esc_html( $title ) ?>
																</h3>
															</div>
														<?php endif; ?>

														<?php if ( !empty( $text ) ) : ?>
															<div class="our-process-card__full-description text-content">
																<?= apply_filters( 'the_content', $text ) ?>
															</div>
														<?php endif; ?>
													</div>
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
	</div>
<?php endif; ?>
