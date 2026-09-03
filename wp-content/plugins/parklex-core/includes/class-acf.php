<?php

/**
 * Clase para registrar los Custom Post Types
 */

namespace Bis_Core;
defined( 'ABSPATH' ) || exit;
class ACF
{
    /**
     * Inicializa los hooks
     */
    public static function init()
    {
        add_action( 'acf/init', array( __CLASS__, 'register_options_pages' ) );
        add_action( 'acf/include_fields', array( __CLASS__, 'register_custom_fields' ) );
    }

    /**
     * Registra las páginas de opciones de ACF
     */
    public static function register_options_pages()
    {
        if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
            return;
        }

        acf_add_options_sub_page( array(
            'page_title'  => __( 'Ajustes de Fichas Técnicas', 'parklex-core' ),
            'menu_title'  => __( 'Ajustes', 'parklex-core' ),
            'menu_slug'   => 'acf-options-technical-card',
            'parent_slug' => 'edit.php?post_type=technical-card',
            'capability'  => 'manage_options',
            'redirect'    => false,
        ) );
    }

    /**
     * Registra los Custom Fields
     */
    public static function register_custom_fields()
    {
        if( function_exists('acf_add_local_field_group') ) {
            $fields_dir = BIS_CORE_DIR . 'fields/';    
            if ( is_dir( $fields_dir ) ) {
                $field_files = glob( $fields_dir . '*.php' );
                
                if ( ! empty( $field_files ) ) {
                    foreach ( $field_files as $field_file ) {
                        require_once $field_file;
                    }
                }
            }
        }
    }



}

ACF::init();