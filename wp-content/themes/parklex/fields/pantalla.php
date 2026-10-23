<?php
// Mismas claves que el grupo antiguo de BD (group_63741649a08aa, desactivado) para
// que los valores ya guardados en las páginas sigan resolviendo.
acf_add_local_field_group( array(
	'key' => 'group_63741649a08aa',
	'title' => 'Pantalla',
	'fields' => array(
		array(
			'key' => 'field_6374164aacbe4',
			'label' => 'Video',
			'name' => 'video',
			'type' => 'file',
			'return_format' => 'url',
			'library' => 'all',
			'mime_types' => 'mp4',
		),
		array(
			'key' => 'field_65c243fd3fe0f',
			'label' => 'Video url',
			'name' => 'video_url',
			'type' => 'url',
			'instructions' => 'Use <code>*.mp4</code> format. Solo se usa si no hay vídeo subido.',
		),
	),
	'location' => array(
		array(
			array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/pantalla.php' ),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'active' => true,
	'show_in_rest' => 0,
) );
