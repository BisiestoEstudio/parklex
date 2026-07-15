import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { store as editorStore } from '@wordpress/editor';
import { useState } from '@wordpress/element';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

export default function Edit( { context } ) {
	const blockProps = useBisiestoBlockProps( { className: 'alignfull is-layout-constrained' } );

	const { postId, postType } = useSelect( ( select ) => {
		const editor = select( editorStore );
		return {
			postId: context.postId ?? editor.getCurrentPostId(),
			postType: context.postType ?? editor.getCurrentPostType(),
		};
	}, [ context.postId, context.postType ] );

	const post = useSelect(
		( select ) =>
			postId && postType
				? select( coreStore ).getEditedEntityRecord( 'postType', postType, postId )
				: null,
		[ postId, postType ]
	);

	const featuredMediaId = post?.featured_media || 0;
	const galleryIds = Array.isArray( post?.acf?.gallery ) ? post.acf.gallery : [];

	const thumbIds = [ ...new Set( [ featuredMediaId, ...galleryIds ].filter( Boolean ) ) ];

	const media = useSelect(
		( select ) =>
			thumbIds.reduce( ( acc, id ) => {
				acc[ id ] = select( coreStore ).getMedia( id );
				return acc;
			}, {} ),
		[ thumbIds.join( ',' ) ]
	);

	const termIds = post?.products_type || [];

	const terms = useSelect(
		( select ) =>
			termIds.length
				? select( coreStore ).getEntityRecords( 'taxonomy', 'products_type', {
						include: termIds,
						per_page: -1,
				  } )
				: [],
		[ termIds.join( ',' ) ]
	);

	const [ activeIndex, setActiveIndex ] = useState( 0 );
	const activeId = thumbIds[ activeIndex ] ?? thumbIds[ 0 ];
	const activeUrl = media[ activeId ]?.source_url;

	return (
		<section { ...blockProps }>
			<div className="b-header-products__wrapper alignwide">
				<div className="b-header-products__content">
					{ !! terms?.length && (
						<p className="b-header-products__eyebrow">
							{ terms.map( ( term ) => term.name ).join( ', ' ) }
						</p>
					) }
					<h1 className="b-header-products__title">
						{ post?.title?.raw || __( 'Título del producto', 'parklex-blocks' ) }
					</h1>
					<div className="b-header-products__inner">
						<InnerBlocks templateLock={ false } />
					</div>
				</div>

				<div className="b-header-products__media">
					<div className="b-header-products__main-image">
						{ activeUrl ? (
							<img src={ activeUrl } alt="" />
						) : (
							<span className="b-header-products__placeholder">
								{ __( 'Selecciona una imagen destacada para el producto', 'parklex-blocks' ) }
							</span>
						) }
					</div>

					{ thumbIds.length > 1 && (
						<div className="b-header-products__thumbs">
							{ thumbIds.map( ( id, index ) => (
								<button
									key={ id }
									type="button"
									className={
										'b-header-products__thumb' +
										( index === activeIndex ? ' is-active' : '' )
									}
									onClick={ () => setActiveIndex( index ) }
								>
									{ media[ id ]?.source_url && (
										<img src={ media[ id ].source_url } alt="" />
									) }
								</button>
							) ) }
						</div>
					) }
				</div>
			</div>
		</section>
	);
}
