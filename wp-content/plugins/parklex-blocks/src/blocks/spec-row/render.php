<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$label = $attributes['label'] ?? '';
$value = $attributes['value'] ?? '';
$className = $attributes['className'] ?? '';

$is_heading = strpos( $className, 'is-style-heading' ) !== false;

$heading_class = $is_heading ? ' has-display-s-font-size' : '';
$value_class = $is_heading ? '' : ' u_bold';
?>

<div <?php echo bis_get_block_prop( $block ); ?>>
	<div class="b-spec-row__content">
	<span class="b-spec-row__label<?php echo esc_attr( $heading_class ); ?>"><?php echo wp_kses_post( $label ); ?></span>
		<span class="b-spec-row__value<?php echo esc_attr( $value_class ); ?>"><?php echo wp_kses_post( $value ); ?></span>
	</div>
</div>
