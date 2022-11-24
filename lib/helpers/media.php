<?php

function hmt_get_media_url( $attachment_id, $image_size = 'full-hd' ) {
	if ( wp_attachment_is_image( $attachment_id ) ) {
		$attachment = wp_get_attachment_image_src( $attachment_id, $image_size );

		return $attachment[0];
	}

	return wp_get_attachment_url( $attachment_id );
}

function hmt_get_media_type_by_id( $file_id ) {
	$file_url = wp_get_attachment_url( $file_id );
	$file_extension = wp_check_filetype( $file_url );

	return $file_extension['ext'];
}