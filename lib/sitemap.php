<?php
add_filter( 'wpseo_sitemap_index', 'hmt_add_post_pagination_sitemap_to_sitemap_index' );
function hmt_add_post_pagination_sitemap_to_sitemap_index() {
	global $wpseo_sitemaps;
	$date = date( 'c' );

	$smp = '<sitemap>' . "\n";
	$smp .= '<loc>' . site_url() . '/blog-pagination-sitemap.xml</loc>' . "\n";
	$smp .= '<lastmod>' . htmlspecialchars( $date ) . '</lastmod>' . "\n";
	$smp .= '</sitemap>' . "\n";

	return $smp;
}

add_action( 'init', 'hmt_register_post_pagination_sitemap', 99 );
function hmt_register_post_pagination_sitemap() {
	global $wpseo_sitemaps;
	$wpseo_sitemaps->register_sitemap( 'blog-pagination', 'hmt_generate_pagination_sitemap' );
}

function hmt_generate_pagination_sitemap() {
	global $wpseo_sitemaps;

	$output = '';
	$post_per_page = 6;
	$first_page_post_count = 6;

	$post_category_list = get_terms( array(
		'hide_empty' => false,
		'taxonomy' => ['category', 'post_tag'],
	) );

	$total_post_count = count(
		get_posts( array(
			'post_type' => 'post',
			'posts_per_page' => -1,
		) )
	);

	$post_lists_map = array(
		'all' => array(
			'post_count' => $total_post_count,
			'url' => get_post_type_archive_link( 'post' ),
			'date' => date( 'c' )
		)
	);

	foreach ( $post_category_list as $category ) {
		$post_lists_map[$category->slug] = array(
			'post_count' => $category->count,
			'url' => get_term_link( $category->term_id )
		);
	}

	foreach ( $post_lists_map as $post_category ) {
		$paged_post_count = $post_category['post_count'] - $first_page_post_count;
		$page_count = ceil( $paged_post_count / $post_per_page ) + 1;

		$tested[] = $page_count;

		if ( 2 <= $page_count ) {
			for ( $page = 2; $page <= $page_count; $page++ ) {
				$url = array(
					'loc' => "{$post_category['url']}page/{$page}/",
					'mod' => date( 'c' )
				);
				$output .= $wpseo_sitemaps->renderer->sitemap_url( $url );
			}
		}
	}

	if ( empty( $output ) ) {
		$wpseo_sitemaps->bad_sitemap = true;
		return;
	}

	$sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
	$sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
	$sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	$sitemap .= $output . '</urlset>';

	$wpseo_sitemaps->set_sitemap( $sitemap );
}
