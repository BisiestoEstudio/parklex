<?php
/**
 * Shared body template for all 6 Lunch & Learn emails (Bis_Core_Email_Lunch_Learn_*_Legacy).
 * The admin-configured "content_email" text (wp-admin > WooCommerce > Settings > Emails) is
 * plain text with placeholder tokens — same mechanism as the original theme.
 *
 * @var WC_Email $email
 * @var string   $email_heading
 */
defined( 'ABSPATH' ) || exit;

$replacements = array(
	'{request_id}'           => $email->object['request_id'],
	'{user_name}'             => $email->object['user_name'],
	'{user_email}'            => $email->object['user_email'],
	'{request_name}'          => $email->object['request_name'],
	'{admin_link}'            => $email->object['admin_link'],
	'{invoice_account_link}'  => $email->object['invoice_account_link'],
	'{request_details}'       => $email->object['request_details'],
	'{site_title}'            => get_bloginfo( 'name' ),
	PHP_EOL                   => '<br>',
);

do_action( 'woocommerce_email_header', $email_heading, $email );

echo wp_kses_post( str_replace( array_keys( $replacements ), $replacements, $email->object['content'] ) );

do_action( 'woocommerce_email_footer', $email );
