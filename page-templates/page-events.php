<?php
/*
Template Name: Events
*/
?>

<?php get_header() ?>

<?php get_template_part( 'sections/calendar-events/calendar-events', 'events' ); ?>

<?php the_content() ?>

<?php
	$global_block = get_posts(
		[
			'post_type' => 'wp_block',
			'title' => 'Contact Us',
		]
	);

	if ( isset( $global_block[0] ) ) {
		foreach ( parse_blocks( ($global_block[0]->post_content) ) as $block ) {
			echo render_block( $block );
		}
	}
?>

<?php get_footer() ?>