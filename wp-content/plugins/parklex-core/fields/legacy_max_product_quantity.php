<?php
/**
 * ACF field group traspasado tal cual del theme antiguo (grupo "Maximum product quantity").
 * Define el límite de cantidad máxima para el rol "distributor" a tres niveles:
 * global (options page), por producto, y por usuario.
 *
 * La options page original vivía en el slug 'theme-general-settings' (tema antiguo, ya no existe);
 * en el sitio nuevo se usa la options page 'acf-options-general' registrada por el tema parklex.
 */
defined( 'ABSPATH' ) || exit;

$group_key = 'bisiesto_legacy_max_product_quantity';

$max_qty_field = array(
	'key'   => "{$group_key}_distributor_max_qty",
	'label' => __( 'Maximum product quantity', 'parklex-core' ),
	'name'  => 'distributor_max_qty',
	'type'  => 'number',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => __( 'Maximum product quantity', 'parklex-core' ),
	'fields'                => array(
		$max_qty_field,
	),
	'location'              => array(
		array(
			array(
				'param'    => 'user_role',
				'operator' => '==',
				'value'    => 'distributor',
			),
		),
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'product',
			),
		),
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'acf-options-general',
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
