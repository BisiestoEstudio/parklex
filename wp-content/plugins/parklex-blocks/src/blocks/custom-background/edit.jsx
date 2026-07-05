import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import MediaPicker from '../../components/MediaPicker';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const { video } = attributes;

	const blockProps = useBisiestoBlockProps( {} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Vídeo de fondo', 'parklex-blocks' ) }
					initialOpen={ true }
				>
					<MediaPicker
						mode="video-only"
						videoUrl={ video?.url || '' }
						onVideoUrlChange={ ( url ) =>
							setAttributes( {
								video: {
									url,
									posterId: video?.posterId || 0,
								},
							} )
						}
						posterId={ video?.posterId || 0 }
						onPosterChange={ ( posterId ) =>
							setAttributes( {
								video: {
									url: video?.url || '',
									posterId,
								},
							} )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ video?.url ? (
					<video
						className="b-custom-background__preview"
						src={ video.url }
						muted
						loop
						playsInline
					/>
				) : (
					<div className="b-custom-background__placeholder">
						<span>
							{ __( 'Custom Background', 'parklex-blocks' ) }
						</span>
						<p>
							{ __(
								'Selecciona un vídeo desde el panel lateral',
								'parklex-blocks'
							) }
						</p>
					</div>
				) }
			</div>
		</>
	);
}
