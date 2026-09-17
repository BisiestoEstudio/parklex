<?php
/**
 * Submit Documents (My Account tab).
 *
 * Shows a second curated selection of Technical Card posts, picked in
 * Technical Cards > Ajustes > Submit Documents (ACF relationship field
 * "submit_documents_technical_cards").
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'templates/technical-card-list', null, array(
	'acf_field'    => 'submit_documents_technical_cards',
	'empty_notice' => __( 'No documents available yet.', 'parklex-core' ),
) );
