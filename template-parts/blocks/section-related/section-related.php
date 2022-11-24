<?php
/**
 * Section Related Posts Template.
 * @param array|WP_Post[] $postsList The posts.
 * @param array $params The block inner HTML (empty).
 * @var $args
 * @var $block
 */

$id = $block['id'] ?? '';

$block_style = '';

$class_name = 'section section-related';
$class_name .= ' section-related--style-v1' . $block_style;

$section_title = $args['section_title'] ?? null;
$post_list = $args['posts_list'] ?? null;

if ( !empty( $post_list ) ) {
	$post_list = array_chunk( $post_list, 4 );
} else {
	$post_list = get_posts( array('numberposts' => 12) );
	$post_list = array_chunk( $post_list, 4 );
}
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif( isset( $post_list ) && !empty( $post_list ) ) : ?>
	<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
		<?php
			get_template_part( 'parts/resources/section-background', '', [
				'class_name' => 'section-related__bg',
				'field_prefix' => 'related_section_background',
				'field_id' => ''
			] );
		?>

		<div class="section__body">
			<div class="section-related__content">
				<div class="container">
					<div class="section-related__recent recent-blogs">
						<div class="recent-blogs__header">
							<div class="recent-blogs__title section-title section-title--style1">
								<h3>
									<?= !empty( $section_title ) ? esc_html( $section_title ) : esc_html( __( 'Recent Blogs', THEME_TEXTDOMAIN ) ) ?>
								</h3>
							</div>
						</div>

						<div class="recent-blogs__slider swiper swiper-container js-recent-blogs-slider">
							<div class="swiper-wrapper">
								<?php foreach ( $post_list as $sub_array ) : ?>
									<div class="swiper-slide">
										<div class="recent-blogs__grid">
											<?php foreach ( $sub_array as $index => $article ) : ?>
												<?php
													extract( (array)$article );
													$post_author_name = null;
													$post_author_page_link = null;

													if ( !empty( $post_author ) ) {
														$post_author_name = 'by ' . get_the_author_meta( 'display_name', $post_author );
														$post_author_page_link = get_author_posts_url( $post_author );
													}

													$id = !empty( $ID ) ? $ID : '';

													$short_description = wp_trim_words( get_field( 'single_post_description', $ID ), 40 );
												?>
												<div class="recent-blogs__cell">
													<div
														class="related-card <?= esc_attr( $index == 0 ? 'related-card--full' : '' ); ?>"
														tabindex="0"
													>
														<?php if ( $id ) : ?>
															<div class="related-card__bg">
																<div class="background-img">
																	<picture>
																		<img
																			src="<?= esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'section-background-mobile' ) ) ?>"
																			alt="<?= esc_attr( hmt_get_attachment_image_alt( get_post_thumbnail_id( $id ) ) ); ?>"
																		>
																	</picture>
																</div>
															</div>
														<?php endif; ?>

														<div class="related-card__content">
															<div class="related-card__body">
																<?php if ( !empty( $post_title ) ) : ?>
																	<a href="<?= get_permalink( $id ) ?>" class="related-card__title section-title">
																		<h3>
																			<?= esc_html( $post_title ) ?>
																		</h3>
																	</a>
																<?php endif; ?>

																<?php if ( !empty( $short_description ) ) : ?>
																	<div class="related-card__description text-content">
																		<?= apply_filters( 'the_content', $short_description ) ?>
																	</div>
																<?php endif; ?>

																<div class="related-card__date">
																	<span>
																		<?= get_the_date( 'M jS, Y', $id ); ?> |
																	</span>

																	<?php if ( $post_author_name ) : ?>
																		<a class="related-card__author" href="<?= esc_url( $post_author_page_link ); ?>" aria-label="<?= esc_attr( __( 'View ' . $post_author_name . ' profile', THEME_TEXTDOMAIN ) ); ?>">
																			<?= esc_html( $post_author_name ); ?>
																		</a>
																	<?php endif; ?>
																</div>
															</div>

															<?php if ( $id ) : ?>
																<div class="related-card__button-wrapper">
																	<a href="<?= get_permalink( $id ) ?>" class="button button-bordered button-bordered-white related-card__button">
																		<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
																	</a>
																</div>
															<?php endif; ?>
														</div>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<?php if ( isset( $post_list ) && empty( $post_list ) ) : ?>
								<div>
									<?= esc_html( __( 'This author doesn\'t have any articles at the moment.', THEME_TEXTDOMAIN ) ); ?>
								</div>
							<?php endif ?>

							<?php if ( isset( $post_list ) && count( $post_list ) > 1 ) : ?>
								<div class="swiper-controls swiper-controls--fraction">
									<button class="swiper-button-prev">
										<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
									</button>

									<button class="swiper-button-next">
										<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
									</button>

									<div class="swiper-pagination"></div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>