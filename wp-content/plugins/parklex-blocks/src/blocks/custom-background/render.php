<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$video      = $attributes['video'] ?? array();
$video_url  = $video['url'] ?? '';
$poster_id  = $video['posterId'] ?? 0;
$poster_url = $poster_id ? wp_get_attachment_image_url( $poster_id, 'full' ) : '';

if ( ! $video_url ) {
	return;
}
?>
<div <?php echo bis_get_block_prop( $block, false ); ?>>
	<video
		class="b-custom-background__video"
		autoplay muted loop playsinline
		<?php echo $poster_url ? 'poster="' . esc_url( $poster_url ) . '"' : ''; ?>
	>
		<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
	</video>
</div>
