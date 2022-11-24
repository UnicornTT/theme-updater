<?php
function hmt_render_event_time( $mec_date ) {
	$start_hour = $mec_date['start']['hour'] < 10 ? '0' . $mec_date['start']['hour'] : $mec_date['start']['hour'];
	$end_hour = $mec_date['end']['hour'] < 10 ? '0' . $mec_date['end']['hour'] : $mec_date['end']['hour'];
	$start_min = $mec_date['start']['minutes'] < 10 ? '0' . $mec_date['start']['minutes'] : $mec_date['start']['minutes'];
	$end_min = $mec_date['end']['minutes'] < 10 ? '0' . $mec_date['end']['minutes'] : $mec_date['end']['minutes'];
	$start_ampm = strtolower( $mec_date['start']['ampm'] );
	$end_ampm = strtolower( $mec_date['end']['ampm'] );

	return "$start_hour:$start_min $start_ampm - $end_hour:$end_min $end_ampm";
}


add_filter( 'mec_search_fields_to_box', 'hmt_search_fields_to_box', 5, 10 );
function hmt_search_fields_to_box( $output, $field, $type, $atts, $id ) {
	if ( $field == 'category' ) {
		$output = str_replace( '<i class="mec-sl-folder"></i>', '<span class="icon-filter"><span class="path1"></span><span class="path2"></span></span>', $output );
		return str_replace( 'Category', 'All', $output );
	}
	return $output;
}


add_filter( 'manage_mec-events_posts_columns', 'hmt_events_posts_columns' );
function hmt_events_posts_columns( $columns ) {
	$featured_column = array(
		'featured' => __( 'Featured', THEME_TEXTDOMAIN )
	);

	$columns = array_slice( $columns, 0, 3 ) + $featured_column + array_slice( $columns, 3 );

	return $columns;
}


add_filter( 'manage_edit-mec-events_sortable_columns', 'hmt_events_sortable_columns' );
function hmt_events_sortable_columns( $columns ) {
	$columns['featured'] = 'featured';

	return $columns;
}


add_action( 'pre_get_posts', 'hmt_pre_get_posts' );
function hmt_pre_get_posts( $query ) {
	if ( !is_admin() )
		return;

	$orderby = $query->get( 'orderby' );

	if ( 'featured' == $orderby ) {
		$query->set( 'meta_key', '_is_featured_event' );
		$query->set( 'orderby', 'meta_value' );
	}
}

?>