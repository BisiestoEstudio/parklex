<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$image             = $attributes['image'] ?? 0;
$background_image  = $attributes['backgroundImage'] ?? 0;
$description       = $attributes['description'] ?? '';
$overlay_color     = $attributes['overlayColor'] ?? '';
$overlay_opacity   = max( 0, min( 100, (int) ( $attributes['overlayOpacity'] ?? 50 ) ) );
$link              = $attributes['link'] ?? [];
$link_url          = $link['url'] ?? '';

$background_url = $background_image ? wp_get_attachment_image_url( $background_image, 'large' ) : '';

$style = '';
if ( $background_url ) {
	$style .= '--click-card-bg-image:url(' . esc_url( $background_url ) . ');';
}
if ( $overlay_color ) {
	$style .= '--click-card-overlay-color:' . esc_attr( $overlay_color ) . ';--click-card-overlay-opacity:' . esc_attr( $overlay_opacity / 100 ) . ';';
}

$tag   = $link_url ? 'a' : 'div';
$attrs = $link_url ? bis_get_link_attributes( $link ) : [];
if ( $style ) {
	$attrs['style'] = $style;
}
?>

<<?php echo $tag; ?> <?php echo bis_get_block_prop( $block, false, $attrs ); ?>>
	<div class="b-click-card__media">
		<?php if ( $image ) : ?>
			<div class="b-click-card__front">
				<?php echo wp_get_attachment_image( $image, 'large', false, [ 'class' => 'b-click-card__front-img' ] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $background_url ) : ?>
			<span class="b-click-card__background"></span>
		<?php endif; ?>

		<?php if ( $overlay_color ) : ?>
			<span class="b-click-card__overlay"></span>
		<?php endif; ?>

		<?php if ( $description ) : ?>
			<div class="b-click-card__description">
				<p><?php echo wp_kses_post( $description ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<div class="b-click-card__content">
		<?php echo $content; ?>
	</div>
</<?php echo $tag; ?>>
