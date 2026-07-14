<?php
defined( 'ABSPATH' ) || exit;

/**
 * Auto-selects related "proyecto" posts for the "bisiesto/projects" block, and
 * exposes the same logic over REST so the block editor can preview it (the
 * "products" branch relies on an ACF field that isn't exposed to core-data).
 */
class Bis_Blocks_Related_Projects {

	const REST_NAMESPACE = 'parklex-blocks/v1';

	function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	function register_routes() {
		register_rest_route(
			self::REST_NAMESPACE,
			'/related-projects',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'handle_request' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => array(
					'post_id' => array(
						'type'     => 'integer',
						'required' => true,
					),
				),
			)
		);
	}

	function handle_request( $request ) {
		$post_id  = (int) $request->get_param( 'post_id' );
		$projects = array_map( array( $this, 'format_project' ), self::get_related_project_ids( $post_id ) );

		return rest_ensure_response( $projects );
	}

	private function format_project( $project_id ) {
		$author_terms = get_the_terms( $project_id, 'project_author' );

		return array(
			'id'     => $project_id,
			'title'  => get_the_title( $project_id ),
			'image'  => get_the_post_thumbnail_url( $project_id, 'medium' ) ?: '',
			'author' => ( ! empty( $author_terms ) && ! is_wp_error( $author_terms ) ) ? $author_terms[0]->name : '',
		);
	}

	/**
	 * Up to 2 related "proyecto" IDs for $reference_id, based on its post type:
	 * - proyecto: other projects sharing an "Architectural Solution" (app) term
	 * - products: projects whose "Related Product" field lists this product
	 * Any other post type returns no results.
	 */
	public static function get_related_project_ids( $reference_id ) {
		switch ( get_post_type( $reference_id ) ) {
			case 'proyecto':
				return self::get_projects_by_shared_solution( $reference_id );
			case 'products':
				return self::get_projects_by_related_product( $reference_id );
			default:
				return array();
		}
	}

	private static function get_projects_by_shared_solution( $project_id ) {
		$terms = wp_get_post_terms( $project_id, 'app', array( 'fields' => 'ids' ) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array();
		}

		$query = new WP_Query( array(
			'post_type'      => 'proyecto',
			'post_status'    => 'publish',
			'post__not_in'   => array( $project_id ),
			'posts_per_page' => 2,
			'orderby'        => 'rand',
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy' => 'app',
					'field'    => 'term_id',
					'terms'    => $terms,
				),
			),
		) );

		return $query->posts;
	}

	/**
	 * Matching on the raw "related_product" postmeta (via meta_query) is
	 * unreliable: we can't be sure of its exact storage key or serialization
	 * format. get_field() is the one API guaranteed to resolve it correctly
	 * regardless of storage details, so every published project is checked
	 * through it directly rather than pre-filtered with SQL.
	 */
	private static function get_projects_by_related_product( $product_id ) {
		$candidate_ids = get_posts( array(
			'post_type'      => 'proyecto',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		) );

		$matches = array();

		foreach ( $candidate_ids as $candidate_id ) {
			$related = get_field( 'related_product', $candidate_id );

			if ( empty( $related ) ) {
				continue;
			}

			$related_ids = array();

			foreach ( (array) $related as $item ) {
				if ( is_object( $item ) && isset( $item->ID ) ) {
					$related_ids[] = (int) $item->ID;
				} elseif ( is_array( $item ) && isset( $item['ID'] ) ) {
					$related_ids[] = (int) $item['ID'];
				} elseif ( is_numeric( $item ) ) {
					$related_ids[] = (int) $item;
				}
			}

			if ( in_array( (int) $product_id, $related_ids, true ) ) {
				$matches[] = $candidate_id;
			}
		}

		if ( empty( $matches ) ) {
			return array();
		}

		shuffle( $matches );

		return array_slice( $matches, 0, 2 );
	}
}

new Bis_Blocks_Related_Projects();
