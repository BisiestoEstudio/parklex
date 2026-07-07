addEventListener( 'DOMContentLoaded', function () {
	const isDesktop = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

	if ( isDesktop ) {
		return;
	}

	document.querySelectorAll( '.b-icon-card' ).forEach( ( card ) => {
		card.addEventListener( 'click', () => {
			card.classList.toggle( 'is-active' );
		} );
	} );
} );
