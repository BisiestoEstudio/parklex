<?php
/**
 * 404 provisional.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="is-layout-constrained has-global-padding" style="padding-block: var(--wp--preset--spacing--xxl); text-align: center;">
	<p class="has-display-l-font-size">404</p>
	<h1><?php esc_html_e( 'Page not found', 'parklex' ); ?></h1>
	<p><?php esc_html_e( 'The page you are looking for does not exist or has been moved.', 'parklex' ); ?></p>

	<div class="wp-block-buttons is-layout-flex is-content-justification-center">
		<div class="wp-block-button">
			<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to home', 'parklex' ); ?>
			</a>
		</div>
	</div>
</main>

<?php
get_footer();
