import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	RichText,
	MediaUpload,
	MediaUploadCheck,
	useSettings,
} from '@wordpress/block-editor';
import { PanelBody, BaseControl, ColorPalette, RangeControl, Button } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const { icon, title, description, overlayColor, overlayOpacity } = attributes;
	const blockProps = useBisiestoBlockProps( {} );
	const [ colors ] = useSettings( 'color.palette' );

	const iconUrl = useSelect(
		( select ) => ( icon ? select( coreStore ).getMedia( icon )?.source_url : null ),
		[ icon ]
	);

	const style = {
		'--icon-card-overlay-color': overlayColor,
		'--icon-card-overlay-opacity': overlayOpacity / 100,
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Icono', 'parklex-blocks' ) }>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) => setAttributes( { icon: media.id } ) }
							allowedTypes={ [ 'image' ] }
							value={ icon }
							render={ ( { open } ) => (
								<div className="b-icon-card__inspector-icon">
									{ iconUrl ? (
										<img
											src={ iconUrl }
											alt=""
											className="b-icon-card__inspector-preview"
											onClick={ open }
										/>
									) : (
										<Button onClick={ open } variant="secondary">
											{ __( 'Seleccionar icono', 'parklex-blocks' ) }
										</Button>
									) }
								</div>
							) }
						/>
					</MediaUploadCheck>
					{ icon > 0 && (
						<Button
							onClick={ () => setAttributes( { icon: 0 } ) }
							variant="tertiary"
							isDestructive
						>
							{ __( 'Eliminar icono', 'parklex-blocks' ) }
						</Button>
					) }
				</PanelBody>

				<PanelBody title={ __( 'Imagen de fondo', 'parklex-blocks' ) }>
					<p className="b-icon-card__inspector-hint">
						{ __(
							'Selecciona la imagen de fondo desde Estilos > Fondo, en el panel lateral del bloque.',
							'parklex-blocks'
						) }
					</p>
					<BaseControl label={ __( 'Color del overlay', 'parklex-blocks' ) } id="icon-card-overlay-color">
						<ColorPalette
							colors={ colors }
							value={ overlayColor }
							onChange={ ( value ) => setAttributes( { overlayColor: value ?? '#020202' } ) }
						/>
					</BaseControl>
					<RangeControl
						label={ __( 'Opacidad del overlay', 'parklex-blocks' ) }
						value={ overlayOpacity }
						onChange={ ( value ) => setAttributes( { overlayOpacity: value ?? 0 } ) }
						min={ 0 }
						max={ 100 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps } style={ style }>
				<span className="b-icon-card__overlay" />
				<span className="b-icon-card__mask" />

				<div className="b-icon-card__content">
					<div className="b-icon-card__icon">
						{ iconUrl ? (
							<img src={ iconUrl } alt="" className="b-icon-card__icon-img" />
						) : (
							<span className="b-icon-card__icon-placeholder">
								{ __( 'Icono', 'parklex-blocks' ) }
							</span>
						) }
					</div>

					<div className="b-icon-card__text">
						<RichText
							tagName="h3"
							className="b-icon-card__title"
							value={ title }
							onChange={ ( value ) => setAttributes( { title: value } ) }
							placeholder={ __( 'Título', 'parklex-blocks' ) }
							allowedFormats={ [] }
						/>

						<div className="b-icon-card__description">
							<div className="b-icon-card__description-inner">
								<RichText
									tagName="p"
									value={ description }
									onChange={ ( value ) => setAttributes( { description: value } ) }
									placeholder={ __( 'Descripción', 'parklex-blocks' ) }
								/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
