const ALIGNMENT_XY = {
	'top left': { x: 0, y: 0 },
	'top center': { x: 50, y: 0 },
	top: { x: 50, y: 0 },
	'top right': { x: 100, y: 0 },
	'center left': { x: 0, y: 50 },
	left: { x: 0, y: 50 },
	center: { x: 50, y: 50 },
	'center center': { x: 50, y: 50 },
	'center right': { x: 100, y: 50 },
	right: { x: 100, y: 50 },
	'bottom left': { x: 0, y: 100 },
	'bottom center': { x: 50, y: 100 },
	bottom: { x: 50, y: 100 },
	'bottom right': { x: 100, y: 100 },
};

/**
 * Converts an AlignmentMatrixControl value ("top left", "center", "bottom right"...)
 * into x/y coordinates (0, 50, 100), mirroring bis_get_alignment_matrix_xy() in PHP.
 *
 * @param {string} position
 * @return {{x: number, y: number}}
 */
export function getAlignmentXY( position ) {
	return ALIGNMENT_XY[ position ] ?? ALIGNMENT_XY.center;
}
