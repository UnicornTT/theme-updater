<?php

namespace Harbinger_Marketing;

class ACF_Helper {
	private static $the_blocks = null;

	/**
	 * Return block data for global $post
	 * @param $id
	 *
	 * @return array|mixed|null
	 */
	public static function get_the_block_by_id( $id ) {
		$block_list = self::get_the_blocks();
		$id_list = array_column( array_column( $block_list, 'attrs' ), 'id' );
		$index = array_search( $id, $id_list );
		return false !== $index ? $block_list[$index] : null;
	}

	/**
	 * Return block data for global $post
	 * @param string $block_name
	 * @param string $prefix 'acf/' by default
	 *
	 * @return array
	 */
	public static function get_the_block_list_by_block_name( string $block_name, string $prefix = 'acf/' ) {
		$block_list = self::get_the_blocks();
		$filtered_block_list = [];

		$block_name = $prefix . $block_name;

		foreach ( $block_list as $block ) {
			if ( $block['blockName'] === $block_name ) {
				$filtered_block_list[] = $block;
			}
		}

		return $filtered_block_list;
	}

	/**
	 * Return block list for global $post
	 * @return array[]|null
	 */
	public static function get_the_blocks() {
		if ( self::$the_blocks ) {
			return self::$the_blocks;
		}

		return self::$the_blocks = self::parse_the_blocks();
	}

	private static function parse_the_blocks() {
		return parse_blocks( get_the_content() );
	}
}