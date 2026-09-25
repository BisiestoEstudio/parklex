<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_Loader {

	public static function init() {
		require_once BIS_CORE_DIR . 'includes/class-cpt-manager.php';
		require_once BIS_CORE_DIR . 'includes/class-taxonomy-manager.php';
		require_once BIS_CORE_DIR . 'includes/class-acf.php';
		require_once BIS_CORE_DIR . 'includes/class-woocommerce-legacy.php';
		require_once BIS_CORE_DIR . 'includes/class-woocommerce.php';
		require_once BIS_CORE_DIR . 'includes/class-roles-legacy.php';
		require_once BIS_CORE_DIR . 'includes/class-internal-projects.php';
		require_once BIS_CORE_DIR . 'includes/class-lunch-learn-legacy.php';
		require_once BIS_CORE_DIR . 'includes/class-lunch-learn.php';

		add_action( 'init', array( 'Bis_Core_CPT_Manager', 'register' ) );
		add_action( 'init', array( 'Bis_Core_Taxonomy_Manager', 'register' ) );
		add_action( 'init', array( 'Bis_Core_WooCommerce_Legacy', 'init' ) );
		add_action( 'init', array( 'Bis_Core_WooCommerce', 'init' ) );
		add_action( 'init', array( 'Bis_Core_Roles_Legacy', 'init' ) );
		add_action( 'init', array( 'Bis_Core_Internal_Projects', 'init' ) );
		add_action( 'init', array( 'Bis_Core_Lunch_Learn_Legacy', 'init' ) );
		add_action( 'init', array( 'Bis_Core_Lunch_Learn', 'init' ) );
	}

	public static function activate() {
		require_once BIS_CORE_DIR . 'includes/class-lunch-learn-legacy.php';

		Bis_Core_CPT_Manager::register();
		Bis_Core_Taxonomy_Manager::register();
		Bis_Core_Lunch_Learn_Legacy::activate();
		flush_rewrite_rules();
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}
}
