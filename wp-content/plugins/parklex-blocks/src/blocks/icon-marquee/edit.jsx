import { __ } from '@wordpress/i18n';
import { createBlock } from '@wordpress/blocks';
import {
	MediaPlaceholder,
	BlockControls,
	MediaReplaceFlow,
	InspectorControls,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl } from '@wordpress/components';
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

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { speed, repeatCount, invertDirection } = attributes;

	const blockProps = useBisiestoBlockProps( {
		className: `align${ attributes.align }${
			invertDirection ? ' b-icon-marquee--invert' : ''
		}`,
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
			<InspectorControls>
				<PanelBody title={ __( 'Marquee', 'parklex-blocks' ) }>
					<RangeControl
						label={ __( 'Velocidad', 'parklex-blocks' ) }
						value={ speed }
						onChange={ ( value ) =>
							setAttributes( { speed: value ?? 0 } )
						}
						min={ -5 }
						max={ 5 }
						step={ 1 }
					/>
					<RangeControl
						label={ __( 'Repeticiones', 'parklex-blocks' ) }
						help={ __(
							'Sube este valor si hay pocos logos y queda espacio vacío en pantalla.',
							'parklex-blocks'
						) }
						value={ repeatCount }
						onChange={ ( value ) =>
							setAttributes( { repeatCount: value ?? 2 } )
						}
						min={ 2 }
						max={ 6 }
						step={ 1 }
					/>
					<ToggleControl
						label={ __( 'Invertir dirección', 'parklex-blocks' ) }
						checked={ !! invertDirection }
						onChange={ ( value ) =>
							setAttributes( { invertDirection: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
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
						icon="controls-repeat"
						labels={ {
							title: __( 'Icon Marquee', 'parklex-blocks' ),
							instructions: __(
								'Selecciona o sube las imágenes que scrollearán horizontalmente.',
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
