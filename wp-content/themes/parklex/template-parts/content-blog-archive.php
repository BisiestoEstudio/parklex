<?php
defined( 'ABSPATH' ) || exit;

$header_image_id = get_field( 'blog_header_image', 'option' );
$archive_title    = get_field( 'blog_archive_title', 'option' );
$archive_subtitle = get_field( 'blog_archive_subtitle', 'option' );
$search           = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
$categories       = get_categories( array( 'hide_empty' => true ) );
$blog_archive_url = bis_theme_get_blog_archive_url();
?>


<header class="c-blog-archive__header">
	<?php if ( $header_image_id ) : ?>
		<?php echo wp_get_attachment_image( $header_image_id, 'full', false, array( 'class' => 'c-blog-archive__header-image' ) ); ?>
	<?php endif; ?>

	<div class="c-blog-archive__header-content">
		<?php if ( $archive_title ) : ?>
			<h1 class="c-blog-archive__title has-display-xl-font-size"><?php echo esc_html( $archive_title ); ?></h1>
		<?php endif; ?>

		<?php if ( $archive_subtitle ) : ?>
			<p class="c-blog-archive__subtitle has-h-5-font-size"><?php echo esc_html( $archive_subtitle ); ?></p>
		<?php endif; ?>
	</div>
</header>


<main class="c-blog-archive is-layout-constrained has-global-padding">
	<div class="c-blog-archive__nav">
		<ul class="c-blog-archive__tabs">
			<li class="c-blog-archive__tab<?php echo is_home() ? ' is-active' : ''; ?>">
				<a href="<?php echo esc_url( $blog_archive_url ); ?>"><?php esc_html_e( 'Todos', 'parklex' ); ?></a>
			</li>
			<?php foreach ( $categories as $category ) : ?>
				<li class="c-blog-archive__tab<?php echo is_category( $category->term_id ) ? ' is-active' : ''; ?>">
					<a href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>

		<form class="c-blog-archive__search" method="GET">
			<label for="blog-search"><?php esc_html_e( 'Buscador', 'parklex' ); ?></label>
			<input type="search" id="blog-search" name="search" placeholder="<?php esc_attr_e( 'Escribe tu búsqueda aquí', 'parklex' ); ?>" value="<?php echo esc_attr( $search ); ?>">
		</form>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="c-blog-archive__grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			?>
		</div>

		<div class="c-blog-archive__pagination">
			<?php bis_paint_pagination(); ?>
		</div>
	<?php else : ?>
		<div class="c-blog-archive__empty">
			<p><?php esc_html_e( 'No se han encontrado artículos. Prueba a cambiar los filtros de búsqueda.', 'parklex' ); ?></p>
		</div>
	<?php endif; ?>
</main>
