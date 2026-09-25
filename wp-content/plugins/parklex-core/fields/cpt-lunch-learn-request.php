<?php
/**
 * ACF field group for the Lunch & Learn request CPT.
 *
 * Field *names* below must match the ones already used in ~150 published
 * `lunch_learn_request` posts migrated from the old theme (field *keys* are new,
 * ACF resolves get_field()/update_field() by name). The "official" field from the
 * original theme (CEU/RIBA/CPD accreditation) was dropped: empty on every post,
 * never used in practice — see docs/lunch-and-learn.md.
 */
defined( 'ABSPATH' ) || exit;

$cpt       = 'lunch_learn_request';
$title     = __( 'Lunch & Learn Request Fields', 'parklex-core' );
$group_key = 'bisiesto_cpt_lunch_learn_request';

$general_tab_field = array(
	'key'   => "{$group_key}_general_tab",
	'label' => __( 'General', 'parklex-core' ),
	'name'  => '',
	'type'  => 'tab',
);

$admin_note_field = array(
	'key'      => "{$group_key}_admin_note",
	'label'    => '',
	'name'     => '',
	'type'     => 'message',
	'message'  => __( 'Publish this post to <code>approve</code> the request (in the sidebar, click <code>Publish</code>) — this sends the approval email to the distributor. To <code>reject</code> it, move the post to <code>Draft</code> — this sends the rejection email instead.', 'parklex-core' ),
	'new_lines' => 'wpautop',
);

$user_id_field = array(
	'key'           => "{$group_key}_user_id",
	'label'         => __( 'User', 'parklex-core' ),
	'name'          => 'user_id',
	'type'          => 'user',
	'required'      => 1,
	'return_format' => 'id',
);

$design_firm_field = array(
	'key'   => "{$group_key}_design_firm_request",
	'label' => __( 'Architectural / Interior design Firm', 'parklex-core' ),
	'name'  => 'design-firm-request',
	'type'  => 'text',
);

$date_field = array(
	'key'            => "{$group_key}_date_request",
	'label'          => __( 'Date', 'parklex-core' ),
	'name'           => 'date-request',
	'type'           => 'date_picker',
	'display_format' => 'd/m/Y',
	'return_format'  => 'd/m/Y',
	'first_day'      => 1,
);

$time_field = array(
	'key'   => "{$group_key}_time_request",
	'label' => __( 'Time', 'parklex-core' ),
	'name'  => 'time-request',
	'type'  => 'text',
);

$location_field = array(
	'key'   => "{$group_key}_location_request",
	'label' => __( 'Location', 'parklex-core' ),
	'name'  => 'location-request',
	'type'  => 'text',
);

$address_field = array(
	'key'   => "{$group_key}_address_request",
	'label' => __( 'Address', 'parklex-core' ),
	'name'  => 'address-request',
	'type'  => 'text',
);

$assistants_field = array(
	'key'        => "{$group_key}_assistants",
	'label'      => __( 'Attendees', 'parklex-core' ),
	'name'       => 'assistants',
	'type'       => 'repeater',
	'layout'     => 'block',
	'sub_fields' => array(
		array(
			'key'     => "{$group_key}_assistants_name",
			'label'   => __( 'Name', 'parklex-core' ),
			'name'    => 'name',
			'type'    => 'text',
			'wrapper' => array( 'width' => '33.33' ),
		),
		array(
			'key'     => "{$group_key}_assistants_surname",
			'label'   => __( 'Surname', 'parklex-core' ),
			'name'    => 'surname',
			'type'    => 'text',
			'wrapper' => array( 'width' => '33.33' ),
		),
		array(
			'key'     => "{$group_key}_assistants_email",
			'label'   => __( 'Email', 'parklex-core' ),
			'name'    => 'email',
			'type'    => 'email',
			'wrapper' => array( 'width' => '33.33' ),
		),
		array(
			'key'     => "{$group_key}_assistants_associate_number",
			'label'   => __( 'Associate number', 'parklex-core' ),
			'name'    => 'associate_number',
			'type'    => 'text',
			'wrapper' => array( 'width' => '50' ),
		),
		array(
			'key'     => "{$group_key}_assistants_certificate",
			'label'   => __( 'Certificate', 'parklex-core' ),
			'name'    => 'certificate',
			'type'    => 'text',
			'wrapper' => array( 'width' => '50' ),
		),
		array(
			'key'   => "{$group_key}_assistants_comments",
			'label' => __( 'Comments', 'parklex-core' ),
			'name'  => 'comments',
			'type'  => 'textarea',
			'rows'  => 2,
		),
	),
);

