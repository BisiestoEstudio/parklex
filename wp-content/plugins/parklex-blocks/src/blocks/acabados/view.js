addEventListener( 'DOMContentLoaded', function () {
	document.querySelectorAll( '.b-acabados__grid.swiper' ).forEach( ( el ) => {
		new Swiper( el, {
			slidesPerView: 1.2,
			spaceBetween: 16,
			watchOverflow: true,
			breakpoints: {
				768: { enabled: false },
			},
		} );
	} );
} );
