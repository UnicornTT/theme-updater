<div class="modal fade modal-open-position" id="modal-open-position" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn modal-close svg-icon" data-dismiss="modal" aria-label="<?= esc_attr( __( 'Close popup', THEME_TEXTDOMAIN ) ) ?>">
                    <?= hmt_get_svg_inline( THEME_ASSETS_URL . "/theme/img/icons/icon-close.svg" ); ?>
                </button>
            </div>

            <div class="modal-body">
                <div class="open-position-modal-info">
					<div class="section-title section-title--style3 open-position-modal-info__title">
						<h3>
							
						</h3>
					</div>

					<div class="open-position-modal-info__general text-content"></div>

					<div class="open-position-modal-info__specifications text-content"></div>

					<button data-position-id="" data-target="#job-application" class="button button-primary open-position-modal-info__button">
						<?= esc_html( __( 'Apply Now', THEME_TEXTDOMAIN ) ) ?>
					</button>
                </div>
            </div>
        </div>
    </div>
</div>