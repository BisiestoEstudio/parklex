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
		wp_enqueue_style( 'bis-theme-woocommerce', BIS_THEME_URI . '/assets/css/woocommerce.min.css', array( 'bis-theme-main' ), BIS_THEME_VERSION );
		// JS
		if ( is_post_type_archive( 'technical-card' ) ) {
			wp_enqueue_script(
				'bis-theme-technical-video-modal',
				BIS_THEME_URI . '/assets/js/technical-video-modal.js',
				array(),
				BIS_THEME_VERSION,
				true
			);
		}

		if ( is_singular( 'project_internal' ) || is_post_type_archive( 'project_internal' ) ) {
			self::enqueue_internal_projects_assets();
		}
	}

	/**
	 * Third-party libraries for the Internal Projects gallery (masonry grid, lightbox,
	 * client-side zip download) — vendored locally, no CDN, same pattern as Swiper in
	 * parklex-blocks. Only the single view needs the gallery libs; the archive only
	 * needs the plain filter-reload script.
	 */
	private static function enqueue_internal_projects_assets() {
		$deps = array();

		if ( is_singular( 'project_internal' ) ) {
			wp_enqueue_style( 'bis-theme-fancybox', BIS_THEME_URI . '/assets/css/vendor/fancybox.css', array(), BIS_THEME_VERSION );

			wp_enqueue_script( 'bis-theme-jszip', BIS_THEME_URI . '/assets/js/vendor/jszip.js', array(), BIS_THEME_VERSION, true );
			wp_enqueue_script( 'bis-theme-jszip-utils', BIS_THEME_URI . '/assets/js/vendor/jszip-utils.js', array( 'bis-theme-jszip' ), BIS_THEME_VERSION, true );
			wp_enqueue_script( 'bis-theme-filesaver', BIS_THEME_URI . '/assets/js/vendor/filesaver.min.js', array(), BIS_THEME_VERSION, true );
			wp_enqueue_script( 'bis-theme-fancybox', BIS_THEME_URI . '/assets/js/vendor/fancybox.umd.js', array(), BIS_THEME_VERSION, true );
			wp_enqueue_script( 'bis-theme-packery', BIS_THEME_URI . '/assets/js/vendor/packery.pkgd.min.js', array(), BIS_THEME_VERSION, true );

			$deps = array( 'bis-theme-jszip-utils', 'bis-theme-filesaver', 'bis-theme-fancybox', 'bis-theme-packery' );
		}

		wp_enqueue_script(
			'bis-theme-internal-projects',
			BIS_THEME_URI . '/assets/js/internal-projects.js',
			$deps,
			BIS_THEME_VERSION,
			true
		);
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
