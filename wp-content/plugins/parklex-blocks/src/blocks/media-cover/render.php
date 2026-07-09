<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$content_position = $attributes['contentPosition'] ?? 'center center';

list( $v_position, $h_position ) = array_pad( explode( ' ', $content_position ), 2, 'center' );

$valid_v = in_array( $v_position, [ 'top', 'center', 'bottom' ], true ) ? $v_position : 'center';
$valid_h = in_array( $h_position, [ 'left', 'center', 'right' ], true ) ? $h_position : 'center';

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
<section <?php echo bis_get_block_prop( $block, true, [ 'class' => 'alignfull' ] ); ?>>
	<?php echo $bg_shapes_html; ?>
	<div class="b-media-cover__content alignwide b-media-cover__content--v-<?php echo esc_attr( $valid_v ); ?> b-media-cover__content--h-<?php echo esc_attr( $valid_h ); ?>">
		<div class="b-media-cover__inner">
			<?php echo $content_html; ?>
		</div>
	</div>
</section>
