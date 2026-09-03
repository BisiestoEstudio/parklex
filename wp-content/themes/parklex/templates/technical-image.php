<?php
/**
 * "Image Card" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;

$disable_label = ! empty( $args['disable_label'] );
$vimeo_url     = get_field( 'link' );
$image         = get_field( 'image' );
?>
<div class="c-technical-row">
	<?php get_template_part( 'templates/technical-chips', null, array( 'disable_label' => $disable_label ) ); ?>

	<div class="c-technical-row__header">
		<p class="c-technical-row__title"><?php echo esc_html( get_the_title() ); ?></p>

		<div class="c-technical-row__actions">
			<?php if ( ! empty( $vimeo_url ) ) : ?>
				<button type="button" class="c-technical-video-trigger js-technical-video-trigger has-base-font-size" data-vimeo-url="<?php echo esc_url( $vimeo_url ); ?>">
					<?php esc_html_e( 'Ver', 'parklex' ); ?>
				</button>
			<?php endif; ?>

			<?php get_template_part( 'templates/technical-downloads' ); ?>
		</div>
	</div>

	<?php if ( ! empty( $image['ID'] ) ) : ?>
		<div class="c-technical-media">
			<?php echo wp_get_attachment_image( $image['ID'], 'large', false, array( 'class' => 'c-technical-media__img' ) ); ?>
		</div>
	<?php endif; ?>
</div>
