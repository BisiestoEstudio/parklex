<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

if ( ! trim( (string) $content ) ) {
	return;
}

// Forzar la carga en resolución completa: con loading="lazy" core antepone
// `auto,` a `sizes` y el navegador elige un candidato pequeño del srcset
// porque las slides son mucho más estrechas que el viewport.
$bis_images = new WP_HTML_Tag_Processor( $content );
while ( $bis_images->next_tag( 'img' ) ) {
	$bis_images->set_attribute( 'loading', 'eager' );

	$bis_sizes = $bis_images->get_attribute( 'sizes' );
	if ( is_string( $bis_sizes ) ) {
		$bis_images->set_attribute( 'sizes', preg_replace( '/^\s*auto\s*,\s*/i', '', $bis_sizes ) );
	}
}
$content = $bis_images->get_updated_html();
?>

<div <?php echo bis_get_block_prop( $block, false, array( 'class' => 'alignfull' ) ); ?>>
	<div class="b-image-carousel__grid swiper alignwide">
		<div class="swiper-wrapper b-image-carousel__wrapper">
			<?php echo $content; ?>
		</div>
	</div>
</div>
