<?php
/**
 * "Card" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c-technical-card">
	<a class="c-technical-card__link" href="<?php echo esc_url( get_permalink() ); ?>">
		<div class="c-technical-card__image">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'c-technical-card__img' ) ); ?>
		</div>
		<h3 class="c-technical-card__title"><?php echo esc_html( get_the_title() ); ?></h3>
	</a>
</div>
