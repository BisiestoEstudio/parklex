<?php
/**
 * Home layout for the technical-card archive (no classification filter active).
 */
defined( 'ABSPATH' ) || exit;

$active_category = $args['active_category'];

$classification_ids = get_field( 'classification_categories', 'option' );

$classification_categories = array();

if ( ! empty( $classification_ids ) ) {
	$classification_categories = get_terms(
		array(
			'taxonomy'   => 'classification_technical_card',
			'include'    => $classification_ids,
			'orderby'    => 'include',
			'hide_empty' => false,
		)
	);
}
?>

<?php foreach ( $classification_categories as $classification_category ) : ?>
	<?php
	$classification_query_args = array(
		'post_type'                      => 'technical-card',
		'posts_per_page'                 => 2,
		'classification_technical_card'  => $classification_category->slug,
	);

	if ( $active_category ) {
		$classification_query_args['category_technical_card'] = $active_category;
	}

	$classification_query = new WP_Query( $classification_query_args );

	if ( ! $classification_query->have_posts() ) {
		continue;
	}
	?>

	<h2 class="c-technical-home__title has-h-5-font-size"><?php echo esc_html( $classification_category->name ); ?></h2>

	<?php
	get_template_part(
		'templates/technical',
		'classification',
		array(
			'active_classification' => $classification_category->slug,
			'active_category'       => $active_category,
			'query'                 => $classification_query,
		)
	);
	?>
<?php endforeach; ?>
