<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_CPT_Manager {

	public static function register() {
		self::register_technical_card();
		self::register_proyecto();
		self::register_project_internal();
		self::register_products();

		add_filter( 'post_type_link', array( __CLASS__, 'filter_products_permalink' ), 1, 2 );
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
				'supports'           => array( 'title', 'thumbnail' ),
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
				'supports'           => array( 'title', 'excerpt', 'thumbnail', 'editor' ),
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
			'name'               => _x( 'Products', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Product', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Products', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Product', 'parklex-core' ),
			'add_new_item'       => __( 'Add Product', 'parklex-core' ),
			'new_item'           => __( 'New Product', 'parklex-core' ),
			'edit_item'          => __( 'Edit Product', 'parklex-core' ),
			'view_item'          => __( 'View Product', 'parklex-core' ),
			'all_items'          => __( 'All Products', 'parklex-core' ),
			'search_items'       => __( 'Search Products', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Product:', 'parklex-core' ),
			'not_found'          => __( 'No Products found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Products found in Trash.', 'parklex-core' ),
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
