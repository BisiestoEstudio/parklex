<?php
/**
 * ACF field group for the Technical Card options page.
 */
defined( 'ABSPATH' ) || exit;

$option_page = 'technical-card';
$title       = __( 'Technical Card Options', 'parklex-core' );
$group_key   = 'bisiesto_option_technical_card';

$classification_categories_field = array(
	'key'           => "{$group_key}_classification_categories",
	'label'         => __( 'Categorías', 'parklex-core' ),
	'name'          => 'classification_categories',
	'instructions'  => __( 'Categorías de fichas técnicas que se mostrarán en la página principal. El orden de selección se conserva.', 'parklex-core' ),
	'type'          => 'taxonomy',
	'taxonomy'      => 'classification_technical_card',
	'field_type'    => 'multi_select',
	'add_term'      => 0,
	'save_terms'    => 0,
	'load_terms'    => 0,
	'return_format' => 'id',
	'allow_null'    => 1,
	'multiple'      => 0,
);

$presentations_technical_cards_field = array(
	'key'           => "{$group_key}_presentations_technical_cards",
	'label'         => __( 'Presentations', 'parklex-core' ),
	'name'          => 'presentations_technical_cards',
	'instructions'  => __( 'Fichas técnicas que se mostrarán en la pestaña "Presentations" de Mi Cuenta. El orden de selección se conserva.', 'parklex-core' ),
	'type'          => 'relationship',
	'post_type'     => array( 'technical-card' ),
	'filters'       => array( 'search' ),
	'return_format' => 'id',
);

$submit_documents_technical_cards_field = array(
	'key'           => "{$group_key}_submit_documents_technical_cards",
	'label'         => __( 'Submit Documents', 'parklex-core' ),
	'name'          => 'submit_documents_technical_cards',
	'instructions'  => __( 'Fichas técnicas que se mostrarán en la pestaña "Submit Documents" de Mi Cuenta. El orden de selección se conserva.', 'parklex-core' ),
	'type'          => 'relationship',
	'post_type'     => array( 'technical-card' ),
	'filters'       => array( 'search' ),
	'return_format' => 'id',
);

$script_field = array(
	'key'          => "{$group_key}_registration_form",
	'label'        => __( 'Formulario de registro', 'parklex-core' ),
	'name'         => 'registration_form',
	'instructions' => __( 'Introduce el script del formulario de hubspot de registro de acceso a la zona técnica', 'parklex-core' ),
	'type'         => 'textarea',
	'rows'         => 10,
	'new_lines'    => '',
);

$access_gate_title_field = array(
	'key'   => "{$group_key}_access_gate_title",
	'label' => __( 'Título', 'parklex-core' ),
	'name'  => 'access_gate_title',
	'type'  => 'text',
);

$access_gate_text_field = array(
	'key'   => "{$group_key}_access_gate_text",
	'label' => __( 'Texto', 'parklex-core' ),
	'name'  => 'access_gate_text',
	'type'  => 'wysiwyg',
);

$access_gate_image_field = array(
	'key'           => "{$group_key}_access_gate_image",
	'label'         => __( 'Imagen', 'parklex-core' ),
	'name'          => 'access_gate_image',
	'type'          => 'image',
	'return_format' => 'array',
);

$hubspot_api_key_field = array(
	'key'          => "{$group_key}_hubspot_api_key",
	'label'        => __( 'HubSpot API Key', 'parklex-core' ),
	'name'         => 'hubspot_api_key',
	'instructions' => __( 'Clave privada de la API de HubSpot.', 'parklex-core' ),
	'type'         => 'password',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$classification_categories_field,
		$presentations_technical_cards_field,
		$submit_documents_technical_cards_field,
		$script_field,
		$access_gate_title_field,
		$access_gate_text_field,
		$access_gate_image_field,
		$hubspot_api_key_field,
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
