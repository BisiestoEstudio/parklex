import { registerBlockType } from '@wordpress/blocks';
import { justifySpaceBetween } from '@wordpress/icons';
import Edit from './edit';
import metadata from './block.json';
import './style.scss';

registerBlockType( metadata.name, {
	icon: { src: justifySpaceBetween, foreground: '#7900D8' },
	edit: Edit,
	save: () => null,
} );
