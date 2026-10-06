import { __ } from '@wordpress/i18n';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import './editor.scss';

export default function Edit() {
	const blockProps = useBisiestoBlockProps( {} );

	return (
		<div { ...blockProps }>
			<div className="b-interactive-map__placeholder">
				<p className="b-interactive-map__placeholder-title">
					{ __( 'Mapa interactivo', 'parklex-blocks' ) }
				</p>
				<p className="b-interactive-map__placeholder-hint">
					{ __(
						'Los pines se gestionan desde el CPT "Map Pin". El mapa con todos los pines agrupados se renderiza en el frontend.',
						'parklex-blocks'
					) }
				</p>
			</div>
		</div>
	);
}
