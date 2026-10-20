<?php
defined( 'ABSPATH' ) || exit;

$featured_image = get_field( 'featured_image' );
$title           = get_field( 'project_name' ) ?: get_the_title();
?>
<div class="c-internal-projects__card">
	<a href="<?php the_permalink(); ?>">
		<figure class="c-internal-projects__card-image">
			<?php if ( $featured_image ) : ?>
				<?php
				// No srcset: always serve 'large', don't let the browser pick a smaller candidate.
				add_filter( 'wp_calculate_image_srcset', '__return_false' );
				echo wp_get_attachment_image( $featured_image['ID'], 'large' );
				remove_filter( 'wp_calculate_image_srcset', '__return_false' );
				?>
			<?php endif; ?>
		</figure>
		<span class="c-internal-projects__card-title"><?php echo esc_html( $title ); ?></span>
	</a>
</div>
