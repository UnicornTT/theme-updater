<?php
/*
Template Name: Single Industry
*/

$background = (array)get_field( 'industries_served_background' );
$media = get_field( 'industries_served_group' );
$description = get_field( 'industries_served_description' );

$section_media_block = get_field( 'industries_media_group' );
$section_media = $section_media_block['industries_media_media_type'];

$popup_video_type = $section_media_block['industries_popup_video_video_type'];
$popup_video_src = $section_media_block['industries_popup_video_video_file'];
$popup_video_youtube_id = hmt_get_youtube_video_id_from_url( $section_media_block['industries_popup_video_youtube_link'] );
$popup_image_poster = $section_media_block['industries_popup_video_image_poster'] ?? '';

$popup_id = wp_generate_uuid4();

$popup_video_enabled = false;

if ( $popup_video_type == 'youtube' ) {
	if ( $popup_video_youtube_id ) {
		$popup_video_enabled = true;
	}
}

if ( $popup_video_type == 'self_hosted' ) {
	if ( $popup_video_src ) {
		$popup_video_enabled = true;
	}
}
?>

<?php get_header() ?>

	<section id="industry-article" class="section section-industry">
		<?php
			get_template_part( 'parts/resources/section-background', '', [
				'class_name' => 'section-industry__bg',
				'field_prefix' => 'industries_served_background',
				'field_id' => ''
			] );
		?>

		<div class="section__body">
			<article class="section-industry__content">
				<div class="container">
					<div class="section-industry__header">
						<div class="row justify-content-between">
							<div class="col-12 col-xl-11">
								<div class="section-industry__media">
									<div class="background-img">
										<?php if ( $section_media == 'image' ) : ?>
											<picture>
												<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media_block['industries_media_image'], 'section-background-desktop' ) ) ?>" media="(min-width: 1280px)">
												<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media_block['industries_media_image'], 'section-background-tablet' ) ) ?>" media="(max-width: 1279px)">
												<source srcset="<?= esc_url( wp_get_attachment_image_url( $section_media_block['industries_media_image'], 'section-background-mobile' ) ) ?>" media="(max-width: 767px)">
												<img src="<?= esc_url( wp_get_attachment_image_url( $section_media_block['industries_media_image'], 'section-background-desktop' ) ) ?>" alt="<?= esc_attr( hmt_get_attachment_image_alt( $section_media_block['industries_media_image'] ) ); ?>">
											</picture>

										<?php elseif ( $section_media == 'video' ) : ?>
											<?php
												$poster = wp_get_attachment_image_url( $section_media_block['industries_media_video_poster'], 'section-background-desktop' );
												$video_src = wp_get_attachment_url( $section_media_block['industries_media_video'] );
											?>
											<video disablePictureInPicture muted playsinline autoplay poster="<?= esc_url( $poster ) ?>" loop="loop">
												<source src="<?= esc_url( $video_src ) ?>" type="video/mp4">
											</video>

										<?php endif; ?>

										<?php if ( $popup_video_enabled ) : ?>
											<a href="#modal-video-single-industry" class="button-play button-play--small" data-toggle="modal" aria-label="<?= esc_attr( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>">
												<span class="button-play__icon">
													<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-play.svg' ); ?>
												</span>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<?php
								$next_post_id = get_adjacent_post( false, '', true )->ID;

								if ( !$next_post_id ) {
									$latest_post = get_posts( [
										'post_type' => 'industries-served',
										'limit' => 1,
									] );

									if ( isset( $latest_post[0] ) ) {
										$next_post_id = $latest_post[0]->ID;
									}
								}
							?>
							<?php if ( $next_post_id ) : ?>
								<div class="col-12 col-xl-1">
									<div class="section-industry__next">
										<a class="next-link" href="<?= esc_url( get_permalink( $next_post_id ) ) ?>">
											<span class="next-link__icon icon icon-wrap" aria-hidden="true">
												<?= hmt_get_svg_inline( THEME_ASSETS_URL . '/theme/img/icons/icon-arrow-left.svg' ); ?>
											</span>

											<span class="next-link__text section-title section-title--style5">
												<?= get_post( $next_post_id )->post_title ?>
											</span>
										</a>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="section-industry__main">
						<div class="section-industry__subtitle section-title section-title--style6" aria-hidden="true">
							<?= esc_html( __( 'Industries Served', THEME_TEXTDOMAIN ) ) ?>
						</div>

						<div class="section-industry__title section-title section-title--style1">
							<h1>
								<?= esc_html( get_the_title() ) ?>
							</h1>
						</div>

						<?php if ( !empty( $description ) ) : ?>
							<div class="section-industry__article text-content">
								<?= apply_filters( 'the_content', $description ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</article>
		</div>
	</section>

<?php
$global_block = get_posts(
	[
		'post_type' => 'wp_block',
		'title' => 'Contact Us',
	]
);

if ( isset( $global_block[0] ) ) {
	foreach ( parse_blocks( ($global_block[0]->post_content) ) as $block ) {
		echo render_block( $block );
	}
}
?>

<?php if( $popup_video_enabled ): ?>
	<?php
	get_template_part(
		'template-parts/popups/popup',
		'video',
		[
			'popup_id' => 'modal-video-single-industry',
			'popup_video_youtube_player_id' => 'modal-video-player-single-industry',
			'popup_video_type' => $popup_video_type,
			'popup_video_poster_id' => $popup_image_poster,
			'popup_video_src_id' => $popup_video_src,
			'popup_video_youtube_id' => $popup_video_youtube_id,
		]
	);
	?>
<?php endif; ?>

<?php get_footer() ?>