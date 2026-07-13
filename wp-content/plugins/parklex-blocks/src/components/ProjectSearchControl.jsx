import { ComboboxControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

/**
 * Buscador de posts de tipo "proyecto" por título.
 *
 * @param {Object}   props
 * @param {string}   props.label    Etiqueta del control.
 * @param {number}   props.value    ID del proyecto seleccionado.
 * @param {Function} props.onChange Callback al cambiar la selección, recibe el ID (0 si se limpia).
 */
export default function ProjectSearchControl( { label, value, onChange } ) {
	const [ search, setSearch ] = useState( '' );

	const searchResults = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'postType', 'proyecto', {
				search,
				per_page: 20,
				status: 'publish',
				orderby: 'title',
				order: 'asc',
				_fields: 'id,title',
			} ),
		[ search ]
	);

	const selectedProject = useSelect(
		( select ) =>
			value
				? select( coreStore ).getEntityRecord( 'postType', 'proyecto', value, {
						_fields: 'id,title',
				  } )
				: null,
		[ value ]
	);

	const options = [];
	if ( selectedProject ) {
		options.push( {
			value: selectedProject.id,
			label: selectedProject.title?.rendered || `#${ selectedProject.id }`,
		} );
	}
	( searchResults || [] ).forEach( ( project ) => {
		if ( project.id !== value ) {
			options.push( {
				value: project.id,
				label: project.title?.rendered || `#${ project.id }`,
			} );
		}
	} );

	return (
		<ComboboxControl
			label={ label }
			value={ value || null }
			options={ options }
			onFilterValueChange={ ( inputValue ) => setSearch( inputValue ) }
			onChange={ ( newValue ) => onChange( newValue ? Number( newValue ) : 0 ) }
		/>
	);
}
