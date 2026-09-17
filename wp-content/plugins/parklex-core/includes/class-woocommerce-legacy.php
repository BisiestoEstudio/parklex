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

		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'cart_count_fragment' ) );

		add_action( 'add_meta_boxes', array( __CLASS__, 'add_shipping_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'save_shipping_meta_box' ), 20, 1 );

		add_action( 'woocommerce_after_order_notes', array( __CLASS__, 'add_project_name_field' ) );
		add_action( 'woocommerce_checkout_process', array( __CLASS__, 'validate_project_name_field' ) );
		add_action( 'woocommerce_checkout_update_order_meta', array( __CLASS__, 'save_project_name_field' ) );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( __CLASS__, 'render_project_name_admin_field' ) );
		add_action( 'woocommerce_process_shop_order_meta', array( __CLASS__, 'save_project_name_admin_field' ), 45, 2 );

		add_filter( 'woocommerce_available_variation', array( __CLASS__, 'apply_distributor_max_qty_to_variation' ) );
		add_filter( 'woocommerce_quantity_input_args', array( __CLASS__, 'apply_distributor_max_qty_to_quantity_input' ), 10, 2 );

		add_filter( 'woocommerce_my_account_my_orders_columns', array( __CLASS__, 'add_my_orders_columns' ) );
		add_action( 'woocommerce_my_account_my_orders_column_project-name', array( __CLASS__, 'render_my_orders_project_name_column' ) );
		add_action( 'woocommerce_my_account_my_orders_column_courier', array( __CLASS__, 'render_my_orders_courier_column' ) );

		add_filter( 'woocommerce_email_recipient_new_order', array( __CLASS__, 'add_new_order_email_recipient_for_spain' ), 10, 2 );
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

	/**
	 * Keep the header cart count in sync via WooCommerce's own native fragment refresh
	 * (wc-cart-fragments), no custom AJAX endpoint needed.
	 */
	public static function cart_count_fragment( $fragments ) {
		$fragments['.inside-cart-wrap .inside-cart'] = '<span class="inside-cart">' . WC()->cart->get_cart_contents_count() . '</span>';
		return $fragments;
	}

	/**
	 * Add a "Shipping details" meta box (courier + cost) to the order edit page.
	 */
	public static function add_shipping_meta_box() {
		add_meta_box(
			'shipping',
			__( 'Shipping details', 'parklex-core' ),
			array( __CLASS__, 'render_shipping_meta_box' ),
			'shop_order',
			'side'
		);
	}

	public static function render_shipping_meta_box( $post ) {
		wp_nonce_field( 'bis_core_save_shipping_meta_box', 'bis_core_shipping_meta_box_nonce' );

		echo '<p>Courier: <input type="text" style="width:100%" id="shipping_courier" name="shipping_courier" value="' . esc_attr( get_post_meta( $post->ID, 'shipping_courier', true ) ) . '" /></p>';
		echo '<p>Cost: <input type="text" style="width:100%" id="shipping_cost" name="shipping_costs" value="' . esc_attr( get_post_meta( $post->ID, 'shipping_costs', true ) ) . '" /></p>';
	}

	public static function save_shipping_meta_box( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['bis_core_shipping_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['bis_core_shipping_meta_box_nonce'], 'bis_core_save_shipping_meta_box' ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'shop_order' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_shop_order', $post_id ) ) {
				return;
			}
		}

		if ( isset( $_POST['shipping_costs'] ) ) {
			update_post_meta( $post_id, 'shipping_costs', sanitize_text_field( $_POST['shipping_costs'] ) );
		}

		if ( isset( $_POST['shipping_courier'] ) ) {
			update_post_meta( $post_id, 'shipping_courier', sanitize_text_field( $_POST['shipping_courier'] ) );
		}
	}

	/**
	 * Add a required "Project Name" field to the checkout.
	 */
	public static function add_project_name_field( $checkout ) {
		woocommerce_form_field( 'project_name', array(
			'type'     => 'text',
			'required' => true,
			'class'    => array( 'form-row-wide' ),
			'label'    => __( 'Project Name', 'parklex-core' ),
		), $checkout->get_value( 'project_name' ) );
	}

	public static function validate_project_name_field() {
		if ( ! isset( $_POST['project_name'] ) || ! $_POST['project_name'] ) {
			wc_add_notice( __( 'You must enter a Project Name.', 'parklex-core' ), 'error' );
		}
	}

	public static function save_project_name_field( $order_id ) {
		if ( ! empty( $_POST['project_name'] ) ) {
			update_post_meta( $order_id, 'project_name', sanitize_text_field( $_POST['project_name'] ) );
		}
	}

	/**
	 * Show the "Project Name" field as editable in the admin order edit screen.
	 */
	public static function render_project_name_admin_field( $order ) {
		$project_name = get_post_meta( $order->get_id(), 'project_name', true );

		echo '<p><strong>' . __( 'Project Name', 'parklex-core' ) . ':</strong><br>';
		echo '<input type="text" name="project_name" value="' . esc_attr( $project_name ) . '" style="width:100%;" /></p>';
	}

	public static function save_project_name_admin_field( $order_id, $post ) {
		if ( isset( $_POST['project_name'] ) ) {
			update_post_meta( $order_id, 'project_name', sanitize_text_field( $_POST['project_name'] ) );
		}
	}

	/**
	 * Limit the max quantity of a variation for "distributor" users.
	 */
	public static function apply_distributor_max_qty_to_variation( $args ) {
		$product = wc_get_product( $args['variation_id'] );

		if ( current_user_can( 'distributor' ) ) {
			$args['max_value'] = self::get_distributor_max_qty( $product->get_parent_id() );
		}

		return $args;
	}

	/**
	 * Limit the max quantity in the product quantity input for "distributor" users.
	 */
	public static function apply_distributor_max_qty_to_quantity_input( $args, $product ) {
		if ( current_user_can( 'distributor' ) ) {
			$args['max_value'] = self::get_distributor_max_qty( $product->get_id() );
		}

		return $args;
	}

	/**
	 * Resolve the max quantity for the current distributor user: global option value
	 * (fallback 20) overridden by a per-product value. NOTE: a per-user value also
	 * exists (distributor_max_qty on the user) but, same as in the original code,
	 * it is read and never actually applied.
	 */
	private static function get_distributor_max_qty( $product_id ) {
		$max_qty_global  = get_field( 'distributor_max_qty', 'option' );
		$max_qty_product = get_field( 'distributor_max_qty', $product_id );
		$max_qty_user    = get_field( 'distributor_max_qty', 'user_' . get_current_user_id() );

		if ( ! $max_qty_global ) {
			$max_qty_global = 20;
		}

		$max_qty = $max_qty_global;

		if ( $max_qty_product ) {
			$max_qty = $max_qty_product;
		}

		if ( $max_qty_user ) {
			$max_qty = $max_qty_global;
		}

		return $max_qty;
	}

	/**
	 * Add "Project name" and "Courier" columns to the My Account > Orders table,
	 * right before the "Total" column, preserving any other columns already present.
	 */
	public static function add_my_orders_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $label ) {
			if ( 'order-total' === $key ) {
				$new_columns['project-name'] = __( 'Project name', 'parklex-core' );
				$new_columns['courier']      = __( 'Courier', 'parklex-core' );
			}
			$new_columns[ $key ] = $label;
		}

		return $new_columns;
	}

	public static function render_my_orders_project_name_column( $order ) {
		echo esc_html( get_post_meta( $order->get_id(), 'project_name', true ) ?: '—' );
	}

	public static function render_my_orders_courier_column( $order ) {
		echo esc_html( get_post_meta( $order->get_id(), 'shipping_courier', true ) ?: '—' );
	}

	/**
	 * CC the "new order" admin notification to a fixed recipient when the order ships to Spain.
	 */
	public static function add_new_order_email_recipient_for_spain( $recipient, $order ) {
		if ( ! $order instanceof WC_Order ) {
			return $recipient;
		}

		if ( 'ES' === $order->get_shipping_country() ) {
			$recipient .= ', ingrid.iribas@parklexprodema.com';
		}

		return $recipient;
	}
}
