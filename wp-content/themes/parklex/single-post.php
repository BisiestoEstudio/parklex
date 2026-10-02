<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content is-layout-constrained has-global-padding">
	<?php
	while ( have_posts() ) :
		the_post();

		$categories = get_the_category();
		$toc_data   = bis_theme_build_toc( apply_filters( 'the_content', get_the_content() ) );
		?>

		<article class="c-blog-single">
			<header class="c-blog-single__header">
				<?php if ( $categories ) : ?>
					<p class="c-blog-single__category display-xxs">
						<?php echo esc_html( implode( ', ', wp_list_pluck( $categories, 'name' ) ) ); ?>
					</p>
				<?php endif; ?>

				<h1 class="c-blog-single__title"><?php the_title(); ?></h1>

				<p class="c-blog-single__date"><?php echo esc_html( get_the_date( 'j \d\e F Y' ) ); ?></p>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="c-blog-single__featured-image alignwide">
					<?php
					echo wp_get_attachment_image(
						get_post_thumbnail_id(),
						'large',
						false,
						array( 'class' => 'c-blog-single__featured-image-img' )
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( ! empty( $toc_data['toc'] ) ) : ?>
				<?php get_template_part( 'template-parts/content-blog-toc', null, array( 'items' => $toc_data['toc'] ) ); ?>
			<?php endif; ?>

			<div class="c-blog-single__content is-layout-constrained">
				<?php echo $toc_data['content']; ?>
			</div>
		</article>

	<?php endwhile; ?>
</main>

<?php
get_footer();
