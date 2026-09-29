/**
 * Marks the visitor as registered once the HubSpot form (options > registration_form)
 * embedded in templates/technical-access-gate.php reports a successful submission, then
 * reloads so the gate is skipped and the archive content shows.
 */
( function () {
	var COOKIE_NAME = 'technical_area_registered_user';
	var COOKIE_MAX_AGE = 60 * 60 * 24 * 365;

	window.addEventListener( 'message', function ( event ) {
		var data = event.data;

		if ( ! data || 'hsFormCallback' !== data.type || 'onFormSubmitted' !== data.eventName ) {
			return;
		}

		document.cookie = COOKIE_NAME + '=true; path=/; max-age=' + COOKIE_MAX_AGE + '; SameSite=Lax';
		window.location.reload();
	} );
} )();
