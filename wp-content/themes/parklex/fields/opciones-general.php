<?php
$option_page= 'general';
$acf_field_group_title = 'General';

$acabados_group_slug = "bisiesto-option_{$option_page}_acabados";
$acabados_group = array(
	'key' => $acabados_group_slug,
	'label' => 'Acabados',
	'name' => 'acabados',
	'type' => 'group',
	'sub_fields' => array(
		array(
			'key' => $acabados_group_slug . '_title',
			'label' => 'Título de la página de archivo de acabados',
			'name' => 'title',
			'type' => 'text',
		),
		array(
			'key' => $acabados_group_slug . '_image',
			'label' => 'Imagen',
			'instructions' => 'Esta imagen se mostrará en la cabecera de la página de archivo de acabados.',
			'name' => 'image',
			'type' => 'image',
			'return_format' => 'id',
			'allow_null' => 1,
		),
	),
);

$page404_slug = "bisiesto-option_{$option_page}_404";
$page404 = array(
	'key' => $page404_slug,
	'label' => 'Page 404',
	'name' => "error404",
	'instructions' => 'Bloque que se mostrará en la página de error 404. Si no lo tienes creado, puedes crearlo <a href="/wp-admin/site-editor.php?postType=wp_block" target="_blank">aquí</a>',
	'type' => 'post_object',
	'post_type' => array(
		0 => 'wp_block',
	),
	'return_format' => 'id',
	'allow_null' => 1,
	
);



$footer_slug = "bisiesto-option_{$option_page}_footer";
$footer = array(
	'key' => $footer_slug,
	'label' => 'Footer',
	'name' => 'footer',
	'type' => 'group',
	'sub_fields' => array(
		array(
			'key' => $footer_slug . '_upper_footer',
			'label' => 'Bloque superior del footer',
			'instructions' => 'Bloque superior del footer. Si no lo tienes creado, puedes crearlo <a href="/wp-admin/site-editor.php?postType=wp_block" target="_blank">aquí</a>',
			'name' => 'upper_footer',
			'type' => 'post_object',
			'post_type' => array(
				0 => 'wp_block',
			),
			'return_format' => 'id',
			'allow_null' => 1,
		),
		array(
			'key' => $footer_slug . '_gallery',
			'label' => __( 'Galería de imágenes del footer', 'unico' ),
			'name' => 'gallery',
			'type' => 'gallery',
			'return_format' => 'id',
			'allow_null' => 1,
		),
		array(
            'key' => $footer_slug . '_legal_menu',
            'label' => __('Menú legal del footer', 'unico-core'),
			'instructions' => 'Menú legal del footer. Si no lo tienes creado, puedes crearlo <a href="/wp-admin/nav-menus.php" target="_blank">aquí</a>',
            'name' => 'legal_menu',
            'type' => 'taxonomy',
            'taxonomy' => 'nav_menu',
            'field_type' => 'select',
            'allow_null' => 1,
            'return_format' => 'id'
        ),
	),
);



$blog_pattern_slug = "bisiesto-option_{$option_page}_blog_pattern";
$blog_pattern = array(
	'key' => $blog_pattern_slug,
	'label' => 'Patrón de blog',
	'instructions' => 'Patrón de blog que se mostrará bajo el contenido de los post. Está pensado para meter el bloque de compartir en redes. Si no lo tienes creado, puedes crearlo <a href="/wp-admin/site-editor.php?postType=wp_block" target="_blank">aquí</a>',
	'name' => 'blog_pattern',
	'type' => 'post_object',
	'post_type' => array(
		'wp_block',
	),
	'return_format' => 'id',
	'allow_null' => 1,
);







acf_add_local_field_group( array(
	'key' => "bisiesto-option_{$option_page}",
	'title' => $acf_field_group_title,
	'fields' => array(
		//$page404,
		$acabados_group,
		//$footer,
		//$blog_pattern,
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-' . $option_page
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


