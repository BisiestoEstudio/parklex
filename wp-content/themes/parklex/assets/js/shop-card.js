/**
 * Shop archive cards: size chips, quantity and AJAX add to cart.
 * Markup: includes/class-woocommerce.php (Bis_Theme_Woocommerce::paint_card_form).
 */
( function () {
	const config = window.bisShopCard;
	if ( ! config ) {
		return;
	}

	const matches = ( variation, selected ) =>
		Object.keys( variation.attributes ).every( ( key ) => ! variation.attributes[ key ] || variation.attributes[ key ] === selected[ key ] );

	const applyFragments = ( fragments ) => {
		Object.keys( fragments || {} ).forEach( ( selector ) => {
			document.querySelectorAll( selector ).forEach( ( el ) => {
				el.outerHTML = fragments[ selector ];
			} );
		} );
	};

	document.querySelectorAll( '.c-shop__cart' ).forEach( ( form ) => {
		const button = form.querySelector( '.c-shop__add' );
		const chips = form.querySelectorAll( '.c-shop__chip-input' );
		const variationInput = form.elements.variation_id;
		const card = form.closest( 'li.product' );
		const price = card && card.querySelector( '.price' );
		const defaultPrice = price ? price.innerHTML : '';
		const variations = form.dataset.variations ? JSON.parse( form.dataset.variations ) : null;
		const qty = form.querySelector( '.c-shop__qty-input' );

		const onSelect = () => {
			const selected = {};
			chips.forEach( ( chip ) => {
				if ( chip.checked ) {
					selected[ chip.name ] = chip.value;
				}
			} );
			const variation = variations.find( ( v ) => matches( v, selected ) );
			variationInput.value = variation ? variation.id : 0;
			button.disabled = ! variation;
			if ( price ) {
				price.innerHTML = variation ? variation.price_html.replace( /^<span class="price">|<\/span>$/g, '' ) : defaultPrice;
			}
			if ( qty ) {
				if ( variation && variation.max_qty ) {
					qty.max = variation.max_qty;
				} else {
					qty.removeAttribute( 'max' );
				}
			}
		};

		form.querySelectorAll( '.c-shop__qty-btn' ).forEach( ( stepButton ) => {
			stepButton.addEventListener( 'click', () => {
				if ( Number( stepButton.dataset.step ) > 0 ) {
					qty.stepUp();
				} else {
					qty.stepDown();
				}
			} );
		} );

		if ( variations ) {
			chips.forEach( ( chip ) => chip.addEventListener( 'change', onSelect ) );
			// Browsers may restore a checked chip on reload.
			onSelect();
		}

		form.addEventListener( 'submit', ( event ) => {
			event.preventDefault();

			if ( variations && ! Number( variationInput.value ) ) {
				const unchosen = Array.from( chips ).find( ( chip ) => ! chip.disabled && ! form.querySelector( '[name="' + chip.name + '"]:checked' ) );
				if ( unchosen ) {
					unchosen.focus();
				}
				return;
			}

			const body = new URLSearchParams( {
				product_id: variations ? variationInput.value : form.elements.product_id.value,
				quantity: qty ? qty.value : 1,
			} );

			button.disabled = true;
			button.textContent = config.i18n.adding;

			fetch( config.ajaxUrl, { method: 'POST', body } )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					if ( ! data || data.error ) {
						window.location.href = data && data.product_url ? data.product_url : form.action;
						return;
					}
					applyFragments( data.fragments );
					button.textContent = config.i18n.added;
					setTimeout( () => {
						button.textContent = config.i18n.add;
						button.disabled = Boolean( variations ) && ! Number( variationInput.value );
					}, 1500 );
				} )
				.catch( () => {
					window.location.href = form.action;
				} );
		} );
	} );
}() );
