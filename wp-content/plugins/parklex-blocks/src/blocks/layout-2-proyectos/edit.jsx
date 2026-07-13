import { __ } from '@wordpress/i18n';
import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import ProjectSearchControl from '../../components/ProjectSearchControl';

const ALLOWED_BLOCKS = [ 'core/heading', 'core/paragraph', 'core/buttons', 'core/spacer', 'bisiesto/spacer' ];

const TEMPLATE = [
	[ 'core/heading', { placeholder: __( 'Título', 'parklex-blocks' ) } ],
	[ 'core/paragraph', { placeholder: __( 'Texto...', 'parklex-blocks' ) } ],
];

export default function Edit( { attributes, setAttributes } ) {
	const { project1, project2 } = attributes;
	const blockProps = useBisiestoBlockProps( { className: 'alignfull is-layout-constrained' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Proyectos', 'parklex-blocks' ) }>
					<ProjectSearchControl
						label={ __( 'Proyecto 1', 'parklex-blocks' ) }
						value={ project1 }
						onChange={ ( value ) => setAttributes( { project1: value } ) }
					/>
					<ProjectSearchControl
						label={ __( 'Proyecto 2', 'parklex-blocks' ) }
						value={ project2 }
						onChange={ ( value ) => setAttributes( { project2: value } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="b-layout-2-proyectos__wrapper alignwide">
					<div className="b-layout-2-proyectos__text">
						<InnerBlocks
							allowedBlocks={ ALLOWED_BLOCKS }
							template={ TEMPLATE }
							templateLock={ false }
						/>
					</div>
					<ProjectCardPreview projectId={ project1 } modifier="1" />
					<ProjectCardPreview projectId={ project2 } modifier="2" />
				</div>
			</section>
		</>
	);
}

function ProjectCardPreview( { projectId, modifier } ) {
	const project = useSelect(
		( select ) =>
			projectId
				? select( coreStore ).getEntityRecord( 'postType', 'proyecto', projectId, {
						_fields: 'id,title,featured_media,project_author',
				  } )
				: null,
		[ projectId ]
	);

	const imageUrl = useSelect(
		( select ) =>
			project?.featured_media
				? select( coreStore ).getMedia( project.featured_media )?.source_url
				: null,
		[ project?.featured_media ]
	);

	const authorTermId = project?.project_author?.[ 0 ];

	const authorTerm = useSelect(
		( select ) =>
			authorTermId
				? select( coreStore ).getEntityRecord( 'taxonomy', 'project_author', authorTermId )
				: null,
		[ authorTermId ]
	);

	return (
		<div className={ `b-layout-2-proyectos__project b-layout-2-proyectos__project--${ modifier }` }>
			<div className="b-layout-2-proyectos__image">
				{ imageUrl ? (
					<img src={ imageUrl } alt="" />
				) : (
					<span className="b-layout-2-proyectos__placeholder">
						{ __( 'Selecciona un proyecto en el panel lateral', 'parklex-blocks' ) }
					</span>
				) }
			</div>
			{ project && (
				<p className="b-layout-2-proyectos__caption has-caption-font-size">
					<span className="b-layout-2-proyectos__title">{ project.title?.rendered }</span>
					{ authorTerm && (
						<span className="b-layout-2-proyectos__author">{ authorTerm.name }</span>
					) }
				</p>
			) }
		</div>
	);
}
