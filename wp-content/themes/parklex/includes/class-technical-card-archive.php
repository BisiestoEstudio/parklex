<?php
defined( 'ABSPATH' ) || exit;

/**
 * Query filtering for the Technical Card archive.
 *
 * The category_technical_card / classification_technical_card taxonomies are registered
 * with publicly_queryable => false (no term archives, no sitemap), so WordPress doesn't
 * parse them from the URL anymore: the ?category_technical_card= / ?classification_technical_card=
 * filters used by archive-technical-card.php are applied here as an explicit tax_query.
 */
class Bis_Theme_Technical_Card_Archive {

	public static function init() {
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_archive_query' ) );
	}

	public static function filter_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'technical-card' ) ) {
			return;
		}

		$tax_query = self::get_tax_query( self::get_active_term( 'category_technical_card' ), self::get_active_term( 'classification_technical_card' ) );

		if ( $tax_query ) {
			$query->set( 'tax_query', $tax_query );
		}
	}

	/**
	 * Term slug of the given taxonomy filter from the URL, or false if it isn't set.
	 */
	public static function get_active_term( $taxonomy ) {
		return isset( $_GET[ $taxonomy ] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public archive filter.
			? sanitize_title( wp_unslash( $_GET[ $taxonomy ] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			: false;
	}

	/**
	 * tax_query for the given category/classification slugs (either may be false).
	 */
	public static function get_tax_query( $category, $classification ) {
		$tax_query = array();

		if ( $category ) {
			$tax_query[] = array(
				'taxonomy' => 'category_technical_card',
				'field'    => 'slug',
				'terms'    => $category,
			);
		}

		if ( $classification ) {
			$tax_query[] = array(
				'taxonomy' => 'classification_technical_card',
				'field'    => 'slug',
				'terms'    => $classification,
			);
		}

		return $tax_query;
	}
}

Bis_Theme_Technical_Card_Archive::init();
