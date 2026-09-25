<?php
/**
 * ACF field group for the Lunch & Learn "Statistics" options page.
 *
 * The "report_container" message field is just a placeholder: the actual report UI
 * (generate/download buttons + result table) is rendered into it by
 * Bis_Core_Lunch_Learn_Legacy::render_statistics_report_field(), same approach as the
 * Internal Projects gallery uploader field.
 */
defined( 'ABSPATH' ) || exit;

$option_page = 'lunch-learn-statistics';
$title       = __( 'Lunch & Learn Statistics', 'parklex-core' );
$group_key   = 'bisiesto_option_lunch_learn_statistics';

$analytics_type_field = array(
	'key'           => "{$group_key}_analytics_type",
	'label'         => __( 'Report type', 'parklex-core' ),
	'name'          => 'analytics_type',
	'type'          => 'select',
	'choices'       => array(
		'distributor' => __( 'Per distributor', 'parklex-core' ),
		'studios'     => __( 'Per studios', 'parklex-core' ),
		'assistants'  => __( 'Per assistants', 'parklex-core' ),
	),
	'default_value' => 'distributor',
);

$report_container_field = array(
	'key'     => "{$group_key}_report_container",
	'label'   => '',
	'name'    => 'report_container',
	'type'    => 'message',
	'message' => '',
	'wrapper' => array( 'id' => 'bis-lunch-learn-statistics-container' ),
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$analytics_type_field,
		$report_container_field,
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
