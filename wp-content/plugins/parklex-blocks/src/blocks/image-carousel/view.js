addEventListener( 'DOMContentLoaded', function () {
	document.querySelectorAll( '.b-image-carousel__grid.swiper' ).forEach( ( el ) => {
		new Swiper( el, {
			slideClass: 'wp-block-image',
			slidesPerView: 1.15,
			spaceBetween: 16,
			watchOverflow: true,
			breakpoints: {
				768: { slidesPerView: 2.2, spaceBetween: 16 },
				1080: { slidesPerView: 2.7, spaceBetween: 16 },
			},
		} );
	} );
} );
