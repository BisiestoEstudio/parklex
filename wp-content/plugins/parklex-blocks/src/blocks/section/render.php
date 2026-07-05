<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$gap = $attributes['gap'] ?? '';

$gapStyles = $gap ? ' style="--section-gap: ' . esc_attr( $gap ) . ';"' : '';

// Separate background blocks (shape/video) so they render outside the content div.
$bg_shapes_html = '';
$content_html   = '';
foreach ( $block->inner_blocks as $inner_block ) {
	if ( in_array( $inner_block->name, array( 'bisiesto/background-shape', 'bisiesto/custom-background' ), true ) ) {
		$bg_shapes_html .= $inner_block->render();
	} else {
		$content_html .= $inner_block->render();
	}
}
?>

<section <?php echo bis_get_block_prop( $block, true ); ?>>
	<?php echo $bg_shapes_html; ?>
	<div class="b-section__content is-layout-constrained has-global-padding alignfull"<?php echo $gapStyles; ?>>
		<?php echo $content_html; ?>
	</div>
</section>
