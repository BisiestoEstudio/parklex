<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$media      = $attributes['media'] ?? array();
$media_type = $media['mediaType'] ?? 'image';
$image_id   = (int) ( $media['imageId'] ?? 0 );
$video_url  = trim( (string) ( $media['videoUrl'] ?? '' ) );
$poster_id  = (int) ( $media['posterId'] ?? 0 );
$poster_url = $poster_id ? wp_get_attachment_image_url( $poster_id, 'full' ) : '';

$focal_point = $attributes['focalPoint'] ?? [ 'x' => 0.5, 'y' => 0.5 ];
$focal_x     = (float) ( $focal_point['x'] ?? 0.5 );
$focal_y     = (float) ( $focal_point['y'] ?? 0.5 );
$object_fit = in_array( $attributes['objectFit'] ?? 'cover', [ 'cover', 'contain' ], true )
	? $attributes['objectFit']
	: 'cover';

$media_style = 'object-position:' . ( $focal_x * 100 ) . '% ' . ( $focal_y * 100 ) . '%;object-fit:' . $object_fit . ';';

$overlay_color   = $attributes['overlayColor'] ?? '';
$overlay_opacity = max( 0, min( 100, (int) ( $attributes['overlayOpacity'] ?? 50 ) ) );

if ( $media_type !== 'video' && ! $image_id ) {
	return;
}

if ( $media_type === 'video' && ! $video_url ) {
	return;
}
?>
<div <?php echo bis_get_block_prop( $block, false ); ?>>
	<?php if ( $media_type === 'video' ) : ?>
		<video
			class="b-custom-background__video"
			style="<?php echo esc_attr( $media_style ); ?>"
			autoplay muted loop playsinline
			<?php echo $poster_url ? 'poster="' . esc_url( $poster_url ) . '"' : ''; ?>
		>
			<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
		</video>
	<?php else : ?>
		<?php echo wp_get_attachment_image( $image_id, 'full', false, [ 'class' => 'b-custom-background__image', 'loading' => 'lazy', 'style' => $media_style ] ); ?>
	<?php endif; ?>

	<?php if ( $overlay_color ) : ?>
		<span
			class="b-custom-background__overlay"
			style="background-color:<?php echo esc_attr( $overlay_color ); ?>;opacity:<?php echo esc_attr( $overlay_opacity / 100 ); ?>;"
		></span>
	<?php endif; ?>
</div>
