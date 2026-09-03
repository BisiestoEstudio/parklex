<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="entry-content is-layout-constrained has-global-padding">
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
						class="c-technical-card-categories__link<?php echo '' === $active_category ? ' active' : ''; ?>"
						href="<?php echo esc_url( remove_query_arg( 'category_technical_card' ) ); ?>"
					>
						<?php esc_html_e( 'All', 'parklex' ); ?>
					</a>
				</li>
				<?php foreach ( $technical_card_categories as $technical_card_category ) : ?>
					<li class="c-technical-card-categories__item">
						<a
							class="c-technical-card-categories__link<?php echo $active_category === $technical_card_category->slug ? ' active' : ''; ?>"
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
					<div class="c-technical-card-body__clasification-group">
						<span class="has-display-xxs-font-size"><?php echo esc_html( $classification_parent->name ); ?></span>
						<?php if ( ! empty( $classification_children ) && ! is_wp_error( $classification_children ) ) : ?>
							<ul class="c-technical-card-body__clasification-terms">
								<?php foreach ( $classification_children as $classification_child ) : ?>
									<li class="c-technical-card-body__clasification-term">
										<a
											class="c-technical-card-body__clasification-link<?php echo $active_classification === $classification_child->slug ? ' active' : ''; ?>"
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
			if ( '' !== $active_classification ) :
				get_template_part( 'templates/technical-clasification' );
			else:
				get_template_part( 'templates/technical-home' );

			endif;

			while ( have_posts() ) :
				the_post();
				the_title();
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

