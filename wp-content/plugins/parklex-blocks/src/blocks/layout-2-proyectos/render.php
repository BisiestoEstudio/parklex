<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$project_ids = array(
	'1' => (int) ( $attributes['project1'] ?? 0 ),
	'2' => (int) ( $attributes['project2'] ?? 0 ),
);
?>

<section <?php echo bis_get_block_prop( $block, true, array( 'class' => 'alignfull' ) ); ?>>
	<div class="b-layout-2-proyectos__wrapper alignwide">
		<div class="b-layout-2-proyectos__text is-prose">
			<?php echo $content; ?>
		</div>

		<?php foreach ( $project_ids as $modifier => $project_id ) : ?>
			<?php if ( ! $project_id || 'publish' !== get_post_status( $project_id ) ) continue; ?>

			<?php
			$author_terms = get_the_terms( $project_id, 'project_author' );
			$author_name  = ( ! empty( $author_terms ) && ! is_wp_error( $author_terms ) ) ? $author_terms[0]->name : '';
			?>

			<div class="b-layout-2-proyectos__project b-layout-2-proyectos__project--<?php echo esc_attr( $modifier ); ?>">
				<a class="b-layout-2-proyectos__image" href="<?php echo esc_url( get_permalink( $project_id ) ); ?>">
					<?php echo get_the_post_thumbnail( $project_id, 'large', array( 'class' => 'b-layout-2-proyectos__img' ) ); ?>
				</a>
				<p class="b-layout-2-proyectos__caption has-caption-font-size">
					<span class="b-layout-2-proyectos__title"><?php echo esc_html( get_the_title( $project_id ) ); ?></span>
					<?php if ( $author_name ) : ?>
						<span class="b-layout-2-proyectos__author"><?php echo esc_html( $author_name ); ?></span>
					<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>
	</div>
</section>
