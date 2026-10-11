<?php
/**
 * ACF field group for the front-end Lunch & Learn request page ("type_of_events").
 * Read by the theme via bis_theme_get_page_by_template( 'page-request-lunch-learn.php' ).
 */
defined( 'ABSPATH' ) || exit;

$group_key = 'bisiesto_option_lunch_learn_settings';

$type_of_events_field = array(
	'key'        => "{$group_key}_type_of_events",
	'label'      => __( 'Types of event', 'parklex-core' ),
	'name'       => 'type_of_events',
	'type'       => 'repeater',
	'layout'     => 'block',
	'sub_fields' => array(
		array(
			'key'   => "{$group_key}_type_of_events_type",
			'label' => __( 'Type', 'parklex-core' ),
			'name'  => 'type',
			'type'  => 'text',
		),
		array(
			'key'        => "{$group_key}_type_of_events_course_names",
			'label'      => __( 'Course names', 'parklex-core' ),
			'name'       => 'course_names',
			'type'       => 'repeater',
			'layout'     => 'table',
			'sub_fields' => array(
				array(
					'key'     => "{$group_key}_type_of_events_course_names_code",
					'label'   => __( 'Code', 'parklex-core' ),
					'name'    => 'code',
					'type'    => 'text',
					'wrapper' => array( 'width' => '33.33' ),
				),
				array(
					'key'     => "{$group_key}_type_of_events_course_names_name",
					'label'   => __( 'Name', 'parklex-core' ),
					'name'    => 'name',
					'type'    => 'text',
					'wrapper' => array( 'width' => '66.66' ),
				),
			),
		),
	),
);

acf_add_local_field_group( array(
	'key'      => 'bisiesto_page_lunch_learn_request',
	'title'    => __( 'Lunch & Learn request', 'parklex-core' ),
	'fields'   => array(
		$type_of_events_field,
	),
	'location' => array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'page-request-lunch-learn.php',
			),
		),
	),
	'active'   => true,
) );
