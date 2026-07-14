<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$image_id     = (int) ( $attributes['id'] ?? 0 );
$alt          = (string) ( $attributes['alt'] ?? '' );
$aspect_ratio = (string) ( $attributes['aspectRatio'] ?? '' );
$scale        = in_array( $attributes['scale'] ?? 'cover', [ 'cover', 'contain' ], true )
	? $attributes['scale']
	: 'cover';

if ( ! $image_id ) {
	return;
}

$img_style = $aspect_ratio ? 'aspect-ratio:' . $aspect_ratio . ';' : '';
$img_style .= 'object-fit:' . $scale . ';';
?>
<figure <?php echo bis_get_block_prop( $block ); ?>>
	<?php
	echo wp_get_attachment_image(
		$image_id,
		'full',
		false,
		[
			'class'   => 'b-zoom-image__img',
			'alt'     => $alt,
			'loading' => 'lazy',
			'style'   => $img_style,
		]
	);
	?>
</figure>
