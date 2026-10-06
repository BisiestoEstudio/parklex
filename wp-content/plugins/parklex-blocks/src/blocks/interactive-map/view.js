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
		// Esri ya declara maxZoom en el tileLayer, pero lo fijamos también aquí: si esa
		// capa fallara al cargar, leaflet.markercluster necesita igualmente uno finito.
		maxZoom: 19,
		maxBounds: [
			[ -85, -200 ],
			[ 85, 200 ],
		],
	} ).setView( [ 20, 0 ], 2 );

	// Tiles ráster gratuitos de Esri: sin API key, sin login, con relieve/topografía.
	L.tileLayer(
		'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}',
		{
			attribution:
				'Tiles &copy; Esri &mdash; Sources: Esri, HERE, Garmin, Intermap, increment P Corp., GEBCO, USGS, FAO, NPS, NRCAN, GeoBase, IGN, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), (c) OpenStreetMap contributors, and the GIS User Community',
			maxZoom: 19,
		}
	).addTo( map );

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
	const lines = [];

	const location = [ pin.city, pin.country[ 0 ] ].filter( Boolean ).join( ', ' );
	if ( location ) {
		lines.push( `<span>${ escapeHtml( location ) }</span>` );
	}

	lines.push( `<span class="has-h-3-font-size">${ escapeHtml( pin.title ) }</span>` );

	if ( pin.architect ) {
		lines.push( `<span>${ escapeHtml( pin.architect ) }</span>` );
	}
	if ( pin.year[ 0 ] ) {
		lines.push( `<span>${ escapeHtml( pin.year[ 0 ] ) }</span>` );
	}
	if ( pin.product.length ) {
		lines.push( `<span>${ escapeHtml( pin.product.join( ' | ' ) ) }</span>` );
	}
	if ( pin.finish.length ) {
		lines.push( `<span>${ escapeHtml( pin.finish.join( ' | ' ) ) }</span>` );
	}

	return `<div class="b-interactive-map__popup">${ lines.join( '' ) }</div>`;
}

function escapeHtml( value ) {
	const div = document.createElement( 'div' );
	div.textContent = value ?? '';
	return div.innerHTML;
}
