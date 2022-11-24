<?php
/**
 * Widget Tags.
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

?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php else : ?>
	<?php
	global $wp_query;
	$paged = get_query_var( 'paged' );
	$max_num_pages = ceil( (count( (array)$wp_query->posts )) / 9 );
	$wp_query->set( 'orderby', 'date' );
	$wp_query->set( 'order', 'DESC' );
	$queried_qbject = get_queried_object();
	$tags = array_filter( get_tags(), function ($tag) {
		return $tag->count > 0;
	} );
	?>
	<?php if ( count( $tags ) > 0 ) : ?>
		<div class="collapse-panel collapse-panel--tags">
			<div class="collapse-panel__wrap">
				<button
					class="collapse-panel__toggler" type="button" data-target=".collapse-panel-tags"
					data-toggle="collapse" aria-expanded="true"
					aria-label="<?= esc_attr( __( 'Toggle Tags', THEME_TEXTDOMAIN ) ); ?>"
				>
					<span class="collapse-panel__title section-title">
						<?= esc_html( __( 'Tags', THEME_TEXTDOMAIN ) ) ?>
					</span>

					<span class="icon icon-wrap" aria-hidden="true">
						<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-chevron-right-menu.svg' ); ?>
					</span>
				</button>

				<div class="collapse-panel-tags collapse-panel__body collapse show">
					<div class="collapse-panel__content">
						<div id="simpletags-2" class="widget widget-simpletags">
							<h2 class="widget_title">
								<?= esc_html( __( 'Tags', THEME_TEXTDOMAIN ) ) ?>
							</h2>

							<div class="st-tag-cloud">
								<?php foreach ( $tags as $tag ) : ?>
									<a
										href="<?= esc_url( get_term_link( $tag->term_id ) ) ?>"
										class="st-tags <?= $queried_qbject->slug == $tag->slug ? 'active' : '' ?>"
										data-uw-rm-brl="false"
									>
										<?= esc_html( $tag->name ) ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>


