<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_CPT_Manager {

	public static function register() {
		self::register_technical_zone();
		self::register_proyecto();
		self::register_case_studies();
		self::register_project_internal();
		self::register_products();
		self::register_distributor();
		self::register_request();
		self::register_reviews();

		add_filter( 'post_type_link', array( __CLASS__, 'filter_products_permalink' ), 1, 2 );
	}

	private static function register_technical_zone() {
		$labels = array(
			'name'               => _x( 'Technical Areas', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Technical Area', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Technical Areas', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Technical Area', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Technical Area', 'parklex-core' ),
			'add_new_item'       => __( 'Add Technical Area', 'parklex-core' ),
			'new_item'           => __( 'New Technical Area', 'parklex-core' ),
			'edit_item'          => __( 'Edit Technical Area', 'parklex-core' ),
			'view_item'          => __( 'View Technical Area', 'parklex-core' ),
			'all_items'          => __( 'All Technical Areas', 'parklex-core' ),
			'search_items'       => __( 'Search Technical Areas', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Technical Area:', 'parklex-core' ),
			'not_found'          => __( 'No Technical Areas found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Technical Areas found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'technical-zone',
			array(
				'labels'             => $labels,
				'description'        => __( 'Technical Area', 'parklex-core' ),
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

	private static function register_case_studies() {
		$labels = array(
			'name'               => _x( 'Case Studies', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Case Study', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Case Studies', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Case Study', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Case Study', 'parklex-core' ),
			'add_new_item'       => __( 'Add Case Study', 'parklex-core' ),
			'new_item'           => __( 'New Case Study', 'parklex-core' ),
			'edit_item'          => __( 'Edit Case Study', 'parklex-core' ),
			'view_item'          => __( 'View Case Study', 'parklex-core' ),
			'all_items'          => __( 'All Case Studies', 'parklex-core' ),
			'search_items'       => __( 'Search Case Studies', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Case Study:', 'parklex-core' ),
			'not_found'          => __( 'No Case Studies found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Case Studies found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'case-studies',
			array(
				'labels'             => $labels,
				'description'        => __( 'Case Studies', 'parklex-core' ),
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
					'slug'       => 'case-studies',
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
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'          => 'dashicons-cart',
			)
		);
	}

	private static function register_distributor() {
		$labels = array(
			'name'               => _x( 'Distributors', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Distributor', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Distributors', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Distributor', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Distributor', 'parklex-core' ),
			'add_new_item'       => __( 'Add Distributor', 'parklex-core' ),
			'new_item'           => __( 'New Distributor', 'parklex-core' ),
			'edit_item'          => __( 'Edit Distributor', 'parklex-core' ),
			'view_item'          => __( 'View Distributor', 'parklex-core' ),
			'all_items'          => __( 'All Distributors', 'parklex-core' ),
			'search_items'       => __( 'Search Distributors', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Distributor:', 'parklex-core' ),
			'not_found'          => __( 'No Distributors found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Distributors found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'distributor',
			array(
				'labels'             => $labels,
				'description'        => __( 'Distributors', 'parklex-core' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'capability_type'    => 'page',
				'has_archive'        => true,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title', 'thumbnail' ),
				'menu_icon'          => 'dashicons-businessman',
			)
		);
	}

	private static function register_request() {
		$labels = array(
			'name'               => _x( 'Requests', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Request', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Requests', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Request', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Request', 'parklex-core' ),
			'add_new_item'       => __( 'Add Request', 'parklex-core' ),
			'new_item'           => __( 'New Request', 'parklex-core' ),
			'edit_item'          => __( 'Edit Request', 'parklex-core' ),
			'view_item'          => __( 'View Request', 'parklex-core' ),
			'all_items'          => __( 'All Requests', 'parklex-core' ),
			'search_items'       => __( 'Search Requests', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Request:', 'parklex-core' ),
			'not_found'          => __( 'No Requests found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Requests found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'request',
			array(
				'labels'             => $labels,
				'description'        => __( 'Requests', 'parklex-core' ),
				'public'             => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'publicly_queryable' => false,
				'show_in_rest'       => false,
				'query_var'          => true,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title', 'thumbnail' ),
			)
		);
	}

	private static function register_reviews() {
		$labels = array(
			'name'               => _x( 'Reviews', 'post type general name', 'parklex-core' ),
			'singular_name'      => _x( 'Review', 'post type singular name', 'parklex-core' ),
			'menu_name'          => _x( 'Reviews', 'admin menu', 'parklex-core' ),
			'name_admin_bar'     => _x( 'Review', 'add new on admin bar', 'parklex-core' ),
			'add_new'            => _x( 'Add New', 'Review', 'parklex-core' ),
			'add_new_item'       => __( 'Add Review', 'parklex-core' ),
			'new_item'           => __( 'New Review', 'parklex-core' ),
			'edit_item'          => __( 'Edit Review', 'parklex-core' ),
			'view_item'          => __( 'View Review', 'parklex-core' ),
			'all_items'          => __( 'All Reviews', 'parklex-core' ),
			'search_items'       => __( 'Search Reviews', 'parklex-core' ),
			'parent_item_colon'  => __( 'Parent Review:', 'parklex-core' ),
			'not_found'          => __( 'No Reviews found.', 'parklex-core' ),
			'not_found_in_trash' => __( 'No Reviews found in Trash.', 'parklex-core' ),
		);

		register_post_type(
			'reviews',
			array(
				'labels'             => $labels,
				'description'        => __( 'Reviews', 'parklex-core' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => false,
				'query_var'          => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title' ),
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
