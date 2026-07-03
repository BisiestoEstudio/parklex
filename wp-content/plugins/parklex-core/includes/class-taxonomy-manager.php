<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_Taxonomy_Manager {

	public static function register() {
		self::register_technical_zone_taxonomies();
		self::register_proyecto_taxonomies();
		self::register_products_taxonomies();
		self::register_project_internal_taxonomies();
		self::register_request_taxonomies();
		self::register_distributor_taxonomies();
	}

	private static function register_technical_zone_taxonomies() {
		register_taxonomy(
			'category_technical_zone',
			'technical-zone',
			array(
				'label'             => __( 'Technical Area Category', 'parklex-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => 'technical-area/category',
					'with_front' => false,
				),
			)
		);
	}

	private static function register_proyecto_taxonomies() {
		register_taxonomy(
			'project_author',
			'proyecto',
			array(
				'label'             => __( 'Project Author', 'parklex-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => 'project-author',
					'with_front' => false,
				),
			)
		);

		register_taxonomy(
			'type_work',
			'proyecto',
			array(
				'label'             => __( 'Project Type', 'parklex-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => 'type-work',
					'with_front' => false,
				),
			)
		);

		register_taxonomy(
			'app',
			'proyecto',
			array(
				'label'             => __( 'Architectural Solution', 'parklex-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => 'app',
					'with_front' => false,
				),
			)
		);
	}

	private static function register_products_taxonomies() {
		register_taxonomy(
			'products_type',
			'products',
			array(
				'label'             => __( 'Product Type', 'parklex-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => 'products',
					'with_front' => false,
				),
			)
		);

		register_taxonomy(
			'wood_species',
			'products',
			array(
				'label'             => __( 'Wood Species', 'parklex-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => 'wood-species',
					'with_front' => false,
				),
			)
		);
	}

	/**
	 * Internal-facing taxonomies for "project_internal" — kept non-public/non-REST
	 * to match the source theme, which never exposed these outside wp-admin.
	 */
	private static function register_project_internal_taxonomies() {
		$taxonomies = array(
			'product_internal'        => __( 'Finish', 'parklex-core' ),
			'product_name_internal'   => __( 'Product Name', 'parklex-core' ),
			'product_type_internal'   => __( 'Product Type', 'parklex-core' ),
			'work_type_internal'      => __( 'Type of Work', 'parklex-core' ),
			'building_type_internal'  => __( 'Building Type', 'parklex-core' ),
			'application_internal'    => __( 'Application', 'parklex-core' ),
			'country_internal'        => __( 'Country', 'parklex-core' ),
			'installation_internal'   => __( 'Installation System', 'parklex-core' ),
			'surface_internal'        => __( 'Interior Surface', 'parklex-core' ),
			'sustainability_internal' => __( 'Sustainability', 'parklex-core' ),
			'status_internal'         => __( 'Status', 'parklex-core' ),
		);

		foreach ( $taxonomies as $taxonomy => $label ) {
			register_taxonomy(
				$taxonomy,
				'project_internal',
				array(
					'label'             => $label,
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'show_in_rest'      => false,
					'public'            => false,
				)
			);
		}
	}

	private static function register_request_taxonomies() {
		$taxonomies = array(
			'request_zone'         => array(
				'name'          => __( 'Areas', 'parklex-core' ),
				'singular_name' => __( 'Area', 'parklex-core' ),
			),
			'request_subject'      => array(
				'name'          => __( 'Subjects', 'parklex-core' ),
				'singular_name' => __( 'Subject', 'parklex-core' ),
			),
			'request_profesion'    => array(
				'name'          => __( 'Professions', 'parklex-core' ),
				'singular_name' => __( 'Profession', 'parklex-core' ),
			),
			'request_know'         => array(
				'name'          => __( 'How Did You Find Us?', 'parklex-core' ),
				'singular_name' => __( 'How Did You Find Us?', 'parklex-core' ),
			),
			'request_project_size' => array(
				'name'          => __( 'Project Sizes', 'parklex-core' ),
				'singular_name' => __( 'Project Size', 'parklex-core' ),
			),
			'request_language'     => array(
				'name'          => __( 'Languages', 'parklex-core' ),
				'singular_name' => __( 'Language', 'parklex-core' ),
			),
		);

		foreach ( $taxonomies as $taxonomy => $names ) {
			register_taxonomy(
				$taxonomy,
				'request',
				array(
					'label'             => $names['name'],
					'labels'            => array(
						'name'          => $names['name'],
						'singular_name' => $names['singular_name'],
					),
					'public'            => true,
					'show_in_nav_menus' => true,
					'hierarchical'      => true,
					'query_var'         => true,
					'show_in_rest'      => false,
				)
			);
		}
	}

	private static function register_distributor_taxonomies() {
		register_taxonomy(
			'distributor_interno',
			'distributor',
			array(
				'label'             => __( 'Internal', 'parklex-core' ),
				'public'            => true,
				'show_in_nav_menus' => true,
				'hierarchical'      => true,
				'query_var'         => true,
				'show_in_rest'      => true,
			)
		);
	}

}
