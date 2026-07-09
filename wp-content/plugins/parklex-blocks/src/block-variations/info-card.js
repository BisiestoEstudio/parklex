import { __ } from '@wordpress/i18n';
import { registerBlockVariation } from '@wordpress/blocks';
import { receipt } from '@wordpress/icons';

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

const SPEC_ROW = ( labelPlaceholder, valuePlaceholder ) => [
	'core/group',
	{
		layout: {
			type: 'flex',
			flexWrap: 'nowrap',
			justifyContent: 'space-between',
		},
	},
	[
		[ 'core/paragraph', { placeholder: labelPlaceholder } ],
		[
			'core/paragraph',
			{
				placeholder: valuePlaceholder,
				style: { typography: { fontWeight: '500' } },
			},
		],
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
	icon: { src: receipt, foreground: '#7900D8' },
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
		SPEC_ROW(
			__( 'Etiqueta', 'parklex-blocks' ),
			__( 'Valor', 'parklex-blocks' )
		),
		SPEC_ROW(
			__( 'Etiqueta', 'parklex-blocks' ),
			__( 'Valor', 'parklex-blocks' )
		),
	],
	isActive: ( blockAttributes ) =>
		blockAttributes?.backgroundColor === VARIATION_ATTRIBUTES.backgroundColor &&
		blockAttributes?.layout?.type === VARIATION_ATTRIBUTES.layout.type,
} );
