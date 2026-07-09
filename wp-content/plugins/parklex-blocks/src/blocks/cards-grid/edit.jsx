import { __ } from '@wordpress/i18n';
import { InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

const ALLOWED_BLOCKS = [ 'bisiesto/icon-card', 'bisiesto/click-card', 'core/group' ];

export default function Edit( { attributes, setAttributes } ) {
	const { columns, gap, style } = attributes;

	useEffect( () => {
		let newGap = gap ?? false;
		if ( style?.spacing?.blockGap ) {
			newGap = style.spacing.blockGap;
			if ( newGap.startsWith( 'var:' ) ) {
				newGap = newGap.split( '|' ).pop();
				newGap = 'var(--wp--preset--spacing--' + newGap + ')';
			}
		}
		setAttributes( { gap: newGap } );
	}, [ attributes ] );

	const blockProps = useBisiestoBlockProps( {
		style: {
			'--columns-mobile': 1,
			'--columns-tablet': Math.min( 2, columns ),
			'--columns': columns,
			...( gap ? { '--cards-grid-gap': gap } : {} ),
		},
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Columnas', 'factoria-cruzcampo-blocks' ) }>
					<RangeControl
						label={ __( 'Número de columnas', 'factoria-cruzcampo-blocks' ) }
						value={ columns }
						onChange={ ( value ) => setAttributes( { columns: value } ) }
						min={ 1 }
						max={ 4 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					templateLock={ false }
					template={ [ [ 'bisiesto/icon-card' ] ] }
					renderAppender={ InnerBlocks.ButtonBlockAppender }
				/>
			</div>
		</>
	);
}
