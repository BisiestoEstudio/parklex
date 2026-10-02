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

/**
 * URL of the blog archive (the static "Posts page" if one is set in
 * Settings > Reading, otherwise the site root).
 */
function bis_theme_get_blog_archive_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );

	return $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/' );
}

/**
 * Extracts a nested h2/h3 table of contents out of already-filtered post
 * content, and returns the content with matching anchor ids applied so the
 * TOC links actually resolve. A heading's own "HTML anchor" (block editor
 * advanced setting) is kept as-is; ids are only generated for headings that
 * don't already have one.
 *
 * @param string $content Content already run through the `the_content` filter.
 * @return array{toc: array, content: string}
 */
function bis_theme_build_toc( $content ) {
	if ( false === stripos( $content, '<h2' ) && false === stripos( $content, '<h3' ) ) {
		return array(
			'toc'     => array(),
			'content' => $content,
		);
	}

	$wrapper_id = 'bis-theme-toc-root';

	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML(
		'<?xml encoding="utf-8" ?><div id="' . $wrapper_id . '">' . $content . '</div>',
		LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();

	$headings = ( new DOMXPath( $dom ) )->query( '//h2 | //h3' );

	if ( 0 === $headings->length ) {
		return array(
			'toc'     => array(),
			'content' => $content,
		);
	}

	$toc          = array();
	$used_ids     = array();
	$last_h2_index = null;

	foreach ( $headings as $heading ) {
		$text = trim( $heading->textContent );

		if ( '' === $text ) {
			continue;
		}

		$id = $heading->getAttribute( 'id' );

		if ( '' === $id ) {
			$id = sanitize_title( $text );

			if ( isset( $used_ids[ $id ] ) ) {
				$id .= '-' . ++$used_ids[ $id ];
			} else {
				$used_ids[ $id ] = 1;
			}

			$heading->setAttribute( 'id', $id );
		}

		$item = array(
			'id'       => $id,
			'text'     => $text,
			'children' => array(),
		);

		if ( 'h2' === $heading->nodeName || null === $last_h2_index ) {
			$toc[]         = $item;
			$last_h2_index = count( $toc ) - 1;
		} else {
			$toc[ $last_h2_index ]['children'][] = $item;
		}
	}

	$wrapper = $dom->getElementById( $wrapper_id );
	$html    = '';

	foreach ( $wrapper->childNodes as $node ) {
		$html .= $dom->saveHTML( $node );
	}

	return array(
		'toc'     => $toc,
		'content' => $html,
	);
}

