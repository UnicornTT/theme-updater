<?php

/**
 * Section Residential/Commercial Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @var $block
 */

$page_id = get_queried_object_id();

$block_style = hmt_get_block_style_name( $block );

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$section_top_padding_type = get_field( 'section_top_padding_type' );
$section_bottom_padding_type = get_field( 'section_bottom_padding_type' );

if( $section_top_padding_type && !empty($section_top_padding_type) ) {
	$section_top_padding = 'section-top-padding--' . $section_top_padding_type;
} else {
	$section_top_padding = 'section-top-padding--default';
}
if ( $section_bottom_padding_type && !empty($section_bottom_padding_type) ) {
	$section_bottom_padding = 'section-bottom-padding--' . $section_bottom_padding_type;
} else {
	$section_bottom_padding = 'section-bottom-padding--default';
}

$class_name = 'section section-residential-commercial';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

//$background = (array)get_field( 'residential_commercial_section_background' );
$section_title = get_field( 'residential_commercial_section_title' );

$service_types = get_field( 'service_types' );

do_action('qm/info', $section_title);
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( $section_title && is_array($service_types) && $service_types[0] ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-residential-commercial__bg',
			'field_prefix' => 'residential_commercial_section_background',
			'field_id' => ''
		] );
		?>

		<div class="section__body">
			<div class="section-residential-commercial__content">
				<div class="container">
					<?php if ( !empty( $section_title ) ) : ?>
						<div class="section-residential-commercial__header">
							<div class="section-title section-title--style1 section-residential-commercial__title">
								<h2>
									<?= nl2br( $section_title ) ?>
								</h2>
							</div>
						</div>
					<?php endif; ?>

					<div class="section-residential-commercial__cards">
						<div class="row justify-content-center justify-content-md-between align-content-center">
							<?php foreach ( $service_types as $key => $service_type ) : ?>
								<?php extract( $service_type ); ?>
								<?php if ( $image && $title && $link  && $content ) : ?>
									<div class="col-12 col-md-6">
										<div class="card-item" tabindex="0">
											<div class="card-item__img">
												<div class="background-img">
													<picture>
														<img src="<?= esc_url( wp_get_attachment_image_url( $image, 'section-background-mobile' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $image ) ); ?>">
													</picture>
												</div>
											</div>

											<div class="card-item__title card-item__title--main section-title section-title--style5">
												<h3>
													<?= esc_html( $title ) ?>
												</h3>
											</div>

											<?php if ( isset( $link ) && !empty( $link ) ) : ?>
												<?php
													$url = $link['url'];
													$link_title = $link['title'] ? $link['title'] : '';
													$target = $link['target'] ? $link['target'] : '_self';
												?>
												<div class="card-item__button-wrapper-mobile">
													<a class="button button-bordered button-bordered-white card-item__button" role="button" href="<?= esc_url( $url ) ?>" target="<?= esc_attr( $target ) ?>">
														<?= $link_title ? esc_html( $link_title ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
													</a>
												</div>
											<?php endif; ?>

											<div class="card-item__full">
												<div class="card-item__full-body">
													<div class="card-item__full-content">
														<div class="card-item__title section-title section-title--style5">
															<h3>
																<?= esc_html( $title ) ?>
															</h3>
														</div>

														<div class="card-item__description text-content">
															<?= nl2br( $content ) ?>
														</div>
													</div>

													<?php if ( isset( $link ) && !empty( $link ) ) : ?>
														<?php
															$url = $link['url'];
															$link_title = $link['title'] ? $link['title'] : '';
															$target = $link['target'] ? $link['target'] : '_self';
														?>
														<div class="card-item__button-wrapper">
															<a class="button button-bordered button-bordered-white card-item__button" role="button" href="<?= esc_url( $url ) ?>" target="<?= esc_attr( $target ) ?>">
																<?= $link_title ? esc_html( $link_title ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
															</a>
														</div>
													<?php endif; ?>
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
	</section>
<?php endif; ?>



