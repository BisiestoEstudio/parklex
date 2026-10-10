<?php
/**
 * Language switcher (WPML) as a dropdown: the toggle shows the current language,
 * the list the other ones.
 */
defined( 'ABSPATH' ) || exit;

// skip_missing=0: show every language, linking to its home when the current page has no translation.
$languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );

if ( empty( $languages ) || count( $languages ) < 2 ) {
	return;
}

$current = null;
foreach ( $languages as $language ) {
	if ( $language['active'] ) {
		$current = $language;
		break;
	}
}

if ( ! $current ) {
	return;
}
?>

<div class="lang-switcher js-lang-switcher">
	<button
		class="lang-switcher__toggle"
		type="button"
		aria-expanded="false"
		aria-controls="lang-switcher-list"
		aria-label="<?php echo esc_attr( sprintf( __( 'Idioma: %s', 'parklex' ), $current['native_name'] ) ); ?>"
	>
		<span class="lang-switcher__current"><?php echo esc_html( strtoupper( $current['code'] ) ); ?></span>
	</button>

	<ul class="lang-switcher__list" id="lang-switcher-list" hidden>
		<?php foreach ( $languages as $language ) : ?>
			<?php if ( $language['active'] ) continue; ?>
			<li class="lang-switcher__item">
				<a
					class="lang-switcher__link"
					href="<?php echo esc_url( $language['url'] ); ?>"
					hreflang="<?php echo esc_attr( $language['code'] ); ?>"
					lang="<?php echo esc_attr( $language['code'] ); ?>"
					title="<?php echo esc_attr( $language['native_name'] ); ?>"
				><?php echo esc_html( strtoupper( $language['code'] ) ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
