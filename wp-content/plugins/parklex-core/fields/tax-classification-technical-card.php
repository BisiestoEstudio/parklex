<?php
/**
 * ACF field group for the classification_technical_card taxonomy terms.
 */
defined( 'ABSPATH' ) || exit;

$taxonomy  = 'classification_technical_card';
$title     = __( 'Technical Card Classification Fields', 'parklex-core' );
$group_key = 'bisiesto_tax_classification_technical_card';

$card_type_field = array(
	'key'           => "{$group_key}_card_type",
	'label'         => __( 'Tipo de Ficha', 'parklex-core' ),
	'name'          => 'tipo_de_ficha',
	'type'          => 'select',
	'required'      => 0,
	'choices'       => array(
		'card'       => __( 'Card', 'parklex-core' ),
		'row'        => __( 'Row', 'parklex-core' ),
		'image' => __( 'Image Card', 'parklex-core' ),
	),
	'allow_null'    => 0,
	'multiple'      => 0,
	'ui'            => 0,
	'return_format' => 'value',
);

$has_label_field = array(
	'key'           => "{$group_key}_has_label",
	'label'         => __( 'Has Label', 'parklex-core' ),
	'name'          => 'has_label',
	'type'          => 'true_false',
	'instructions'  => __( 'Indica si las etiquetas están activadas.', 'parklex-core' ),
	'required'      => 0,
	'default_value' => 0,
	'ui'            => 1,
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$card_type_field,
		$has_label_field,
	),
	'show_in_rest'          => true,
	'location'              => array(
		array(
			array(
				'param'    => 'taxonomy',
				'operator' => '==',
				'value'    => $taxonomy,
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
