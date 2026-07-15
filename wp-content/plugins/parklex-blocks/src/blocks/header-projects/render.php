<?php
defined('ABSPATH') || exit;

/** @var array $attributes */
/** @var string $content */
/** @var WP_Block|null $block */

$post_id      = get_the_ID();
$project_info = function_exists('get_field') ? (array) get_field('project_info', $post_id) : array();

$year        = $project_info['year'] ?? '';
$location    = $project_info['location'] ?? '';
$photography = $project_info['photography'] ?? '';

$product_terms = ! empty($project_info['product']) ? (array) $project_info['product'] : array();

$finish_posts = ! empty($project_info['related_product']) ? (array) $project_info['related_product'] : array();
$finish_names = array();
foreach ($finish_posts as $finish_post) {
	if ($finish_post) {
		$finish_names[] = get_the_title($finish_post);
	}
}

$featured_id = get_post_thumbnail_id($post_id);

$author_terms   = get_the_terms($post_id, 'project_author');
$author_names   = (! empty($author_terms) && ! is_wp_error($author_terms)) ? wp_list_pluck($author_terms, 'name') : array();

$work_type_terms = get_the_terms($post_id, 'type_work');
$solution_terms  = get_the_terms($post_id, 'app');

/**
 * Convierte una lista de términos en enlaces "Nombre" separados por comas.
 */
$term_links = function ($terms) {
	if (empty($terms) || is_wp_error($terms)) {
		return '';
	}
	$links = array();
	foreach ($terms as $term) {
		$url = get_term_link($term);
		if (is_wp_error($url)) {
			$links[] = esc_html($term->name);
		} else {
			$links[] = sprintf('<a href="%s">%s</a>', esc_url($url), esc_html($term->name));
		}
	}
	return implode(', ', $links);
};
?>

<section <?php echo bis_get_block_prop($block, true, array('class' => 'alignfull')); ?>>
	<div class="b-header-projects__media alignfull">
		<?php if ($featured_id) : ?>
			<?php echo wp_get_attachment_image($featured_id, 'large', false, array('class' => 'b-header-projects__main-img')); ?>
		<?php endif; ?>
	</div>

	<div class="b-header-projects__section alignwide">
		<div class="b-header-projects__intro">
			<h1 class="b-header-projects__title has-display-l-font-size"><?php echo esc_html(get_the_title($post_id)); ?></h1>
			<?php if ($author_names) : ?>
				<p class="b-header-projects__author"><?php echo esc_html(implode(', ', $author_names)); ?></p>
			<?php endif; ?>
		</div>
		<div class="b-header-projects__specs">

			<div class="b-header-projects__spec-group">
				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Año', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo esc_html($year); ?></span>
				</div>
			</div>

			<div class="b-header-projects__spec-group">
				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Localización', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo esc_html($location); ?></span>
				</div>
			</div>

			<div class="b-header-projects__spec-group">
				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Producto', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo wp_kses_post($term_links($product_terms)); ?></span>
				</div>
			</div>

		</div>
	</div>
	<div class="b-header-projects__section alignwide">

		<div class="b-header-projects__content is-prose">
			<?php echo $content; ?>
		</div>

		<div class="b-header-projects__specs">

			<div class="b-header-projects__spec-group">
				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Acabado', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo esc_html(implode(', ', $finish_names)); ?></span>
				</div>
				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Fotografía', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo esc_html($photography); ?></span>
				</div>
			</div>


			<div class="b-header-projects__spec-group">
				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Tipo de obra', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo wp_kses_post($term_links($work_type_terms)); ?></span>
				</div>

				<div class="b-header-projects__spec">
					<span class="b-header-projects__spec-label"><?php esc_html_e('Soluciones', 'parklex-blocks'); ?></span>
					<span class="b-header-projects__spec-value"><?php echo wp_kses_post($term_links($solution_terms)); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>