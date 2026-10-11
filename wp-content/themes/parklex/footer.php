<?php
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/site-footer' );
?>

<?php if ( is_post_type_archive( 'technical-card' ) ) : ?>
	<?php get_template_part( 'templates/technical-video-modal' ); ?>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
