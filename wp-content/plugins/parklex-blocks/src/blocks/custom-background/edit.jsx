import { __ } from '@wordpress/i18n';
import { InspectorControls, useSettings } from '@wordpress/block-editor';
import {
	PanelBody,
	FocalPointPicker,
	AlignmentMatrixControl,
	BaseControl,
	ColorPalette,
	RangeControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControl as ToggleGroupControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import MediaPicker from '../../components/MediaPicker';
import { getVimeoId, getVimeoEmbedUrl } from '../../utils/getVimeoId';
import { getAlignmentXY } from '../../utils/getAlignmentXY';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const {
		media,
		focalPoint,
		overlayColor,
		overlayOpacity,
		objectFit,
		videoPosition,
	} = attributes;
	const [ colors ] = useSettings( 'color.palette' );

	const imageUrl = useSelect(
		( select ) =>
			media?.imageId
				? select( coreStore ).getMedia( media.imageId )?.source_url
				: null,
		[ media?.imageId ]
	);

	const vimeo =
		media?.mediaType === 'video' ? getVimeoId( media?.videoUrl ) : null;

	const objectPosition = `${ focalPoint?.x * 100 }% ${ focalPoint?.y * 100 }%`;
	const videoPositionXY = getAlignmentXY( videoPosition );
	const videoObjectPosition = `${ videoPositionXY.x }% ${ videoPositionXY.y }%`;

	const blockProps = useBisiestoBlockProps( {} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Imagen o vídeo de fondo', 'parklex-blocks' ) }
					initialOpen={ true }
				>
					<MediaPicker
						mode="both"
						mediaType={ media?.mediaType }
						onMediaTypeChange={ ( mediaType ) =>
							setAttributes( { media: { ...media, mediaType } } )
						}
						imageId={ media?.imageId }
						onImageChange={ ( imageId ) =>
							setAttributes( { media: { ...media, imageId } } )
						}
						videoUrl={ media?.videoUrl }
						onVideoUrlChange={ ( videoUrl ) =>
							setAttributes( { media: { ...media, videoUrl } } )
						}
						posterId={ media?.posterId }
						onPosterChange={ ( posterId ) =>
							setAttributes( { media: { ...media, posterId } } )
						}
					/>
					{ media?.mediaType === 'image' && imageUrl && (
						<ToggleGroupControl
							label={ __( 'Ajuste', 'parklex-blocks' ) }
							value={ objectFit }
							onChange={ ( value ) =>
								setAttributes( { objectFit: value } )
							}
							isBlock
							__nextHasNoMarginBottom
						>
							<ToggleGroupControlOption
								value="cover"
								label={ __( 'Cover', 'parklex-blocks' ) }
							/>
							<ToggleGroupControlOption
								value="contain"
								label={ __( 'Contain', 'parklex-blocks' ) }
							/>
						</ToggleGroupControl>
					) }
					{ media?.mediaType === 'image' && imageUrl && (
						<FocalPointPicker
							label={ __( 'Punto focal', 'parklex-blocks' ) }
							url={ imageUrl }
							value={ focalPoint }
							onChange={ ( value ) =>
								setAttributes( { focalPoint: value } )
							}
						/>
					) }
					{ media?.mediaType === 'video' && media?.videoUrl && (
						<BaseControl
							label={ __( 'Posición del vídeo', 'parklex-blocks' ) }
							id="custom-background-video-position"
							help={ __(
								'Punto del vídeo que queda fijo al recortarlo para cubrir el contenedor.',
								'parklex-blocks'
							) }
						>
							<AlignmentMatrixControl
								value={ videoPosition }
								onChange={ ( value ) =>
									setAttributes( { videoPosition: value } )
								}
							/>
						</BaseControl>
					) }
				</PanelBody>

				<PanelBody
					title={ __( 'Overlay', 'parklex-blocks' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Color del overlay', 'parklex-blocks' ) }
						id="custom-background-overlay-color"
						help={ __(
							'Déjalo sin seleccionar para no aplicar overlay.',
							'parklex-blocks'
						) }
					>
						<ColorPalette
							colors={ colors }
							value={ overlayColor }
							onChange={ ( value ) =>
								setAttributes( { overlayColor: value ?? '' } )
							}
						/>
					</BaseControl>

					{ overlayColor && (
						<RangeControl
							label={ __( 'Opacidad del overlay', 'parklex-blocks' ) }
							value={ overlayOpacity }
							onChange={ ( value ) =>
								setAttributes( { overlayOpacity: value ?? 0 } )
							}
							min={ 0 }
							max={ 100 }
						/>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ media?.mediaType === 'video' && media?.videoUrl ? (
					vimeo ? (
						<iframe
							className="b-custom-background__preview"
							src={ getVimeoEmbedUrl( vimeo ) }
							title={ __(
								'Vista previa del vídeo de Vimeo',
								'parklex-blocks'
							) }
							allow="autoplay; fullscreen; picture-in-picture"
							tabIndex={ -1 }
						/>
					) : (
						<video
							className="b-custom-background__preview"
							src={ media.videoUrl }
							style={ {
								objectPosition: videoObjectPosition,
								objectFit: 'cover',
							} }
							muted
							loop
							playsInline
						/>
					)
				) : imageUrl ? (
					<img
						className="b-custom-background__preview"
						src={ imageUrl }
						style={ { objectPosition, objectFit } }
						alt=""
					/>
				) : (
					<div className="b-custom-background__placeholder">
						<span>
							{ __( 'Custom Background', 'parklex-blocks' ) }
						</span>
						<p>
							{ __(
								'Selecciona una imagen o vídeo desde el panel lateral',
								'parklex-blocks'
							) }
						</p>
					</div>
				) }

				{ overlayColor && (
					<span
						className="b-custom-background__overlay"
						style={ {
							backgroundColor: overlayColor,
							opacity: overlayOpacity / 100,
						} }
					/>
				) }
			</div>
		</>
	);
}
