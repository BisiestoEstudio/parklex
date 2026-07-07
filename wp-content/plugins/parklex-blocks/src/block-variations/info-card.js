import { __ } from '@wordpress/i18n';
import { registerBlockVariation } from '@wordpress/blocks';
import { blockDefault } from '@wordpress/icons';

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
	layout: { type: 'default' },
};

const SPEC_ROW = ( label, value ) => [
	'core/group',
	{
		layout: {
			type: 'flex',
			flexWrap: 'nowrap',
			justifyContent: 'space-between',
		},
	},
	[
		[ 'core/paragraph', { content: label } ],
		[ 'core/paragraph', { content: '<strong>' + value + '</strong>' } ],
	],
];

registerBlockVariation( 'core/group', {
	name: 'bisiesto/info-card',
	title: __( 'Info Card', 'parklex-blocks' ),
	description: __(
		'Tarjeta de ficha técnica: título con enlace y filas de especificaciones.',
		'parklex-blocks'
	),
	category: 'bisiesto',
	icon: { src: blockDefault, foreground: '#7900D8' },
	attributes: VARIATION_ATTRIBUTES,
	innerBlocks: [
		[
			'core/group',
			{
				layout: {
					type: 'flex',
					flexWrap: 'nowrap',
					justifyContent: 'space-between',
				},
			},
			[
				[
					'core/paragraph',
					{
						fontSize: 'display-s',
						placeholder: __( 'Título', 'parklex-blocks' ),
					},
				],
				[
					'core/paragraph',
					{ content: __( 'Ver detalles', 'parklex-blocks' ) },
				],
			],
		],
		SPEC_ROW( __( 'Largo', 'parklex-blocks' ), '' ),
		SPEC_ROW( __( 'Anchos', 'parklex-blocks' ), '' ),
		SPEC_ROW( __( 'Espesores', 'parklex-blocks' ), '' ),
		SPEC_ROW( __( 'Materia prima certificada', 'parklex-blocks' ), '' ),
		SPEC_ROW( __( 'Reacción al fuego', 'parklex-blocks' ), '' ),
	],
	isActive: ( blockAttributes ) =>
		blockAttributes?.backgroundColor === VARIATION_ATTRIBUTES.backgroundColor &&
		blockAttributes?.layout?.type === VARIATION_ATTRIBUTES.layout.type,
} );
