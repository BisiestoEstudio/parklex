<?php
defined( 'ABSPATH' ) || exit;

$featured_image = get_field( 'featured_image' );
$title           = get_field( 'project_name' ) ?: get_the_title();
?>
<div class="c-internal-projects__card">
	<a href="<?php the_permalink(); ?>">
		<?php if ( $featured_image ) : ?>
			<figure class="c-internal-projects__card-image">
				<img src="<?php echo esc_url( $featured_image['sizes']['medium'] ?? $featured_image['url'] ); ?>" alt="<?php echo esc_attr( $featured_image['alt'] ); ?>" loading="lazy">
			</figure>
		<?php endif; ?>
		<span class="c-internal-projects__card-title"><?php echo esc_html( $title ); ?></span>
	</a>
</div>
