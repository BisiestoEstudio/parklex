<?php
/**
 * Renders a curated list of Technical Card posts (title + downloads) picked via an
 * ACF relationship field on the Technical Card options page. Shared by the
 * "Presentations" and "Submit Documents" My Account tabs.
 */
defined( 'ABSPATH' ) || exit;

$acf_field     = $args['acf_field'];
$empty_notice  = $args['empty_notice'];
$technical_card_ids = get_field( $acf_field, 'option' );

if ( empty( $technical_card_ids ) ) {
	wc_print_notice( esc_html( $empty_notice ), 'notice' );
	return;
}

$query = new WP_Query( array(
	'post_type'      => 'technical-card',
	'post__in'       => $technical_card_ids,
	'orderby'        => 'post__in',
	'posts_per_page' => -1,
) );

if ( ! $query->have_posts() ) {
	wc_print_notice( esc_html( $empty_notice ), 'notice' );
	return;
}
?>

<ul class="c-presentations">
	<?php
	while ( $query->have_posts() ) :
		$query->the_post();
		?>
		<li class="c-presentations__item">
			<p class="c-presentations__title"><?php echo esc_html( get_the_title() ); ?></p>
			<?php get_template_part( 'templates/technical-downloads' ); ?>
		</li>
		<?php
	endwhile;
	wp_reset_postdata();
	?>
</ul>
