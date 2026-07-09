import { __ } from '@wordpress/i18n';
import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, AlignmentMatrixControl } from '@wordpress/components';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

const ALLOWED_BLOCKS = [
	'core/heading',
	'core/paragraph',
	'core/buttons',
	'core/spacer',
	'bisiesto/spacer',
	'bisiesto/background-shape',
	'bisiesto/custom-background',
];

export default function Edit( { attributes, setAttributes } ) {
	const { contentPosition } = attributes;

	const [ vPosition, hPosition ] = contentPosition.split( ' ' );
	const blockProps = useBisiestoBlockProps( { className: 'alignfull' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Posición del contenido', 'parklex-blocks' ) }>
					<AlignmentMatrixControl
						label={ __( 'Posición del contenido', 'parklex-blocks' ) }
						value={ contentPosition }
						onChange={ ( value ) =>
							setAttributes( { contentPosition: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div
					className={ `b-media-cover__content alignwide b-media-cover__content--v-${ vPosition } b-media-cover__content--h-${ hPosition }` }
				>
					<div className="b-media-cover__inner">
						<InnerBlocks
							allowedBlocks={ ALLOWED_BLOCKS }
							templateLock={ false }
						/>
					</div>
				</div>
			</section>
		</>
	);
}
