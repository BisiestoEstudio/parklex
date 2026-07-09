import { __ } from '@wordpress/i18n';
import { InspectorControls, useSettings } from '@wordpress/block-editor';
import {
	PanelBody,
	FocalPointPicker,
	BaseControl,
	ColorPalette,
	RangeControl,
	ToggleGroupControl,
	ToggleGroupControlOption,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import MediaPicker from '../../components/MediaPicker';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const { media, focalPoint, overlayColor, overlayOpacity, objectFit } =
		attributes;
	const [ colors ] = useSettings( 'color.palette' );

	const imageUrl = useSelect(
		( select ) =>
			media?.imageId
				? select( coreStore ).getMedia( media.imageId )?.source_url
				: null,
		[ media?.imageId ]
	);

	const mediaUrl =
		media?.mediaType === 'video' ? media?.videoUrl : imageUrl;

	const objectPosition = `${ focalPoint?.x * 100 }% ${ focalPoint?.y * 100 }%`;

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
					{ mediaUrl && (
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
					{ mediaUrl && (
						<FocalPointPicker
							label={ __( 'Punto focal', 'parklex-blocks' ) }
							url={ mediaUrl }
							value={ focalPoint }
							onChange={ ( value ) =>
								setAttributes( { focalPoint: value } )
							}
						/>
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
					<video
						className="b-custom-background__preview"
						src={ media.videoUrl }
						style={ { objectPosition, objectFit } }
						muted
						loop
						playsInline
					/>
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
