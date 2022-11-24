<?php

/**
 * @var $args ;
 */

$block_id = $args['block_id'];

$section_title = get_field( 'our_work_section_title' );
$section_subtitle = get_field( 'our_work_section_subtitle' );

$filter_works_type = get_field( 'our_work_section_filter_posts' );

if ( $filter_works_type === 'latest' ) {
	$project_list = get_posts( array(
		'post_type' => 'hmt_project',
		'post_status' => 'any',
		'posts_per_page' => - 1
	) );
} else {
	$project_list = get_field( 'our_work_section_selected_posts_key' );
}
?>

<?php if ( $project_list ) : ?>

	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-our-work__bg',
		'field_prefix' => 'our_work_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-our-work__content">
			<div class="container">
				<div class="section-our-work__header">
					<?php if ( $section_title ) : ?>
						<div class="section-title section-title--style1 section-our-work__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					<?php endif; ?>

					<?php if ( $section_subtitle ) : ?>
						<div class="section-title section-title--style5 section-our-work__subtitle">
							<h2>
								<?= esc_html( $section_subtitle ) ?>
							</h2>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="swiper section-our-work__slider js-our-work-slider">
				<div class="swiper-wrapper">
					<?php foreach ( $project_list as $project ) : ?>
						<?php
						$project_title = $project->post_title;
						$excerpt = wp_trim_words( get_the_excerpt( $project ), 60 );
						$excerpt_custom = get_field( 'project_description', $project->ID );
						$short_description = $excerpt ?: wp_trim_words( get_extended($excerpt_custom)['main'], 60 );
						$project_image = get_field( 'project_gallery', $project->ID );
						$project_image_id = $project_image[0] ?? 0;

						$project_image_url = wp_get_attachment_image_url( $project_image_id, 'full-hd' );
						$project_image_desktop_url = wp_get_attachment_image_url( $project_image_id, 'section-background-tablet' );
						$project_image_tablet_url = wp_get_attachment_image_url( $project_image_id, 'section-background-mobile' );
						$project_image_mobile_url = wp_get_attachment_image_url( $project_image_id, 'thumbs-desktop' );
						$project_image_alt = hmt_get_attachment_image_alt( $project_image_id );
						?>
						<?php if ( $project_image_id ) : ?>
							<div class="swiper-slide">
								<div class="work-card">
									<div class="work-card__img">
										<div class="background-img">
											<picture>
												<source
													srcset="<?= esc_url( $project_image_desktop_url ) ?>"
													media="(min-width: 1025px)"
												>
												<source
													srcset="<?= esc_url( $project_image_tablet_url ) ?>"
													media="(max-width: 1024px)"
												>
												<source
													srcset="<?= esc_url( $project_image_mobile_url ) ?>"
													media="(max-width: 575px)"
												>
												<img
													src="<?= esc_url( $project_image_url ) ?>"
													alt="<?= esc_attr( $project_image_alt ) ?>"
												>
											</picture>
										</div>
									</div>

									<?php if ( $project_title ) : ?>
										<div
											class="work-card__title work-card__title--main section-title section-title--style5"
										>
											<h3>
												<?= esc_html( $project_title ) ?>
											</h3>
										</div>
									<?php endif; ?>

									<div class="work-card__full">
										<div class="work-card__full-body">
											<div class="scrollbar-outer">
												<div class="work-card__full-content">
													<?php if ( $short_description ) : ?>
														<div
															class="work-card__title section-title section-title--style5"
														>
															<h3>
																<?= esc_html( $project_title ) ?>
															</h3>
														</div>
													<?php endif; ?>

													<?php if ( $short_description ) : ?>
														<div class="work-card__description text-content">
															<?= apply_filters( 'the_content', $short_description ) ?>
														</div>
													<?php endif; ?>
												</div>
											</div>

											<div class="work-card__button-wrapper">
												<a
													class="button button-bordered button-bordered-white work-card__button"
													role="button" data-toggle="modal"
													href="#modal-our-work-<?= esc_attr( $project->ID ) ?>"
												>
													<?= esc_html( __( 'View More', THEME_TEXTDOMAIN ) ) ?>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif ?>
					<?php endforeach; ?>
				</div>

				<div class="container">
					<div class="swiper-controls swiper-controls--circle">
						<button class="swiper-button-prev">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
						</button>

						<button class="swiper-button-next">
							<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Our Work Popups -->
	<?php get_template_part(
		'template-parts/blocks/section-our-work/popups/section-our-work',
		'popup',
		[
			'project_list' => $project_list,
			'block_id' => $block_id
		]
	) ?>
	<!-- End Our Work Popups -->
<?php endif; ?>