<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_CPT_Manager {

	public static function register() {
		self::register_technical_card();
		self::register_proyecto();
		self::register_project_internal();
		self::register_products();
		self::register_lunch_learn_request();
		self::register_map_pin();

		add_filter( 'post_type_link', array( __CLASS__, 'filter_products_permalink' ), 1, 2 );
		add_action( 'pre_get_posts', array( __CLASS__, 'apply_manual_order' ) );

		foreach ( array( 'proyecto', 'products', 'technical-card' ) as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", array( __CLASS__, 'add_order_column' ) );
			add_action( "manage_{$post_type}_posts_custom_column", array( __CLASS__, 'render_order_column' ), 10, 2 );
			add_filter( "manage_edit-{$post_type}_sortable_columns", array( __CLASS__, 'make_order_column_sortable' ) );
		}
	}

	/**
	 * Projects, Acabados and Technical Cards support "page-attributes", which gives editors
	 * a native "Order" field. Apply it to any front-end/REST query of these post types that
	 * hasn't already asked for a specific orderby (e.g. 'rand' for related content, or
	 * 'post__in' to preserve a manually picked relationship field order).
	 */
	public static function apply_manual_order( $query ) {
		if ( is_admin() || $query->get( 'orderby' ) ) {
			return;
		}

		$ordered_post_types = array( 'proyecto', 'products', 'technical-card' );
		$post_type          = $query->get( 'post_type' );

		if ( is_array( $post_type ) ) {
			if ( ! array_intersect( $post_type, $ordered_post_types ) ) {
				return;
			}
		} elseif ( ! in_array( $post_type, $ordered_post_types, true ) ) {
			return;
		}

		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}

	/**
	 * Insert the "Orden" column right after Title, so editors see the menu_order value
	 * that drives the manual sort applied in apply_manual_order().
	 */
	public static function add_order_column( $columns ) {
		$position = array_search( 'title', array_keys( $columns ), true );
		$position = false === $position ? count( $columns ) : $position + 1;

		return array_merge(
			array_slice( $columns, 0, $position, true ),
			array( 'bis_order' => __( 'Orden', 'parklex-core' ) ),
			array_slice( $columns, $position, null, true )
		);
	}

	public static function render_order_column( $column, $post_id ) {
		if ( 'bis_order' === $column ) {
			echo (int) get_post_field( 'menu_order', $post_id );
		}
	}

	public static function make_order_column_sortable( $columns ) {
		$columns['bis_order'] = 'menu_order';
		return $columns;
	}

	private static function register_technical_card() {
		$labels = array(
			'name'               => _x( 'Technical Cards', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Technical Card', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Technical Cards', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Technical Card', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Technical Card', 'parklex-core' ),
			'add_new_item'       => __( 'Add Technical Card', 'parklex-core' ),
			'new_item'           => __( 'New Technical Card', 'parklex-core' ),
			'edit_item'          => __( 'Edit Technical Card', 'parklex-core' ),
			'view_item'          => __( 'View Technical Card', 'parklex-core' ),
			'all_items'          => __( 'All Technical Cards', 'parklex-core' ),
			'search_items'       => __( 'Search Technical Cards', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Technical Card:', 'parklex-core' ),
			'not_found'          => __( 'No Technical Cards found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Technical Cards found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'technical-card',
			array(
				'labels'             => $labels,
				'description'        => __( 'Technical Card', 'parklex-core' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'capability_type'    => 'page',
				'rewrite'            => array(
					'slug'       => 'technical-area',
					'with_front' => false,
				),
				'has_archive'        => true,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
				'menu_icon'          => 'dashicons-hammer',
			)
		);
	}

	private static function register_proyecto() {
		$labels = array(
			'name'               => _x( 'Projects', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Project', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Projects', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Project', 'parklex-core' ),
			'add_new_item'       => __( 'Add Project', 'parklex-core' ),
			'new_item'           => __( 'New Project', 'parklex-core' ),
			'edit_item'          => __( 'Edit Project', 'parklex-core' ),
			'view_item'          => __( 'View Project', 'parklex-core' ),
			'all_items'          => __( 'All Projects', 'parklex-core' ),
			'search_items'       => __( 'Search Projects', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Project:', 'parklex-core' ),
			'not_found'          => __( 'No Projects found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Projects found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'proyecto',
			array(
				'labels'             => $labels,
				'description'        => __( 'Projects', 'parklex-core' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'capability_type'    => 'page',
				'has_archive'        => true,
				'hierarchical'       => true,
				'rewrite'            => array(
					'slug'       => 'projects',
					'with_front' => false,
				),
				'menu_position'      => null,
				'supports'           => array( 'title', 'excerpt', 'thumbnail', 'editor', 'page-attributes' ),
				'menu_icon'          => 'dashicons-building',
			)
		);
	}

	private static function register_project_internal() {
		$labels = array(
			'name'               => _x( 'Internal Projects', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Internal Project', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Internal Projects', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Internal Project', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Internal Project', 'parklex-core' ),
			'add_new_item'       => __( 'Add Internal Project', 'parklex-core' ),
			'new_item'           => __( 'New Internal Project', 'parklex-core' ),
			'edit_item'          => __( 'Edit Internal Project', 'parklex-core' ),
			'view_item'          => __( 'View Internal Project', 'parklex-core' ),
			'all_items'          => __( 'All Internal Projects', 'parklex-core' ),
			'search_items'       => __( 'Search Internal Projects', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Internal Project:', 'parklex-core' ),
			'not_found'          => __( 'No Internal Projects found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Internal Projects found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'project_internal',
			array(
				'labels'             => $labels,
				'description'        => __( 'Internal Projects', 'parklex-core' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => false,
				'query_var'          => true,
				'capability_type'    => 'page',
				'has_archive'        => true,
				'hierarchical'       => true,
				'rewrite'            => array(
					'slug'       => 'internal-projects',
					'with_front' => false,
				),
				'menu_position'      => null,
				'supports'           => array( 'title' ),
				'menu_icon'          => 'dashicons-building',
			)
		);
	}

	private static function register_products() {
		$labels = array(
			'name'               => _x( 'Acabados', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Acabado', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Acabados', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Acabado', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Acabado', 'parklex-core' ),
			'add_new_item'       => __( 'Add Acabado', 'parklex-core' ),
			'new_item'           => __( 'New Acabado', 'parklex-core' ),
			'edit_item'          => __( 'Edit Acabado', 'parklex-core' ),
			'view_item'          => __( 'View Acabado', 'parklex-core' ),
			'all_items'          => __( 'All Acabados', 'parklex-core' ),
			'search_items'       => __( 'Search Acabados', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Acabado:', 'parklex-core' ),
			'not_found'          => __( 'No Acabados found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Acabados found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'products',
			array(
				'labels'             => $labels,
				'description'        => __( 'Products', 'parklex-core' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'capability_type'    => 'page',
				'rewrite'            => array(
					'slug'       => 'products/%products_type%',
					'with_front' => false,
				),
				'has_archive'        => 'products',
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
				'menu_icon'          => 'dashicons-cart',
			)
		);
	}

	/**
	 * Not public: requests are only ever listed/edited from wp-admin and from the
	 * front-end "Lunch & Learn" areas (which query directly), never browsed as an archive.
	 * Keeps the original theme's custom capability_type ("llrequest"/"llrequests"), granted
	 * to "lunch_learn_editor"/"editor"/"administrator" by Bis_Core_Lunch_Learn_Legacy.
	 */
	private static function register_lunch_learn_request() {
		$labels = array(
			'name'               => _x( 'Lunch & Learn requests', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Lunch & Learn request', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Lunch & Learn requests', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Lunch & Learn request', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Lunch & Learn request', 'parklex-core' ),
			'add_new_item'       => __( 'Add Lunch & Learn request', 'parklex-core' ),
			'new_item'           => __( 'New Lunch & Learn request', 'parklex-core' ),
			'edit_item'          => __( 'Edit Lunch & Learn request', 'parklex-core' ),
			'view_item'          => __( 'View Lunch & Learn request', 'parklex-core' ),
			'all_items'          => __( 'All Lunch & Learn requests', 'parklex-core' ),
			'search_items'       => __( 'Search Lunch & Learn requests', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Lunch & Learn request:', 'parklex-core' ),
			'not_found'          => __( 'No Lunch & Learn requests found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Lunch & Learn requests found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'lunch_learn_request',
			array(
				'labels'             => $labels,
				'description'        => __( 'Lunch & Learn requests', 'parklex-core' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => false,
				'query_var'          => true,
				'capability_type'    => array( 'llrequest', 'llrequests' ),
				'has_archive'        => false,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title' ),
				'menu_icon'          => 'dashicons-coffee',
			)
		);
	}

	/**
	 * No archive, no single: pins are only ever managed from wp-admin and consumed
	 * server-side (e.g. via WP_Query/REST) by the interactive map block.
	 */
	private static function register_map_pin() {
		$labels = array(
			'name'               => _x( 'Map Pins', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Map Pin', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Map Pins', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Map Pin', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Map Pin', 'parklex-core' ),
			'add_new_item'       => __( 'Add Map Pin', 'parklex-core' ),
			'new_item'           => __( 'New Map Pin', 'parklex-core' ),
			'edit_item'          => __( 'Edit Map Pin', 'parklex-core' ),
			'view_item'          => __( 'View Map Pin', 'parklex-core' ),
			'all_items'          => __( 'All Map Pins', 'parklex-core' ),
			'search_items'       => __( 'Search Map Pins', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Map Pin:', 'parklex-core' ),
			'not_found'          => __( 'No Map Pins found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Map Pins found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'map-pin',
			array(
				'labels'             => $labels,
				'description'        => __( 'Map Pins', 'parklex-core' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => false,
				'rewrite'            => false,
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title' ),
				'menu_icon'          => 'dashicons-location-alt',
			)
		);
	}

	/**
	 * The "products" rewrite slug uses a %products_type% placeholder that WordPress
	 * doesn't resolve natively; replace it with the post's products_type term slug.
	 */
	public static function filter_products_permalink( $post_link, $post ) {
		if ( is_object( $post ) && 'products' === $post->post_type && false !== strpos( $post_link, '%products_type%' ) ) {
			$terms = wp_get_object_terms( $post->ID, 'products_type' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				return str_replace( '%products_type%', $terms[0]->slug, $post_link );
			}
		}
		return $post_link;
	}

}
