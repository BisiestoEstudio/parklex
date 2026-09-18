<?php
defined( 'ABSPATH' ) || exit;

get_header();

$search           = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
$archive_title    = get_field( 'internal_projects_title', 'option' );
$enable_search    = get_field( 'enable_search_internal', 'option' );
$submit_page_id   = bis_theme_get_page_by_template( 'page-submit-internal-project.php' );

$meta_filters = array(
	'years'     => array( 'year', __( 'Years', 'parklex' ) ),
	'architect' => array( 'architect', __( 'Architect', 'parklex' ) ),
	'studio'    => array( 'studio', __( 'Studio', 'parklex' ) ),
	'city'      => array( 'city', __( 'City', 'parklex' ) ),
);

$taxonomy_filters = array(
	'country_internal'        => __( 'Country', 'parklex' ),
	'product_type_internal'   => __( 'Product type', 'parklex' ),
	'product_name_internal'   => __( 'Product name', 'parklex' ),
	'product_internal'        => __( 'Finish', 'parklex' ),
	'application_internal'    => __( 'Application', 'parklex' ),
	'work_type_internal'      => __( 'Type of work', 'parklex' ),
	'building_type_internal'  => __( 'Type of building', 'parklex' ),
	'installation_internal'   => __( 'Installation system', 'parklex' ),
	'surface_internal'        => __( 'Interior surface', 'parklex' ),
	'sustainability_internal' => __( 'Sustainability', 'parklex' ),
);
?>

<main class="entry-content is-layout-constrained has-global-padding">
	<?php if ( $archive_title ) : ?>
		<h1 class="c-internal-projects__title"><?php echo esc_html( $archive_title ); ?></h1>
	<?php endif; ?>

	<div class="c-internal-projects alignwide">
		<div class="c-internal-projects__sidebar">
			<form id="internal-project-search-form" class="c-internal-projects__filters" action="<?php echo esc_url( get_post_type_archive_link( 'project_internal' ) ); ?>" method="GET">
				<?php if ( $enable_search ) : ?>
					<div class="c-internal-projects__filter-item">
						<input type="search" id="internal-project-search" name="search" placeholder="<?php esc_attr_e( 'Búsqueda', 'parklex' ); ?>" value="<?php echo esc_attr( $search ); ?>">
					</div>
				<?php endif; ?>

				<?php foreach ( $meta_filters as $get_key => list( $meta_key, $label ) ) : ?>
					<?php $values = Bis_Core_Internal_Projects::get_distinct_meta_values( $meta_key ); ?>
					<?php if ( empty( $values ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<?php $active = isset( $_GET[ $get_key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $get_key ] ) ) : ''; ?>
					<div class="c-internal-projects__filter-item">
						<select data-tax="<?php echo esc_attr( $get_key ); ?>">
							<option value=""<?php selected( '', $active ); ?>><?php echo esc_html( $label ); ?></option>
							<?php foreach ( $values as $value ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $value, $active ); ?>><?php echo esc_html( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endforeach; ?>

				<?php foreach ( $taxonomy_filters as $taxonomy => $label ) : ?>
					<?php $terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) ); ?>
					<?php if ( empty( $terms ) || is_wp_error( $terms ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<?php $active = isset( $_GET[ $taxonomy ] ) ? sanitize_title( wp_unslash( $_GET[ $taxonomy ] ) ) : ''; ?>
					<div class="c-internal-projects__filter-item">
						<select data-tax="<?php echo esc_attr( $taxonomy ); ?>">
							<option value=""<?php selected( '', $active ); ?>><?php echo esc_html( $label ); ?></option>
							<?php foreach ( $terms as $term ) : ?>
								<option value="<?php echo esc_attr( $term->slug ); ?>"<?php selected( $term->slug, $active ); ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endforeach; ?>

				<?php if ( ! empty( $_GET ) ) : ?>
					<div class="c-internal-projects__filter-item">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'project_internal' ) ); ?>"><?php esc_html_e( 'Reset all filters', 'parklex' ); ?></a>
					</div>
				<?php endif; ?>
			</form>

			<?php if ( $submit_page_id ) : ?>
				<div class="c-internal-projects__new">
					<a class="btn" href="<?php echo esc_url( get_permalink( $submit_page_id ) ); ?>"><?php esc_html_e( 'New project', 'parklex' ); ?></a>
				</div>
			<?php endif; ?>
		</div>

		<div class="c-internal-projects__results">
			<?php if ( have_posts() ) : ?>
				<div class="c-internal-projects__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'No projects found. Try changing your search options.', 'parklex' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
