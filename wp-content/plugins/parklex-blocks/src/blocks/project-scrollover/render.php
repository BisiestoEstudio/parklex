<?php
defined( 'ABSPATH' ) || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$project_ids = array_filter( array_map( 'absint', $attributes['projects'] ?? [] ) );
?>

<section <?php echo bis_get_block_prop( $block, true ); ?>>
	<div class="b-project-scrollover__content">
		<?php echo $content; ?>
	</div>
	<div class="b-project-scrollover__projects">
		<?php foreach ( $project_ids as $project_id ) : ?>
			<?php
			$title = get_the_title( $project_id );
			if ( ! $title ) {
				continue;
			}
			?>
			<div class="b-project-scrollover__project">
				<?php echo wp_get_attachment_image( get_post_thumbnail_id( $project_id ), 'large', false, [ 'class' => 'b-project-scrollover__image' ] ); ?>
				<span class="b-project-scrollover__title"><?php echo esc_html( $title ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
