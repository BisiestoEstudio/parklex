<?php
defined( 'ABSPATH' ) || exit;

/**
 * Makes the images of a translated "products" post resolve to the attachments of that
 * post's own language.
 *
 * ACFML isn't installed, so nothing maps the attachment IDs stored in the ACF `gallery`
 * field. Instead of depending on what was saved, IDs are translated at read time with
 * `wpml_object_id` — idempotent, so an ID that already belongs to the right language is
 * left untouched, and an attachment without a translation falls back to the original.
 */
class Bis_Core_WPML_Media {

	const POST_TYPES = array( 'products' );

	public static function init() {
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return;
		}

		add_filter( 'acf/format_value/name=gallery', array( __CLASS__, 'translate_gallery_ids' ), 10, 3 );
	}

	public static function translate_gallery_ids( $value, $post_id, $field ) {
		$post = is_numeric( $post_id ) ? get_post( (int) $post_id ) : null;

		if ( ! $post || empty( $value ) || ! is_array( $value ) || ! in_array( $post->post_type, self::POST_TYPES, true ) ) {
			return $value;
		}

		return array_map(
			function ( $attachment_id ) use ( $post ) {
				return self::translate_attachment_id( (int) $attachment_id, $post );
			},
			$value
		);
	}

	/**
	 * Maps to the post's language rather than the current request's, so it's also right
	 * in admin screens listing every language.
	 */
	private static function translate_attachment_id( $attachment_id, $post ) {
		$language = apply_filters(
			'wpml_element_language_code',
			null,
			array(
				'element_id'   => $post->ID,
				'element_type' => 'post_' . $post->post_type,
			)
		);

		return (int) apply_filters( 'wpml_object_id', $attachment_id, 'attachment', true, $language );
	}
}
