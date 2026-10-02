<?php
/**
 * ACF field group for the Blog archive options page.
 */
defined( 'ABSPATH' ) || exit;

$option_page = 'blog';
$title       = __( 'Blog Settings', 'parklex-core' );
$group_key   = 'bisiesto_option_blog';

$header_image_field = array(
	'key'           => "{$group_key}_header_image",
	'label'         => __( 'Imagen de cabecera', 'parklex-core' ),
	'name'          => 'blog_header_image',
	'type'          => 'image',
	'return_format' => 'id',
	// Same image regardless of WPML language — see Bis_Core\ACF::maybe_register_synced_field().
	'translation'   => 'sync',
);

$archive_title_field = array(
	'key'   => "{$group_key}_archive_title",
	'label' => __( 'Título', 'parklex-core' ),
	'name'  => 'blog_archive_title',
	'type'  => 'text',
);

$archive_subtitle_field = array(
	'key'   => "{$group_key}_archive_subtitle",
	'label' => __( 'Subtítulo', 'parklex-core' ),
	'name'  => 'blog_archive_subtitle',
	'type'  => 'text',
);

acf_add_local_field_group( array(
	'key'                   => $group_key,
	'title'                 => $title,
	'fields'                => array(
		$header_image_field,
		$archive_title_field,
		$archive_subtitle_field,
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
