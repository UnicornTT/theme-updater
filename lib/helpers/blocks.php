<?php

function hmt_get_block_style_name( $block ) {
	$block_style_name = false;
	$block_styles = $block['styles'] ?? [];
	$block_class_name = $block['className'] ?? [];

	foreach ( $block_styles as $i => $block_style ) :
		if ( str_contains( $block_class_name, 'is-style-' . $block_style['name'] ) ) :
			$block_style_name = $block_style['name'];
		endif;
	endforeach;

	return $block_style_name;
}
