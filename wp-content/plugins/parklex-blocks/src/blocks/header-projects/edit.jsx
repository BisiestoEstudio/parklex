import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { store as editorStore } from '@wordpress/editor';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';

function useTermNames( termIds, taxonomy ) {
	return useSelect(
		( select ) =>
			termIds?.length
				? select( coreStore ).getEntityRecords( 'taxonomy', taxonomy, {
						include: termIds,
						per_page: -1,
				  } )
				: [],
		[ termIds?.join( ',' ), taxonomy ]
	);
}

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
	const featuredMedia = useSelect(
		( select ) => ( featuredMediaId ? select( coreStore ).getMedia( featuredMediaId ) : null ),
		[ featuredMediaId ]
	);

	const authorTerms = useTermNames( post?.project_author, 'project_author' );
	const workTypeTerms = useTermNames( post?.type_work, 'type_work' );
	const solutionsTerms = useTermNames( post?.app, 'app' );

	const authorLabel = authorTerms?.length ? authorTerms.map( ( term ) => term.name ).join( ', ' ) : '';
	const workTypeLabel = workTypeTerms?.length ? workTypeTerms.map( ( term ) => term.name ).join( ', ' ) : '—';
	const solutionsLabel = solutionsTerms?.length ? solutionsTerms.map( ( term ) => term.name ).join( ', ' ) : '—';

	// Año, localización, producto, acabado y fotografía viven en un grupo ACF
	// que no se expone por REST, así que en el editor solo se puede mostrar un placeholder.
	const acfPlaceholder = '—';

	return (
		<section { ...blockProps }>
			<div className="b-header-projects__wrapper alignwide">
				<div className="b-header-projects__media">
					{ featuredMedia?.source_url ? (
						<img
							className="b-header-projects__main-img"
							src={ featuredMedia.source_url }
							alt=""
						/>
					) : (
						<span className="b-header-projects__placeholder">
							{ __( 'Selecciona una imagen destacada para el proyecto', 'parklex-blocks' ) }
						</span>
					) }
				</div>

				<div className="b-header-projects__info">
					<div className="b-header-projects__intro">
						<h1 className="b-header-projects__title">
							{ post?.title?.raw || __( 'Título del proyecto', 'parklex-blocks' ) }
						</h1>
						{ authorLabel && <p className="b-header-projects__author">{ authorLabel }</p> }
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--year">
						<span className="b-header-projects__spec-label">{ __( 'Año', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ acfPlaceholder }</span>
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--location">
						<span className="b-header-projects__spec-label">{ __( 'Localización', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ acfPlaceholder }</span>
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--product">
						<span className="b-header-projects__spec-label">{ __( 'Producto', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ acfPlaceholder }</span>
					</div>

					<div className="b-header-projects__content">
						<InnerBlocks templateLock={ false } />
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--acabado">
						<span className="b-header-projects__spec-label">{ __( 'Acabado', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ acfPlaceholder }</span>
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--photo">
						<span className="b-header-projects__spec-label">{ __( 'Fotografía', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ acfPlaceholder }</span>
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--worktype">
						<span className="b-header-projects__spec-label">{ __( 'Tipo de obra', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ workTypeLabel }</span>
					</div>

					<div className="b-header-projects__spec b-header-projects__spec--solutions">
						<span className="b-header-projects__spec-label">{ __( 'Soluciones', 'parklex-blocks' ) }</span>
						<span className="b-header-projects__spec-value">{ solutionsLabel }</span>
					</div>
				</div>
			</div>
		</section>
	);
}
