<?php
/**
 * Section Our Process.
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @var $args ;
 */

$class_name = 'section section-our-process';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}
$class_name .= ' section-our-process--style-v1';


$block_id = $args['block_id'];
$section_title = get_field( 'our_process_section_title' );

$fields_faq = get_field( 'our_process_faq' );
$fields_list = $fields_faq['our_process_section_faq'] ?? [];
$fields = array();

foreach ( $fields_list as $value ) {
	array_push( $fields, [
		'title' => $value['our_process_title'],
		'text' => $value['our_process_description_v1'],
		'icon_id' => $value['our_process_section_content_icon'],
		'icon' => wp_get_attachment_image_url( $value['our_process_section_content_icon'], 'thumbs-desktop' ),
		'icon_alt' => get_post_meta( $value['our_process_section_content_icon'], '_wp_attachment_image_alt', TRUE ),
		'icon_tab_id' => $value['our_process_section_tab_icon'],
		'icon_tab' => wp_get_attachment_image_url( $value['our_process_section_tab_icon'], 'thumbs-desktop' ),
		'icon_tab_alt' => get_post_meta( $value['our_process_section_tab_icon'], '_wp_attachment_image_alt', TRUE ),
	] );
}

$default_active = 0;

$show_section = false;
foreach ( $fields as $index => $value ){
	extract( $value );
	if( $value['title'] && $value['icon_tab'] && $value['icon'] && $value['text'] ){
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
							<h1>
								<?= esc_html( $section_title ) ?>
							</h1>
						</div>
					</div>
				<?php endif; ?>

				<div class="our-process">
					<div class="our-process__selector">
						<div class="our-process__list">
							<div class="scrollbar-outer js-horizontal-scroll">
								<div class="our-process">
									<div
										class="our-process__items-wrapper" id="<?= esc_attr( $block_id ) ?>-tablist"
										role="tablist"
									>
										<?php $index = 0 ?>
										<?php foreach ( $fields as $index => $value ) : ?>
											<?php extract( $value ); ?>
											<?php if( !empty( $title ) && !empty( $icon_tab ) && !empty( $icon ) && !empty( $text ) ): ?>
												<div
													class="our-process__item js-our-process__item <?= esc_attr( $index === $default_active ? "active" : "" ) ?>"
													data-title="<?= !empty( $title ) ? esc_attr( $title ) : '' ?>"
													data-index="<?= esc_attr( $index ) ?>" tabindex="0"
												>
													<div class="our-process__item-wrapper">
														<?php if ( !empty( $icon_tab ) && !empty( $icon_tab_id ) ) : ?>
															<div class="our-process__item-logo white-icon">
																<?php if ( hmt_is_svg( $icon_tab_id ) ) : ?>
																	<?= hmt_get_svg_inline( $icon_tab ) ?>
																<?php endif ?>
															</div>
														<?php endif; ?>

														<?php if ( !empty( $icon ) && !empty( $icon_id ) ) : ?>
															<div class="our-process__item-logo colorful-icon">
																<?php if ( hmt_is_svg( $icon_id ) ) : ?>
																	<?= hmt_get_svg_inline( $icon ) ?>
																<?php endif ?>
															</div>
														<?php endif; ?>

														<div class="our-process__item-index">
															<?= esc_html( ++ $index ) ?>
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

					<div class="our-process__content js-our-process__content">
						<?php $index = 0 ?>
						<?php foreach ( $fields as $index => $value ) : ?>
							<?php extract( $value ); ?>
							<?php if( !empty( $title ) && !empty( $icon_tab ) && !empty( $icon ) && !empty( $text ) ): ?>
								<div
									class="one-process js-one-process<?= esc_attr( $index == $default_active ? " active" : "" ) ?>"
									data-index="<?= esc_attr( $index ) ?>"
									id="<?= esc_attr( $block_id . '-tab' . $index ) ?>"
									role="tabpanel" aria-labelledby="<?= esc_attr( $block_id . '-tab' . $index ) ?>-tab"
								>
									<div class="one-process__left">
										<span class="one-process__index"><?= esc_html( ++ $index ) ?></span>

										<?php if ( !empty( $icon ) ) : ?>
											<div class="one-process__icon-wrapper">
												<img
													class="one-process__icon" src="<?= esc_attr( $icon ) ?>"
													alt="<?= !empty( $icon_alt ) ? esc_attr( $icon_alt ) : '' ?>"
												>
											</div>
										<?php endif; ?>
									</div>

									<div class="one-process__right">
										<div class="one-process__text">
											<?php if ( !empty( $title ) ) : ?>
												<div class="section-title--style1 one-process__title">
													<?= esc_html( $title ) ?>
												</div>
											<?php endif; ?>

											<?php if ( !empty( $text ) ) : ?>
												<div class="one-process__description-wrapper">
													<div class="one-process__description">
														<div class="text-content">
															<?= apply_filters( 'the_content', $text ) ?>
														</div>
													</div>
												</div>
											<?php endif; ?>
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
