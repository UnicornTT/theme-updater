<?php
/**
 * Section Services Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$block_style = get_field( 'services_section_choose-variants' );

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

$class_name = 'section section-services';
$class_name .= ' ' . $section_top_padding . ' ' . $section_bottom_padding;

if ( !empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( !empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$class_name .= ' section-services--style-' . $block_style;

if ( $block_style == 'v2' ){
	$filter_posts_type = get_field( 'services_filter_posts_short' );
} else {
	$filter_posts_type = get_field( 'services_filter_posts' );
}

if ( $filter_posts_type === 'all' ) {
	$services = get_posts( [
		'post_type' => 'hmt_service',
		'posts_per_page' => - 1,
	] );
} else {
	if ( $block_style == 'v2' ){
		$services = get_field( 'services_section_services_short' );
	} else {
		$services = get_field( 'services_section_services' );
	}
}

$section_title = get_field( 'services_section_title' );
?>

<?php if ( isset( $block['data']['block_preview_images'] ) ) : ?>
	<?php hmt_get_template_part_with_params( 'template-parts/blocks/block-preview-image', ['block' => $block] ); ?>
<?php elseif ( !empty( $services ) && !empty( $section_title ) ) : ?>
	<?php if ( $block_style == 'v1' ) : ?>
		<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
			<?php get_template_part( 'template-parts/blocks/section-services/variants/section-services-v1', '', ['block_id' => $block['id'], 'services' => $services] ); ?>
		</section>
	<?php elseif ( $block_style == 'v2' ) : ?>
		<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
			<?php get_template_part( 'template-parts/blocks/section-services/variants/section-services-v2', '', ['block_id' => $block['id'], 'services' => $services] ); ?>
		</section>
	<?php elseif ( $block_style == 'v3' ) : ?>
		<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
			<?php get_template_part( 'template-parts/blocks/section-services/variants/section-services-v3', '', ['block_id' => $block['id'], 'services' => $services] ); ?>
		</section>
	<?php elseif ( $block_style == 'v4' ) : ?>
		<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
			<?php get_template_part( 'template-parts/blocks/section-services/variants/section-services-v4', '', ['block_id' => $block['id'], 'services' => $services] ); ?>
		</section>
	<?php elseif ( $block_style == 'v5' ) : ?>
		<section id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( $class_name ); ?>">
			<?php get_template_part( 'template-parts/blocks/section-services/variants/section-services-v5', '', ['block_id' => $block['id'], 'services' => $services] ); ?>
		</section>
	<?php endif; ?>

	<!-- Popups -->
	<?php if ( $block_style == 'v1' || $block_style == 'v2') : ?>
		<?php
			foreach ( $services as $key => $service ) {
				$service_gallery = get_field( 'service_gallery', $service->ID );
				$service_current_gallery = $service_gallery[0]['service_media_group'];
				$popup_video_type = $service_current_gallery['video_type'] ?? '';
				$popup_video_src = $service_current_gallery['video_file'] ?? '';
				$popup_video_poster = $service_current_gallery['image_poster'] ?? '';
				$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $service_current_gallery['youtube_link'] );
				$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;

				if ( $popup_video_enabled ) {
					get_template_part(
						'template-parts/popups/popup',
						'video',
						[
							'popup_id' => 'modal-video-services-' . $block['id'] . '-' . $key,
							'popup_video_youtube_player_id' => 'modal-video-player-services-' . $block['id'] . '-' . $key,
							'popup_video_type' => $popup_video_type,
							'popup_video_poster_id' => $popup_video_poster,
							'popup_video_src_id' => $popup_video_src,
							'popup_video_youtube_id' => $popup_video_youtube_id,
						]
					);
				}
			}
		?>
	<?php endif; ?>
<?php endif; ?>
