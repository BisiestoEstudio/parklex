import { __ } from '@wordpress/i18n';
import { registerBlockVariation } from '@wordpress/blocks';
import { mediaAndText } from '@wordpress/icons';

const VARIATION_ATTRIBUTES = {
	align: 'wide',
	verticalAlignment: 'bottom',
	isStackedOnMobile: true,
	linkDestination: 'none',
	className: 'b-bisiesto-variation',
	mediaWidth: 55,
};

registerBlockVariation( 'core/media-text', {
	name: 'media-text',
	title: __( 'Media Text', 'parklex-blocks' ),
	description: __(
		'Imagen y texto en ancho wide, alineado abajo y apilado en móvil.',
		'parklex-blocks'
	),
	category: 'bisiesto',
	icon: { src: mediaAndText, foreground: '#7900D8' },
	attributes: VARIATION_ATTRIBUTES,
	innerBlocks: [
		[ 'core/heading', { level: 2 } ],
		[ 'core/paragraph', {} ],
	],
	isActive: ( blockAttributes ) =>
		Object.entries( VARIATION_ATTRIBUTES ).every(
			( [ key, value ] ) => blockAttributes[ key ] === value
		),
} );

