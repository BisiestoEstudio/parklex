<?php
defined( 'ABSPATH' ) || exit;

/**
 * Query filtering for the Products archive.
 */
class Bis_Theme_Products_Archive {

	public static function init() {
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_archive_query' ) );
	}

	/**
	 * Forces 12 posts per page and the manual "Order" sort on the Products archive
	 * and the products_type taxonomy archive (same template/layout for both).
	 */
	public static function filter_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( ! $query->is_post_type_archive( 'products' ) && ! $query->is_tax( 'products_type' ) ) {
			return;
		}

		$query->set( 'posts_per_page', 12 );

		// The products_type archive query has no post_type set, so parklex-core's
		// Bis_Core_CPT_Manager::apply_manual_order() skips it — apply the same order here.
		if ( ! $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'menu_order title' );
			$query->set( 'order', 'ASC' );
		}
	}
}

Bis_Theme_Products_Archive::init();
