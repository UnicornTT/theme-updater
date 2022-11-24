<?php

function hmt_get_template_part_with_params( $path, array $params = array() ) {
	extract( $params );

	require(locate_template( $path . '.php' ));
}


function hmt_template_part_with_params( $path, array $params = array() ) {
	ob_start();
	get_template_part( $path, '', $params );
	$layout = ob_get_contents();
	ob_end_clean();

	return $layout;
}


function hmt_get_page_by_template( $template ) {
	$page = get_posts(
		array(
			'post_type' => 'any',
			'numberposts' => 1,
			'meta_key' => '_wp_page_template',
			'meta_value' => $template
		)
	);

	if ( $page ) {
		return $page[0];
	}
}


function hmt_get_page_link_by_template( $template ) {
	$page = hmt_get_page_by_template( $template );

	if ( $page ) {
		return get_permalink( $page->ID );
	}
}


function hmt_get_page_link_by_slug( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page ) {
		return get_permalink( $page->ID );
	}
}


function hmt_get_locations() {
	$locations = []; //array to store all of your category names

	if( taxonomy_exists('hmt_location') ){
		$category_list_items = get_terms( 'hmt_location', [
			'hide_empty' => false,
		] );

		foreach ( $category_list_items as $category_list_item ) {
			if ( !empty( $category_list_item->slug ) ) {
				array_push( $locations, $category_list_item->slug );
			}
		}
	}

	return $locations;
}


function hmt_check_external_url( $url ) {
	$components = parse_url( $url );
	return !empty( $components['host'] ) && strcasecmp( $components['host'], $_SERVER['SERVER_NAME'] ); // empty host will indicate url like '/relative.php'
}


/**
 * @return WP_Term|null Current location term or null (if location is 'default')
 */
function hmt_get_current_location_term(): ?WP_Term {
	$location_slug = $_SESSION['location'] ?? 'default';

	if ( 'default' === $location_slug ) {
		return null;
	}

	$location_list = hmt_get_location_list();
	foreach ( $location_list as $location ) {
		if ( $location->slug === $location_slug ) {
			return $location;
		}
	}

	return null;
}


/**
 * @return string Current location name or 'default'
 */
function hmt_get_current_location_slug(): string {
	$location_term = hmt_get_current_location_term();

	return !is_null( $location_term ) ? $location_term->slug : 'default';
}


/**
 * @param string|WP_Term $location Location
 */
function hmt_set_current_location( $location ) {
	$_SESSION['location'] = is_string( $location ) ? $location : $location->slug;
}


/**
 * @return WP_Term[]
 */
function hmt_get_location_list(): array {
	static $location_list = null;
	if ( !is_null( $location_list ) ) {
		return $location_list;
	}

	if( taxonomy_exists('hmt_location') ){
		$location_list = get_terms( array(
			'taxonomy' => 'hmt_location',
			'hide_empty' => false,
		) );

		return $location_list;
	}

	return array();
}


/**
 * @param bool $cached Get Cached list (One-time load)
 *
 * @return array
 */
function hmt_get_location_slug_list( $cached = true ): array {
	static $location_slug_list = null; //array to store all of your category names
	if ( !is_null( $location_slug_list ) && $cached ) {
		return $location_slug_list;
	}

	$location_slug_list = [];

	$location_list = hmt_get_location_list();

	foreach ( $location_list as $location ) {
		if ( !empty( $location->slug ) ) {
			$location_slug_list[] = $location->slug;
		}
	}

	return $location_slug_list;
}


/**
 * @param int|string $id Image ID
 *
 * @return string
 */
function hmt_get_attachment_image_alt( $id ): string {
	return esc_html( (string)get_post_meta( $id, '_wp_attachment_image_alt', true ) );
}


/**
 * Return Service list for location
 *
 * @param null|string|WP_Term $location
 *
 * @return WP_Post[] Service list
 */
function hmt_get_location_service_list( $location, $service_type = null ): array {

	if( taxonomy_exists('hmt_location') ){
	
		$location_menu_logics = get_field( 'location_menu_logics', 'option' );
		$location_tax_condition = null;

		if ( (is_null( $location ) || 'default' === $location) && $location_menu_logics == 'without_default' ) {
			$location_tax_condition = array(
				'taxonomy' => 'hmt_location',
				'operator' => 'NOT EXISTS',
			);
		} else if ( is_string( $location ) ) {
			$location_tax_condition = array(
				'taxonomy' => 'hmt_location',
				'field' => 'slug',
				'terms' => $location
			);
		} else {
			$location_tax_condition = array(
				'taxonomy' => 'hmt_location',
				'terms' => [$location->term_id ?? '']
			);
		}

		$tax_query['relation'] = 'OR';
		if ( $location_menu_logics == 'with_default' ) {
			$tax_query[] = [
				'taxonomy' => 'hmt_location',
				'operator' => 'NOT EXISTS',
			];
		}

		$tax_query[] = $location_tax_condition;
		$tax_query = [$tax_query];

		$residential_commercial = [];
		if ( !is_null( $service_type ) ) {
			$residential_commercial = array(
				'taxonomy' => 'service-types',
				'field' => 'slug',
				'terms' => array($service_type)
			);

			$tax_query[] = $residential_commercial;
		}

		$location_services = get_posts( array(
			'post_type' => 'hmt_service',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'tax_query' => [...$tax_query]
		) );


		if ( $location_menu_logics == 'with_default' ) {
			$filtered_location_services = [];
			foreach ( $location_services as $key => $service ) {
				$service_slug_without_location = str_replace( '-' . hmt_get_current_location_slug(), '', $service->post_name );

				if ( !isset( $filtered_location_services[$service_slug_without_location] ) || strripos( $service->post_name, '-' . hmt_get_current_location_slug() ) !== false ) {
					$filtered_location_services[$service_slug_without_location] = $service->ID;
					continue;
				}
			}

			$location_services = array_filter( $location_services, function ( $service ) use ( $filtered_location_services ) {
				return in_array( $service->ID, $filtered_location_services );
			} );
		}

		return $location_services;
	}

	return array();
}


