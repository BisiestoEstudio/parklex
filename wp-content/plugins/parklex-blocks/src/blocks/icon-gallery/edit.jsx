import { __ } from '@wordpress/i18n';
import { createBlock } from '@wordpress/blocks';
import {
	MediaPlaceholder,
	BlockControls,
	MediaReplaceFlow,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { useDispatch, useSelect } from '@wordpress/data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

const ALLOWED_MEDIA_TYPES = [ 'image' ];

function imagesToBlocks( media ) {
	return media
		.filter( ( item ) => item.url )
		.map( ( item ) =>
			createBlock( 'core/image', {
				id: item.id,
				url: item.url,
				alt: item.alt || '',
				caption: item.caption || '',
			} )
		);
}

export default function Edit( { attributes, clientId } ) {
	const blockProps = useBisiestoBlockProps( {
		className: `align${ attributes.align }`,
	} );

	const innerImageBlocks = useSelect(
		( select ) => select( blockEditorStore ).getBlocks( clientId ),
		[ clientId ]
	);

	const { replaceInnerBlocks, insertBlocks } = useDispatch( blockEditorStore );

	const hasImages = innerImageBlocks.length > 0;

	const onSelectImages = ( media ) => {
		const newBlocks = imagesToBlocks( media );
		if ( ! newBlocks.length ) {
			return;
		}
		if ( hasImages ) {
			insertBlocks( newBlocks, innerImageBlocks.length, clientId );
		} else {
			replaceInnerBlocks( clientId, newBlocks );
		}
	};

	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'core/image' ],
		orientation: 'horizontal',
		templateLock: false,
	} );

	return (
		<>
			<BlockControls group="other">
				<MediaReplaceFlow
					allowedTypes={ ALLOWED_MEDIA_TYPES }
					accept="image/*"
					multiple
					mediaIds={ innerImageBlocks.map(
						( block ) => block.attributes.id
					) }
					addToGallery={ hasImages }
					onSelect={ onSelectImages }
					name={ __( 'Añadir imágenes', 'parklex-blocks' ) }
				/>
			</BlockControls>
			{ hasImages ? (
				<div { ...innerBlocksProps } />
			) : (
				<div { ...blockProps }>
					<MediaPlaceholder
						icon="grid-view"
						labels={ {
							title: __( 'Icon Gallery', 'parklex-blocks' ),
							instructions: __(
								'Selecciona o sube las imágenes de iconos para esta galería.',
								'parklex-blocks'
							),
						} }
						onSelect={ onSelectImages }
						accept="image/*"
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						multiple
					/>
				</div>
			) }
		</>
	);
}
