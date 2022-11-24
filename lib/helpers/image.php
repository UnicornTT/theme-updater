<?php

function hmt_get_image_dimensions( $image_path ) {
	$image_path = hmt_convert_url_to_path( $image_path );
	$image_dimensions = getimagesize( $image_path );

	return array(
		'width' => $image_dimensions[0],
		'height' => $image_dimensions[1]
	);
}


function hmt_get_image_width( $image_path ) {
	return hmt_get_image_dimensions( $image_path )['width'];
}


function hmt_get_image_height( $image_path ) {
	return hmt_get_image_dimensions( $image_path )['height'];
}


function hmt_get_image_aspect_ratio( $image_path ) {
	$image_dimensions = hmt_get_image_dimensions( $image_path );

	return $image_dimensions['width'] / $image_dimensions['height'];
}