/* global JSZip, JSZipUtils, saveAs, Fancybox, Packery */
( function () {
	function initArchiveFilters() {
		var form = document.getElementById( 'internal-project-search-form' );

		if ( ! form ) {
			return;
		}

		function reload() {
			var params = new URLSearchParams();
			var search = form.querySelector( '#internal-project-search' );

			if ( search && search.value ) {
				params.set( 'search', search.value );
			}

			form.querySelectorAll( '[data-tax]' ).forEach( function ( select ) {
				if ( select.value ) {
					params.set( select.dataset.tax, select.value );
				}
			} );

			var query = params.toString();
			window.location.href = form.action + ( query ? '?' + query : '' );
		}

		form.querySelectorAll( '[data-tax]' ).forEach( function ( select ) {
			select.addEventListener( 'change', reload );
		} );

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			reload();
		} );
	}

	function initGallery() {
		var grids = document.querySelectorAll( '.images-grid-inpr' );

		if ( ! grids.length || 'undefined' === typeof Packery ) {
			return;
		}

		grids.forEach( function ( grid ) {
			var holder = grid.querySelector( '.holder' );

			if ( ! holder ) {
				return;
			}

			var packery = new Packery( holder, {
				itemSelector: '.grid-item',
				columnWidth: '.grid-sizer',
				percentPosition: true,
				initLayout: false,
			} );

			holder.classList.add( 'packery-init' );
			packery.layout();

			holder.querySelectorAll( 'img[loading="lazy"]' ).forEach( function ( img ) {
				img.addEventListener( 'load', function () {
					packery.layout();
				} );
			} );
		} );

		if ( 'undefined' !== typeof Fancybox ) {
			Fancybox.bind( '[data-fancybox]' );
		}
	}

	function initDownloadZip() {
		var button = document.querySelector( '.download-zip' );

		if ( ! button || 'undefined' === typeof JSZip ) {
			return;
		}

		button.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			var confirmText = button.dataset.confirmText;

			if ( confirmText && ! window.confirm( confirmText ) ) {
				return;
			}

			var urls = JSON.parse( button.dataset.urls || '[]' );
			var fileName = button.dataset.filename || 'internal-project';
			var spinner = button.querySelector( '.download-zip__spinner' );
			var zip = new JSZip();

			if ( spinner ) {
				spinner.hidden = false;
			}

			var fetches = urls.map( function ( url ) {
				return new Promise( function ( resolve, reject ) {
					JSZipUtils.getBinaryContent( url, function ( error, data ) {
						if ( error ) {
							reject( error );
							return;
						}

						zip.file( url.split( '/' ).pop(), data, { binary: true } );
						resolve();
					} );
				} );
			} );

			Promise.all( fetches )
				.then( function () {
					return zip.generateAsync( { type: 'blob' } );
				} )
				.then( function ( content ) {
					saveAs( content, fileName + '.zip' );
				} )
				.finally( function () {
					if ( spinner ) {
						spinner.hidden = true;
					}
				} );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initArchiveFilters();
		initGallery();
		initDownloadZip();
	} );
} )();
