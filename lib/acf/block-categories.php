<?php

add_filter( 'block_categories_all', 'hmt_filter_block_categories_when_post_provided', 10, 2 );
function hmt_filter_block_categories_when_post_provided( $block_categories, $editor_context ) {

	array_unshift( $block_categories, array(
		'slug' => 'sections',
		'title' => __( 'Sections', THEME_TEXTDOMAIN ),
		'icon' => null,
	) );

	return $block_categories;
}