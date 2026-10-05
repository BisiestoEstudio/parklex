import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button, ToggleControl, TextControl } from '@wordpress/components';
import './MediaPicker.scss';

function VideoSelector( { videoUrl, onVideoUrlChange } ) {
	return (
		<div className="media-picker__video">
			<TextControl
				label={ __( 'URL de Vimeo', 'parklex-blocks' ) }
				help={ __(
					'Pega la URL del vídeo de Vimeo, p. ej. https://vimeo.com/123456789',
					'parklex-blocks'
				) }
				value={ videoUrl }
				onChange={ onVideoUrlChange }
				placeholder="https://vimeo.com/123456789"
			/>
		</div>
	);
}

function ImageSelector( { imageId, onImageChange } ) {
	const imageUrl = useSelect(
		( select ) => {
			if ( ! imageId ) {
				return null;
			}
			return select( coreStore ).getMedia( imageId )?.source_url ?? null;
		},
		[ imageId ]
	);

	return (
		<MediaUploadCheck>
			<MediaUpload
				onSelect={ ( media ) => onImageChange( media.id ) }
				allowedTypes={ [ 'image' ] }
				value={ imageId }
				render={ ( { open } ) => (
					<div className="media-picker__selector">
						{ imageUrl && (
							<div
								className="media-picker__preview"
								onClick={ open }
							>
								<img src={ imageUrl } alt="" />
							</div>
						) }
						<Button
							onClick={ open }
							variant="secondary"
							style={ { width: '100%' } }
						>
							{ imageId
								? __(
										'Cambiar imagen',
										'factoria-cruzcampo-blocks'
								  )
								: __(
										'Seleccionar imagen',
										'factoria-cruzcampo-blocks'
								  ) }
						</Button>
						{ imageId > 0 && (
							<Button
								onClick={ ( e ) => {
									e.stopPropagation();
									onImageChange( 0 );
								} }
								variant="tertiary"
								isDestructive
								style={ { width: '100%', marginTop: '4px' } }
							>
								{ __(
									'Eliminar imagen',
									'factoria-cruzcampo-blocks'
								) }
							</Button>
						) }
					</div>
				) }
			/>
		</MediaUploadCheck>
	);
}

export default function MediaPicker( {
	mode = 'both',
	mediaType = 'image',
	onMediaTypeChange,
	imageId = 0,
	onImageChange,
	videoUrl = '',
	onVideoUrlChange,
} ) {
	const showToggle = mode === 'both';
	const showImage =
		mode === 'image-only' || ( mode === 'both' && mediaType === 'image' );
	const showVideo =
		mode === 'video-only' || ( mode === 'both' && mediaType === 'video' );

	return (
		<div className="media-picker">
			{ showToggle && (
				<ToggleControl
					label={
						mediaType === 'image'
							? __( 'Imagen', 'factoria-cruzcampo-blocks' )
							: __( 'Vídeo', 'factoria-cruzcampo-blocks' )
					}
					checked={ mediaType === 'video' }
					onChange={ ( val ) =>
						onMediaTypeChange( val ? 'video' : 'image' )
					}
				/>
			) }
			{ showImage && (
				<ImageSelector
					imageId={ imageId }
					onImageChange={ onImageChange }
				/>
			) }
			{ showVideo && (
				<VideoSelector
					videoUrl={ videoUrl }
					onVideoUrlChange={ onVideoUrlChange }
				/>
			) }
		</div>
	);
}
