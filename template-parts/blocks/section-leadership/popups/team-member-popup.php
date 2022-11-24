<?php
/**
 * @var $args ;
 */

$team_member_info = $args['team_member_info'];
$popup_title = $team_member_info['title'] ?? '';
$popup_first_name = $team_member_info['first_name'] ?? '';
$popup_last_name = $team_member_info['last_name'] ?? '';
$popup_position = $team_member_info['position'] ?? '';
$description = isset( $team_member_info['description'] ) ? get_extended( $team_member_info['description'] ) : '';
?>

<div class="modal fade modal-person" id="<?= $args['popup_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<button
					type="button" class="btn modal-close svg-icon" data-dismiss="modal"
					aria-label="<?= esc_html( __( 'Close popup', THEME_TEXTDOMAIN ) ) ?>"
				>
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
				</button>
			</div>

			<div class="modal-body">
				<div class="team-member-card">
					<div class="team-member-card__img">
						<?= wp_get_attachment_image( $team_member_info['photo'], 'section-background-desktop', false, ['alt' => esc_attr( hmt_get_attachment_image_alt( $team_member_info['photo'] ) )] ); ?>
					</div>

					<div class="team-member-card__content">
						<div class="scrollbar-outer">
							<div class="team-member-card__body">
								<div class="team-member-card__title section-title section-title--style3">
									<?php if ( !empty( $popup_first_name ) || !empty( $popup_last_name ) ) : ?>
										<h2>
											<?= esc_html( $popup_first_name ) . (esc_html( $popup_first_name ) ? ' ' : '') . esc_html( $popup_last_name ) . (esc_html( $popup_first_name ) || esc_html( $popup_last_name ) ? ', ' : '') . (esc_html( $popup_position ) ?? '') ?>
										</h2>
									<?php endif; ?>
								</div>

								<?php if ( !empty( $description ) ) : ?>
									<div class="team-member-card__description text-content">
										<?php if ( $description['main'] ) : ?>
											<?= apply_filters('the_content', $description['main']) ?>
											<?= apply_filters('the_content', $description['extended']) ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>