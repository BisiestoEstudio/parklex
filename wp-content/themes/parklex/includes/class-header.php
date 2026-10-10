<?php
/**
 * Data helpers for the site header (template-parts/site-header.php).
 */

defined( 'ABSPATH' ) || exit;

class Bis_Theme_Header {

	const MENU_LOCATION = 'primary';

	/**
	 * Top-level items of the "Menú principal" location, each with its direct children
	 * under ->children. Only two levels: deeper items are ignored.
	 *
	 * @return WP_Post[]
	 */
	public static function get_menu_tree() {
		$locations = get_nav_menu_locations();

		if ( empty( $locations[ self::MENU_LOCATION ] ) ) {
			return array();
		}

		$items = wp_get_nav_menu_items( $locations[ self::MENU_LOCATION ] );

		if ( empty( $items ) ) {
			return array();
		}

		// Adds current-menu-item / current-menu-ancestor classes, same as wp_nav_menu().
		_wp_menu_item_classes_by_context( $items );

		$tree = array();

		foreach ( $items as $item ) {
			if ( ! $item->menu_item_parent ) {
				$item->children     = array();
				$tree[ $item->ID ] = $item;
			}
		}

		foreach ( $items as $item ) {
			if ( isset( $tree[ $item->menu_item_parent ] ) ) {
				$tree[ $item->menu_item_parent ]->children[] = $item;
			}
		}

		return array_values( $tree );
	}

	/**
	 * Whether a menu item is the current page or one of its ancestors.
	 */
	public static function is_current( $item ) {
		return (bool) array_intersect(
			array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent' ),
			(array) $item->classes
		);
	}

	/**
	 * Link field "Botón de menú superior" from Theme Options.
	 *
	 * @return array|null ACF link array (url, title, target).
	 */
	public static function get_button() {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		$button = get_field( 'header_button', 'option' );

		return ! empty( $button['url'] ) ? $button : null;
	}
}
