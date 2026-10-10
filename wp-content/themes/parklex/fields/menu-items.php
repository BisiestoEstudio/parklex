<?php
$menu_item_slug = 'bisiesto-menu-item';

acf_add_local_field_group( array(
	'key' => $menu_item_slug,
	'title' => 'Item de menú',
	'fields' => array(
		array(
			'key' => $menu_item_slug . '_image',
			'label' => 'Imagen',
			'name' => 'image',
			'type' => 'image',
			'return_format' => 'id',
			'preview_size' => 'thumbnail',
			'allow_null' => 1,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'location/primary',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
