<!-- Request Consultation Popup -->
<div class="modal fade modal-form js-modal-form" id="request-consultation" tabindex="-1" role="dialog"
     aria-hidden="true">
	<div class="modal-dialog modal-dialog-form modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button
					type="button"
					class="btn modal-close svg-icon"
					data-dismiss="modal"
					aria-label="<?= esc_attr( __( 'Close popup', THEME_TEXTDOMAIN ) ) ?>"
				>
					<?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
				</button>
			</div>

			<div class="modal-body">
				<div class="modal-form-wrapper">
					<?php
					$request_consultation_form_id = get_field( 'request_consultation_form_id', 'option' );
					?>
					<?php if ( !empty( $request_consultation_form_id ) ) : ?>
						<?= do_shortcode( "[ninja_form id={$request_consultation_form_id}]" ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Request Consultation Popup -->