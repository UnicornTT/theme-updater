<?php
/**
 * Widget Recent Posts.
 * @param array $block The block settings and attributes.
 * @var $block
 */

$id = $block['id'];
if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = '';
if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$widget_title = get_field( 'widget_resources_recent_title' );
$recent_posts = get_posts( ['numberposts' => 12] );
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php else : ?>
	<?php if ( is_single() || is_admin() || is_home() || hmt_is_blog() ) : ?>
		<div class="widget widget-slider">
			<div class="widget-slider__wrap">
				<?php if ( !empty( $widget_title ) ) : ?>
					<div class="widget-slider__title section-title">
						<?= esc_html( $widget_title ) ?>
					</div>
				<?php endif; ?>

				<div class="recent-slider swiper swiper-container js-recent-blogs-slider">
					<div class="swiper-wrapper">
						<?php foreach ( $recent_posts as $recent_post ) : ?>
							<?php
							$short_description = wp_trim_words( get_field( 'single_post_description', $recent_post->ID ), 40 );
							?>
							<div class="swiper-slide">
								<div class="resources-card" tabindex="0">
									<div class="resources-card__bg">
										<div class="background-img">
											<?= get_the_post_thumbnail( $recent_post->ID, 'section-background-mobile', ['alt' => esc_attr( hmt_get_attachment_image_alt( get_post_thumbnail_id( get_the_ID() ) ) )] ); ?>
										</div>
									</div>

									<div class="resources-card__content">
										<div class="resources-card__body">
											<?php if ( $recent_post->post_title ) : ?>
												<a
													href="<?= esc_url( get_permalink( $recent_post->ID ) ) ?>"
													class="resources-card__title section-title"
												>
													<h3>
														<?= esc_html( $recent_post->post_title ) ?>
													</h3>
												</a>
											<?php endif; ?>

											<div class="resources-card__description text-content">
												<?= apply_filters( 'the_content', $short_description ) ?>
											</div>

											<div class="resources-card__date">
												<span><?= esc_html( get_the_date( 'M jS, Y', $recent_post->ID ) ); ?> |</span>

												<?php $author_id = get_post_field( 'post_author', $recent_post->ID ); ?>
												<a
													class="resources-card__author"
													href="<?= esc_url( get_author_posts_url( $author_id ) ) ?>"
												>
													<?= esc_html( __( 'by ', THEME_TEXTDOMAIN ) . get_the_author_meta( 'display_name', $author_id ) ) ?>
												</a>
											</div>
										</div>

										<div class="resources-card__button-wrapper">
											<a
												href="<?= esc_url( get_permalink( $recent_post->ID ) ) ?>"
												class="button button-bordered button-bordered-white resources-card__button"
											>
												<?= esc_html( __( 'Read More', THEME_TEXTDOMAIN ) ) ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( isset( $recent_posts ) && count( $recent_posts ) > 1 ) : ?>
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
	<?php endif ?>
<?php endif; ?>
