<?php
/**
 * ACF field group for the Internal Projects CPT.
 */
defined( 'ABSPATH' ) || exit;

$cpt       = 'project_internal';
$title     = __( 'Internal Project Fields', 'parklex-core' );
$group_key = 'bisiesto_cpt_project_internal';

$project_name_field = array(
	'key'      => "{$group_key}_project_name",
	'label'    => __( 'Project name', 'parklex-core' ),
	'name'     => 'project_name',
	'type'     => 'text',
	'required' => 1,
);

/**
 * The gallery is driven by a custom drag&drop uploader (see class-internal-projects.php
 * and assets/js/internal-project-form.js), same mechanism as the original theme:
 * - `images_ids` (hidden) collects the uploaded attachment IDs, comma-separated, from JS.
 * - `custom_gallery` (message) is the container the uploader UI renders into.
 * - `image_gallery` (repeater) and `featured_image` are populated server-side, on save,
 *   from `images_ids` (see Bis_Core_Internal_Projects::sync_gallery_from_images_ids()) —
 *   not edited directly. Both must stay hidden on the front-end form via CSS.
 */
$images_ids_field = array(
	'key'      => "{$group_key}_images_ids",
	'label'    => __( 'Images', 'parklex-core' ),
	'name'     => 'images_ids',
	'type'     => 'text',
	'required' => 1,
	'wrapper'  => array( 'class' => 'bis-internal-project-images-ids' ),
);

$custom_gallery_field = array(
	'key'     => "{$group_key}_custom_gallery",
	'label'   => '',
	'name'    => 'custom_gallery',
	'type'    => 'message',
	'message' => '',
	'wrapper' => array( 'id' => 'bis-internal-project-custom-gallery' ),
);

$image_gallery_field = array(
	'key'        => "{$group_key}_image_gallery",
	'label'      => __( 'Image gallery', 'parklex-core' ),
	'name'       => 'image_gallery',
	'type'       => 'repeater',
	'layout'     => 'table',
	'wrapper'    => array( 'class' => 'bis-internal-project-hidden-field' ),
	'sub_fields' => array(
		array(
			'key'           => "{$group_key}_image_gallery_image",
			'label'         => __( 'Image', 'parklex-core' ),
			'name'          => 'image',
			'type'          => 'image',
			'required'      => 1,
			'return_format' => 'array',
		),
	),
);

$featured_image_field = array(
	'key'           => "{$group_key}_featured_image",
	'label'         => __( 'Featured image', 'parklex-core' ),
	'name'          => 'featured_image',
	'type'          => 'image',
	'return_format' => 'array',
	'wrapper'       => array( 'class' => 'bis-internal-project-hidden-field' ),
);

$hide_download_link_field = array(
	'key'   => "{$group_key}_hide_download_link",
	'label' => __( 'Hide download link', 'parklex-core' ),
	'name'  => 'hide_download_link',
	'type'  => 'true_false',
);

$year_field = array(
	'key'   => "{$group_key}_year",
	'label' => __( 'Year', 'parklex-core' ),
	'name'  => 'year',
	'type'  => 'number',
);

$architect_field = array(
	'key'   => "{$group_key}_architect",
	'label' => __( 'Architect', 'parklex-core' ),
	'name'  => 'architect',
	'type'  => 'text',
);

$studio_field = array(
	'key'   => "{$group_key}_studio",
	'label' => __( 'Studio', 'parklex-core' ),
	'name'  => 'studio',
	'type'  => 'text',
);

$city_field = array(
	'key'      => "{$group_key}_city",
	'label'    => __( 'City', 'parklex-core' ),
	'name'     => 'city',
	'type'     => 'text',
	'required' => 1,
);

/**
 * Taxonomy fields: [ ACF field name => [ taxonomy slug, label, field_type, required ] ].
 * `country` uses `select` (single term); the rest use `multi_select`.
 */
$taxonomy_fields_map = array(
	'country'             => array( 'country_internal', __( 'Country', 'parklex-core' ), 'select', 1 ),
	'product_type'        => array( 'product_type_internal', __( 'Product type', 'parklex-core' ), 'multi_select', 1 ),
	'product_name'        => array( 'product_name_internal', __( 'Product name', 'parklex-core' ), 'multi_select', 1 ),
	'product'             => array( 'product_internal', __( 'Finish', 'parklex-core' ), 'multi_select', 1 ),
	'application'         => array( 'application_internal', __( 'Application', 'parklex-core' ), 'multi_select', 1 ),
	'type_of_work'        => array( 'work_type_internal', __( 'Type of work', 'parklex-core' ), 'multi_select', 1 ),
	'building_type'       => array( 'building_type_internal', __( 'Type of building', 'parklex-core' ), 'multi_select', 1 ),
	'installation_system' => array( 'installation_internal', __( 'Installation system', 'parklex-core' ), 'multi_select', 1 ),
	'interior_surface'    => array( 'surface_internal', __( 'Interior surface', 'parklex-core' ), 'multi_select', 0 ),
	'sustainability'      => array( 'sustainability_internal', __( 'Sustainability', 'parklex-core' ), 'multi_select', 1 ),
	'status'              => array( 'status_internal', __( 'Status', 'parklex-core' ), 'select', 1 ),
);

$taxonomy_fields = array();

foreach ( $taxonomy_fields_map as $field_name => $config ) {
	list( $taxonomy, $label, $field_type, $required ) = $config;

	$taxonomy_fields[] = array(
		'key'           => "{$group_key}_{$field_name}",
		'label'         => $label,
		'name'          => $field_name,
		'type'          => 'taxonomy',
		'taxonomy'      => $taxonomy,
		'field_type'    => $field_type,
		'add_term'      => 0,
		'save_terms'    => 1,
		'load_terms'    => 1,
		'return_format' => 'object',
		'required'      => $required,
	);
}

$comments_field = array(
	'key'   => "{$group_key}_comments",
	'label' => __( 'Comments', 'parklex-core' ),
	'name'  => 'comments',
	'type'  => 'textarea',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array_merge(
		array(
			$project_name_field,
			$images_ids_field,
			$custom_gallery_field,
			$image_gallery_field,
			$featured_image_field,
			$hide_download_link_field,
			$year_field,
			$architect_field,
			$studio_field,
			$city_field,
		),
		$taxonomy_fields,
		array( $comments_field )
	),
	'location'              => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $cpt,
			),
		),
	),
	'menu_order'            => 0,
	'position'              => 'normal',
	'style'                 => 'default',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'active'                => true,
) );
