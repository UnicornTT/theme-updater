<?php
/**
 * @var $args
 */

$id = $args['block']['id'];

$section_title = get_field( 'industries_served_section_title' );
$filter_posts_type = get_field( 'industries_served_filter_posts' );

if ( $filter_posts_type === 'all' ) {
	$items = get_posts( array(
		'post_type' => 'hmt_industry',
		'posts_per_page' => - 1,
	) );
} else {
	$items = get_field( 'industries_served_selected_posts_type' );
}
?>

<?php if ( $items && is_array( $items ) ) : ?>
	<?php
	get_template_part( 'parts/resources/section-background', '', [
		'class_name' => 'section-industries-served__bg',
		'field_prefix' => 'industries_served_section_background',
		'field_id' => ''
	] );
	?>

	<div class="section__body">
		<div class="section-industries-served__content">
			<div class="container">
				<?php if ( $section_title ) : ?>
					<div class="section-industries-served__header">
						<div class="section-title section-title--style1 section-industries-served__title">
							<h2>
								<?= esc_html( $section_title ) ?>
							</h2>
						</div>
					</div>
				<?php endif; ?>

				<div class="section-industries-served__industries-container">
					<?php foreach ( $items as $key => $item ) : ?>
						<?php
							$excerpt = wp_trim_words( get_the_excerpt( $item ), 75 );
							$excerpt_custom = get_field( 'industries_served_description', $item->ID );
							$short_description = $excerpt ?: wp_trim_words( get_extended($excerpt_custom)['main'], 75 );
							$icon_id = get_field( 'industries_served_icon', $item->ID );
							$img_url = esc_url( wp_get_attachment_image_url( $icon_id, 'thumbs-mobile' ) );
							$img_alt = esc_attr( hmt_get_attachment_image_alt( $icon_id ) );
						?>
						<div class="col-12 col-md-6 col-xl-3">
							<div class="industries-card">
								<div class="industries-card__main-content">
									<?php if( $icon_id ): ?>
										<div class="industries-card__logo icon-wrap">
											<?= hmt_is_svg( $icon_id ) ? hmt_get_svg_inline( $img_url ) : "<img src=\"{$img_url}\" alt=\"{$img_alt}\" />" ?>
										</div>
									<?php endif; ?>

									<?php if ( $item->post_title ) : ?>
										<div
											class="industries-card__title industries-card__title--main section-title section-title--style5"
										>
											<h3>
												<?= esc_html( $item->post_title ) ?>
											</h3>
										</div>
									<?php endif; ?>
								</div>

								<div class="industries-card__full">
									<div class="industries-card__full-body">
										<div class="industries-card__full-content">
											<?php if ( $item->post_title ) : ?>
												<div
													class="industries-card__title industries-card__title--full-title section-title section-title--style5"
												>
													<h3>
														<?= esc_html( $item->post_title ) ?>
													</h3>
												</div>
											<?php endif; ?>

											<?php if ( $short_description ) : ?>
												<div class="industries-card__description text-content">
													<?= apply_filters( 'the_content', $short_description ) ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="industries-card__button-wrapper">
											<a
												class="button button-bordered button-bordered-white industries-card__button"
												href="<?= esc_url( get_permalink( $item->ID ) ) ?>"
											>
												<?= esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ) ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>