/**
 * Return Service list for current location
 *
 * @return WP_Post[] Service List
 */
function hmt_get_current_location_service_list( $service_type = null ): array {
	return hmt_get_location_service_list( hmt_get_current_location_term(), $service_type );
}


/**
 * Return Post list
 *
 * @param
 *
 * @return WP_Post[] Posts list
 */
function hmt_get_post_list( $post_per_page ): array {
	return get_posts( array(
		'post_type' => 'post',
		'posts_per_page' => $post_per_page,
	) );
}


/**
 * Return User Posts list
 *
 * @param
 *
 * @return WP_Post[] Posts list
 */
function hmt_get_user_post_list( $userID ): array {
	return get_posts( array(
		'author' => $userID,
		'orderby' => 'date',
		'order' => 'DESC',
		'posts_per_page' => -1,
	) );
}


/**
 * Return Page User URL
 *
 * @param number $userID
 *
 * @return string Absolute User Page URL
 */
function hmt_get_user_page_url( $userID ): string {
	return get_site_url() . '/profiles/' . get_the_author_meta( 'nicename', $userID );
}


/**
 * Return Compound <ul> (<ol>) List Into Array
 *
 * @param string $stringHTML
 *
 *
 * @example
 * 		Get:
 * 			<p>Some paragraph.</p>
 * 			<ul>
 * 				<li>first</li>
 * 			</ul>
 * 		Return:
 * 			[0] => <p>Some paragraph.</p>,
 * 			[1] => <ul><li>first</li></ul>
 *
 * @return array|null
 */
function hmt_compound_list_HTML( string $stringHTML ): ?array {
	preg_match_all( '/.*\n*/', $stringHTML, $content_matches );

	if ( !$content_matches[0] ) return null;

	$new_html = [];
	$compare_list = '';

	foreach ( $content_matches[0] as $node ) {

		switch ( $node ) {
			case stripos( $node, '<ul>' ) !== false || stripos( $node, '<ol>' ) !== false :
				$compare_list .= $node;
				break;
			case stripos( $node, '<li>' ) !== false :
				$compare_list .= $node;
				break;
			case stripos( $node, '</ul>' ) !== false || stripos( $node, '</ol>' ) !== false :
				$compare_list .= $node;
				array_push( $new_html, $compare_list );
				$compare_list = '';
				break;
			default:
				array_push( $new_html, $node );
				break;
		}

	}

	return $new_html;
}


/**
 * @return bool is blog page
 */
function hmt_is_blog(): bool {
	return (is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type() || is_search();
}

/**
 * Return Post list
 *
 * @param
 *
 * @return WP_Post[] Posts list
 */
function hmt_array_sort( $array, $on, $order = SORT_ASC ) {
	$new_array = array();
	$sortable_array = array();

	if ( $array ) {
		if ( count( $array ) > 0 ) {
			foreach ( $array as $k => $v ) {
				if ( is_array( $v ) ) {
					foreach ( $v as $k2 => $v2 ) {
						if ( $k2 == $on ) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ( $order ) {
				case SORT_ASC:
					asort( $sortable_array );
					break;
				case SORT_DESC:
					arsort( $sortable_array );
					break;
			}

			foreach ( $sortable_array as $k => $v ) {
				$new_array[$k] = $array[$k];
			}
		}
	}

	return $new_array;
}


function hmt_esc_extended_content( $string ) {
	$string = trim( $string );

	preg_match( '/<[\w\d\s]+>/', $string, $matches);
	$space_pos = stripos( $string, ' ' );
	$open_tag_pos = stripos( $string, $matches[0] );
	$plus_index = ($space_pos < $open_tag_pos) ? $space_pos : $open_tag_pos;
	$start_substr = trim( substr( $string, 0, $plus_index ) );
	$start_substr = preg_replace( '/<\/[a-zA-Z0-9]+>/', '', $start_substr );

	$space_pos = strrpos( $string, ' ' );
	$close_tag_pos = strrpos( $string, '</' );
	$extract_pos = ($space_pos > $close_tag_pos) ? $space_pos : $close_tag_pos;
	$minus_index = strlen( $string ) - $extract_pos;
	$end_substr = trim( substr( $string, -$minus_index ) );
	$end_substr = preg_replace( '/<[a-zA-Z0-9]+>/', '', $end_substr );

	$middle_string = substr( $string, $plus_index, -$minus_index );

	return trim($start_substr . ' ' . $middle_string . ' ' . $end_substr);
}


function hmt_trim_text( $string, $offset = 300 ) {
	$str_lenght = strlen($string);
	if( $str_lenght > $offset ){
		$plus_index = stripos( $string, ' ', $offset);
		if($plus_index){
			$trimed_string = trim( substr( $string, 0, $plus_index ) );
			$trimed_string .= '...';
			return $trimed_string;
		}
	}
	return $string;
	
}

function hmt_truncate($text, $length = 100, $options = array()) {
    $default = array(
        'ending' => '...', 'exact' => true, 'html' => false
    );
    $options = array_merge($default, $options);
    extract($options);
 
    if ($html) {
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';
 
        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];
 
            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }
 
                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, ' ');
        if (isset($spacepos)) {
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
    $truncate .= $ending;
 
    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }
 
    return $truncate;
}