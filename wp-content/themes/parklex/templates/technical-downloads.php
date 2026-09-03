<?php
/**
 * Download links (from the "downloads" repeater) for a technical-card post.
 * Shared across card layouts.
 */
defined( 'ABSPATH' ) || exit;

$downloads = get_field( 'downloads' );

if ( empty( $downloads ) ) {
	return;
}
?>
<ul class="c-technical-downloads">
	<?php foreach ( $downloads as $download ) : ?>
		<?php $file = $download['file'] ?? null; ?>
		<?php if ( $file ) : ?>
			<?php $extension = pathinfo( $file['filename'], PATHINFO_EXTENSION ); ?>
			<li class="c-technical-download">
				<a class="c-technical-download-link" href="<?php echo esc_url( $file['url'] ); ?>" download>
					<span class="c-technical-download-label"><?php echo esc_html( strtoupper( $extension ) ); ?></span>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
