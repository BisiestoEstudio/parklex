<?php
/**
 * ACF field group for the "Lunch & Learn" permission on the user profile screen.
 */
defined( 'ABSPATH' ) || exit;

$group_key = 'bisiesto_user_lunch_learn';

$allow_ll_request_field = array(
	'key'   => "{$group_key}_allow_ll_request",
	'label' => __( 'Allow "Lunch & Learn" requests for user', 'parklex-core' ),
	'name'  => 'allow_ll_request',
	'type'  => 'true_false',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => __( 'Lunch & Learn', 'parklex-core' ),
	'fields'                => array(
		$allow_ll_request_field,
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
