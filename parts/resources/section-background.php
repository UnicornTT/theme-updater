<?php
/**
 * Sections Background Template.
 *
 * @param string $class_name
 * @param string $field_prefix
 * @param string $field_id
 *
 * @var $args
 */

extract( $args );

$field_id = $field_id ?? '';
$field_prefix = $field_prefix ?? '';
$background = get_field( $field_prefix . '_background', $field_id );
?>

<?php if ( !empty( $background ) ) : ?>
	<?php
		$background_type = $background['bg_type'];
	?>

	<?php if ( 'empty' !== $background_type ) : ?>
		<div class="section__bg <?= esc_attr( $class_name ?? '' ) ?>" aria-hidden="true">
			<div class="background-img">
				<?php if ( 'image' === $background_type ) : ?>
					<?php
						$background_image = $background['bg_image'];
					?>
					<picture>
						<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-4k' ) ) ?>" media="(min-width: 1920px)">
						<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-desktop' ) ) ?>" media="(min-width: 1280px)">
						<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-tablet' ) ) ?>" media="(max-width: 1279px)">
						<source srcset="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
						<img src="<?= esc_url( wp_get_attachment_image_url( $background_image, 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $background_image ) ) ?>">
					</picture>

				<?php elseif ( 'video' === $background_type ) : ?>
					<?php
						$background_video_url = wp_get_attachment_url( $background['bg_video'] );
						$background_video_poster = wp_get_attachment_image_url( $background['bg_video_poster'] );
					?>
					<video disablePictureInPicture loop autoplay playsinline muted poster="<?= esc_url( $background_video_poster ); ?>"/>
						<source src="<?= esc_url( $background_video_url ); ?>" type="video/mp4">
					</video>

				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>