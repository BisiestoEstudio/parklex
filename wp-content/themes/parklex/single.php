<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content is-layout-constrained has-global-padding">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();
