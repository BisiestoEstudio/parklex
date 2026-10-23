<?php
/**
 * Template Name: Pantalla
 *
 * Página a pantalla completa que solo reproduce un vídeo en bucle (pantallas de
 * showroom/feria). Sin cabecera, pie ni wp_head() a propósito.
 */
defined( 'ABSPATH' ) || exit;

$video = get_field( 'video' ) ?: get_field( 'video_url' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo esc_html( get_the_title() ); ?></title>
	<style>
		html, body { margin: 0; padding: 0; height: 100%; background: #000; overflow: hidden; }
		video { display: block; width: 100vw; height: 100vh; object-fit: contain; }
	</style>
</head>
<body>
<?php if ( $video ) : ?>
	<video autoplay loop muted playsinline controls width="1920" height="1080">
		<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
	</video>
<?php endif; ?>
</body>
</html>
