<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

if ( ! trim( (string) $content ) ) {
	return;
}
?>

<div <?php echo bis_get_block_prop( $block, false, array( 'class' => 'alignfull' ) ); ?>>
	<div class="b-image-carousel__grid swiper alignwide">
		<div class="swiper-wrapper b-image-carousel__wrapper">
			<?php echo $content; ?>
		</div>
	</div>
</div>
