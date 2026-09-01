<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content is-layout-constrained">
	<div class="c-technical-card-categories alignfull">
		<?php
		$technical_card_categories = get_terms(
			array(
				'taxonomy'   => 'category_technical_card',
				'hide_empty' => false,
			)
		);
		?>
		<?php if ( ! empty( $technical_card_categories ) && ! is_wp_error( $technical_card_categories ) ) : ?>
			<ul class="c-technical-card-categories__list alignwide">
				<li class="c-technical-card-categories__item">
					<a class="c-technical-card-categories__link" href="<?php echo esc_url( get_post_type_archive_link( 'technical-card' ) ); ?>">
						<?php esc_html_e( 'All', 'parklex' ); ?>
					</a>
				</li>
				<?php foreach ( $technical_card_categories as $technical_card_category ) : ?>
					<li class="c-technical-card-categories__item">
						<a class="c-technical-card-categories__link" href="<?php echo esc_url( get_term_link( $technical_card_category ) ); ?>">
							<?php echo esc_html( $technical_card_category->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();

