<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$speed        = $attributes['speed'] ?? 0;
$repeat_count = max( 2, (int) ( $attributes['repeatCount'] ?? 2 ) );

if ( '' === trim( $content ) ) {
	return;
}

$base_duration = 20000;
$duration      = $base_duration;

if ( $speed ) {
	$duration = $speed > 0 ? $base_duration / $speed : $base_duration * $speed * -1;
	$duration = (int) $duration;
}

$item_style = sprintf( 'animation-duration:%dms;', $duration );
?>

<div <?php echo bis_get_block_prop( $block, true ); ?>>
	<div class="b-icon-marquee__track">
		<?php for ( $i = 0; $i < $repeat_count; $i++ ) : ?>
			<div
				class="b-icon-marquee__item"
				style="<?php echo esc_attr( $item_style ); ?>"
				<?php echo $i > 0 ? 'aria-hidden="true"' : ''; ?>
			>
				<?php echo $content; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>
