<?php
defined( 'ABSPATH' ) || exit;

$categories = get_the_category();
$category   = $categories ? $categories[0] : null;
$position   = isset( $args['position'] ) ? (int) $args['position'] : 0;
?>
<article class="c-blog-post b-bisiesto<?php echo $position ? ' c-blog-post--' . $position : ''; ?>">
	<a class="c-blog-post__link" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="c-blog-post__featured-image">
				<?php echo wp_get_attachment_image( get_post_thumbnail_id(), 'medium_large', false, array( 'class' => 'c-blog-post__featured-image-img' ) ); ?>
			</figure>
		<?php endif; ?>

		<div class="c-blog-post__details">
			<?php if ( $category ) : ?>
				<span class="c-blog-post__category"><?php echo esc_html( $category->name ); ?></span>
			<?php endif; ?>

			<h2 class="c-blog-post__title"><?php the_title(); ?></h2>

			<div class="c-blog-post__excerpt"><?php the_excerpt(); ?></div>
		</div>
	</a>
</article>
