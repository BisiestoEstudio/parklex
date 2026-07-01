<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_Loader {

	public static function init() {
		require_once BIS_CORE_DIR . 'includes/class-cpt-manager.php';
		require_once BIS_CORE_DIR . 'includes/class-taxonomy-manager.php';

		add_action( 'init', array( 'Bis_Core_CPT_Manager', 'register' ) );
		add_action( 'init', array( 'Bis_Core_Taxonomy_Manager', 'register' ) );
	}

	public static function activate() {
		Bis_Core_CPT_Manager::register();
		Bis_Core_Taxonomy_Manager::register();
		flush_rewrite_rules();
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}
}
