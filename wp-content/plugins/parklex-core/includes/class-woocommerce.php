<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_WooCommerce {

	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'rename_downloads_to_presentations' ) );

		// Registered directly here (init() is itself already an 'init' callback) rather
		// than via a nested add_action('init', ...): a callback added to the SAME priority
		// (10) of a hook that is currently executing that same priority never runs — WP_Hook
		// already took a snapshot of that priority's callback list for the current pass.
		// The rewrite rule for this endpoint was missing from the compiled rewrite_rules
		// option because of this; confirmed empirically, found while debugging the
		// analogous "lunch-learn" endpoint (see Bis_Core_Lunch_Learn::init()).
		add_rewrite_endpoint( 'submit-documents', EP_PAGES );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_submit_documents_menu_item' ) );
		add_action( 'woocommerce_account_submit-documents_endpoint', array( __CLASS__, 'render_submit_documents_content' ) );

		add_filter( 'woocommerce_order_number', array( __CLASS__, 'legacy_order_number' ), 10, 2 );
		add_filter( 'woocommerce_shop_order_search_fields', array( __CLASS__, 'add_legacy_order_number_search_field' ) );
	}

	/**
	 * Orders migrated from the old site (2026-10-15) whose original ID was already taken
	 * on the new site were created with a new ID and keep the old one in `_legacy_order_id`.
	 * Customers already have that number in their emails and invoices, so it stays the
	 * visible order number (admin, My Account, emails, PDF invoices).
	 */
	public static function legacy_order_number( $number, $order ) {
		if ( $order instanceof WC_Order ) {
			$legacy = $order->get_meta( '_legacy_order_id' );
			if ( $legacy ) {
				return (string) $legacy;
			}
		}

		return $number;
	}

	/**
	 * Let the admin order search find migrated orders by their old number.
	 */
	public static function add_legacy_order_number_search_field( $fields ) {
		$fields[] = '_legacy_order_id';

		return $fields;
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
