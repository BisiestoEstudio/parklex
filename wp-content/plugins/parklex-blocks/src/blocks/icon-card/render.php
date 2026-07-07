<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$icon            = $attributes['icon'] ?? 0;
$title           = $attributes['title'] ?? '';
$description     = $attributes['description'] ?? '';
$overlay_color   = $attributes['overlayColor'] ?? '#020202';
$overlay_opacity = max( 0, min( 100, (int) ( $attributes['overlayOpacity'] ?? 40 ) ) );

$style = '--icon-card-overlay-color:' . esc_attr( $overlay_color ) . ';--icon-card-overlay-opacity:' . esc_attr( $overlay_opacity / 100 ) . ';';
?>

<div <?php echo bis_get_block_prop( $block, false, [ 'style' => $style ] ); ?>>
	<span class="b-icon-card__overlay"></span>
	<span class="b-icon-card__mask"></span>

	<div class="b-icon-card__content">
		<?php if ( $icon ) : ?>
			<div class="b-icon-card__icon">
				<?php echo wp_get_attachment_image( $icon, 'thumbnail', false, [ 'class' => 'b-icon-card__icon-img' ] ); ?>
			</div>
		<?php endif; ?>
		<div class="b-icon-card__text">

		<?php if ( $title ) : ?>
			<h3 class="b-icon-card__title"><?php echo wp_kses_post( $title ); ?></h3>
		<?php endif; ?>

		<?php if ( $description ) : ?>
			<div class="b-icon-card__description">
				<div class="b-icon-card__description-inner">
					<p><?php echo wp_kses_post( $description ); ?></p>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
