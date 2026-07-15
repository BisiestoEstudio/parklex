addEventListener( 'DOMContentLoaded', function () {
	document.querySelectorAll( '.b-image-carousel__grid.swiper' ).forEach( ( el ) => {
		new Swiper( el, {
			slideClass: 'wp-block-image',
			slidesPerView: 1.15,
			spaceBetween: 16,
			watchOverflow: true,
			breakpoints: {
				768: { slidesPerView: 2.2, spaceBetween: 24 },
				1080: { slidesPerView: 3.2, spaceBetween: 32 },
			},
		} );
	} );
} );
