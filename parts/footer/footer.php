<?php

$phone_number = get_field( 'phone_number', 'option' );
$address = get_field( 'address', 'option' );
$address_link = get_field( 'address_link', 'option' );
$socials = get_field( 'social', 'option' );

$logo_footer_colorful_id = get_field( 'logo_header_single_dark', 'option' );
$logo_footer_id = get_field( 'logo_header_single_light', 'option' );

$home_url = get_home_url();

$action_button_type = get_field( 'footer_section_action_button_type', 'option' );
$action_button_text = get_field( 'footer_section_action_button_text', 'option' );

?>

<footer class="page-footer">
	<div class="container">
		<div class="page-footer__content">
			<div class="page-footer__info page-footer__info--v1">
				<div class="row align-items-start justify-content-between default-wp-widgets-container">
					<?php dynamic_sidebar( 'footer-sidebar' ); ?>
				</div>
			</div>

			<div class="page-footer__copyright">
				<?= get_template_part( 'parts/footer/footer-copyright' ) ?>
			</div>
		</div><!-- .page-footer__content -->
	</div>
</footer>
