import { InnerBlocks } from '@wordpress/block-editor';
import './editor.scss';
import { useEffect } from '@wordpress/element';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

export default function Edit( { attributes, setAttributes } ) {
	const { align, gap, style } = attributes;

	const blockProps = useBisiestoBlockProps( {
		className: `align${ align } is-layout-constrained`,
	} );

	useEffect( () => {
		let newBlockGap = gap ?? false;
		if ( style?.spacing?.blockGap ) {
			newBlockGap = style.spacing.blockGap;
			if ( newBlockGap.startsWith( 'var:' ) ) {
				newBlockGap = newBlockGap.split( '|' ).pop();
				newBlockGap = 'var(--wp--preset--spacing--' + newBlockGap + ')';
			}
		}
		setAttributes( { gap: newBlockGap } );
	}, [ attributes ] );

	return (
		<div { ...blockProps }>
			<div
				className="b-section__content alignfull has-global-padding"
				style={ gap ? { '--section-gap': gap } : undefined }
			>
				<InnerBlocks templateLock={ false } />
			</div>
		</div>
	);
}
 