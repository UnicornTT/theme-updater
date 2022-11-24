<?php
/**
 * Widget Categories.
 *
 * @param array $block The block settings and attributes.
 *
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

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php else : ?>
	<?php
	$queried_object = get_queried_object();

	$categories = array_filter( get_categories(), function ($category) {
		return $category->count > 0;
	} );

	$categories = wp_list_sort( $categories, 'count', 'DESC' );
	?>
	<div class="collapse-panel collapse-panel--categories">
		<div class="collapse-panel__wrap">
			<button
				class="collapse-panel__toggler" type="button" data-target=".collapse-panel-categories"
				data-toggle="collapse" aria-expanded="true"
				aria-label="<?= esc_attr( __( 'Toggle Categories', THEME_TEXTDOMAIN ) ) ?>"
			>
				<span class="collapse-panel__title section-title">
					<?= esc_html( __( 'Categories', THEME_TEXTDOMAIN ) ) ?>
				</span>

				<span class="icon icon-wrap" aria-hidden="true">
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
				</span>
			</button>

			<div class="collapse-panel-categories collapse-panel__body collapse show">
				<div class="collapse-panel__content">
					<div id="categories-3" class="widget widget_categories">
						<h2 class="widget_title">
							<?= esc_html( __( 'Categories', THEME_TEXTDOMAIN ) ) ?>
						</h2>

						<ul>
							<?php
							if ( get_option( 'page_for_posts' ) == $queried_object->ID ) {
								$active = 'current-cat';
							} else {
								$active = '';
							}
							?>
							<li class="cat-item <?= $active ?> ">
								<a
									aria-current="page"
									href="<?= esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
									data-uw-rm-brl="false"
									aria-label="<?= esc_attr( __( 'View all posts', THEME_TEXTDOMAIN ) ) ?>"
								>
									<?= esc_html( __( 'All', THEME_TEXTDOMAIN ) ) ?>

									<span>
										<?= esc_html( wp_count_posts( "post" )->publish ); ?>
									</span>
								</a>
							</li>

							<?php foreach ( $categories as $category ) : ?>
								<?php
								$classes = '';

								if ( is_category() ) {
									if ( $category->term_id == $queried_object->term_id ) {
										$classes = 'current-cat';
									} else {
										$classes = '';
									}
								}
								?>
								<li class="cat-item <?= $classes ?>">
									<a
										href="<?= esc_url( get_term_link( $category->term_id ) ) ?>" data-uw-rm-brl="false"
										aria-label="<?= esc_attr( __( 'Category', THEME_TEXTDOMAIN ) . ' ' . $category->name ) ?>"
									>
										<?= esc_html( $category->name ) ?>

										<span>
											<?= esc_html( $category->count ) ?>
										</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>