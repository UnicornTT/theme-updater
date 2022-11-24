<?php

namespace Harbinger_Marketing;

class Modal_Action {
	private static $s__action_description_map = [];
	private static $s__action_renderer_map = [];
	private static $s_should_be_rendered_map = [];
	private static $s_permanently_removed_action_map = [];


	/**
	 * Register new modal
	 * @param string $action_name modal(action) name (element ID)
	 * @param string|null $action_description Action(modal) description. If it was added, it well be returned in description map (for select in admin menu)
	 * @param callable $action_modal_renderer Form renderer function
	 */
	public static function register_modal_action( string $action_name, ?string $action_description, callable $action_modal_renderer ) {
		if ( !empty( self::$s_permanently_removed_action_map[$action_name] ) ) {
			return;
		}

		if ( $action_description ) {
			self::$s__action_description_map[$action_name] = $action_description;
		}

		self::$s__action_renderer_map[$action_name] = $action_modal_renderer;
	}


	/**
	 * Remove the modal
	 * @param string $action_name
	 */
	public static function remove_modal_action( string $action_name ) {
		unset( self::$s__action_description_map[$action_name] );
		unset( self::$s__action_renderer_map[$action_name] );
		unset( self::$s_should_be_rendered_map[$action_name] );
	}


	/**
	 * Remove the modal and prohibit adding the new modal with the target name
	 * @param string $action_name
	 */
	public static function remove_modal_action_permanently( string $action_name ) {
		self::remove_modal_action( $action_name );
		self::$s_permanently_removed_action_map[$action_name] = true;
	}


	/**
	 * Add Modal to render list
	 * @param string $action_name Action name should be registered!
	 */
	public static function add_modal_action_to_render_list( string $action_name ) {
		if ( !isset( self::$s__action_renderer_map[$action_name] ) ) {
			return;
		}
		self::$s_should_be_rendered_map[$action_name] = true;
	}


	/**
	 * @return array Map of registered actions only with descriptions
	 */
	public static function get_modal_action_description_map() {
		return self::$s__action_description_map;
	}


	/**
	 * @return array All actions(key) with renderers(value)
	 */
	public static function get_modal_action_renderer_map() {
		return self::$s__action_renderer_map;
	}


	/**
	 * Render all modals from rendering list
	 */
	public static function render_modals() {
		foreach ( self::$s_should_be_rendered_map as $key => $value ) {
			(self::$s__action_renderer_map[$key])();
		}
	}
}