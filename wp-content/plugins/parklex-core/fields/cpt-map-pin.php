<?php
/**
 * ACF field group for the Map Pin CPT.
 */
defined( 'ABSPATH' ) || exit;

$cpt       = 'map-pin';
$title     = __( 'Map Pin Fields', 'parklex-core' );
$group_key = 'bisiesto_cpt_map_pin';

$location_field = array(
	'key'        => "{$group_key}_location",
	'label'      => __( 'Location', 'parklex-core' ),
	'name'       => 'location',
	'type'       => 'group',
	'required'   => 0,
	'layout'     => 'block',
	'sub_fields' => array(
		array(
			'key'          => "{$group_key}_location_lat",
			'label'        => __( 'Latitude', 'parklex-core' ),
			'name'         => 'lat',
			'type'         => 'number',
			'required'     => 1,
			'step'         => 'any',
		),
		array(
			'key'          => "{$group_key}_location_lng",
			'label'        => __( 'Longitude', 'parklex-core' ),
			'name'         => 'lng',
			'type'         => 'number',
			'required'     => 1,
			'step'         => 'any',
		),
	),
);

$info_field = array(
	'key'        => "{$group_key}_info",
	'label'      => __( 'Info', 'parklex-core' ),
	'name'       => 'info',
	'type'       => 'group',
	'required'   => 0,
	'layout'     => 'block',
	'sub_fields' => array(
		array(
			'key'   => "{$group_key}_info_city",
			'label' => __( 'Ciudad', 'parklex-core' ),
			'name'  => 'city',
			'type'  => 'text',
		),
		array(
			'key'   => "{$group_key}_info_architect",
			'label' => __( 'Arquitecto', 'parklex-core' ),
			'name'  => 'architect',
			'type'  => 'text',
		),
	),
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$location_field,
		$info_field,
	),
	'show_in_rest'          => true,
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
