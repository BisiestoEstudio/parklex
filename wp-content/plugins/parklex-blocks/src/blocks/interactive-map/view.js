addEventListener( 'DOMContentLoaded', function () {
	document.querySelectorAll( '.b-interactive-map__map' ).forEach( initMap );
} );

function initMap( container ) {
	if ( typeof L === 'undefined' ) {
		console.error( 'Leaflet no está cargado; el mapa interactivo no puede inicializarse.' );
		return;
	}

	const map = L.map( container, {
		scrollWheelZoom: false,
		minZoom: 2,
		// Sin esta capa de tiles no hay nada que le dé al mapa un maxZoom implícito,
		// y leaflet.markercluster exige uno finito (si no, lanza al añadir el grupo).
		maxZoom: 18,
		maxBounds: [
			[ -85, -200 ],
			[ 85, 200 ],
		],
	} ).setView( [ 20, 0 ], 2 );

	// Países en vez de tiles online: sin API key, sin límite de peticiones, offline.
	fetch( container.dataset.worldGeojson )
		.then( ( response ) => response.json() )
		.then( ( world ) => {
			L.geoJSON( world, {
				interactive: false,
				style: {
					color: '#c7cdd6',
					weight: 0.8,
					fillColor: '#f1f1f1',
					fillOpacity: 1,
				},
			} ).addTo( map );
			map.attributionControl.setPrefix( false );
			map.attributionControl.addAttribution( 'Países: Natural Earth' );
		} )
		.catch( ( error ) => {
			console.error( 'No se pudo cargar el mapa base.', error );
		} );

	// Si leaflet.markercluster no ha cargado por lo que sea, no bloqueamos los pines:
	// se muestran sueltos (sin agrupar) en vez de no mostrarse.
	const pinsLayer =
		typeof L.markerClusterGroup === 'function'
			? L.markerClusterGroup()
			: ( console.error( 'leaflet.markercluster no está disponible; se muestran los pines sin agrupar.' ),
			  L.featureGroup() );

	fetch( container.dataset.mapPinsEndpoint )
		.then( ( response ) => response.json() )
		.then( ( pins ) => {
			const markers = pins
				.filter( ( pin ) => typeof pin.lat === 'number' && typeof pin.lng === 'number' )
				.map( ( pin ) =>
					L.marker( [ pin.lat, pin.lng ], { icon: pinIcon() } ).bindPopup( buildPopupContent( pin ) )
				);

			if ( ! markers.length ) {
				console.warn( 'El mapa no tiene pines con coordenadas válidas que mostrar.' );
				return;
			}

			if ( typeof pinsLayer.addLayers === 'function' ) {
				pinsLayer.addLayers( markers );
			} else {
				markers.forEach( ( marker ) => pinsLayer.addLayer( marker ) );
			}

			map.addLayer( pinsLayer );
			map.fitBounds( pinsLayer.getBounds(), { padding: [ 40, 40 ] } );
			console.info( `Mapa interactivo: ${ markers.length } pines cargados.` );
		} )
		.catch( ( error ) => {
			console.error( 'No se pudieron cargar los pines del mapa.', error );
		} );
}

function pinIcon() {
	return L.divIcon( {
		className: 'b-interactive-map__pin',
		iconSize: [ 16, 16 ],
	} );
}

function buildPopupContent( pin ) {
	const lines = [ `<strong>${ escapeHtml( pin.title ) }</strong>` ];

	const location = [ pin.city, pin.country[ 0 ] ].filter( Boolean ).join( ', ' );
	if ( location ) {
		lines.push( `<span>${ escapeHtml( location ) }</span>` );
	}
	if ( pin.year[ 0 ] ) {
		lines.push( `<span>${ escapeHtml( pin.year[ 0 ] ) }</span>` );
	}
	if ( pin.architect ) {
		lines.push( `<span>${ escapeHtml( pin.architect ) }</span>` );
	}
	if ( pin.product.length ) {
		lines.push( `<span>${ escapeHtml( pin.product.join( ', ' ) ) }</span>` );
	}
	if ( pin.finish.length ) {
		lines.push( `<span>${ escapeHtml( pin.finish.join( ', ' ) ) }</span>` );
	}

	return `<div class="b-interactive-map__popup">${ lines.join( '' ) }</div>`;
}

function escapeHtml( value ) {
	const div = document.createElement( 'div' );
	div.textContent = value ?? '';
	return div.innerHTML;
}
