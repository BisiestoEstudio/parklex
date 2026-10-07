<?php
/**
 * Provisional language switcher (WPML).
 *
 * TODO: provisional — sustituir cuando se maquete el menú definitivo.
 */
defined( 'ABSPATH' ) || exit;

// skip_missing=0: show every language, linking to its home when the current page has no translation.
$languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );

if ( empty( $languages ) || count( $languages ) < 2 ) {
	return;
}
?>

<nav class="lang-switcher" aria-label="<?php esc_attr_e( 'Language', 'parklex' ); ?>">
	<ul class="lang-switcher__list">
		<?php foreach ( $languages as $language ) : ?>
			<li class="lang-switcher__item<?php echo $language['active'] ? ' lang-switcher__item--active' : ''; ?>">
				<a
					class="lang-switcher__link"
					href="<?php echo esc_url( $language['url'] ); ?>"
					hreflang="<?php echo esc_attr( $language['code'] ); ?>"
					lang="<?php echo esc_attr( $language['code'] ); ?>"
					title="<?php echo esc_attr( $language['native_name'] ); ?>"
					<?php echo $language['active'] ? 'aria-current="true"' : ''; ?>
				><?php echo esc_html( strtoupper( $language['code'] ) ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
