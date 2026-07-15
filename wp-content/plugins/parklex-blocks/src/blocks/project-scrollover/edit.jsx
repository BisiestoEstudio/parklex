import { __ } from '@wordpress/i18n';
import { InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, Icon } from '@wordpress/components';
import { closeSmall, chevronUp, chevronDown } from '@wordpress/icons';
import { useBisiestoBlockProps } from '../../hooks/useBisiestoBlockProps';
import ProjectSearchControl from '../../components/ProjectSearchControl';
import './editor.scss';

const ALLOWED_BLOCKS = [ 'core/paragraph', 'core/heading', 'core/buttons', 'bisiesto/spacer' ];

export default function Edit( { attributes, setAttributes } ) {
	const { projects } = attributes;
	const blockProps = useBisiestoBlockProps( { className: 'alignfull is-layout-constrained' } );

	const updateProject = ( index, projectId ) => {
		const newProjects = [ ...projects ];
		newProjects[ index ] = projectId;
		setAttributes( { projects: newProjects } );
	};

	const removeProject = ( index ) => {
		setAttributes( { projects: projects.filter( ( _, i ) => i !== index ) } );
	};

	const moveProject = ( index, direction ) => {
		const newIndex = index + direction;
		if ( newIndex < 0 || newIndex >= projects.length ) {
			return;
		}
		const newProjects = [ ...projects ];
		[ newProjects[ index ], newProjects[ newIndex ] ] = [ newProjects[ newIndex ], newProjects[ index ] ];
		setAttributes( { projects: newProjects } );
	};

	const addProject = () => {
		setAttributes( { projects: [ ...projects, 0 ] } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Proyectos', 'parklex-blocks' ) }>
					{ projects.map( ( projectId, index ) => (
						<div className="b-project-scrollover__repeater-item" key={ index }>
							<ProjectSearchControl
								label={ __( 'Proyecto', 'parklex-blocks' ) + ` ${ index + 1 }` }
								value={ projectId }
								onChange={ ( value ) => updateProject( index, value ) }
							/>
							<Button
								icon={ chevronUp }
								label={ __( 'Subir', 'parklex-blocks' ) }
								onClick={ () => moveProject( index, -1 ) }
								disabled={ index === 0 }
							/>
							<Button
								icon={ chevronDown }
								label={ __( 'Bajar', 'parklex-blocks' ) }
								onClick={ () => moveProject( index, 1 ) }
								disabled={ index === projects.length - 1 }
							/>
							<Button
								icon={ closeSmall }
								label={ __( 'Eliminar', 'parklex-blocks' ) }
								onClick={ () => removeProject( index ) }
								isDestructive
							/>
						</div>
					) ) }
					<Button variant="secondary" onClick={ addProject }>
						{ __( 'Añadir proyecto', 'parklex-blocks' ) }
					</Button>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div className="b-project-scrollover__content">
					<InnerBlocks allowedBlocks={ ALLOWED_BLOCKS } templateLock={ false } />
				</div>
			</div>
		</>
	);
}
