<?php
/**
 * ACF field group for the "Internal Projects" permission on the user profile screen.
 */
defined( 'ABSPATH' ) || exit;

$group_key = 'bisiesto_user_internal_projects';

$allow_internal_projects_field = array(
	'key'   => "{$group_key}_allow_internal_projects",
	'label' => __( 'Allow "Internal Projects" for user', 'parklex-core' ),
	'name'  => 'allow_internal_projects',
	'type'  => 'true_false',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => __( 'Internal Projects', 'parklex-core' ),
	'fields'                => array(
		$allow_internal_projects_field,
	),
	'location'              => array(
		array(
			array(
				'param'    => 'user_role',
				'operator' => '==',
				'value'    => 'all',
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
