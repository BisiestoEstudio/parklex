<?php
defined( 'ABSPATH' ) || exit;

/**
 * Redesigned front-end for "Lunch & Learn" (see docs/lunch-and-learn.md): the public
 * request form and the My Account area are rebuilt in vanilla JS with real template parts,
 * on top of the tal-cual-migrated business logic in Bis_Core_Lunch_Learn_Legacy.
 */
class Bis_Core_Lunch_Learn {

	const SUBMIT_PAGE_TEMPLATE = 'page-request-lunch-learn.php';

	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'gate_frontend_access' ) );

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Registered directly here (init() is itself already an 'init' callback) rather
		// than via a nested add_action('init', ...) — the rewrite rule for the endpoint
		// was missing from the compiled rewrite_rules option with the nested version.
		add_rewrite_endpoint( 'lunch-learn', EP_PAGES );

		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_my_account_menu_item' ) );
		add_action( 'woocommerce_account_lunch-learn_endpoint', array( __CLASS__, 'render_my_account_content' ) );
	}

	/**
	 * Blocks the public request page for logged-out users and users without the
	 * "allow_ll_request" permission — same gate pattern as Bis_Core_Internal_Projects.
	 */
	public static function gate_frontend_access() {
		if ( ! is_page_template( self::SUBMIT_PAGE_TEMPLATE ) ) {
			return;
		}

		if ( ! is_user_logged_in() || ! get_field( 'allow_ll_request', 'user_' . get_current_user_id() ) ) {
			nocache_headers();
			wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
			exit;
		}
	}

	/**
	 * Adds "Lunch & Learn requests" to the My Account menu, right before "Log out" — only
	 * for users with the "allow_ll_request" permission (same gate as the front-end).
	 */
	public static function add_my_account_menu_item( $items ) {
		if ( ! get_field( 'allow_ll_request', 'user_' . get_current_user_id() ) ) {
			return $items;
		}

		if ( ! isset( $items['customer-logout'] ) ) {
			$items['lunch-learn'] = __( 'Lunch & Learn requests', 'parklex-core' );
			return $items;
		}

		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		$items['lunch-learn']     = __( 'Lunch & Learn requests', 'parklex-core' );
		$items['customer-logout'] = $logout;

		return $items;
	}

	/**
	 * Renders either the request list or (with ?lrequest-id=) a single request's detail —
	 * own template parts in the theme, replacing the original's echoed PHP strings.
	 */
	public static function render_my_account_content() {
		if ( ! get_field( 'allow_ll_request', 'user_' . get_current_user_id() ) ) {
			return;
		}

		$request_id = ! empty( $_GET['lrequest-id'] ) ? absint( $_GET['lrequest-id'] ) : 0;

		if ( $request_id
			&& 'lunch_learn_request' === get_post_type( $request_id )
			&& absint( get_field( 'user_id', $request_id ) ) === get_current_user_id()
		) {
			wc_get_template( 'myaccount/lunch-learn/single-request.php', array( 'request_id' => $request_id ) );
			return;
		}

		wc_get_template( 'myaccount/lunch-learn/request-list.php', array(
			'request_ids' => self::get_user_request_ids(),
		) );
	}

	public static function get_user_request_ids() {
		return get_posts( array(
			'post_type'      => 'lunch_learn_request',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => 'user_id',
			'meta_value'     => get_current_user_id(),
		) );
	}
}
