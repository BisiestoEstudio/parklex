<?php
defined( 'ABSPATH' ) || exit;

/**
 * Lightweight, unpaginated REST endpoint for the interactive map block.
 *
 * The default wp/v2 REST API caps posts_per_page at 100, which doesn't work for a
 * cluster map that needs all 1500-5000 "map-pin" posts in a single request. Response
 * is cached (map-pin posts aren't edited often) and flushed whenever one is saved.
 */
class Bis_Core_Map_Pins_REST {

	const CACHE_KEY = 'parklex_map_pins';

	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
		add_action( 'save_post_map-pin', array( __CLASS__, 'flush_cache' ) );
		add_action( 'deleted_post', array( __CLASS__, 'flush_cache' ) );
	}

	public static function register_routes() {
		register_rest_route( 'parklex/v1', '/map-pins', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'get_pins' ),
			'permission_callback' => '__return_true',
		) );
	}

	public static function get_pins() {
		$pins = get_transient( self::CACHE_KEY );

		if ( false !== $pins ) {
			return rest_ensure_response( $pins );
		}

		$query = new WP_Query( array(
			'post_type'              => 'map-pin',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		) );

		$pins = array_map( array( __CLASS__, 'format_pin' ), $query->posts );

		set_transient( self::CACHE_KEY, $pins, DAY_IN_SECONDS );

		return rest_ensure_response( $pins );
	}

	private static function format_pin( $post ) {
		$location = function_exists( 'get_field' ) ? get_field( 'location', $post->ID ) : null;
		$info     = function_exists( 'get_field' ) ? get_field( 'info', $post->ID ) : null;

		return array(
			'id'        => $post->ID,
			'title'     => get_the_title( $post ),
			'lat'       => isset( $location['lat'] ) ? (float) $location['lat'] : null,
			'lng'       => isset( $location['lng'] ) ? (float) $location['lng'] : null,
			'city'      => $info['city'] ?? '',
			'architect' => $info['architect'] ?? '',
			'country'   => self::get_term_names( $post->ID, 'country_map_pin' ),
			'year'      => self::get_term_names( $post->ID, 'year_map_pin' ),
			'product'   => self::get_term_names( $post->ID, 'product_type_map_pin' ),
			'finish'    => self::get_term_names( $post->ID, 'product_map_pin' ),
		);
	}

	private static function get_term_names( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array();
		}

		return wp_list_pluck( $terms, 'name' );
	}

	public static function flush_cache() {
		delete_transient( self::CACHE_KEY );
	}
}
