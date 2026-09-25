<?php
/**
 * ACF field group for the Lunch & Learn "Settings" options page.
 *
 * "type_of_events" used to live on the front-end request page itself (read via a
 * `theme_get_template_id()` lookup of "which page uses this template") — moved here
 * since it's global configuration, not page content. Same field name, so no data migration
 * needed once the values are re-entered.
 */
defined( 'ABSPATH' ) || exit;

$option_page = 'lunch-learn-settings';
$title       = __( 'Lunch & Learn Settings', 'parklex-core' );
$group_key   = 'bisiesto_option_lunch_learn_settings';

$text_lunch_learn_field = array(
	'key'           => "{$group_key}_text_lunch_learn",
	'label'         => __( 'Requests list intro text', 'parklex-core' ),
	'name'          => 'text_lunch_learn',
	'type'          => 'wysiwyg',
	'default_value' => __( 'Your Lunch & Learn requests.', 'parklex-core' ),
);

$text_lunch_learn_single_field = array(
	'key'           => "{$group_key}_text_lunch_learn_single",
	'label'         => __( 'Single request intro text', 'parklex-core' ),
	'name'          => 'text_lunch_learn_single',
	'type'          => 'wysiwyg',
	'default_value' => __( 'Request details.', 'parklex-core' ),
);

$remember_interval_field = array(
	'key'           => "{$group_key}_remember_interval",
	'label'         => __( 'Invoice reminder — days after the event', 'parklex-core' ),
	'name'          => 'remember_interval',
	'type'          => 'number',
	'default_value' => 7,
);

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
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$text_lunch_learn_field,
		$text_lunch_learn_single_field,
		$remember_interval_field,
		$type_of_events_field,
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
