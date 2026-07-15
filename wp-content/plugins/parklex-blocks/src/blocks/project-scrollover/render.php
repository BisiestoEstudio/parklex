<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$project_ids = array_filter( array_map( 'absint', $attributes['projects'] ?? [] ) );
?>

<section <?php echo bis_get_block_prop( $block, true ); ?>>
	<div class="b-project-scrollover__media-wrap alignfull">
		<?php echo $content; ?>
	</div>
	<div class="b-project-scrollover__projects alignwide">
		<?php foreach ( $project_ids as $project_id ) : ?>
			<?php if ( ! $project_id || 'publish' !== get_post_status( $project_id ) ) continue; ?>

			<?php
			$author_terms = get_the_terms( $project_id, 'project_author' );
			$author_name  = ( ! empty( $author_terms ) && ! is_wp_error( $author_terms ) ) ? $author_terms[0]->name : '';
			?>

			<div class="b-project-scrollover__project">
				<a class="b-project-scrollover__image" href="<?php echo esc_url( get_permalink( $project_id ) ); ?>">
					<?php echo get_the_post_thumbnail( $project_id, 'large', array( 'class' => 'b-project-scrollover__img' ) ); ?>
				</a>
				<p class="b-project-scrollover__caption has-caption-font-size">
					<span class="b-project-scrollover__title"><?php echo esc_html( get_the_title( $project_id ) ); ?></span>
					<?php if ( $author_name ) : ?>
						<span class="b-project-scrollover__author"><?php echo esc_html( $author_name ); ?></span>
					<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>
		<div class="b-project-scrollover__spacer" style="height: 100vh; width: 100%;"></div>
	</div>
</section>
