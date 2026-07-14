addEventListener( 'DOMContentLoaded', function () {
	if ( ! window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches ) {
		return;
	}

	document.querySelectorAll( '.b-zoom-image' ).forEach( ( figure ) => {
		const img = figure.querySelector( '.b-zoom-image__img' );
		if ( ! img ) {
			return;
		}

		let rect = null;
		let pendingEvent = null;
		let ticking = false;

		const updateOrigin = () => {
			ticking = false;
			if ( ! rect || ! pendingEvent ) {
				return;
			}
			const x = ( ( pendingEvent.clientX - rect.left ) / rect.width ) * 100;
			const y = ( ( pendingEvent.clientY - rect.top ) / rect.height ) * 100;
			img.style.setProperty( '--zoom-x', `${ x }%` );
			img.style.setProperty( '--zoom-y', `${ y }%` );
		};

		figure.addEventListener( 'mouseenter', () => {
			rect = figure.getBoundingClientRect();
		} );

		figure.addEventListener( 'mousemove', ( e ) => {
			pendingEvent = e;
			if ( ! ticking ) {
				ticking = true;
				requestAnimationFrame( updateOrigin );
			}
		} );

		figure.addEventListener( 'mouseleave', () => {
			img.style.setProperty( '--zoom-x', '50%' );
			img.style.setProperty( '--zoom-y', '50%' );
		} );
	} );
} );
