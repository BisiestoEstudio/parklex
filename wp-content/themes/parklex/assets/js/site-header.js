/**
 * Site header (template-parts/site-header.php):
 * - Card submenus: toggle on click of the parent item (which doesn't navigate); they close on
 *   clicking it again, clicking outside, following a card link, or Escape.
 * - Collapsed menu: offset panel opened with the toggle button.
 * - Language switcher dropdown.
 * - Retractable header: hides on scroll down, reappears on scroll up.
 */
( function () {
	var header = document.querySelector( '.js-site-header' );

	if ( header ) {
		initSubmenus( header );
		initOffsetMenu( header );
	}

	document.querySelectorAll( '.js-lang-switcher' ).forEach( initLangSwitcher );

	var wrapper = document.querySelector( '.site-header-wrapper' );

	/**
	 * Collapsed menu (below the menu breakpoint): the nav becomes an offset panel opened with
	 * the toggle button. The bar height is exposed as --site-header-bar-height so the panel
	 * starts right under it.
	 */
	function initOffsetMenu( header ) {
		var toggle = header.querySelector( '.js-site-header-toggle' );
		var bar = header.querySelector( '.site-header__bar' );
		var desktop = window.matchMedia( '(min-width: 1280px)' );

		if ( ! toggle || ! bar ) {
			return;
		}

		function setOpen( isOpen ) {
			header.classList.toggle( 'is-menu-open', isOpen );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			document.documentElement.classList.toggle( 'has-site-menu-open', isOpen );
		}

		function setBarHeight() {
			header.style.setProperty( '--site-header-bar-height', bar.offsetHeight + 'px' );
		}

		toggle.addEventListener( 'click', function () {
			setBarHeight();
			setOpen( ! header.classList.contains( 'is-menu-open' ) );
		} );

		// Escape closes the panel (submenus are handled first, see initSubmenus).
		header.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && header.classList.contains( 'is-menu-open' ) && ! header.classList.contains( 'has-submenu-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );

		// Back to the full menu: reset the panel.
		desktop.addEventListener( 'change', function ( event ) {
			if ( event.matches ) {
				setOpen( false );
			}
		} );

		if ( 'ResizeObserver' in window ) {
			new ResizeObserver( setBarHeight ).observe( bar );
		}

		setBarHeight();
	}

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
			var inUse = header.classList.contains( 'has-submenu-open' ) || header.classList.contains( 'is-menu-open' ) || header.contains( document.activeElement ) && document.activeElement !== document.body;
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
		var items = header.querySelectorAll( '.site-header__item--has-children' );

		function setOpen( item, isOpen ) {
			var link = item.querySelector( ':scope > .site-header__link' );

			item.classList.toggle( 'is-open', isOpen );
			link.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}

		function closeAll() {
			items.forEach( function ( item ) {
				setOpen( item, false );
			} );
			header.classList.remove( 'has-submenu-open' );
		}

		// Opening one closes the others; clicking the open one closes it.
		function toggle( item ) {
			var willOpen = ! item.classList.contains( 'is-open' );

			closeAll();

			if ( willOpen ) {
				setOpen( item, true );
				header.classList.add( 'has-submenu-open' );
			}
		}

		items.forEach( function ( item ) {
			var link = item.querySelector( ':scope > .site-header__link' );

			link.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				toggle( item );
			} );
		} );

		// Click outside the open submenu / its menu (anywhere but the parent links and the submenu itself).
		document.addEventListener( 'click', function ( event ) {
			if ( ! event.target.closest( '.site-header__item--has-children' ) ) {
				closeAll();
			}
		} );

		// Following a card link or any other link of the header also closes it (same-page anchors).
		header.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '.site-header__submenu a' ) ) {
				closeAll();
			}
		} );

		header.addEventListener( 'focusout', function ( event ) {
			if ( event.relatedTarget && ! header.contains( event.relatedTarget ) ) {
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
				// First Escape only closes the submenu, not the offset panel too.
				event.stopImmediatePropagation();
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
