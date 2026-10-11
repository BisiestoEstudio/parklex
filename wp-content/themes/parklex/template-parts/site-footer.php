<?php
/**
 * Site footer: 4 menu columns (locations footer_menu_1..4), address (Theme Options), logo and copyright.
 */
defined( 'ABSPATH' ) || exit;

$address = function_exists( 'get_field' ) ? get_field( 'footer_address', 'option' ) : '';
$logo_id = function_exists( 'get_field' ) ? (int) get_field( 'footer_logo', 'option' ) : 0;
?>

<footer class="site-footer">
	<div class="site-footer__inner">

		<div class="site-footer__menus">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<?php if ( has_nav_menu( "footer_menu_{$i}" ) ) : ?>
					<?php
					wp_nav_menu( array(
						'theme_location' => "footer_menu_{$i}",
						'container'      => 'nav',
						'container_class' => 'site-footer__nav',
						'menu_class'     => 'site-footer__menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				<?php endif; ?>
			<?php endfor; ?>
		</div>

		<div class="site-footer__bottom">
			<div class="site-footer__address"><?php echo wp_kses_post( $address ); ?></div>

			<div class="site-footer__logo">
				<?php if ( $logo_id ) : ?>
					<?php echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'site-footer__logo-img' ) ); ?>
				<?php endif; ?>
			</div>

			<p class="site-footer__copy">
				<?php
				/* translators: %s: current year */
				printf( esc_html__( 'All Rights Reserved. %s.', 'parklex' ), esc_html( wp_date( 'Y' ) ) );
				?>
			</p>
		</div>

	</div>
</footer>
