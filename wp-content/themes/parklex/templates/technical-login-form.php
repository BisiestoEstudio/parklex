<?php
/**
 * Email-only alternative to the HubSpot registration form, shown by default on the
 * Technical Card access gate (see templates/technical-access-gate.php). Submitted via
 * AJAX (assets/js/technical-access-gate.js) to Bis_Core_Technical_Card::handle_login(),
 * which — for now — accepts any valid email and marks the visitor as registered; a
 * later step will check it against HubSpot before granting access.
 */
defined( 'ABSPATH' ) || exit;
?>
<form class="c-technical-login-form" data-role="technical-login-form" novalidate>
	<label class="c-technical-login-form__label" for="technical-login-email">
		<?php esc_html_e( 'Email', 'parklex' ); ?>
	</label>
	<input
		type="email"
		id="technical-login-email"
		name="email"
		class="c-technical-login-form__input"
		placeholder="email@email.com"
		required
	>
	<p class="c-technical-login-form__error" data-role="technical-login-error" hidden></p>
	<button type="submit" class="c-technical-login-form__submit">
		<?php esc_html_e( 'Acceder', 'parklex' ); ?>
	</button>
</form>
