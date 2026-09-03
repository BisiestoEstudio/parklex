<?php
/**
 * ACF field group for the Technical Card CPT.
 */
defined( 'ABSPATH' ) || exit;

$cpt       = 'technical-card';
$title     = __( 'Technical Card Fields', 'parklex-core' );
$group_key = 'bisiesto_cpt_technical_card';

$downloads_field = array(
	'key'          => "{$group_key}_downloads",
	'label'        => __( 'Downloads', 'parklex-core' ),
	'name'         => 'downloads',
	'type'         => 'repeater',
	'required'     => 1,
	'layout'       => 'table',
	'button_label' => __( 'Add File', 'parklex-core' ),
	'sub_fields'   => array(
		array(
			'key'           => "{$group_key}_downloads_file",
			'label'         => __( 'File', 'parklex-core' ),
			'name'          => 'file',
			'type'          => 'file',
			'required'      => 1,
			'return_format' => 'array',
			'library'       => 'all',
		),
	),
);

$link_field = array(
	'key'          => "{$group_key}_link",
	'label'        => __( 'Link al video de vimeo', 'parklex-core' ),
	'instructions' => __( 'El link debe ser de un video de vimeo. De lo contrario, no funcionará', 'parklex-core' ),
	'name'         => 'link',
	'type'         => 'url',
	'required'     => 0,
);

$image_field = array(
	'key'           => "{$group_key}_image",
	'label'         => __( 'Image', 'parklex-core' ),
	'name'          => 'image',
	'type'          => 'image',
	'required'      => 0,
	'return_format' => 'array',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$downloads_field,
		$link_field,
		$image_field,
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
