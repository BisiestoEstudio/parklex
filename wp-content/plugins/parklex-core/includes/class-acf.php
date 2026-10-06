<?php

/**
 * Clase para registrar los Custom Post Types
 */

namespace Bis_Core;
defined( 'ABSPATH' ) || exit;
class ACF
{
    /**
     * Field "name"s already wired up for sync (see maybe_register_synced_field()),
     * so a field seen again — e.g. a sub_field loaded for several rows — doesn't
     * get its value filters registered more than once.
     */
    private static $synced_field_names = array();

    /**
     * Inicializa los hooks
     */
    public static function init()
    {
        add_action( 'acf/init', array( __CLASS__, 'register_options_pages' ) );
        add_action( 'acf/include_fields', array( __CLASS__, 'register_custom_fields' ) );
        add_filter( 'acf/load_field', array( __CLASS__, 'maybe_register_synced_field' ) );
    }

    /**
     * Makes an ACF Options-page field's value stay identical across every WPML
     * language, instead of the separate per-language copy WPML makes ACF store by
     * default. ACF core suffixes the resolved "options" post_id with the current
     * admin language whenever it's not the site's default one (see
     * acf_validate_post_id() in advanced-custom-fields-pro/includes/api/api-helpers.php),
     * e.g. "options_es_my_field" vs. "options_my_field" for the default language —
     * right for translatable content, wrong for settings that are configuration or
     * secrets (an API key, a toggle, an ID...) where every language must read and
     * write the exact same value.
     *
     * To make a field sync: add 'translation' => 'sync' to its field array, next to
     * 'key'/'name'/'type' — e.g.:
     *
     *   array(
     *       'key'         => '...',
     *       'name'        => 'hubspot_api_key',
     *       'translation' => 'sync',
     *       'type'        => 'password',
     *   )
     *
     * Nothing else to wire up: this hooks acf/load_field (which runs for every field
     * ACF loads, options-page or not, top-level or nested in a group/repeater) and
     * reacts the first time it sees that attribute on a given field name. The field
     * "name" must be unique across the site — the sync is keyed by name only, not
     * by which field group it belongs to.
     */
    public static function maybe_register_synced_field( $field )
    {
        if ( empty( $field['translation'] ) || 'sync' !== $field['translation'] || empty( $field['name'] ) ) {
            return $field;
        }

        if ( in_array( $field['name'], self::$synced_field_names, true ) ) {
            return $field;
        }

        self::$synced_field_names[] = $field['name'];

        add_filter( "acf/load_value/name={$field['name']}", array( __CLASS__, 'load_language_independent_value' ), 10, 3 );
        add_filter( "acf/update_value/name={$field['name']}", array( __CLASS__, 'update_language_independent_value' ), 10, 3 );

        return $field;
    }

    /**
     * Always reads from the one canonical, unsuffixed "options_{field_name}" row,
     * ignoring whatever language-specific post_id ACF resolved for this request.
     */
    public static function load_language_independent_value( $value, $post_id, $field )
    {
        return get_option( self::language_independent_option_name( $field['name'] ), $value );
    }

    /**
     * Counterpart of load_language_independent_value(): always writes that same
     * canonical row, regardless of which language the options page was saved in.
     * ACF still also saves its own language-specific copy afterwards — harmless,
     * since load_language_independent_value() never reads it.
     */
    public static function update_language_independent_value( $value, $post_id, $field )
    {
        update_option( self::language_independent_option_name( $field['name'] ), $value );

        return $value;
    }

    private static function language_independent_option_name( $field_name )
    {
        return "options_{$field_name}";
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

        acf_add_options_sub_page( array(
            'page_title'  => __( 'Internal Projects Settings', 'parklex-core' ),
            'menu_title'  => __( 'Settings', 'parklex-core' ),
            'menu_slug'   => 'acf-options-internal-projects-settings',
            'parent_slug' => 'edit.php?post_type=project_internal',
            'capability'  => 'manage_options',
            'redirect'    => false,
        ) );

        acf_add_options_sub_page( array(
            'page_title'  => __( 'Lunch & Learn Settings', 'parklex-core' ),
            'menu_title'  => __( 'Settings', 'parklex-core' ),
            'menu_slug'   => 'acf-options-lunch-learn-settings',
            'parent_slug' => 'edit.php?post_type=lunch_learn_request',
            'capability'  => 'manage_options',
            'redirect'    => false,
        ) );

        acf_add_options_sub_page( array(
            'page_title'  => __( 'Lunch & Learn Statistics', 'parklex-core' ),
            'menu_title'  => __( 'Statistics', 'parklex-core' ),
            'menu_slug'   => 'acf-options-lunch-learn-statistics',
            'parent_slug' => 'edit.php?post_type=lunch_learn_request',
            'capability'  => 'manage_options',
            'redirect'    => false,
        ) );

        acf_add_options_sub_page( array(
            'page_title'  => __( 'Blog Settings', 'parklex-core' ),
            'menu_title'  => __( 'Blog Settings', 'parklex-core' ),
            'menu_slug'   => 'acf-options-blog',
            'parent_slug' => 'edit.php',
            'capability'  => 'manage_options',
            'redirect'    => false,
        ) );

        acf_add_options_sub_page( array(
            'page_title'  => __( 'Product Settings', 'parklex-core' ),
            'menu_title'  => __( 'Product Settings', 'parklex-core' ),
            'menu_slug'   => 'acf-options-products',
            'parent_slug' => 'edit.php?post_type=products',
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