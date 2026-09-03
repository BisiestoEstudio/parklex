<?php
/**
 * "Row" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c-technical-row">
	<a class="c-technical-row__link" href="<?php echo esc_url( get_permalink() ); ?>">
		<div class="c-technical-row__image">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'c-technical-row__img' ) ); ?>
		</div>
		<h3 class="c-technical-row__title"><?php echo esc_html( get_the_title() ); ?></h3>
	</a>
</div>
