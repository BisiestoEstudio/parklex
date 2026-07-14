<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var WP_Block|null $block */

$mode = $attributes['mode'] ?? 'auto';

if ( 'manual' === $mode ) {
	$project_ids = array_filter( array(
		(int) ( $attributes['project1'] ?? 0 ),
		(int) ( $attributes['project2'] ?? 0 ),
	) );
} else {
	$reference_id = get_queried_object_id() ?: get_the_ID();
	$project_ids  = class_exists( 'Bis_Blocks_Related_Projects' )
		? Bis_Blocks_Related_Projects::get_related_project_ids( $reference_id )
		: array();
}
?>

<section <?php echo bis_get_block_prop( $block, true, array( 'class' => 'alignfull' ) ); ?>>
	<div class="b-related-project__wrapper alignwide">
		<?php foreach ( $project_ids as $project_id ) : ?>
			<?php if ( ! $project_id || 'publish' !== get_post_status( $project_id ) ) continue; ?>

			<?php
			$author_terms = get_the_terms( $project_id, 'project_author' );
			$author_name  = ( ! empty( $author_terms ) && ! is_wp_error( $author_terms ) ) ? $author_terms[0]->name : '';
			?>

			<div class="b-related-project__project">
				<a class="b-related-project__image" href="<?php echo esc_url( get_permalink( $project_id ) ); ?>">
					<?php echo get_the_post_thumbnail( $project_id, 'large', array( 'class' => 'b-related-project__img' ) ); ?>
				</a>
				<p class="b-related-project__caption has-caption-font-size">
					<span class="b-related-project__title"><?php echo esc_html( get_the_title( $project_id ) ); ?></span>
					<?php if ( $author_name ) : ?>
						<span class="b-related-project__author"><?php echo esc_html( $author_name ); ?></span>
					<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>
	</div>
</section>
