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
        add_action( 'acf/include_fields', array( __CLASS__, 'register_custom_fields' ) );
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