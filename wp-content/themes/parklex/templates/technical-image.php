<?php
/**
 * "Image Card" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;

$disable_label = ! empty( $args['disable_label'] );
$link          = get_field( 'link' );
$image         = get_field( 'image' );
?>
<div class="c-technical-image">
	<?php get_template_part( 'templates/technical-chips', null, array( 'disable_label' => $disable_label ) ); ?>

	<div class="c-technical-image__header">
		<p class="c-technical-image__title"><?php echo esc_html( get_the_title() ); ?></p>

		<div class="c-technical-image__actions">
			<?php if ( ! empty( $link['url'] ) ) : ?>
				<a class="c-technical-image__link" href="<?php echo esc_url( $link['url'] ); ?>" <?php echo ! empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '"' : ''; ?>>
					<?php echo esc_html( ! empty( $link['title'] ) ? $link['title'] : __( 'Ver', 'parklex' ) ); ?>
				</a>
			<?php endif; ?>

			<?php get_template_part( 'templates/technical-downloads' ); ?>
		</div>
	</div>

	<?php if ( ! empty( $image['ID'] ) ) : ?>
		<div class="c-technical-image__media">
			<?php echo wp_get_attachment_image( $image['ID'], 'large', false, array( 'class' => 'c-technical-image__img' ) ); ?>
		</div>
	<?php endif; ?>
</div>
