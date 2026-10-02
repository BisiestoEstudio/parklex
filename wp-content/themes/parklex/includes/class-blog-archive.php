<?php
defined( 'ABSPATH' ) || exit;

/**
 * Query filtering for the blog archive (home) and category archives.
 */
class Bis_Theme_Blog_Archive {

	public static function init() {
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_archive_query' ) );
	}

	/**
	 * Forces 9 posts per page and applies the GET "search" param to the main
	 * query on the blog index and category archives only.
	 */
	public static function filter_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! ( $query->is_home() || $query->is_category() ) ) {
			return;
		}

		$query->set( 'posts_per_page', 9 );

		if ( ! empty( $_GET['search'] ) ) {
			$query->set( 's', sanitize_text_field( wp_unslash( $_GET['search'] ) ) );
		}
	}
}

Bis_Theme_Blog_Archive::init();
