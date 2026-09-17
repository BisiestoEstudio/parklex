<?php
/**
 * Presentations (My Account "Downloads" tab, repurposed).
 *
 * Shows a curated selection of Technical Card posts, picked in
 * Technical Cards > Ajustes > Presentations (ACF relationship field
 * "presentations_technical_cards"), instead of WooCommerce's native
 * downloadable-products list.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'templates/technical-card-list', null, array(
	'acf_field'    => 'presentations_technical_cards',
	'empty_notice' => __( 'No presentations available yet.', 'parklex-core' ),
) );
