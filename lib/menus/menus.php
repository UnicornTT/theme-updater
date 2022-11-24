<?php

register_nav_menu( 'main-menu', __( 'Main Menu', THEME_TEXTDOMAIN ) );
register_nav_menu( 'copyright-menu', __( 'Copyright Menu', THEME_TEXTDOMAIN ) );


class HMT_Walker_Nav_Menu extends Walker_Nav_Menu {
	public $allowed_submenu_marks_list = ['{{SERVICES_SUBMENU}}', '{{LOCATIONS_SUBMENU}}'];

	private $submenu_marks_map;
	private $location_specified_service_list = null;
	private $page_for_posts;

	public function __construct() {
		$this->page_for_posts = get_option( 'page_for_posts' );
		$this->submenu_marks_map = array_combine(
			$this->allowed_submenu_marks_list,
			array_fill( 0, count( $this->allowed_submenu_marks_list ), true )
		);
	}


	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array('sub-menu');

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args An object of `wp_nav_menu()` arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 * @since 5.9.0 Renamed `$item` to `$data_object` and `$id` to `$current_object_id`
	 *              to match parent class for PHP 8 named parameter support.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param WP_Post $data_object Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @param int $current_object_id Optional. ID of the current menu item. Default 0.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		// Restores the more descriptive, specific name for use within this method.
		$menu_item = $data_object;

		$item_output = '';

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ($depth) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $menu_item->classes ) ? array() : (array)$menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;

		// fix parent active class on resources page
		if ( $data_object->object_id == $this->page_for_posts && hmt_is_blog() ) {
			$classes[] = 'current-menu-parent';
		}

		// fix parent active class on locations
		if ( in_array( 'menu-item-has-children', $classes ) ) {
			foreach ( wp_get_nav_menu_items( 'main-menu' ) as $location_menu_item ) {
				if ( $location_menu_item->title == '{{LOCATIONS_SUBMENU}}' && $data_object->ID == $location_menu_item->menu_item_parent ) {

					$location_list = hmt_get_location_list();
					$is_tax = is_tax( 'hmt_location' );
					$page_term_id = get_queried_object()->term_id;

					foreach ( $location_list as $location ) {
						if ( $is_tax && $page_term_id === $location->term_id ) {
							$classes[] = 'current-menu-parent';
							break;
						}
					}

					break;
				}
			}
		}

		// fix parent active class on services
		if ( in_array( 'menu-item-has-children', $classes ) ) {
			foreach ( wp_get_nav_menu_items( 'main-menu' ) as $service_menu_item ) {
				if ( $service_menu_item->title == '{{SERVICES_SUBMENU}}' && $data_object->ID == $service_menu_item->menu_item_parent ) {
					$service_list = hmt_get_current_location_service_list();
					$is_service_page = is_singular( 'hmt_service' );
					$page_id = get_queried_object_id();

					foreach ( $service_list as $service ) {
						if ( $is_service_page && $page_id === $service->ID ) {
							$classes[] = 'current-menu-parent';
							break;
						}
					}
					break;
				}
			}
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param WP_Post $menu_item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post $menu_item The current menu item object.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post $menu_item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		if ( $this->submenu_marks_map[$data_object->title] ?? false ) {
			$output .= $indent;

			switch ( $data_object->title ) {
				case '{{SERVICES_SUBMENU}}':
					$this->service_sub_menu_template( $output );
					break;
				case '{{LOCATIONS_SUBMENU}}':
					$this->location_list( $output );
					break;
			}
		} else {
			$output .= $indent . '<li' . $id . $class_names . '>';

			$atts = array();
			$atts['title'] = !empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
			$atts['target'] = !empty( $menu_item->target ) ? $menu_item->target : '';
			if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
				$atts['rel'] = 'noopener';
			} else {
				$atts['rel'] = $menu_item->xfn;
			}
			$atts['href'] = !empty( $menu_item->url ) ? $menu_item->url : '';
			$atts['aria-current'] = $menu_item->current ? 'page' : '';

			/**
			 * Filters the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array $atts {
			 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 * @type string $title Title attribute.
			 * @type string $target Target attribute.
			 * @type string $rel The rel attribute.
			 * @type string $href The href attribute.
			 * @type string $aria-current The aria-current attribute.
			 * }
			 *
			 * @param WP_Post $menu_item The current menu item object.
			 * @param stdClass $args An object of wp_nav_menu() arguments.
			 * @param int $depth Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
					$value = ('href' === $attr) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since 4.4.0
			 *
			 * @param string $title The menu item's title.
			 * @param WP_Post $menu_item The current menu item object.
			 * @param stdClass $args An object of wp_nav_menu() arguments.
			 * @param int $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;

			if ( strpos( $class_names, 'menu-item-has-children' ) ) {
				$item_output .= ' <span class="parent-menu-item-arrow">' . hmt_get_svg_inline( get_stylesheet_directory() . '/assets/theme/img/icons/icon-chevron-right-menu.svg' ) . '</span>';
				/* TODO: Back button for mobile version? */
//            $item_output .= '<span class="parent-menu-item-button-back-wrap"><span class="button button--green button--small parent-menu-item-button-back"><span class="button__icon">' . hmt_get_svg_inline( get_stylesheet_directory() . '/assets/theme/img/icons/icon-chevron-right.svg' ) . '</span><span class="button__label"> ' . __( 'Back', THEME_TEXTDOMAIN ) . ' </span></span></span>';
			}

			$item_output .= '</a>';
			$item_output .= $args->after;
		}


		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param WP_Post $menu_item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 * @since 5.9.0 Renamed `$item` to `$data_object` to match parent class for PHP 8 named parameter support.
	 *
	 * @see Walker::end_el()
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param WP_Post $data_object Menu item data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

	/**
	 * @param string $output output string
	 */
	public function service_sub_menu_template( &$output ) {
		$service_list = hmt_get_current_location_service_list();

		$is_service_page = is_singular( 'hmt_service' );
		$page_id = get_the_ID();

		foreach ( $service_list as $service ) {
			$link = get_permalink( $service->ID );

			$active = $is_service_page && $page_id === $service->ID ? 'active' : '';

			$output .= "    <li class=\"menu-item menu-item-type-custom menu-item-object-custom {$active}\">";
			$output .= "        <a href=\"{$link}\">{$service->post_title}</a>";
			$output .= "    </li>";
		}
	}

	private function location_list( &$output ) {
		$locations = hmt_get_location_list();

		$is_tax_page = is_tax( 'hmt_location' );
		$page_id = get_queried_object()->term_id;

		foreach ( $locations as $location ) {
			$link = get_term_link( $location->term_id );
			$active = $is_tax_page && $page_id === $location->term_id ? 'active' : '';

			$output .= "    <li class=\"menu-item menu-item-type-custom menu-item-object-custom {$active}\">";
			$output .= "        <a href=\"{$link}\">{$location->name}</a>";
			$output .= "    </li>";
		}
	}
}