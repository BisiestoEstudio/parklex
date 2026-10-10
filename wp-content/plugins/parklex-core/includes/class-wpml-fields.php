<?php
defined( 'ABSPATH' ) || exit;

/**
 * Makes ACF fields that store IDs (attachments, posts, terms) resolve to the elements of
 * the post's own language.
 *
 * ACFML isn't installed, so the IDs that WPML copies to translations (see wpml-config.xml)
 * are still the original language's ones. Instead of depending on what was saved, IDs are
 * translated at read time with `wpml_object_id` — idempotent, so an ID that already belongs
 * to the right language is left untouched, and an element without a translation falls back
 * to the original.
 */
class Bis_Core_WPML_Fields {

	/**
	 * ACF field key => WPML element type of the IDs it stores.
	 */
	const FIELDS = array(
		'bisiesto_cpt_products_gallery'         => 'attachment',    // products: gallery
		'field_606ace2f3bd88'                   => 'products_type', // proyecto: project_info.product
		'field_6086728a3878b'                   => 'products',      // proyecto: project_info.related_product
		'bisiesto_cpt_technical_card_downloads' => 'attachment',    // technical-card: downloads
		'bisiesto_cpt_technical_card_image'     => 'attachment',    // technical-card: image
	);

	public static function init() {
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return;
		}

		foreach ( array_keys( self::FIELDS ) as $field_key ) {
			// load_value gets the raw stored IDs, before ACF formats them into objects.
			add_filter( "acf/load_value/key={$field_key}", array( __CLASS__, 'translate_ids' ), 10, 3 );
		}
	}

	public static function translate_ids( $value, $post_id, $field ) {
		if ( empty( $value ) || ! is_numeric( $post_id ) ) {
			return $value;
		}

		$element_type = self::FIELDS[ $field['key'] ];
		$language     = self::get_post_language( (int) $post_id );

		if ( ! $language ) {
			return $value;
		}

		$translate = function ( $id ) use ( $element_type, $language ) {
			return (int) apply_filters( 'wpml_object_id', (int) $id, $element_type, true, $language );
		};

		return is_array( $value ) ? array_map( $translate, $value ) : $translate( $value );
	}

	/**
	 * The post's language rather than the current request's, so it's also right in admin
	 * screens listing every language.
	 */
	private static function get_post_language( $post_id ) {
		$post_type = get_post_type( $post_id );

		if ( ! $post_type ) {
			return null;
		}

		return apply_filters(
			'wpml_element_language_code',
			null,
			array(
				'element_id'   => $post_id,
				'element_type' => 'post_' . $post_type,
			)
		);
	}
}
