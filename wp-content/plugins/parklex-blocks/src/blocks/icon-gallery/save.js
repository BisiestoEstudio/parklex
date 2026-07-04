import { InnerBlocks } from '@wordpress/block-editor';
import { useBisiestoBlockPropsSave } from '../../hooks/useBisiestoBlockProps';

export default function save() {
	const blockProps = useBisiestoBlockPropsSave();

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
