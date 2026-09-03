<?php
/**
 * "Row" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;

$disable_label = ! empty( $args['disable_label'] );
?>
<div class="c-technical-row">
	<?php get_template_part( 'templates/technical-chips', null, array( 'disable_label' => $disable_label ) ); ?>

	<div class="c-technical-row__header">
		<p class="c-technical-row__title"><?php echo esc_html( get_the_title() ); ?></p>

		<div class="c-technical-row__actions">
			<?php get_template_part( 'templates/technical-downloads' ); ?>
		</div>
	</div>
</div>
