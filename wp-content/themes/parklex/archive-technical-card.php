<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content is-layout-constrained">
	<div class="c-technical-card-categories alignfull">
		<?php
		$active_category = isset( $_GET['category_technical_card'] )
			? sanitize_title( wp_unslash( $_GET['category_technical_card'] ) )
			: '';

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
					<a
						class="c-technical-card-categories__link<?php echo '' === $active_category ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( remove_query_arg( 'category_technical_card' ) ); ?>"
					>
						<?php esc_html_e( 'All', 'parklex' ); ?>
					</a>
				</li>
				<?php foreach ( $technical_card_categories as $technical_card_category ) : ?>
					<li class="c-technical-card-categories__item">
						<a
							class="c-technical-card-categories__link<?php echo $active_category === $technical_card_category->slug ? ' is-active' : ''; ?>"
							href="<?php echo esc_url( add_query_arg( 'category_technical_card', $technical_card_category->slug ) ); ?>"
						>
							<?php echo esc_html( $technical_card_category->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<div class="c-technical-card-body alignwide">
		<div class="c-technical-card-body__sidebar">
			<?php
			$active_classification = isset( $_GET['classification_technical_card'] )
				? sanitize_title( wp_unslash( $_GET['classification_technical_card'] ) )
				: '';

			$classification_parents = get_terms(
				array(
					'taxonomy'   => 'classification_technical_card',
					'hide_empty' => false,
					'parent'     => 0,
				)
			);
			?>
			<?php if ( ! empty( $classification_parents ) && ! is_wp_error( $classification_parents ) ) : ?>
				<?php foreach ( $classification_parents as $classification_parent ) : ?>
					<?php
					$classification_children = get_terms(
						array(
							'taxonomy'   => 'classification_technical_card',
							'hide_empty' => false,
							'parent'     => $classification_parent->term_id,
						)
					);
					?>
					<div class="c-technical-card-body__sidebar-group">
						<span class="c-technical-card-body__sidebar-parent"><?php echo esc_html( $classification_parent->name ); ?></span>
						<?php if ( ! empty( $classification_children ) && ! is_wp_error( $classification_children ) ) : ?>
							<ul class="c-technical-card-body__sidebar-children">
								<?php foreach ( $classification_children as $classification_child ) : ?>
									<li class="c-technical-card-body__sidebar-item">
										<a
											class="c-technical-card-body__sidebar-link<?php echo $active_classification === $classification_child->slug ? ' is-active' : ''; ?>"
											href="<?php echo esc_url( add_query_arg( 'classification_technical_card', $classification_child->slug ) ); ?>"
										>
											<?php echo esc_html( $classification_child->name ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="c-technical-card-list__grid">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
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

