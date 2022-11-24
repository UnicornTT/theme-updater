<?php
// create routes
add_action( 'rest_api_init', 'hmt_rest_api_routes' );
function hmt_rest_api_routes() {

	// пространство имен
	$namespace = 'api';

	// Show More Posts
	register_rest_route( $namespace, '/load_more_resources/', [
		'methods' => 'POST',
		'callback' => 'hmt_load_more_resources',
	] );

	// Get Filtered Resources
	register_rest_route( $namespace, '/get_filtered_resources/', [
		'methods' => 'POST',
		'callback' => 'hmt_get_filtered_resources',
	] );
}


function hmt_load_more_resources( WP_REST_Request $request ) {
	global $wp_query;
	$args = json_decode( stripslashes( $request->get_param( 'query' ) ), true );
	$args['paged'] = $request->get_param( 'page' ); // we need next page to be loaded
	$args['post_status'] = 'publish';

	$wp_query->set( 'orderby', 'date' );
	$wp_query->set( 'order', 'DESC' );

	query_posts( $args );

	if ( have_posts() ) {
		// run the loop
		while ( have_posts() ) {
			the_post();

			get_template_part( 'parts/resources/card', 'article' );
		}
	}
	die; // here we exit the script and even no wp_reset_query() required!
}


function hmt_get_filtered_resources( WP_REST_Request $request ) {
	$category_id = (int)$request->get_param( 'categoryId' );
	$tags = (array)$request->get_param( 'tags' );
	$tax_query = [];

	if ( $category_id > 0 ) {
		$tax_query[] = [
			'taxonomy' => 'category',
			'terms' => [$category_id],
			'include_children' => false
		];
	}

	if ( count( $tags ) > 0 ) {
		$tax_query[] = [
			'taxonomy' => 'post_tag',
			'terms' => $tags,
		];
	}

	$posts = get_posts( array(
		'post_type' => 'post',
		'posts_per_page' => 5,
		'tax_query' => $tax_query
	) );

	get_template_part( 'parts/resources/filtered', 'articles', ['posts' => $posts] );

	die;
}

?>