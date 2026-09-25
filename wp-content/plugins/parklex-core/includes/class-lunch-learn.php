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

		add_filter( 'manage_lunch_learn_request_posts_columns', array( __CLASS__, 'admin_columns' ) );
		add_action( 'manage_lunch_learn_request_posts_custom_column', array( __CLASS__, 'render_admin_column' ), 10, 2 );
		add_filter( 'manage_edit-lunch_learn_request_sortable_columns', array( __CLASS__, 'sortable_admin_columns' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'sort_admin_columns_query' ) );

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

	/**
	 * Admin list table columns for the "Lunch & Learn requests" screen (wp-admin > Lunch &
	 * Learn requests) — replaces the Admin Columns Pro screen config the old site relied on
	 * (never migrated: it's plugin UI config, not application data), so the wp-admin list
	 * doesn't depend on that plugin staying installed.
	 */
	public static function admin_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;

			if ( 'title' === $key ) {
				$new_columns['request_id'] = __( 'Request ID', 'parklex-core' );
				$new_columns['user']       = __( 'User', 'parklex-core' );
				$new_columns['firm']       = __( 'Architectural / Interior Firm', 'parklex-core' );
				$new_columns['invoices']   = __( 'Invoices', 'parklex-core' );
				$new_columns['complited']  = __( 'Complited', 'parklex-core' );
				$new_columns['event_date'] = __( 'Date', 'parklex-core' );
			}
		}

		// Replace the native "Date" column (post published date) with our own — the
		// screenshot's "Date" column is the event date, not the post's publish date.
		unset( $new_columns['date'] );

		return $new_columns;
	}

	public static function render_admin_column( $column, $post_id ) {
		switch ( $column ) {
			case 'request_id':
				echo '#' . (int) $post_id;
				break;

			case 'user':
				$user_id = get_field( 'user_id', $post_id );
				$user    = $user_id ? get_userdata( $user_id ) : false;
				echo $user ? esc_html( trim( $user->first_name . ' ' . $user->last_name ) ?: $user->user_login ) : '—';
				break;

			case 'firm':
				echo esc_html( get_field( 'design-firm-request', $post_id ) ?: '—' );
				break;

			case 'invoices':
				echo get_field( 'distributor_approved', $post_id ) ? '✅' : '❌';
				break;

			case 'complited':
				echo get_field( 'complited_lrequest', $post_id ) ? '✅' : '❌';
				break;

			case 'event_date':
				echo esc_html( get_field( 'date-request', $post_id ) ?: '—' );
				break;
		}
	}

	public static function sortable_admin_columns( $columns ) {
		$columns['request_id'] = 'ID';
		$columns['invoices']   = 'invoices';
		$columns['complited']  = 'complited';
		$columns['event_date'] = 'event_date';

		return $columns;
	}

	/**
	 * ACF meta-backed sorting for the "Invoices" / "Complited" / "Date" columns above
	 * ("Request ID" sorts natively via orderby=ID, no meta lookup needed).
	 */
	public static function sort_admin_columns_query( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || 'lunch_learn_request' !== $query->get( 'post_type' ) ) {
			return;
		}

		$meta_orderby = array(
			'invoices'   => array( 'distributor_approved', 'meta_value_num' ),
			'complited'  => array( 'complited_lrequest', 'meta_value_num' ),
			'event_date' => array( 'date-request', 'meta_value' ),
		);

		$orderby = $query->get( 'orderby' );

		if ( isset( $meta_orderby[ $orderby ] ) ) {
			list( $meta_key, $orderby_type ) = $meta_orderby[ $orderby ];
			$query->set( 'meta_key', $meta_key );
			$query->set( 'orderby', $orderby_type );
		}
	}
}
