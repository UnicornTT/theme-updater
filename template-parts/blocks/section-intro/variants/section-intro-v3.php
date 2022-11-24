<?php
$section_subtitle = get_field( 'hero_section_subtitle' );
$section_title = get_field( 'hero_section_title' );
$section_description = get_field( 'hero_section_description' );
$action_button = get_field( 'hero_section_action_button' );
$action_button_type = $action_button['type'] ?? '';
$action_button_text = $action_button['text'] ?? '';
$section_media_block = get_field( 'hero_section_video_group' );
$popup_video_type = $section_media_block['video_type'] ?? '';
$popup_video_src = $section_media_block['video_file'] ?? '';
$popup_video_youtube_id = isset( $section_media_block['youtube_link'] ) ? hmt_get_youtube_video_id_from_url( $section_media_block['youtube_link'] ) : '';
$popup_video_poster = $section_media_block['image_poster'] ?? '';
$popup_id = wp_generate_uuid4();
$popup_video_enabled = $popup_video_type === 'youtube' && $popup_video_youtube_id || $popup_video_type === 'self_hosted' && $popup_video_src;
?>

<?php
get_template_part( 'parts/resources/section-background', '', [
	'class_name' => 'section-intro__bg',
	'field_prefix' => 'hero_section',
	'field_id' => ''
] );
?>

<div class="section__body">
	<div class="container">
		<div class="section-intro__content js-font-adjustment" data-fj-min="40">
			<div class="section-intro__main">
				<?php if ( $section_subtitle ) : ?>
					<div class="section-subtitle section-title section-title--style5 section-intro__subtitle">
						<?= esc_html( $section_subtitle ) ?>
					</div>
				<?php endif; ?>

				<?php if ( $section_title ) : ?>
					<div class="section-title section-title--style1 section-intro__title">
						<h1 class="js-font-title">
							<?= $section_title ?>
						</h1>
					</div>
				<?php endif; ?>

				<div class="section-intro__buttons">
					<?php if ( $action_button_type !== 'empty' ) : ?>
						<?php if ( $action_button_type === 'link' ) : ?>
							<?php
							$button_link = $action_button['link'] ?? '';
							?>
							<?php if ( !empty( $button_link ) ) : ?>
								<?php
								$button_title = $button_link['title'] ? $button_link['title'] : '';
								$url = $button_link['url'] ? $button_link['url'] : '';
								$target = $button_link['target'] ? $button_link['target'] : '_self';
								?>
								<a
									href="<?= esc_url( $url ) ?>"
									target="<?= esc_attr( $target ); ?>"
									class="button button-primary section-intro__button"
								>
									<?= $button_title ? esc_html( $button_title ) : esc_html( __( 'Learn More', THEME_TEXTDOMAIN ) ); ?>
								</a>
							<?php endif; ?>

						<?php elseif ( $action_button_type === 'modal' ) : ?>
							<?php
							$action_button_modal = $action_button['modal'] ?? '';
							Harbinger_Marketing\Modal_Action::add_modal_action_to_render_list( $action_button_modal );
							?>
							<a
								href="#<?= $action_button_modal ?>"
								data-toggle="modal"
								class="button button--main-menu main-menu__button"
							>
								<?= esc_html( $action_button_text ) ?>
							</a>

						<?php endif ?>
					<?php endif; ?>

					<!-- Video popup button -->
					<?php if ( $popup_video_enabled ) : ?>
						<a
							href="#modal-video-intro"
							class="button button-bordered button-bordered-white section-intro__button"
							data-toggle="modal"
						>
							<?= esc_html( __( 'Watch Video', THEME_TEXTDOMAIN ) ) ?>
						</a>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if ( $popup_video_enabled ) {
	get_template_part(
		'template-parts/popups/popup',
		'video',
		[
			'popup_id' => 'modal-video-intro',
			'popup_video_youtube_player_id' => 'modal-video-player-intro',
			'popup_video_type' => $popup_video_type,
			'popup_video_poster' => $popup_video_poster,
			'popup_video_src_id' => $popup_video_src,
			'popup_video_youtube_id' => $popup_video_youtube_id,
		]
	);
}
?>