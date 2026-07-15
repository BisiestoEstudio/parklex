<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$post_id = get_the_ID();

// Print post excerpt if it exists for this product.
$excerpt = get_the_excerpt( $post_id );


$featured_id = get_post_thumbnail_id( $post_id );
$gallery_ids = function_exists( 'get_field' ) ? (array) get_field( 'gallery', $post_id ) : array();
$thumb_ids   = array_values( array_unique( array_filter( array_merge( array( $featured_id ), $gallery_ids ) ) ) );

$terms      = get_the_terms( $post_id, 'products_type' );
$term_names = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : array();
?>

<section <?php echo bis_get_block_prop( $block, true, array( 'class' => 'alignfull' ) ); ?>>
	<div class="b-header-products__wrapper alignwide">
		<div class="b-header-products__content">
			<div class="b-header-products__details">
			<?php if ( $term_names ) : ?>
				<p class="b-header-products__eyebrow has-display-s-font-size"><?php echo esc_html( implode( ', ', $term_names ) ); ?></p>
			<?php endif; ?>

			<h1 class="b-header-products__title has-display-l-font-size"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
			<?php if ( $excerpt ) : ?>
				<div class="b-header-products__excerpt">
					<?php echo esc_html( $excerpt ); ?>
				</div>
			<?php endif; ?>
			</div>
			<div class="b-header-products__inner is-prose">
				<?php echo $content; ?>
			</div>
		</div>

		<div class="b-header-products__media">
			<div class="b-header-products__main-image">
				<?php if ( $thumb_ids ) : ?>
					<?php echo wp_get_attachment_image( $thumb_ids[0], 'large', false, array( 'class' => 'b-header-products__main-img' ) ); ?>
				<?php endif; ?>
			</div>

			<?php if ( count( $thumb_ids ) > 1 ) : ?>
				<div class="b-header-products__thumbs">
					<?php foreach ( $thumb_ids as $index => $thumb_id ) : ?>
						<button
							type="button"
							class="b-header-products__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>"
							data-full-src="<?php echo esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ); ?>"
							aria-label="<?php echo esc_attr( sprintf( __( 'Ver imagen %d', 'parklex-blocks' ), $index + 1 ) ); ?>"
						>
							<?php echo wp_get_attachment_image( $thumb_id, 'thumbnail', false, array( 'class' => 'b-header-products__thumb-img' ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
