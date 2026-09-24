( function () {
	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '.js-technical-sidebar-toggle' );

		if ( ! toggle ) {
			return;
		}

		var content = toggle.nextElementSibling;

		if ( ! content ) {
			return;
		}

		var isOpen = content.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
	} );
} )();
