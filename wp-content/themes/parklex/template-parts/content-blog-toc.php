<?php
/**
 * Table of contents for a single blog post, built from its h2 headings.
 *
 * @var array $args {
 *     @type array $items TOC items: array{id, text}.
 * }
 */
defined( 'ABSPATH' ) || exit;

$items = $args['items'] ?? array();

if ( empty( $items ) ) {
	return;
}
?>
<nav class="c-blog-toc" aria-label="<?php esc_attr_e( 'En este artículo', 'parklex' ); ?>">
	<div class="c-blog-toc__header">
		<p class="c-blog-toc__title has-h-4-font-size"><?php esc_html_e( 'En este artículo', 'parklex' ); ?></p>
		<span class="c-blog-toc__icon" aria-hidden="true"></span>
	</div>

	<ul class="c-blog-toc__list">
		<?php foreach ( $items as $item ) : ?>
			<li class="c-blog-toc__item">
				<a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
