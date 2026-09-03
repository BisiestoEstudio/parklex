<?php
/**
 * "Card" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;

$disable_label = ! empty( $args['disable_label'] );
?>
<div class="c-technical-card">
	<?php get_template_part( 'templates/technical-chips', null, array( 'disable_label' => $disable_label ) ); ?>

	<p class="c-technical-card__title"><?php echo esc_html( get_the_title() ); ?></p>

	<?php get_template_part( 'templates/technical-downloads' ); ?>
</div>
