<?php
/**
 * Download link (from the "downloads" file field) for a technical-card post.
 * Shared across card layouts.
 */
defined( 'ABSPATH' ) || exit;

// Read the raw value: on WPML translations the ACF field reference (_downloads) isn't always
// present, so get_field() can't format it and returns a raw string instead of the file array.
// The raw value may be an attachment ID or (imported content) a direct file URL.
$file = get_field( 'downloads', false, false );

if ( empty( $file ) ) {
	return;
}

if ( is_numeric( $file ) ) {
	// The attachment isn't always translated into the post's language in WPML;
	// fall back to the original ID when no translation exists.
	$file_id  = apply_filters( 'wpml_object_id', (int) $file, 'attachment', true );
	$file_url = wp_get_attachment_url( $file_id );
} else {
	$file_url = is_string( $file ) ? $file : '';
}

if ( empty( $file_url ) ) {
	return;
}

$extension = pathinfo( wp_parse_url( $file_url, PHP_URL_PATH ), PATHINFO_EXTENSION );
?>
<div class="c-technical-downloads">
	<a class="c-technical-download-link" href="<?php echo esc_url( $file_url ); ?>" download>
		<span class="c-technical-download-label"><?php echo esc_html( strtoupper( $extension ) ); ?></span>
	</a>
</div>
