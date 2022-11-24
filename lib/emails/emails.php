<?php

use \Harbinger_Marketing\PDF_Generator;

$color_dark = get_field( 'color_dark', 'option' );
$color_accent_1 = get_field( 'color_accent_1', 'option' );
$color_main = is_array( $color_dark ) ? 'rgb(' . $color_dark['red'] . ',' . $color_dark['green'] . ',' . $color_dark['blue'] . ')' : $color_dark;
$color_secondary = is_array( $color_accent_1 ) ? 'rgb(' . $color_accent_1['red'] . ',' . $color_accent_1['green'] . ',' . $color_accent_1['blue'] . ')' : $color_accent_1;

define( 'THEME_BRAND_COLOR_MAIN_EMAIL', $color_main );
define( 'THEME_BRAND_COLOR_SECONDARY_EMAIL', $color_secondary );

add_filter( 'ninja_forms_action_email_from_address', 'hmt_ninja_forms_action_email_from_address', 100 );
function hmt_ninja_forms_action_email_from_address($from_address)
{
	$domain = preg_replace( '#http(s)?://(www\.)?#is', '', get_option( 'siteurl' ) );
	$from_address = 'noreply@' . $domain;
	return $from_address;
}

add_filter( 'ninja_forms_action_email_message', 'hmt_ninja_forms_alter_email_template', 10, 3 );
function hmt_ninja_forms_alter_email_template($message, $data, $action_settings)
{
	ob_start();

	$fields = hmt_organize_ninja_forms_fields_array( $data['fields_by_key'] );

	switch ( $data['settings']['key'] ) {
		case 'application-form':
			require(THEME_EMAIL_TEMPLATES_PATH . '/application-form.php');
			break;

		default:
			require(THEME_EMAIL_TEMPLATES_PATH . '/default.php');
			break;
	}

	return ob_get_clean();
}

add_filter( 'ninja_forms_action_email_attachments', 'hmt_ninja_forms_application_form_email_add_pdf_attachment', 10, 3 );
function hmt_ninja_forms_application_form_email_add_pdf_attachment($attachments, $data, $settings)
{
	if ( $data['settings']['key'] != 'application-form' ) {
		return $attachments;
	}

	$pdf_template = THEME_PDF_TEMPLATES_PATH . '/application-form.php';
	$pdf_data = array(
		'form_title' => $data['settings']['title'],
		'fields' => hmt_organize_ninja_forms_fields_array( $data['fields_by_key'] )
	);
	$pdf_file_name = 'application-' . hash( 'md5', $pdf_template . serialize( $pdf_data ) ) . '.pdf';

	$attachments[] = PDF_Generator::generate( $pdf_template, $pdf_data, $pdf_file_name );

	return $attachments;
}