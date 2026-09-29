/* global bisTechnicalAccessGate */
/**
 * Drives templates/technical-access-gate.php: toggles between the email-only login
 * form and the HubSpot registration form, and marks the visitor as registered (sets
 * the technical_area_registered_user cookie) on either a successful login or a
 * successful HubSpot form submission, then reloads so the gate is skipped.
 */
( function () {
	var COOKIE_NAME = 'technical_area_registered_user';
	var COOKIE_MAX_AGE = 60 * 60 * 24 * 365;

	document.addEventListener( 'click', function ( event ) {
		var showRegistration = event.target.closest( '[data-role="show-registration"]' );
		var showLogin = event.target.closest( '[data-role="show-login"]' );

		if ( ! showRegistration && ! showLogin ) {
			return;
		}

		var loginPanel = document.querySelector( '[data-role="login-panel"]' );
		var registrationPanel = document.querySelector( '[data-role="registration-panel"]' );

		if ( ! loginPanel || ! registrationPanel ) {
			return;
		}

		loginPanel.hidden = !! showRegistration;
		registrationPanel.hidden = !! showLogin;
	} );

	// HubSpot's embedded forms report a successful submission via this postMessage,
	// same event Bis_Core_Technical_Card's registration form relies on.
	window.addEventListener( 'message', function ( event ) {
		var data = event.data;

		if ( ! data || 'hsFormCallback' !== data.type || 'onFormSubmitted' !== data.eventName ) {
			return;
		}

		// No server round-trip for the external HubSpot form, so the cookie is set
		// here; the login form below instead gets it from the AJAX response headers.
		document.cookie = COOKIE_NAME + '=true; path=/; max-age=' + COOKIE_MAX_AGE + '; SameSite=Lax';
		window.location.reload();
	} );

	var loginForm = document.querySelector( '[data-role="technical-login-form"]' );

	if ( ! loginForm || 'undefined' === typeof bisTechnicalAccessGate ) {
		return;
	}

	loginForm.addEventListener( 'submit', function ( event ) {
		event.preventDefault();

		var errorEl = document.querySelector( '[data-role="technical-login-error"]' );

		if ( errorEl ) {
			errorEl.hidden = true;
		}

		var formData = new FormData();
		formData.append( 'action', bisTechnicalAccessGate.action );
		formData.append( 'nonce', bisTechnicalAccessGate.nonce );
		formData.append( 'email', loginForm.email.value );

		fetch( bisTechnicalAccessGate.ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin',
		} )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( response ) {
				if ( response.success ) {
					window.location.reload();
					return;
				}

				showLoginError( errorEl, response.data && response.data.message );
			} )
			.catch( function () {
				showLoginError( errorEl );
			} );
	} );

	function showLoginError( errorEl, message ) {
		if ( ! errorEl ) {
			return;
		}

		errorEl.textContent = message || bisTechnicalAccessGate.i18n.genericError;
		errorEl.hidden = false;
	}
} )();
