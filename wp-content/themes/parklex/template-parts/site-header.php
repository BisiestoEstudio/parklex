<?php
/**
 * Site header: logo, "Menú principal" (with card submenus) and actions (button + language switcher).
 */
defined( 'ABSPATH' ) || exit;

$menu_items = Bis_Theme_Header::get_menu_tree();
$button     = Bis_Theme_Header::get_button();
$logo       = get_custom_logo();
?>

<div class="site-header js-site-header">
	<div class="site-header__bar">

		<div class="site-header__logo">
			<?php if ( $logo ) : ?>
				<?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<a class="site-header__logo-text" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			<?php endif; ?>
		</div>

		<?php if ( $menu_items ) : ?>
			<nav class="site-header__nav" aria-label="<?php esc_attr_e( 'Menú principal', 'parklex' ); ?>">
				<ul class="site-header__menu">
					<?php foreach ( $menu_items as $item ) : ?>
						<?php
						$has_children = ! empty( $item->children );
						$submenu_id   = 'site-header-submenu-' . $item->ID;
						$item_classes = array( 'site-header__item' );

						if ( $has_children ) {
							$item_classes[] = 'site-header__item--has-children';
						}
						if ( Bis_Theme_Header::is_current( $item ) ) {
							$item_classes[] = 'site-header__item--current';
						}
						?>
						<li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
							<a
								class="site-header__link"
								href="<?php echo esc_url( $item->url ); ?>"
								<?php echo $item->target ? 'target="' . esc_attr( $item->target ) . '"' : ''; ?>
								<?php if ( $has_children ) : ?>
									aria-expanded="false"
									aria-controls="<?php echo esc_attr( $submenu_id ); ?>"
								<?php endif; ?>
							><?php echo esc_html( $item->title ); ?></a>

							<?php if ( $has_children ) : ?>
								<div class="site-header__submenu" id="<?php echo esc_attr( $submenu_id ); ?>">
									<ul class="site-header__cards">
										<?php foreach ( $item->children as $child ) : ?>
											<?php $image_id = function_exists( 'get_field' ) ? get_field( 'image', $child ) : 0; ?>
											<li class="site-header__card-item">
												<a
													class="site-header__card"
													href="<?php echo esc_url( $child->url ); ?>"
													<?php echo $child->target ? 'target="' . esc_attr( $child->target ) . '"' : ''; ?>
												>
													<span class="site-header__card-media">
														<?php if ( $image_id ) : ?>
															<?php echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'class' => 'site-header__card-img', 'loading' => 'lazy' ) ); ?>
														<?php endif; ?>
													</span>
													<span class="site-header__card-title"><?php echo esc_html( $child->title ); ?></span>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<div class="site-header__actions">
			<?php if ( $button ) : ?>
				<a
					class="btn btn-outline site-header__button"
					href="<?php echo esc_url( $button['url'] ); ?>"
					<?php echo ! empty( $button['target'] ) ? 'target="' . esc_attr( $button['target'] ) . '"' : ''; ?>
				><?php echo esc_html( $button['title'] ); ?></a>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/language-switcher' ); ?>
		</div>

	</div>
</div>
