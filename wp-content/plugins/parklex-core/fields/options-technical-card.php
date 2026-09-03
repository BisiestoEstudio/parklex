<?php
/**
 * ACF field group for the Technical Card options page.
 */
defined( 'ABSPATH' ) || exit;

$option_page = 'technical-card';
$title       = __( 'Technical Card Options', 'parklex-core' );
$group_key   = 'bisiesto_option_technical_card';

$homepage_categories_field = array(
	'key'           => "{$group_key}_homepage_categories",
	'label'         => __( 'Categorías', 'parklex-core' ),
	'name'          => 'homepage_categories',
	'instructions'  => __( 'Categorías de fichas técnicas que se mostrarán en la página principal. El orden de selección se conserva.', 'parklex-core' ),
	'type'          => 'taxonomy',
	'taxonomy'      => 'category_technical_card',
	'field_type'    => 'multi_select',
	'add_term'      => 0,
	'save_terms'    => 0,
	'load_terms'    => 0,
	'return_format' => 'id',
	'allow_null'    => 1,
	'multiple'      => 0,
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$homepage_categories_field,
	),
	'location'              => array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'acf-options-' . $option_page,
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
