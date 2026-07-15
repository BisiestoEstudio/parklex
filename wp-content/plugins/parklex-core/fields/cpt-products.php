<?php
/**
 * ACF field group for room CPT.
 * Provides fields for room number, size, and room ID.
 */
defined( 'ABSPATH' ) || exit;

$cpt = 'products';
$title = __('Campos de Producto', 'parklex-core');
$group_key = "bisiesto_cpt_{$cpt}";



$gallery_group_key = "{$group_key}_gallery";
$gallery_group = array(
    'key' => $gallery_group_key,
    'label' => __('Galería de imágenes', 'unico-core'),
    'name' => 'gallery',
    'type' => 'gallery',
    'return_format' => 'id',
    'translations' => 'sync',
);

acf_add_local_field_group( array(
    'key'                   => $group_key,
    'title'                 => $title,
    'fields'                => array(
        $gallery_group,
    ),
    'show_in_rest'          => true,
    'location'              => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => $cpt,
            ),
        ),
    ),
    'menu_order'             => 0,
    'position'               => 'normal',
    'style'                  => 'default',
    'label_placement'        => 'top',
    'instruction_placement'  => 'label',
    'active'                 => true,
) );
