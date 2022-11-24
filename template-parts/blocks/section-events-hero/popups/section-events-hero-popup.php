<?php
/**
 * @var $args ;
 */
$block_id = $args['block_id'];
$event = $args['event'];
?>

<!--Registration Event Popup -->
<div
	class="modal fade event-hero-modal" id="modal-events-<?= esc_attr( $block_id ) ?>" tabindex="-1" role="dialog"
	aria-hidden="true"
>
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<button
					type="button" class="btn modal-close svg-icon" data-dismiss="modal"
					aria-label="<?= __( 'Close popup', THEME_TEXTDOMAIN ) ?>"
				>
					<?= hmt_get_svg_inline( get_template_directory_uri() . "/assets/theme/img/icons/icon-close.svg" ); ?>
				</button>
			</div>

			<div class="modal-body">
				<div class="team-member-card">
					<?php if ( has_post_thumbnail( $event->ID ) ): ?>
						<div class="team-member-card__img">
							<div class="background-img">
								<picture>
									<img
										src="<?= wp_get_attachment_image_src( get_post_thumbnail_id( $event->ID ), 'large' )[0] ?>"
										alt=""
									>
								</picture>
							</div>
						</div>
					<?php endif; ?>

					<div class="team-member-card__content <?= has_post_thumbnail( $event->ID ) ? '' : 'full-width' ?>">
						<div class="team-member-card__date-and-time">
							<div class="block-content">
								<?php if ( isset($event->data->locations) && $event->data->locations[$event->data->meta['mec_location_id']] ): ?>
									<p class="team-member-card__location">
										<span class="icon-wrap">
											<?= hmt_get_svg_inline( get_template_directory_uri() . "/assets/theme/img/icons/icon-position-marker-white.svg" ); ?>
										</span>

										<?= $event->data->locations[$event->data->meta['mec_location_id']]['name'] ?>
									</p>
								<?php endif; ?>
								<?php
								$all_day_event = $event->data->meta['mec_date']['allday'];
								$start_date = strtotime($event->date['start']['date']);
								$start_time = "{$event->date['start']['hour']}:{$event->date['start']['minutes']} {$event->date['start']['ampm']}";
								$end_date = strtotime($event->date['end']['date']);
								$end_time = "{$event->date['end']['hour']}:{$event->date['end']['minutes']} {$event->date['end']['ampm']}";
								$hide_time = $event->data->meta['mec_hide_time'];
								$hide_end_time = $event->data->meta['mec_hide_end_time'];
								?>

								<p class="team-member-card__date">
									<span class="icon-wrap">
										<?= hmt_get_svg_inline( get_template_directory_uri() . "/assets/theme/img/icons/icon-calendar.svg" ); ?>
									</span>

									<?php if( $all_day_event && ($start_date ==  $end_date) ): ?>
										<?= date( 'F jS, Y', $start_date ) ?>
									<?php elseif( $all_day_event ): ?>
										<?= date( 'F jS, Y', $start_date ) . ' - ' . date( 'F jS, Y', $end_date ) ?>
									<?php elseif( $start_date ==  $end_date ): ?>
										<?= date( 'F jS, Y', $start_date ) ?>
									<?php else: ?>
										<?php if( $hide_time ): ?>
											<?= date( 'F jS, Y', $start_date ) . ' - ' . date( 'F jS, Y', $end_date ) ?>
										<?php else: ?>
											<?= date( 'F jS, Y', $start_date ) . ' ' . $start_time . ' - ' . date( 'F jS, Y', $end_date ) . (!$hide_end_time ?  ' ' . $end_time: '') ?>
										<?php endif; ?>
									<?php endif; ?>
								</p>

								<?php if( !$all_day_event && ($start_date ==  $end_date) && !$hide_time): ?>
									<p class="team-member-card__time">
										<span class="icon-wrap">
											<?= hmt_get_svg_inline( get_template_directory_uri() . "/assets/theme/img/icons/icon-clock.svg" ); ?>
										</span>

										<?= $hide_end_time ? $start_time : $start_time  . ' - ' . $end_time  ?>
									</p>
								<?php endif; ?>
							</div>
						</div>

						<h3 class="team-member-card__title">
							<?= esc_html( $event->data->title ) ?>
						</h3>

						<div class="team-member-card__body">
							<div class="scrollbar-outer">
								<div class="team-member-card__description text-content">
									<?= wpautop( $event->data->content ) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Registration Event Popup -->
							