<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$columns = max( 1, min( 4, (int) ( $attributes['columns'] ?? 4 ) ) );
$gap = $attributes['gap'] ?? '';

$grid_style = "--columns-mobile: 1; --columns-tablet: " . min( 2, $columns ) . "; --columns: {$columns};";
if ( $gap ) {
	$grid_style .= " --cards-grid-gap: {$gap};";
}

$atts = array(
	'style' => $grid_style,
)
?>



<div <?php echo bis_get_block_prop( $block, false, $atts ); ?> >
	<?php echo $content; ?>
</div>
