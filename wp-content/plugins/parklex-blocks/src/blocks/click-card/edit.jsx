import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	InnerBlocks,
	RichText,
	MediaUpload,
	MediaUploadCheck,
	useSettings,
} from '@wordpress/block-editor';
import { PanelBody, BaseControl, ColorPalette, RangeControl, Button } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import LinkPicker from '../../components/LinkPicker/LinkPicker';
import './editor.scss';

const ALLOWED_BLOCKS = [ 'core/heading', 'core/paragraph' ];

const TEMPLATE = [
	[ 'core/heading', { level: 3, placeholder: __( 'Título', 'parklex-blocks' ) } ],
	[ 'core/paragraph', { placeholder: __( 'Texto', 'parklex-blocks' ) } ],
];

export default function Edit( { attributes, setAttributes } ) {
	const { image, backgroundImage, description, overlayColor, overlayOpacity, link } = attributes;
	const blockProps = useBisiestoBlockProps( {} );
	const [ colors ] = useSettings( 'color.palette' );

	const imageUrl = useSelect(
		( select ) => ( image ? select( coreStore ).getMedia( image )?.source_url : null ),
		[ image ]
	);
	const backgroundUrl = useSelect(
		( select ) =>
			backgroundImage ? select( coreStore ).getMedia( backgroundImage )?.source_url : null,
		[ backgroundImage ]
	);

	const style = {
		...( backgroundUrl ? { '--click-card-bg-image': `url(${ backgroundUrl })` } : {} ),
		...( overlayColor
			? {
					'--click-card-overlay-color': overlayColor,
					'--click-card-overlay-opacity': overlayOpacity / 100,
			  }
			: {} ),
	};

	return (
		<>
			<LinkPicker
				link={ link }
				onLinkChange={ ( value ) => setAttributes( { link: value } ) }
			/>

			<InspectorControls>
				<PanelBody title={ __( 'Imagen frontal', 'parklex-blocks' ) }>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) => setAttributes( { image: media.id } ) }
							allowedTypes={ [ 'image' ] }
							value={ image }
							render={ ( { open } ) => (
								<Button onClick={ open } variant="secondary" style={ { width: '100%' } }>
									{ image
										? __( 'Cambiar imagen', 'parklex-blocks' )
										: __( 'Seleccionar imagen', 'parklex-blocks' ) }
								</Button>
							) }
						/>
					</MediaUploadCheck>
					{ image > 0 && (
						<Button
							onClick={ () => setAttributes( { image: 0 } ) }
							variant="tertiary"
							isDestructive
							style={ { width: '100%', marginTop: '4px' } }
						>
							{ __( 'Eliminar imagen', 'parklex-blocks' ) }
						</Button>
					) }
				</PanelBody>

				<PanelBody title={ __( 'Imagen de fondo (hover)', 'parklex-blocks' ) } initialOpen={ false }>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) => setAttributes( { backgroundImage: media.id } ) }
							allowedTypes={ [ 'image' ] }
							value={ backgroundImage }
							render={ ( { open } ) => (
								<div className="b-click-card__inspector-image">
									{ backgroundUrl && (
										<img
											src={ backgroundUrl }
											alt=""
											className="b-click-card__inspector-preview"
											onClick={ open }
										/>
									) }
									<Button onClick={ open } variant="secondary" style={ { width: '100%' } }>
										{ backgroundImage
											? __( 'Cambiar imagen', 'parklex-blocks' )
											: __( 'Seleccionar imagen', 'parklex-blocks' ) }
									</Button>
								</div>
							) }
						/>
					</MediaUploadCheck>
					{ backgroundImage > 0 && (
						<Button
							onClick={ () => setAttributes( { backgroundImage: 0 } ) }
							variant="tertiary"
							isDestructive
							style={ { width: '100%', marginTop: '4px' } }
						>
							{ __( 'Eliminar imagen', 'parklex-blocks' ) }
						</Button>
					) }

					<BaseControl
						label={ __( 'Color del overlay', 'parklex-blocks' ) }
						id="click-card-overlay-color"
						help={ __(
							'Déjalo sin seleccionar para no aplicar overlay.',
							'parklex-blocks'
						) }
					>
						<ColorPalette
							colors={ colors }
							value={ overlayColor }
							onChange={ ( value ) => setAttributes( { overlayColor: value ?? '' } ) }
						/>
					</BaseControl>

					{ overlayColor && (
						<RangeControl
							label={ __( 'Opacidad del overlay', 'parklex-blocks' ) }
							value={ overlayOpacity }
							onChange={ ( value ) => setAttributes( { overlayOpacity: value ?? 0 } ) }
							min={ 0 }
							max={ 100 }
						/>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps } style={ { ...blockProps.style, ...style } }>
				<div className="b-click-card__media">
					<div className="b-click-card__front">
						{ imageUrl ? (
							<img src={ imageUrl } alt="" className="b-click-card__front-img" />
						) : (
							<span className="b-click-card__front-placeholder">
								{ __(
									'Selecciona una imagen frontal en el panel lateral',
									'parklex-blocks'
								) }
							</span>
						) }
					</div>

					{ backgroundUrl && <span className="b-click-card__background" /> }
					{ overlayColor && <span className="b-click-card__overlay" /> }

					<div className="b-click-card__description">
						<RichText
							tagName="p"
							value={ description }
							onChange={ ( value ) => setAttributes( { description: value } ) }
							placeholder={ __( 'Descripción interior (opcional)', 'parklex-blocks' ) }
						/>
					</div>
				</div>

				<div className="b-click-card__content">
					<InnerBlocks
						allowedBlocks={ ALLOWED_BLOCKS }
						template={ TEMPLATE }
						templateLock={ false }
					/>
				</div>
			</div>
		</>
	);
}
