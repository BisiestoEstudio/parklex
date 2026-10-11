<?php
/**
 * Data helpers for the site header (template-parts/site-header.php).
 */

defined( 'ABSPATH' ) || exit;

class Bis_Theme_Header {

	const MENU_LOCATION = 'primary';
	const SHOP_LOCATION = 'shop_menu';

	public static function init() {
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
	}

	public static function body_class( $classes ) {
		if ( self::is_wholesale() ) {
			// The wholesale header is solid: the page content starts below it (see _header.scss).
			$classes[] = 'has-wholesale-header';
		}

		return $classes;
	}

	/**
	 * Wholesale users (distributor / sample_supervisor) get the shop header: account links
	 * and the product categories instead of the public menu.
	 */
	public static function is_wholesale() {
		return class_exists( 'WooCommerce' )
			&& is_user_logged_in()
			&& ( current_user_can( 'distributor' ) || current_user_can( 'sample_supervisor' ) );
	}

	/**
	 * Category links of the wholesale header: the menu assigned to the "Shop Menu" location
	 * (as in the previous theme), as one flat list in menu order whatever the depth of each
	 * item. Empty when no menu is assigned.
	 *
	 * @return array[] Each: title, url, current (bool).
	 */
	public static function get_shop_items() {
		$locations = get_nav_menu_locations();

		if ( empty( $locations[ self::SHOP_LOCATION ] ) ) {
			return array();
		}

		$menu_items = wp_get_nav_menu_items( $locations[ self::SHOP_LOCATION ] );

		if ( ! $menu_items ) {
			return array();
		}

		_wp_menu_item_classes_by_context( $menu_items );

		$items = array();

		foreach ( $menu_items as $item ) {
			$items[] = array(
				'title'   => $item->title,
				'url'     => $item->url,
				'current' => in_array( 'current-menu-item', (array) $item->classes, true ),
			);
		}

		return $items;
	}

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

	const VARIANTS = array( 'auto', 'light', 'dark' );

	/**
	 * Variant the header is actually painted with: 'light' or 'dark'.
	 *
	 * The editor can choose auto | light | dark ("Variante del menú" field). Auto resolves
	 * to light when the first block of the page content has the class has-white-color
	 * (i.e. the page opens with a dark section), and to dark otherwise.
	 */
	public static function get_variant() {
		$post_id = self::get_current_post_id();
		$variant = $post_id > 0 && function_exists( 'get_field' ) ? get_field( 'header_variant', $post_id ) : '';

		if ( 'light' === $variant || 'dark' === $variant ) {
			return $variant;
		}

		return $post_id > 0 && self::first_block_is_white( $post_id ) ? 'light' : 'dark';
	}

	/**
	 * ID of the page the header belongs to, or 0 when there is none (archives, search...).
	 */
	private static function get_current_post_id() {
		if ( is_singular() || is_home() ) {
			return (int) get_queried_object_id();
		}

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			return (int) wc_get_page_id( 'shop' );
		}

		return 0;
	}

	/**
	 * Whether the root element of the first block of the post content has the class
	 * has-white-color. The block is rendered, so it also covers blocks that add that class
	 * themselves (render.php) and not only the ones with a text colour attribute.
	 */
	private static function first_block_is_white( $post_id ) {
		$content = (string) get_post_field( 'post_content', $post_id );

		foreach ( parse_blocks( $content ) as $block ) {
			// Whitespace between blocks comes as a block without name.
			if ( empty( $block['blockName'] ) && '' === trim( $block['innerHTML'] ) ) {
				continue;
			}

			$tags = new WP_HTML_Tag_Processor( render_block( $block ) );

			return $tags->next_tag() && $tags->has_class( 'has-white-color' );
		}

		return false;
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

Bis_Theme_Header::init();
