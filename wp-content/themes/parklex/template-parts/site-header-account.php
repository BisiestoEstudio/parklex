<?php
/**
 * Wholesale account links: My Account, Logout and Bag (cart count).
 * Rendered twice by site-header.php (top row on desktop, offset panel when collapsed); the
 * .inside-cart-wrap .inside-cart selector is refreshed by WooCommerce's cart fragments.
 *
 * @var array $args { modifier: string }
 */
defined( 'ABSPATH' ) || exit;

$modifier = $args['modifier'] ?? 'bar';
?>

<ul class="site-header__account site-header__account--<?php echo esc_attr( $modifier ); ?>">
	<li class="site-header__account-item">
		<a class="site-header__account-link" href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php esc_html_e( 'My Account', 'parklex' ); ?></a>
	</li>
	<li class="site-header__account-item">
		<a class="site-header__account-link" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Logout', 'parklex' ); ?></a>
	</li>
	<li class="site-header__account-item">
		<a class="site-header__account-link cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'parklex' ); ?>">
			<span class="qty-cart"><?php esc_html_e( 'Bag', 'parklex' ); ?></span>
			<span class="inside-cart-wrap">(<span class="inside-cart"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>)</span>
		</a>
	</li>
</ul>
