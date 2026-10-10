/**
 * Site header (template-parts/site-header.php):
 * - Card submenus: open on hover / keyboard focus, close on leaving the header or Escape.
 *   On touch, the first tap on a parent item opens its submenu instead of navigating.
 * - Language switcher dropdown.
 */
( function () {
	var header = document.querySelector( '.js-site-header' );

	if ( header ) {
		initSubmenus( header );
	}

	document.querySelectorAll( '.js-lang-switcher' ).forEach( initLangSwitcher );

	function initSubmenus( header ) {
		var items = header.querySelectorAll( '.site-header__item' );
		var lastPointerType = 'mouse';

		function open( item ) {
			items.forEach( function ( other ) {
				if ( other !== item ) {
					setOpen( other, false );
				}
			} );
			setOpen( item, true );
			header.classList.add( 'has-submenu-open' );
		}

		function closeAll() {
			items.forEach( function ( item ) {
				setOpen( item, false );
			} );
			header.classList.remove( 'has-submenu-open' );
		}

		function setOpen( item, isOpen ) {
			var link = item.querySelector( ':scope > .site-header__link' );

			item.classList.toggle( 'is-open', isOpen );

			if ( link && link.hasAttribute( 'aria-expanded' ) ) {
				link.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			}
		}

		function hasChildren( item ) {
			return item.classList.contains( 'site-header__item--has-children' );
		}

		items.forEach( function ( item ) {
			var link = item.querySelector( ':scope > .site-header__link' );

			item.addEventListener( 'mouseenter', function () {
				if ( lastPointerType !== 'mouse' ) {
					return;
				}
				hasChildren( item ) ? open( item ) : closeAll();
			} );

			item.addEventListener( 'focusin', function () {
				hasChildren( item ) ? open( item ) : closeAll();
			} );

			if ( link && hasChildren( item ) ) {
				// Read on pointerdown: the tap also focuses the link, and focusin opens the submenu before click.
				var wasOpen = false;

				link.addEventListener( 'pointerdown', function ( event ) {
					lastPointerType = event.pointerType;
					wasOpen = item.classList.contains( 'is-open' );
				} );

				link.addEventListener( 'click', function ( event ) {
					if ( lastPointerType !== 'mouse' && ! wasOpen ) {
						event.preventDefault();
						open( item );
					}
				} );
			}
		} );

		header.addEventListener( 'pointermove', function ( event ) {
			lastPointerType = event.pointerType;
		} );

		header.addEventListener( 'mouseleave', closeAll );

		header.addEventListener( 'focusout', function ( event ) {
			if ( ! header.contains( event.relatedTarget ) ) {
				closeAll();
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! header.contains( event.target ) ) {
				closeAll();
			}
		} );

		header.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Escape' ) {
				return;
			}

			var openItem = header.querySelector( '.site-header__item.is-open' );

			if ( openItem ) {
				closeAll();
				openItem.querySelector( ':scope > .site-header__link' ).focus();
			}
		} );
	}

	function initLangSwitcher( switcher ) {
		var toggle = switcher.querySelector( '.lang-switcher__toggle' );
		var list = switcher.querySelector( '.lang-switcher__list' );

		if ( ! toggle || ! list ) {
			return;
		}

		function setOpen( isOpen ) {
			list.hidden = ! isOpen;
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			switcher.classList.toggle( 'is-open', isOpen );
		}

		toggle.addEventListener( 'click', function () {
			setOpen( list.hidden );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! switcher.contains( event.target ) ) {
				setOpen( false );
			}
		} );

		switcher.addEventListener( 'focusout', function ( event ) {
			if ( ! switcher.contains( event.relatedTarget ) ) {
				setOpen( false );
			}
		} );

		switcher.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && ! list.hidden ) {
				setOpen( false );
				toggle.focus();
			}
		} );
	}
} )();
