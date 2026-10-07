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
	 * Forces 12 posts per page on the Products archive and the products_type
	 * taxonomy archive (same template/layout for both).
	 */
	public static function filter_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( ! $query->is_post_type_archive( 'products' ) && ! $query->is_tax( 'products_type' ) ) {
			return;
		}

		$query->set( 'posts_per_page', 12 );
	}
}

Bis_Theme_Products_Archive::init();
