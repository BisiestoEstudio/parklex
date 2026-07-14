import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { store as editorStore } from '@wordpress/editor';
import apiFetch from '@wordpress/api-fetch';
import { useEffect, useState } from '@wordpress/element';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import ProjectSearchControl from '../../components/ProjectSearchControl';

export default function Edit( { attributes, setAttributes } ) {
	const { mode, project1, project2 } = attributes;
	const blockProps = useBisiestoBlockProps( { className: 'alignfull is-layout-constrained' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Proyectos', 'parklex-blocks' ) }>
					<ToggleGroupControl
						label={ __( 'Modo', 'parklex-blocks' ) }
						value={ mode }
						onChange={ ( value ) => setAttributes( { mode: value } ) }
						isBlock
						__nextHasNoMarginBottom
					>
						<ToggleGroupControlOption value="auto" label={ __( 'Automático', 'parklex-blocks' ) } />
						<ToggleGroupControlOption value="manual" label={ __( 'Manual', 'parklex-blocks' ) } />
					</ToggleGroupControl>
					{ 'manual' === mode && (
						<>
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
						</>
					) }
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="b-related-project__wrapper alignwide">
					{ 'manual' === mode ? (
						<>
							<ProjectCardPreview projectId={ project1 } />
							<ProjectCardPreview projectId={ project2 } />
						</>
					) : (
						<AutoProjectsPreview />
					) }
				</div>
			</section>
		</>
	);
}

function ProjectCardPreview( { projectId } ) {
	const project = useSelect(
		( select ) =>
			projectId
				? select( coreStore ).getEntityRecord( 'postType', 'proyecto', projectId, {
						_fields: 'id,title,featured_media',
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

	const authorTerms = useSelect(
		( select ) =>
			projectId
				? select( coreStore ).getEntityRecords( 'taxonomy', 'project_author', {
						post: projectId,
						per_page: 1,
				  } )
				: null,
		[ projectId ]
	);

	return (
		<div className="b-related-project__project">
			<div className="b-related-project__image">
				{ imageUrl ? (
					<img src={ imageUrl } alt="" />
				) : (
					<span className="b-related-project__placeholder">
						{ __( 'Selecciona un proyecto en el panel lateral', 'parklex-blocks' ) }
					</span>
				) }
			</div>
			{ project && (
				<p className="b-related-project__caption has-caption-font-size">
					<span className="b-related-project__title">{ project.title?.rendered }</span>
					{ authorTerms?.[ 0 ] && (
						<span className="b-related-project__author">{ authorTerms[ 0 ].name }</span>
					) }
				</p>
			) }
		</div>
	);
}

function AutoProjectsPreview() {
	const currentPostId = useSelect( ( select ) => select( editorStore ).getCurrentPostId(), [] );
	const [ projects, setProjects ] = useState( null );

	useEffect( () => {
		if ( ! currentPostId ) {
			setProjects( [] );
			return;
		}

		let cancelled = false;

		apiFetch( { path: `/parklex-blocks/v1/related-projects?post_id=${ currentPostId }` } )
			.then( ( result ) => {
				if ( ! cancelled ) setProjects( result );
			} )
			.catch( () => {
				if ( ! cancelled ) setProjects( [] );
			} );

		return () => {
			cancelled = true;
		};
	}, [ currentPostId ] );

	if ( projects === null ) {
		return <p className="b-related-project__placeholder">{ __( 'Cargando proyectos…', 'parklex-blocks' ) }</p>;
	}

	if ( ! projects.length ) {
		return (
			<p className="b-related-project__placeholder">
				{ __(
					'No se han encontrado proyectos relacionados automáticamente para esta página. Prueba el modo manual.',
					'parklex-blocks'
				) }
			</p>
		);
	}

	return projects.map( ( project ) => (
		<div className="b-related-project__project" key={ project.id }>
			<div className="b-related-project__image">
				{ project.image ? <img src={ project.image } alt="" /> : null }
			</div>
			<p className="b-related-project__caption has-caption-font-size">
				<span className="b-related-project__title">{ project.title }</span>
				{ project.author && <span className="b-related-project__author">{ project.author }</span> }
			</p>
		</div>
	) );
}
