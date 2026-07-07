import { __ } from '@wordpress/i18n';
import { registerBlockVariation } from '@wordpress/blocks';
import { justifySpaceBetween } from '@wordpress/icons';

const VARIATION_ATTRIBUTES = {
	layout: {
		type: 'flex',
		flexWrap: 'nowrap',
		justifyContent: 'space-between',
	},
};

registerBlockVariation( 'core/group', {
	name: 'bisiesto/spec-row',
	title: __( 'Spec Row', 'parklex-blocks' ),
	description: __(
		'Fila de ficha técnica: etiqueta a la izquierda, valor en negrita a la derecha.',
		'parklex-blocks'
	),
	category: 'bisiesto',
	icon: { src: justifySpaceBetween, foreground: '#7900D8' },
	attributes: VARIATION_ATTRIBUTES,
	innerBlocks: [
		[ 'core/paragraph', { placeholder: __( 'Etiqueta', 'parklex-blocks' ) } ],
		[
			'core/paragraph',
			{
				placeholder: __( 'Valor', 'parklex-blocks' ),
				style: { typography: { fontWeight: '500' } },
			},
		],
	],
	isActive: ( blockAttributes ) =>
		blockAttributes?.layout?.type === VARIATION_ATTRIBUTES.layout.type &&
		blockAttributes?.layout?.justifyContent === VARIATION_ATTRIBUTES.layout.justifyContent,
} );
