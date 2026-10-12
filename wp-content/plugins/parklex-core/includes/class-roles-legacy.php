<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
class Bis_Core_Roles_Legacy {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'hide_menus_sample_supervisor' ), 11 );
		add_action( 'template_redirect', array( __CLASS__, 'redirect_distributor_and_guests' ) );
	}

	/**
	 * Lock down the admin UI for the "sample_supervisor" role, leaving mainly WooCommerce → Orders visible.
	 */
	public static function hide_menus_sample_supervisor() {
		if ( ! current_user_can( 'sample_supervisor' ) ) {
			return;
		}

		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit.php?post_type=page' );
		remove_menu_page( 'edit.php?post_type=proyecto' );
		remove_menu_page( 'edit.php?post_type=products' );
		remove_menu_page( 'edit.php?post_type=product' );
		remove_menu_page( 'edit.php?post_type=technical-card' );

		remove_menu_page( 'wc-admin&path=/analytics/revenue' );
		remove_menu_page( 'wc-admin&path=/marketing' );

		remove_menu_page( 'index.php' ); // Dashboard + submenus
		remove_menu_page( 'edit-tags.php' ); // Dashboard + submenus
		remove_menu_page( 'about.php' ); // WordPress menu
		remove_submenu_page( 'index.php', 'update-core.php' ); // Update
		remove_menu_page( 'admin.php?page=theme-general-settings' ); // Pages

		// Hide "WooCommerce → Home".
		remove_submenu_page( 'woocommerce', 'wc-admin' );
		// Hide "WooCommerce → Customers".
		remove_submenu_page( 'woocommerce', 'wc-admin&path=/customers' );
		// Hide "WooCommerce → Reports".
		remove_submenu_page( 'woocommerce', 'wc-reports' );
		// Hide "WooCommerce → Settings".
		remove_submenu_page( 'woocommerce', 'wc-settings' );
		// Hide "WooCommerce → Status".
		remove_submenu_page( 'woocommerce', 'wc-status' );
		// Hide "WooCommerce → Extensions".
		remove_submenu_page( 'woocommerce', 'wc-addons' );

		remove_submenu_page( 'woocommerce', 'wpo_wcpdf_options_page' );

		// Hide "Products".
		remove_menu_page( 'edit.php?post_type=product' );
		// Hide "Products → All Products".
		remove_submenu_page( 'edit.php?post_type=product', 'edit.php?post_type=product' );
		// Hide "Products → Add New".
		remove_submenu_page( 'edit.php?post_type=product', 'post-new.php?post_type=product' );
		// Hide "Products → Categories".
		remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_cat&post_type=product' );
		// Hide "Products → Tags".
		remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&post_type=product' );
		// Hide "Products → Attributes".
		remove_submenu_page( 'edit.php?post_type=product', 'product_attributes' );

		// Hide "Analytics".
		remove_menu_page( 'wc-admin&path=/analytics/overview' );

		// Hide "Marketing".
		remove_menu_page( 'woocommerce-marketing' );

		remove_menu_page( 'edit-comments.php' ); // Comments
		remove_menu_page( 'plugins.php' ); // Plugins
		remove_menu_page( 'tools.php' ); // Tools

		remove_menu_page( 'upload.php' ); // Media
		remove_menu_page( 'themes.php' ); // Appearance
		remove_menu_page( 'options-general.php' ); // Settings

		remove_menu_page( 'sitepress-multilingual-cms/menu/languages.php' );

		remove_action( 'admin_bar_menu', 'wpseo_admin_bar_menu', 95 );
		remove_menu_page( 'wpseo_dashboard' );
	}

	/**
	 * Redirect distributor/sample_supervisor users to the "Samples" product category,
	 * and guests away from WooCommerce pages to the "My account" page.
	 */
	public static function redirect_distributor_and_guests() {
		if ( ( current_user_can( 'distributor' ) || current_user_can( 'sample_supervisor' ) ) && ( is_front_page() || is_shop() ) ) {
			wp_redirect( get_term_link( 'samples', 'product_cat' ) );
			exit();
		}

		if ( ! is_user_logged_in() && ( is_woocommerce() || is_shop() || is_cart() || is_checkout() || is_tax( 'product_cat' ) ) ) {
			wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
			exit();
		}
	}
}
