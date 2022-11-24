<?php
/**
 * @var $args
 */

$block_id = $args['block_id'];
$services = $args['services'];

$section_title = get_field( 'services_section_title' );
?>


<?php if ( !empty( $services ) ) : ?>
	<?php
		get_template_part( 'parts/resources/section-background', '', [
			'class_name' => 'section-services__bg',
			'field_prefix' => 'services_section',
			'field_id' => ''
		] );
	?>

	<div class="section__body">
		<div class="section-services__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-services__header">
						<div class="section-title section-title--style1 section-services__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>
				<?php endif; ?>

				<div class="section-services__main">
					<div class="row service-align js-horizontal-scroll">
						<?php foreach ( $services as $key => $service ) : ?>
							<?php
								$service_title = $service->post_title ?? '';
								$service_link = get_permalink( $service->ID ?? '' );
							?>
							<div class="col service-grid">
								<a class="service" href="<?= esc_url( $service_link ) ?>" aria-label="<?= esc_attr( __( 'More about', THEME_TEXTDOMAIN ) . $service_title ) ?>">
									<div class="service__main-content">
										<div class="service__logo icon-wrap">
											<?= hmt_get_svg_inline( wp_get_attachment_image_url( get_field( 'service_page_icon', $service->ID ) ) ); ?>
										</div>

										<?php if ( $service_title ) : ?>
											<div class="service__title service__title--main section-title">
												<h3>
													<?= esc_html( $service_title ) ?>
												</h3>
											</div>
										<?php endif; ?>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
