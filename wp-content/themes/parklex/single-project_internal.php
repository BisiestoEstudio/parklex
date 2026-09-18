<?php
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$gallery_rows        = get_field( 'image_gallery' );
	$title               = get_field( 'project_name' ) ?: get_the_title();
	$hide_download_link  = get_field( 'hide_download_link' );
	$notes_text          = get_field( 'notes_text_ipf', 'option' );
	$download_confirm    = get_field( 'text_modal_download_photos', 'option' );

	// The gallery repeater takes priority; if it's empty, fall back to the featured image alone.
	$images = array();

	if ( ! empty( $gallery_rows ) ) {
		foreach ( $gallery_rows as $row ) {
			if ( ! empty( $row['image'] ) ) {
				$images[] = $row['image'];
			}
		}
	} elseif ( $featured_image = get_field( 'featured_image' ) ) {
		$images[] = $featured_image;
	}

	$download_urls = wp_list_pluck( $images, 'url' );

	$grid_class = '';
	if ( 2 === count( $images ) ) {
		$grid_class = ' width-half';
	} elseif ( 1 === count( $images ) ) {
		$grid_class = ' width-full';
	}
	?>

	<main class="entry-content is-layout-constrained has-global-padding">
		<div class="c-internal-project alignwide">
			<div class="c-internal-project__gallery">
				<?php if ( ! empty( $images ) ) : ?>
					<div class="images-grid-inpr">
						<div class="holder">
							<div class="grid-sizer"></div>
							<?php foreach ( $images as $image ) : ?>
								<figure class="grid-item<?php echo esc_attr( $grid_class ); ?>">
									<a href="<?php echo esc_url( $image['url'] ); ?>" data-fancybox="gallery">
										<img src="<?php echo esc_url( $image['sizes']['large'] ?? $image['url'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $image['alt'] ); ?>">
									</a>
								</figure>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="c-internal-project__details">
				<h1><?php echo esc_html( $title ); ?></h1>

				<ul class="c-internal-project__details-list">
					<?php
					$fields = array(
						'year'                => __( 'Year', 'parklex' ),
						'architect'           => __( 'Architect', 'parklex' ),
						'studio'              => __( 'Studio', 'parklex' ),
						'city'                => __( 'City', 'parklex' ),
					);

					foreach ( $fields as $field_name => $label ) :
						$value = get_field( $field_name );
						if ( ! $value ) :
							continue;
						endif;
						?>
						<li><?php echo esc_html( $label ); ?>: <?php echo esc_html( $value ); ?></li>
					<?php endforeach; ?>

					<?php
					$taxonomy_fields = array(
						'country'             => __( 'Country', 'parklex' ),
						'product_type'        => __( 'Product type', 'parklex' ),
						'product_name'        => __( 'Product(s)', 'parklex' ),
						'product'             => __( 'Finish', 'parklex' ),
						'application'         => __( 'Application', 'parklex' ),
						'type_of_work'        => __( 'Type of work', 'parklex' ),
						'building_type'       => __( 'Type of building', 'parklex' ),
						'installation_system' => __( 'Installation system', 'parklex' ),
						'interior_surface'    => __( 'Interior surface', 'parklex' ),
						'sustainability'      => __( 'Sustainability', 'parklex' ),
						'status'              => __( 'Status', 'parklex' ),
					);

					foreach ( $taxonomy_fields as $field_name => $label ) :
						$terms = get_field( $field_name );
						if ( empty( $terms ) ) :
							continue;
						endif;
						$terms = is_array( $terms ) ? $terms : array( $terms );
						?>
						<li><?php echo esc_html( $label ); ?>: <?php echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ); ?></li>
					<?php endforeach; ?>

					<?php $comments = get_field( 'comments' ); ?>
					<?php if ( $comments ) : ?>
						<li><?php esc_html_e( 'Comments', 'parklex' ); ?>: <?php echo esc_html( $comments ); ?></li>
					<?php endif; ?>
				</ul>

				<?php if ( ! $hide_download_link && ! empty( $download_urls ) ) : ?>
					<a
						class="btn download-zip"
						href="#"
						data-filename="<?php echo esc_attr( 'internal-project-' . get_post_field( 'post_name', get_the_ID() ) ); ?>"
						data-urls="<?php echo esc_attr( wp_json_encode( $download_urls ) ); ?>"
						<?php if ( $download_confirm ) : ?>
							data-confirm-text="<?php echo esc_attr( $download_confirm ); ?>"
						<?php endif; ?>
					>
						<span class="download-zip__spinner" hidden><?php esc_html_e( 'Preparing…', 'parklex' ); ?></span>
						<?php esc_html_e( 'Download the photos', 'parklex' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $notes_text ) : ?>
					<div class="c-internal-project__notes"><?php echo wp_kses_post( $notes_text ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</main>

<?php endwhile; ?>

<?php get_footer(); ?>
