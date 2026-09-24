<?php
/**
 * Download link (from the "downloads" file field) for a technical-card post.
 * Shared across card layouts.
 */
defined( 'ABSPATH' ) || exit;

$file = get_field( 'downloads' );

if ( empty( $file ) ) {
	return;
}

$extension = pathinfo( $file['filename'], PATHINFO_EXTENSION );
?>
<div class="c-technical-downloads">
	<a class="c-technical-download-link" href="<?php echo esc_url( $file['url'] ); ?>" download>
		<span class="c-technical-download-label"><?php echo esc_html( strtoupper( $extension ) ); ?></span>
	</a>
</div>
