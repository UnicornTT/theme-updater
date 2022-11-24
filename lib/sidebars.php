<?php


add_action( 'widgets_init', 'hmt_register_custom_sidebars' );
function hmt_register_custom_sidebars() {
	register_sidebar(
		array(
			'id' => 'resources-sidebar',
			'name' => __( 'Resources Sidebar' ),
			'description' => __( 'Sidebar for resources' ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		)
	);

	register_sidebar(
		array(
			'id' => 'posts-sidebar',
			'name' => __( 'Posts Sidebar' ),
			'description' => __( 'Sidebar for posts' ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		)
	);

	register_sidebar(
		array(
			'id' => 'footer-sidebar',
			'name' => __( 'Footer Sidebar' ),
			'description' => __( 'Sidebar for footer' ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		)
	);
}