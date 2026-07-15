addEventListener( 'DOMContentLoaded', function () {
	document.querySelectorAll( '.b-header-products' ).forEach( ( block ) => {
		const mainImg = block.querySelector( '.b-header-products__main-img' );
		const thumbs = block.querySelectorAll( '.b-header-products__thumb' );

		if ( ! mainImg || ! thumbs.length ) {
			return;
		}

		thumbs.forEach( ( thumb ) => {
			thumb.addEventListener( 'click', () => {
				const fullSrc = thumb.dataset.fullSrc;
				if ( ! fullSrc ) {
					return;
				}

				mainImg.src = fullSrc;

				thumbs.forEach( ( item ) => item.classList.remove( 'is-active' ) );
				thumb.classList.add( 'is-active' );
			} );
		} );
	} );
} );
