<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */
?>

<section <?php echo bis_get_block_prop( $block, false, [ 'class' => 'alignwide' ] ); ?>>
	<div
		class="b-interactive-map__map"
		data-map-pins-endpoint="<?php echo esc_url( rest_url( 'parklex/v1/map-pins' ) ); ?>"
	></div>
</section>
