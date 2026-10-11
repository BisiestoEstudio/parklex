<?php
$header_variant_slug = 'bisiesto-header-variant';

acf_add_local_field_group( array(
	'key' => $header_variant_slug,
	'title' => 'Menú superior',
	'fields' => array(
		array(
			'key' => $header_variant_slug . '_variant',
			'label' => 'Variante del menú',
			'instructions' => 'Auto adapta el menú al contenido de la página.',
			'name' => 'header_variant',
			'type' => 'select',
			'choices' => array(
				'auto'  => 'Auto',
				'light' => 'Light',
				'dark'  => 'Dark',
			),
			'default_value' => 'auto',
			'allow_null' => 0,
			'return_format' => 'value',
		),
	),
	'location' => array(
		array(
			array( 'param' => 'post_type', 'operator' => '!=', 'value' => 'attachment' ),
			array( 'param' => 'post_type', 'operator' => '!=', 'value' => 'wp_block' ),
			array( 'param' => 'post_type', 'operator' => '!=', 'value' => 'wp_template' ),
			array( 'param' => 'post_type', 'operator' => '!=', 'value' => 'wp_template_part' ),
			array( 'param' => 'post_type', 'operator' => '!=', 'value' => 'wp_navigation' ),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
