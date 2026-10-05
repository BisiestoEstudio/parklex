<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$media      = $attributes['media'] ?? array();
$media_type = $media['mediaType'] ?? 'image';
$image_id   = (int) ( $media['imageId'] ?? 0 );
$video_url  = trim( (string) ( $media['videoUrl'] ?? '' ) );

$focal_point = $attributes['focalPoint'] ?? [ 'x' => 0.5, 'y' => 0.5 ];
$focal_x     = (float) ( $focal_point['x'] ?? 0.5 );
$focal_y     = (float) ( $focal_point['y'] ?? 0.5 );
$object_fit = in_array( $attributes['objectFit'] ?? 'cover', [ 'cover', 'contain' ], true )
	? $attributes['objectFit']
	: 'cover';

$media_style = 'object-position:' . ( $focal_x * 100 ) . '% ' . ( $focal_y * 100 ) . '%;object-fit:' . $object_fit . ';';

$video_position = bis_get_alignment_matrix_xy( $attributes['videoPosition'] ?? 'center' );
$video_anchor_style = 'top:' . $video_position['y'] . '%;left:' . $video_position['x'] . '%;transform:translate(-' . $video_position['x'] . '%,-' . $video_position['y'] . '%);';
$video_object_style = 'object-position:' . $video_position['x'] . '% ' . $video_position['y'] . '%;object-fit:cover;';

$overlay_color   = $attributes['overlayColor'] ?? '';
$overlay_opacity = max( 0, min( 100, (int) ( $attributes['overlayOpacity'] ?? 50 ) ) );

if ( $media_type !== 'video' && ! $image_id ) {
	return;
}

if ( $media_type === 'video' && ! $video_url ) {
	return;
}

$vimeo = $media_type === 'video' ? bis_get_vimeo_id( $video_url ) : null;
?>
<div <?php echo bis_get_block_prop( $block, false ); ?>>
	<?php if ( $media_type === 'video' && $vimeo ) : ?>
		<div class="b-custom-background__video-wrap">
			<iframe
				class="b-custom-background__video"
				style="<?php echo esc_attr( $video_anchor_style ); ?>"
				src="<?php echo esc_url( bis_get_vimeo_embed_url( $vimeo ) ); ?>"
				title="<?php esc_attr_e( 'Vídeo de fondo', 'parklex-blocks' ); ?>"
				loading="lazy"
				allow="autoplay; fullscreen; picture-in-picture"
				tabindex="-1"
				aria-hidden="true"
			></iframe>
		</div>
	<?php elseif ( $media_type === 'video' ) : ?>
		<video
			class="b-custom-background__video"
			style="<?php echo esc_attr( $video_object_style ); ?>"
			autoplay muted loop playsinline
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
