<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content is-layout-constrained has-global-padding">
	<div class="c-products-archive alignwide">
		<?php
		$header_image_id = get_field( 'products_header_image', 'option' );
		$archive_title    = get_field( 'products_archive_title', 'option' );
		?>
		<?php if ( $header_image_id || $archive_title ) : ?>
			<header class="c-products-archive__header">
				<?php if ( $header_image_id ) : ?>
					<?php echo wp_get_attachment_image( $header_image_id, 'full', false, array( 'class' => 'c-products-archive__header-image', 'loading' => 'eager' ) ); ?>
				<?php endif; ?>

				<div class="c-products-archive__header-content">
					<?php if ( $archive_title ) : ?>
						<h1 class="c-products-archive__header-title has-display-xl-font-size"><?php echo esc_html( $archive_title ); ?></h1>
					<?php endif; ?>
				</div>
			</header>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="c-products-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();

					$gallery        = function_exists( 'get_field' ) ? get_field( 'gallery', get_the_ID() ) : false;
					$hover_image_id = ! empty( $gallery ) ? (int) $gallery[0] : 0;
					?>
					<a class="c-products-archive__item" href="<?php echo esc_url( get_permalink() ); ?>">
						<div class="c-products-archive__image">
							<?php echo get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'c-products-archive__img' ) ); ?>
							<?php if ( $hover_image_id ) : ?>
								<?php echo wp_get_attachment_image( $hover_image_id, 'medium', false, array( 'class' => 'c-products-archive__img c-products-archive__img--hover' ) ); ?>
							<?php endif; ?>
						</div>
						<h3 class="c-products-archive__title has-display-m-font-size"><?php echo esc_html( get_the_title() ); ?></h3>
					</a>
					<?php
				endwhile;
				?>
			</div>

			<div class="c-products-archive__pagination">
				<?php bis_paint_pagination(); ?>
			</div>

		<?php else : ?>
			<p class="c-products-archive__placeholder"><?php esc_html_e( 'No hay productos disponibles.', 'parklex' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
