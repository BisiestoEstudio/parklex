<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$speed            = $attributes['speed'] ?? 0;
$repeat_count     = max( 2, (int) ( $attributes['repeatCount'] ?? 2 ) );
$invert_direction = $attributes['invertDirection'] ?? false;

if ( '' === trim( $content ) ) {
	return;
}

$base_duration = 20000;
$duration      = $base_duration;

if ( $speed ) {
	$duration = $speed > 0 ? $base_duration / $speed : $base_duration * $speed * -1;
	$duration = (int) $duration;
}

$animation_duration = 'style="animation-duration:' . $duration . 'ms"';
?>

<div <?php echo bis_get_block_prop( $block, false, array(
	'class' => $invert_direction ? 'b-icon-marquee--invert' : '',
) ); ?>>
	<?php for ( $i = 0; $i < $repeat_count; $i++ ) : ?>
	<div class="b-icon-marquee__item" <?php echo $animation_duration; ?><?php echo $i > 0 ? ' aria-hidden="true"' : ''; ?>>
		<?php echo $content; ?>
	</div>
	<?php endfor; ?>
</div>
