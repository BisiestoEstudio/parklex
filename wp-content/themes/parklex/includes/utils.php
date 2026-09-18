<?php
defined( 'ABSPATH' ) || exit;
/**
 * Función para debuggear. Saca los datos en un formato más legible.
 */
function bis_debug($datos){
    echo '<pre>';
    print_r($datos);
    echo '</pre>';
}

/**
 * Find the published page using a given page template file, if any.
 */
function bis_theme_get_page_by_template( $template ) {
	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => $template,
	) );

	return $pages ? $pages[0] : 0;
}

