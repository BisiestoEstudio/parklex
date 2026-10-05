/**
 * Extracts the numeric ID (and optional privacy hash) from a Vimeo URL.
 * Supports vimeo.com/ID, vimeo.com/ID/HASH, player.vimeo.com/video/ID and channel URLs.
 *
 * @param {string} url
 * @return {{id: string, hash: string|null}|null}
 */
export function getVimeoId( url = '' ) {
	const match = String( url ).match(
		/vimeo(?:\.com)?\/(?:.*\/)?(?:video\/)?(\d+)(?:\/([0-9a-z]+))?/i
	);

	if ( ! match ) {
		return null;
	}

	return { id: match[ 1 ], hash: match[ 2 ] || null };
}

/**
 * Builds a Vimeo player embed URL configured as a silent, autoplaying, looping background video.
 *
 * @param {{id: string, hash: string|null}} vimeo
 * @return {string}
 */
export function getVimeoEmbedUrl( vimeo ) {
	if ( ! vimeo?.id ) {
		return '';
	}

	const params = new URLSearchParams( {
		background: '1',
		autoplay: '1',
		loop: '1',
		muted: '1',
		byline: '0',
		title: '0',
		portrait: '0',
		dnt: '1',
	} );

	if ( vimeo.hash ) {
		params.set( 'h', vimeo.hash );
	}

	return `https://player.vimeo.com/video/${ vimeo.id }?${ params.toString() }`;
}
