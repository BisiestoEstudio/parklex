<?php
defined( 'ABSPATH' ) || exit;

$active_classification = $args['active_classification'];
$active_category       = $args['active_category'];

$classification_term = get_term_by( 'slug', $active_classification, 'classification_technical_card' );

$card_type      = 'card';
$disable_label  = false;
$grid_type      = 'grid-2';

if ( $classification_term && ! is_wp_error( $classification_term ) ) {
	$card_type     = get_field( 'card_type', $classification_term ) ?: 'card';
	$disable_label = (bool) get_field( 'disable_label', $classification_term );
	$grid_type = $card_type === 'card' ? 'grid-3' : 'grid-2';
}
?>

<div class="c-technical-card-grid <?= $grid_type ?>">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'templates/technical', $card_type, array( 'disable_label' => $disable_label ) );
	endwhile;
	?>
</div>