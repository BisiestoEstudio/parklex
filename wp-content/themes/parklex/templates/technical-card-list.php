<?php
/**
 * Renders the Technical Card posts (same grid as the technical zone) of a classification term
 * picked via an ACF taxonomy field on the Technical Card options page (child
 * terms included). Shared by the "Presentations" and "Submit Documents" My
 * Account tabs.
 */
defined( 'ABSPATH' ) || exit;

$acf_field    = $args['acf_field'];
$empty_notice = $args['empty_notice'];
$term_id      = (int) get_field( $acf_field, 'option' );

if ( ! $term_id ) {
	wc_print_notice( esc_html( $empty_notice ), 'notice' );
	return;
}

$query = new WP_Query( array(
	'post_type'      => 'technical-card',
	'tax_query'      => array(
		array(
			'taxonomy' => 'classification_technical_card',
			'field'    => 'term_id',
			'terms'    => $term_id,
		),
	),
	'orderby'        => array(
		'menu_order' => 'ASC',
		'title'      => 'ASC',
	),
	'posts_per_page' => -1,
) );

if ( ! $query->have_posts() ) {
	wc_print_notice( esc_html( $empty_notice ), 'notice' );
	return;
}

$term = get_term( $term_id, 'classification_technical_card' );

get_template_part( 'templates/technical-classification', null, array(
	'active_classification' => $term && ! is_wp_error( $term ) ? $term->slug : '',
	'active_category'       => '',
	'query'                 => $query,
) );
