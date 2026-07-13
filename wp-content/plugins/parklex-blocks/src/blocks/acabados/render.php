<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$products_type = (int) ( $attributes['productsType'] ?? 0 );

if ( ! $products_type ) {
	return;
}

$query = new WP_Query(
	array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'no_found_rows'  => true,
		'tax_query'      => array(
			array(
				'taxonomy' => 'products_type',
				'field'    => 'term_id',
				'terms'    => $products_type,
			),
		),
	)
);

if ( ! $query->have_posts() ) {
	return;
}
?>

<div <?php echo bis_get_block_prop( $block, false, array( 'class' => 'alignfull' ) ); ?>>
	<div class="b-acabados__grid swiper">
		<div class="swiper-wrapper b-acabados__wrapper">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<a class="b-acabados__item swiper-slide" href="<?php echo esc_url( get_permalink() ); ?>">
					<div class="b-acabados__image">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'b-acabados__img' ) ); ?>
					</div>
					<h3 class="b-acabados__title has-display-m-font-size"><?php echo esc_html( get_the_title() ); ?></h3>
				</a>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</div>
