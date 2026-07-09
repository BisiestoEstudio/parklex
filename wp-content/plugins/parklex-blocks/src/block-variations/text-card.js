import { __ } from '@wordpress/i18n';
import { registerBlockVariation } from '@wordpress/blocks';
import { postContent } from '@wordpress/icons';

const VARIATION_ATTRIBUTES = {
	backgroundColor: 'gray-light',
	style: {
		spacing: {
			padding: {
				top: 'var:preset|spacing|m',
				right: 'var:preset|spacing|m',
				bottom: 'var:preset|spacing|m',
				left: 'var:preset|spacing|m',
			},
		},
	},
	layout: { type: 'constrained' },
};

registerBlockVariation( 'core/group', {
	name: 'bisiesto/text-card',
	title: __( 'Text Card', 'parklex-blocks' ),
	description: __(
		'Tarjeta con título y texto sobre fondo gris claro.',
		'parklex-blocks'
	),
	category: 'bisiesto',
	icon: { src: postContent, foreground: '#7900D8' },
	attributes: VARIATION_ATTRIBUTES,
	innerBlocks: [
		[ 'core/heading', { level: 3, placeholder: __( 'Título', 'parklex-blocks' ) } ],
		[ 'core/paragraph', { placeholder: __( 'Texto', 'parklex-blocks' ) } ],
	],
	isActive: ( blockAttributes ) =>
		blockAttributes?.backgroundColor === VARIATION_ATTRIBUTES.backgroundColor &&
		blockAttributes?.layout?.type === VARIATION_ATTRIBUTES.layout.type,
} );
