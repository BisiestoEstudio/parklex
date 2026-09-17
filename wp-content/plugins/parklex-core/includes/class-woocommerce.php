<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_WooCommerce {

	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'rename_downloads_to_presentations' ) );

		add_action( 'init', array( __CLASS__, 'add_submit_documents_endpoint' ) );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_submit_documents_menu_item' ) );
		add_action( 'woocommerce_account_submit-documents_endpoint', array( __CLASS__, 'render_submit_documents_content' ) );
	}

	/**
	 * Rename the "Downloads" My Account tab to "Presentations": it now shows a curated
	 * selection of Technical Card posts instead of WooCommerce downloadable products
	 * (see fields/options-technical-card.php and woocommerce/myaccount/downloads.php).
	 */
	public static function rename_downloads_to_presentations( $items ) {
		if ( isset( $items['downloads'] ) ) {
			$items['downloads'] = __( 'Presentations', 'parklex-core' );
		}

		return $items;
	}

	/**
	 * Register the "submit-documents" My Account endpoint. Requires a permalinks
	 * resave (Settings > Permalinks) once, to flush rewrite rules.
	 */
	public static function add_submit_documents_endpoint() {
		add_rewrite_endpoint( 'submit-documents', EP_PAGES );
	}

	/**
	 * Add the "Submit Documents" tab to the My Account menu, right before "Log out".
	 * Shows a second curated selection of Technical Card posts (see
	 * fields/options-technical-card.php and woocommerce/myaccount/submit-documents.php).
	 */
	public static function add_submit_documents_menu_item( $items ) {
		if ( ! isset( $items['customer-logout'] ) ) {
			$items['submit-documents'] = __( 'Submit Documents', 'parklex-core' );
			return $items;
		}

		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		$items['submit-documents'] = __( 'Submit Documents', 'parklex-core' );
		$items['customer-logout']  = $logout;

		return $items;
	}

	public static function render_submit_documents_content() {
		wc_get_template( 'myaccount/submit-documents.php' );
	}
}
