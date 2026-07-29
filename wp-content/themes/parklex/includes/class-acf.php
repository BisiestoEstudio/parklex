<?php
defined( 'ABSPATH' ) || exit;

class Bis_Theme_ACF
{

    static function init()
    {
        add_action( 'acf/init', array( __CLASS__, 'register_options_pages' ) );
        add_action( 'acf/include_fields', array( __CLASS__, 'register_custom_fields' ) );
    }


    /**
     * Registra las páginas de opciones de ACF
     */
    static function register_options_pages()
    {
        if ( ! function_exists( 'acf_add_options_page' ) ) {
            return;
        }

        acf_add_options_page( array(
            'page_title' => 'Theme Options',
            'menu_title' => 'Theme Options',
            'menu_slug'  => 'acf-options-general',
            'capability' => 'manage_options',
            'redirect'   => false,
        ) );
    }


    /**
     * Registra los Custom Fields
     */
    static function register_custom_fields()
    {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        $fields_dir = BIS_THEME_DIR . '/fields/';

        if ( ! is_dir( $fields_dir ) ) {
            return;
        }

        $field_files = glob( $fields_dir . '*.php' );

        if ( ! empty( $field_files ) ) {
            foreach ( $field_files as $field_file ) {
                require_once $field_file;
            }
        }
    }

}

Bis_Theme_ACF::init();
