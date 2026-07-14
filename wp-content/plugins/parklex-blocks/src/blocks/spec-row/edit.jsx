import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

export default function Edit( { attributes, setAttributes } ) {
	const { label, value } = attributes;
	const blockProps = useBisiestoBlockProps( {} );
	const isHeading = blockProps.className?.includes( 'is-style-heading' );

	return (
		<div { ...blockProps }>
			<div className="b-spec-row__content">
				<RichText
					tagName="span"
					className={ `b-spec-row__label${ isHeading ? ' has-display-s-font-size' : '' }` }
					value={ label }
					onChange={ ( newLabel ) => setAttributes( { label: newLabel } ) }
					placeholder={ __( 'Etiqueta', 'parklex-blocks' ) }
				/>
				<RichText
					tagName="span"
					className={ `b-spec-row__value${ isHeading ? '' : ' u_bold' }` }
					value={ value }
					onChange={ ( newValue ) => setAttributes( { value: newValue } ) }
					placeholder={ __( 'Valor', 'parklex-blocks' ) }
				/>
			</div>
		</div>
	);
}
