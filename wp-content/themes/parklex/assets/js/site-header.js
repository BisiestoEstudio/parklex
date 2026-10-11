/**
 * Site header (template-parts/site-header.php):
 * - Card submenus: open on hover / keyboard focus, close on leaving the header or Escape.
 *   On touch, the first tap on a parent item opens its submenu instead of navigating.
 * - Language switcher dropdown.
 * - Retractable header: hides on scroll down, reappears on scroll up.
 */
( function () {
	var header = document.querySelector( '.js-site-header' );

	if ( header ) {
		initSubmenus( header );
	}

	document.querySelectorAll( '.js-lang-switcher' ).forEach( initLangSwitcher );

	var wrapper = document.querySelector( '.site-header-wrapper' );

	if ( header && wrapper ) {
		initRetractable( wrapper, header );
	}

	/**
	 * Hides the fixed header when scrolling down and shows it again when scrolling up.
	 * Toggles .is-scrolled on the header: set once it has been hidden, cleared back at the top.
	 */
	function initRetractable( wrapper, header ) {
		var threshold = 8;
		var lastY = window.scrollY;
		var ticking = false;

		function update() {
			var y = Math.max( window.scrollY, 0 );
			var delta = y - lastY;
			ticking = false;

			// Back at the top: transparent again, whatever happened before.
			if ( y <= 0 ) {
				wrapper.classList.remove( 'is-hidden' );
				header.classList.remove( 'is-scrolled' );
				lastY = 0;
				return;
			}

			if ( Math.abs( delta ) < threshold ) {
				return;
			}

			// Never hide it near the top, or while a submenu / the language list is in use.
			var inUse = header.classList.contains( 'has-submenu-open' ) || header.contains( document.activeElement ) && document.activeElement !== document.body;
			var shouldHide = delta > 0 && y > wrapper.offsetHeight && ! inUse;

			// Revealing it after it had been hidden: it must already be dark on white.
			if ( ! shouldHide && wrapper.classList.contains( 'is-hidden' ) ) {
				header.classList.add( 'is-scrolled' );
			}

			wrapper.classList.toggle( 'is-hidden', shouldHide );
			lastY = y;
		}

		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		}, { passive: true } );

		// The header stays transparent until it has been hidden for the first time. The dark
		// on white look is applied once the slide-out ends, so it is never seen changing.
		wrapper.addEventListener( 'transitionend', function ( event ) {
			if ( event.target === wrapper && event.propertyName === 'transform' && wrapper.classList.contains( 'is-hidden' ) ) {
				header.classList.add( 'is-scrolled' );
			}
		} );
	}

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
