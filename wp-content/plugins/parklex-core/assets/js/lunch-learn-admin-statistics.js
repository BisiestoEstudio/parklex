( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var generateButton = document.getElementById( 'lgenerate-report' );
		var downloadButton = document.getElementById( 'ldownload-report' );

		if ( ! generateButton && ! downloadButton ) {
			return;
		}

		var typeSelect = document.querySelector( '.acf-field[data-name="analytics_type"] select' );

		function postReportRequest( action, onSuccess, onError ) {
			var body = new URLSearchParams( {
				action: action,
				type: typeSelect ? typeSelect.value : '',
				nonce: window.bisLunchLearnStatistics.nonce,
			} );

			fetch( window.bisLunchLearnStatistics.ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString(),
			} )
				.then( function ( response ) { return response.json(); } )
				.then( function ( response ) {
					if ( response.success ) {
						onSuccess( response.data );
					} else {
						onError( response.data );
					}
				} )
				.catch( function ( error ) { onError( error ); } );
		}

		if ( generateButton ) {
			generateButton.addEventListener( 'click', function ( e ) {
				e.preventDefault();

				var loader = document.getElementById( 'lgenerate-report-loader' );
				var table  = document.getElementById( 'lreport-table' );

				if ( loader ) {
					loader.style.display = '';
				}

				postReportRequest( 'bis_lunch_learn_generate_report', function ( html ) {
					if ( loader ) {
						loader.style.display = 'none';
					}
					if ( table ) {
						table.innerHTML = html;
					}
				}, function ( message ) {
					if ( loader ) {
						loader.style.display = 'none';
					}
					window.alert( message );
				} );
			} );
		}

		if ( downloadButton ) {
			downloadButton.addEventListener( 'click', function ( e ) {
				e.preventDefault();

				var loader = document.getElementById( 'ldownload-report-loader' );

				if ( loader ) {
					loader.style.display = '';
				}

				postReportRequest( 'bis_lunch_learn_download_report', function ( url ) {
					if ( loader ) {
						loader.style.display = 'none';
					}

					var link = document.createElement( 'a' );
					link.href = url;
					link.download = '';
					document.body.appendChild( link );
					link.click();
					link.remove();
				}, function ( message ) {
					if ( loader ) {
						loader.style.display = 'none';
					}
					window.alert( message );
				} );
			} );
		}
	} );
} )();
