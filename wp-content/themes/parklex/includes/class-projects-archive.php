<?php
defined( 'ABSPATH' ) || exit;

/**
 * Query filtering for the Projects archive: pagination, taxonomy filters and search.
 */
class Bis_Theme_Projects_Archive {

	/**
	 * GET param => filter definition. "product" is not a taxonomy of the CPT:
	 * it lives in the ACF field project_info > product (products_type term IDs).
	 */
	public static function get_filters() {
		return array(
			'filter_product'     => array(
				'taxonomy' => 'products_type',
				'label'    => __( 'Tipo de producto', 'parklex' ),
			),
			'filter_application' => array(
				'taxonomy' => 'app',
				'label'    => __( 'Aplicación', 'parklex' ),
			),
			'filter_work_type'   => array(
				'taxonomy' => 'type_work',
				'label'    => __( 'Tipo de obra', 'parklex' ),
			),
		);
	}

	public static function init() {
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_archive_query' ) );
	}

	public static function filter_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'proyecto' ) ) {
			return;
		}

		$query->set( 'posts_per_page', 12 );

		if ( ! empty( $_GET['search'] ) ) {
			$query->set( 's', sanitize_text_field( wp_unslash( $_GET['search'] ) ) );
		}

		$tax_query = array();

		foreach ( self::get_filters() as $param => $filter ) {
			$term_id = isset( $_GET[ $param ] ) ? absint( $_GET[ $param ] ) : 0;

			if ( ! $term_id ) {
				continue;
			}

			if ( 'filter_product' === $param ) {
				// ACF stores the selected term IDs serialized (or as a plain ID).
				$query->set( 'meta_query', array(
					array(
						'key'     => 'project_info_product',
						'value'   => '(^|[:"])' . $term_id . '([;"]|$)',
						'compare' => 'REGEXP',
					),
				) );
				continue;
			}

			$tax_query[] = array(
				'taxonomy' => $filter['taxonomy'],
				'field'    => 'term_id',
				'terms'    => $term_id,
			);
		}

		if ( $tax_query ) {
			$query->set( 'tax_query', $tax_query );
		}
	}
}

Bis_Theme_Projects_Archive::init();
