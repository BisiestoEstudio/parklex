<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
class Bis_Core_WooCommerce_Legacy {

	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'woocommerce_show_page_title', array( __CLASS__, 'hide_page_title' ) );

		add_filter( 'gettext', array( __CLASS__, 'change_cart_string' ), 100, 3 );
		add_filter( 'ngettext', array( __CLASS__, 'change_cart_string' ), 100, 3 );
		add_filter( 'add_to_cart_text', array( __CLASS__, 'custom_single_add_to_cart_text' ) );
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( __CLASS__, 'custom_single_add_to_cart_text' ) );
	}

	/**
	 * Hide the automatic WooCommerce page title (shop/category/cart/checkout)
	 */
	public static function hide_page_title() {
		return false;
	}

	/**
	 * Replace "Cart" with "Bag" in any translated string
	 */
	public static function change_cart_string( $translated_text, $text, $domain ) {
		$translated_text = str_replace( 'cart', 'bag', $translated_text );
		$translated_text = str_replace( 'Cart', 'Bag', $translated_text );
		$translated_text = str_replace( 'View Cart', 'View Bag', $translated_text );
		return $translated_text;
	}

	/**
	 * Set the single product "Add to cart" button text
	 */
	public static function custom_single_add_to_cart_text() {
		return __( 'Add to bag', 'woocommerce' );
	}
}