$expected_assistants_field = array(
	'key'   => "{$group_key}_expected_assistants",
	'label' => __( 'Number of expected attendees', 'parklex-core' ),
	'name'  => 'expected-assistants',
	'type'  => 'number',
	'min'   => 0,
);

$type_event_field = array(
	'key'   => "{$group_key}_type_event",
	'label' => __( 'Type of event', 'parklex-core' ),
	'name'  => 'type-event',
	'type'  => 'text',
);

$course_name_code_field = array(
	'key'     => "{$group_key}_course_name_code",
	'label'   => __( 'Course code', 'parklex-core' ),
	'name'    => 'course-name-code',
	'type'    => 'text',
	'wrapper' => array( 'width' => '33.33' ),
);

$course_name_text_field = array(
	'key'     => "{$group_key}_course_name_text",
	'label'   => __( 'Course name', 'parklex-core' ),
	'name'    => 'course-name-text',
	'type'    => 'text',
	'wrapper' => array( 'width' => '66.66' ),
);

$name_presentation_field = array(
	'key'   => "{$group_key}_name_presentation",
	'label' => __( 'Name of the presentation', 'parklex-core' ),
	'name'  => 'name-presentation',
	'type'  => 'text',
);

$cost_presentation_field = array(
	'key'   => "{$group_key}_cost_presentation",
	'label' => __( 'Cost of the event per person', 'parklex-core' ),
	'name'  => 'cost-presentation',
	'type'  => 'number',
	'min'   => 0,
);

$related_project_field = array(
	'key'   => "{$group_key}_related_project_presentation",
	'label' => __( 'Related to a project (optional)', 'parklex-core' ),
	'name'  => 'related-project-presentation',
	'type'  => 'text',
);

$comments_field = array(
	'key'   => "{$group_key}_request_comments",
	'label' => __( 'Comments', 'parklex-core' ),
	'name'  => 'request-comments',
	'type'  => 'textarea',
);

$invoices_tab_field = array(
	'key'   => "{$group_key}_invoices_tab",
	'label' => __( 'Invoices', 'parklex-core' ),
	'name'  => '',
	'type'  => 'tab',
);

$distributor_approved_field = array(
	'key'   => "{$group_key}_distributor_approved",
	'label' => __( 'Distributor approved invoices', 'parklex-core' ),
	'name'  => 'distributor_approved',
	'type'  => 'true_false',
	'ui'    => 1,
);

$invoices_files_field = array(
	'key'        => "{$group_key}_invoices_files",
	'label'      => __( 'Invoices files', 'parklex-core' ),
	'name'       => 'invoices_files',
	'type'       => 'repeater',
	'layout'     => 'table',
	'sub_fields' => array(
		array(
			'key'           => "{$group_key}_invoices_files_file",
			'label'         => __( 'File', 'parklex-core' ),
			'name'          => 'file',
			'type'          => 'file',
			'return_format' => 'id',
		),
	),
);

$completed_tab_field = array(
	'key'   => "{$group_key}_completed_tab",
	'label' => __( 'Completed', 'parklex-core' ),
	'name'  => '',
	'type'  => 'tab',
);

$completed_field = array(
	'key'   => "{$group_key}_complited_lrequest",
	'label' => __( 'Completed', 'parklex-core' ),
	'name'  => 'complited_lrequest',
	'type'  => 'true_false',
	'ui'    => 1,
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$general_tab_field,
		$admin_note_field,
		$user_id_field,
		$design_firm_field,
		$date_field,
		$time_field,
		$location_field,
		$address_field,
		$assistants_field,
		$expected_assistants_field,
		$type_event_field,
		$course_name_code_field,
		$course_name_text_field,
		$name_presentation_field,
		$cost_presentation_field,
		$related_project_field,
		$comments_field,
		$invoices_tab_field,
		$distributor_approved_field,
		$invoices_files_field,
		$completed_tab_field,
		$completed_field,
	),
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
