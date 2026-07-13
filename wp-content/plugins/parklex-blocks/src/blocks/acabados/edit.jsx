import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

export default function Edit( { attributes, setAttributes } ) {
	const { productsType } = attributes;
	const blockProps = useBisiestoBlockProps( {} );

	const terms = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'taxonomy', 'products_type', {
				per_page: -1,
				hide_empty: false,
			} ),
		[]
	);

	const products = useSelect(
		( select ) => {
			if ( ! productsType ) {
				return [];
			}
			return select( coreStore ).getEntityRecords( 'postType', 'products', {
				products_type: productsType,
				per_page: 4,
				status: 'publish',
				_fields: 'id,title,featured_media',
			} );
		},
		[ productsType ]
	);

	const mediaIds = ( products || [] ).map( ( product ) => product.featured_media ).filter( Boolean );

	const media = useSelect(
		( select ) =>
			mediaIds.length
				? select( coreStore ).getEntityRecords( 'postType', 'media', {
						include: mediaIds,
						per_page: -1,
				  } )
				: [],
		[ mediaIds.join( ',' ) ]
	);

	const getImageUrl = ( id ) => media?.find( ( item ) => item.id === id )?.source_url;

	const termOptions = [
		{ label: __( 'Selecciona un término', 'parklex-blocks' ), value: '0' },
		...( terms || [] ).map( ( term ) => ( {
			label: term.name,
			value: String( term.id ),
		} ) ),
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Tipo de producto', 'parklex-blocks' ) }>
					{ terms === null ? (
						<Spinner />
					) : (
						<SelectControl
							label={ __( 'Término', 'parklex-blocks' ) }
							value={ String( productsType ) }
							options={ termOptions }
							onChange={ ( value ) => setAttributes( { productsType: Number( value ) } ) }
						/>
					) }
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="b-acabados__grid swiper">
					<div className="swiper-wrapper">
						{ ! productsType ? (
							<p className="b-acabados__placeholder">
								{ __( 'Selecciona un término de producto en el panel lateral.', 'parklex-blocks' ) }
							</p>
						) : products === null ? (
							<Spinner />
						) : products.length === 0 ? (
							<p className="b-acabados__placeholder">
								{ __( 'No hay productos con este término.', 'parklex-blocks' ) }
							</p>
						) : (
							products.map( ( product ) => (
								<div className="b-acabados__item swiper-slide" key={ product.id }>
									<div className="b-acabados__image">
										{ getImageUrl( product.featured_media ) && (
											<img
												src={ getImageUrl( product.featured_media ) }
												alt=""
												className="b-acabados__img"
											/>
										) }
									</div>
									<h3 className="b-acabados__title">{ product.title?.rendered || '' }</h3>
								</div>
							) )
						) }
					</div>
				</div>
			</section>
		</>
	);
}
