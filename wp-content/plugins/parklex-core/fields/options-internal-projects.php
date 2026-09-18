<?php
/**
 * ACF field group for the Internal Projects options page.
 */
defined( 'ABSPATH' ) || exit;

$option_page = 'internal-projects-settings';
$title       = __( 'Internal Projects Settings', 'parklex-core' );
$group_key   = 'bisiesto_option_internal_projects';

$notes_text_field = array(
	'key'   => "{$group_key}_notes_text_ipf",
	'label' => __( 'Notes text', 'parklex-core' ),
	'name'  => 'notes_text_ipf',
	'type'  => 'wysiwyg',
);

$updated_form_message_field = array(
	'key'           => "{$group_key}_updated_form_message",
	'label'         => __( 'Success submit message', 'parklex-core' ),
	'name'          => 'updated_form_message',
	'type'          => 'text',
	'default_value' => __( 'Project successfully created and pending to review.', 'parklex-core' ),
);

$submit_button_text_field = array(
	'key'           => "{$group_key}_submit_button_text",
	'label'         => __( 'Submit button text', 'parklex-core' ),
	'name'          => 'submit_button_text',
	'type'          => 'text',
	'default_value' => __( 'Submit', 'parklex-core' ),
);

$internal_projects_title_field = array(
	'key'   => "{$group_key}_internal_projects_title",
	'label' => __( 'Archive title', 'parklex-core' ),
	'name'  => 'internal_projects_title',
	'type'  => 'text',
);

$enable_search_field = array(
	'key'   => "{$group_key}_enable_search_internal",
	'label' => __( 'Enable search', 'parklex-core' ),
	'name'  => 'enable_search_internal',
	'type'  => 'true_false',
);

$download_modal_text_field = array(
	'key'   => "{$group_key}_text_modal_download_photos",
	'label' => __( 'Download-photos confirmation text', 'parklex-core' ),
	'name'  => 'text_modal_download_photos',
	'type'  => 'text',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$notes_text_field,
		$updated_form_message_field,
		$submit_button_text_field,
		$internal_projects_title_field,
		$enable_search_field,
		$download_modal_text_field,
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
