<?php
defined( 'ABSPATH' ) || exit;

$header_image_id  = get_field( 'projects_header_image', 'option' );
$archive_title    = get_field( 'projects_archive_title', 'option' );
$archive_subtitle = get_field( 'projects_archive_subtitle', 'option' );
$search           = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
$is_filtered      = '' !== $search;

foreach ( array_keys( Bis_Theme_Projects_Archive::get_filters() ) as $filter_param ) {
	if ( ! empty( $_GET[ $filter_param ] ) ) {
		$is_filtered = true;
	}
}
?>

<main class="c-projects-archive">
	<header class="c-projects-archive__header">
		<?php if ( $header_image_id ) : ?>
			<?php echo wp_get_attachment_image( $header_image_id, 'full', false, array( 'class' => 'c-projects-archive__header-image', 'loading' => 'eager' ) ); ?>
		<?php endif; ?>

		<div class="c-projects-archive__header-content">
			<?php if ( $archive_title ) : ?>
				<h1 class="c-projects-archive__title has-display-xl-font-size"><?php echo esc_html( $archive_title ); ?></h1>
			<?php endif; ?>

			<?php if ( $archive_subtitle ) : ?>
				<p class="c-projects-archive__subtitle has-h-5-font-size"><?php echo esc_html( $archive_subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</header>

	<div class="is-layout-constrained has-global-padding">
		<form class="c-projects-archive__filters alignwide" method="GET" action="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>" role="search">
			<?php foreach ( Bis_Theme_Projects_Archive::get_filters() as $param => $filter ) : ?>
				<?php
				$terms = get_terms( array( 'taxonomy' => $filter['taxonomy'], 'hide_empty' => true ) );

				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					continue;
				}

				$selected = isset( $_GET[ $param ] ) ? absint( $_GET[ $param ] ) : 0;
				?>
				<div class="c-projects-archive__field">
					<label for="projects-<?php echo esc_attr( $param ); ?>"><?php echo esc_html( $filter['label'] ); ?></label>
					<select id="projects-<?php echo esc_attr( $param ); ?>" name="<?php echo esc_attr( $param ); ?>">
						<option value=""><?php esc_html_e( 'Todos', 'parklex' ); ?></option>
						<?php foreach ( $terms as $term ) : ?>
							<option value="<?php echo esc_attr( $term->term_id ); ?>"<?php selected( $selected, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endforeach; ?>

			<div class="c-projects-archive__field c-projects-archive__field--search">
				<label for="projects-search"><?php esc_html_e( 'Buscador', 'parklex' ); ?></label>
				<input type="search" id="projects-search" name="search" placeholder="<?php esc_attr_e( 'Escribe tu búsqueda aquí', 'parklex' ); ?>" value="<?php echo esc_attr( $search ); ?>">
			</div>
		</form>

		<?php if ( have_posts() ) : ?>
			<div class="c-projects-archive__grid alignwide">
				<?php
				$post_number = 0;
				while ( have_posts() ) :
					the_post();
					$post_number++;
					$item_class  = 'c-projects-archive__item';
					$item_class .= is_paged() || $is_filtered ? '' : ' c-projects-archive__item--' . $post_number;
					?>
					<a class="<?php echo esc_attr( $item_class ); ?>" href="<?php echo esc_url( get_permalink() ); ?>">
						<div class="c-projects-archive__image">
							<?php echo get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'c-projects-archive__img', 'loading' => 'eager' ) ); ?>
						</div>
						<?php $authors = wp_get_post_terms( get_the_ID(), 'project_author', array( 'fields' => 'names' ) ); ?>
						<p class="c-projects-archive__item-text">
							<?php echo esc_html( get_the_title() ); ?>
							<?php if ( ! empty( $authors ) && ! is_wp_error( $authors ) ) : ?>
								<br><?php echo esc_html( implode( ', ', $authors ) ); ?>
							<?php endif; ?>
						</p>
					</a>
				<?php endwhile; ?>
			</div>

			<div class="c-projects-archive__pagination">
				<?php bis_paint_pagination(); ?>
			</div>
		<?php else : ?>
			<div class="c-projects-archive__empty">
				<p><?php esc_html_e( 'No se han encontrado proyectos. Prueba a cambiar los filtros de búsqueda.', 'parklex' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>
