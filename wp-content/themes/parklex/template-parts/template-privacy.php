<?php
/**
 * Template Name: Privacy Page
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article class="c-legal-page">
			<header class="c-legal-page__header is-layout-constrained has-global-padding">
				<h1 class="c-legal-page__title"><?php the_title(); ?></h1>
			</header>

			<div class="c-legal-page__content is-layout-constrained has-global-padding">
				<?php the_content(); ?>
			</div>
		</article>

	<?php endwhile; ?>
</main>

<?php
get_footer();
