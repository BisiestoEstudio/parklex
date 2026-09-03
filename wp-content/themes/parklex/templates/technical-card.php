<?php
/**
 * "Card" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;

$disable_label = ! empty( $args['disable_label'] );
$categories    = get_the_terms( get_the_ID(), 'category_technical_card' );
$downloads     = get_field( 'downloads' );
?>
<div class="c-technical-card">
	<?php if ( ! $disable_label && ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<ul class="c-technical-card__chips">
			<?php foreach ( $categories as $category ) : ?>
				<li class="c-technical-card__chip"><?php echo esc_html( $category->name ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<h3 class="c-technical-card__title"><?php echo esc_html( get_the_title() ); ?></h3>

	<?php if ( ! empty( $downloads ) ) : ?>
		<ul class="c-technical-card__downloads">
			<?php foreach ( $downloads as $download ) : ?>
				<?php $file = $download['file'] ?? null; ?>
				<?php if ( $file ) : ?>
					<?php $extension = pathinfo( $file['filename'], PATHINFO_EXTENSION ); ?>
					<li class="c-technical-card__download">
						<a class="c-technical-card__download-link" href="<?php echo esc_url( $file['url'] ); ?>" download>
							<img class="c-technical-card__download-icon" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/download-icon.svg' ) ); ?>" alt="" width="24" height="24" />
							<span class="c-technical-card__download-label"><?php echo esc_html( strtoupper( $extension ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
