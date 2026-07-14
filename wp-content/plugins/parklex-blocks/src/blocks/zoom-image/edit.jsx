import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	BlockControls,
	MediaPlaceholder,
	MediaReplaceFlow,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextareaControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

const ALLOWED_MEDIA_TYPES = [ 'image' ];

const ASPECT_RATIO_OPTIONS = [
	{ label: __( 'Original', 'parklex-blocks' ), value: '' },
	{ label: '16/9', value: '16/9' },
	{ label: '4/3', value: '4/3' },
	{ label: '1/1', value: '1/1' },
	{ label: '3/4', value: '3/4' },
	{ label: '9/16', value: '9/16' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { id, url, alt, aspectRatio, scale } = attributes;

	const blockProps = useBisiestoBlockProps( {} );

	const onSelectImage = ( media ) => {
		setAttributes( {
			id: media.id,
			url: media.url,
			alt: media.alt || '',
		} );
	};

	if ( ! url ) {
		return (
			<figure { ...blockProps }>
				<MediaPlaceholder
					icon="format-image"
					labels={ {
						title: __( 'Zoom Image', 'parklex-blocks' ),
						instructions: __(
							'Selecciona o sube una imagen. En dispositivos no táctiles, al pasar el ratón por encima se aplicará un zoom centrado en el puntero.',
							'parklex-blocks'
						),
					} }
					onSelect={ onSelectImage }
					accept="image/*"
					allowedTypes={ ALLOWED_MEDIA_TYPES }
					multiple={ false }
				/>
			</figure>
		);
	}

	return (
		<>
			<BlockControls>
				<MediaReplaceFlow
					mediaId={ id }
					mediaURL={ url }
					allowedTypes={ ALLOWED_MEDIA_TYPES }
					accept="image/*"
					onSelect={ onSelectImage }
				/>
			</BlockControls>

			<InspectorControls>
				<PanelBody title={ __( 'Ajustes de imagen', 'parklex-blocks' ) }>
					<SelectControl
						label={ __( 'Relación de aspecto', 'parklex-blocks' ) }
						value={ aspectRatio }
						options={ ASPECT_RATIO_OPTIONS }
						onChange={ ( value ) => setAttributes( { aspectRatio: value } ) }
					/>
					<ToggleGroupControl
						label={ __( 'Ajuste', 'parklex-blocks' ) }
						value={ scale }
						onChange={ ( value ) => setAttributes( { scale: value } ) }
						isBlock
						__nextHasNoMarginBottom
					>
						<ToggleGroupControlOption value="cover" label={ __( 'Cover', 'parklex-blocks' ) } />
						<ToggleGroupControlOption value="contain" label={ __( 'Contain', 'parklex-blocks' ) } />
					</ToggleGroupControl>
					<TextareaControl
						label={ __( 'Texto alternativo', 'parklex-blocks' ) }
						value={ alt }
						onChange={ ( value ) => setAttributes( { alt: value } ) }
						help={ __(
							'Describe la imagen para lectores de pantalla. Déjalo vacío si es decorativa.',
							'parklex-blocks'
						) }
					/>
				</PanelBody>
			</InspectorControls>

			<figure { ...blockProps }>
				<img
					className="b-zoom-image__img"
					src={ url }
					alt={ alt }
					style={ {
						aspectRatio: aspectRatio || undefined,
						objectFit: scale,
					} }
				/>
			</figure>
		</>
	);
}
