<?php
/**
 * Category chips for a technical-card post. Shared across card layouts.
 */
defined( 'ABSPATH' ) || exit;

if ( ! empty( $args['disable_label'] ) ) {
	return;
}

$categories = get_the_terms( get_the_ID(), 'category_technical_card' );

if ( empty( $categories ) || is_wp_error( $categories ) ) {
	return;
}
?>
<ul class="c-technical-chips">
	<?php foreach ( $categories as $category ) : ?>
		<li class="c-technical-chip has-caption-font-size"><?php echo esc_html( $category->name ); ?></li>
	<?php endforeach; ?>
</ul>
