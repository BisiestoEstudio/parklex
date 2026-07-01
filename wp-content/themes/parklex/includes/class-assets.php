<?php
/**
 * This class is responsible for enqueuing the theme's assets.
 */

defined( 'ABSPATH' ) || exit;

class Bis_Theme_Assets {

    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
        add_action( 'after_setup_theme', array( __CLASS__, 'enqueue_editor_styles' ) );
        add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_editor_assets' ) );
    }

	public static function enqueue_scripts() {
		// CSS
		wp_enqueue_style( 'bis-theme-framework', BIS_THEME_URI . '/assets/css/framework.min.css', array(), BIS_THEME_VERSION );
		wp_enqueue_style( 'bis-theme-main', BIS_THEME_URI . '/assets/css/main.min.css', array( 'bis-theme-framework' ), BIS_THEME_VERSION );
		wp_enqueue_style( 'bis-theme-blocks', BIS_THEME_URI . '/assets/css/blocks.css', array( 'bis-theme-main' ), BIS_THEME_VERSION );

		// JS

	}

	public static function enqueue_editor_styles() {
		add_editor_style( 'assets/css/framework.min.css' );
		add_editor_style( 'assets/css/blocks.css' );
		add_editor_style( 'assets/css/editor.min.css' );
	}

	public static function enqueue_editor_assets() {


		wp_enqueue_script(
			'bis-theme-custom-block',
			BIS_THEME_URI . '/assets/js/custom-block.js',
			array( 'wp-blocks', 'wp-dom-ready' ),
			BIS_THEME_VERSION,
			true
		);
	}
}

Bis_Theme_Assets::init();
