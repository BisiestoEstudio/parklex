<?php
/**
 * Wholesale (distributor / sample_supervisor) top bar: My Account, Logout, Bag.
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// TODO: quitar 'administrator' de esta condicion cuando se termine de probar el menu wholesale.
$is_wholesale_user = is_user_logged_in() && ( current_user_can( 'distributor' ) || current_user_can( 'sample_supervisor' ) || current_user_can( 'administrator' ) );

if ( ! $is_wholesale_user ) {
	return;
}
?>

<ul class="add-menu">
	<li><a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'My Account', 'parklex' ); ?></a></li>
	<li><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Logout', 'parklex' ); ?></a></li>
	<li>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'parklex' ); ?>">
			<span class="qty-cart"><?php esc_html_e( 'Cart ', 'parklex' ); ?></span><span class="inside-cart-wrap">(<span class="inside-cart"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>)</span>
		</a>
	</li>
</ul>
