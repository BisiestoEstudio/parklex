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

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$classification_categories_field,
		$presentations_technical_cards_field,
		$submit_documents_technical_cards_field,
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
