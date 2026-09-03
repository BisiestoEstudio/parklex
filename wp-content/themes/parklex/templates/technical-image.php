<?php
/**
 * "Image Card" layout for a single technical-card post.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c-technical-image">
	<a class="c-technical-image__link" href="<?php echo esc_url( get_permalink() ); ?>">
		<div class="c-technical-image__image">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'c-technical-image__img' ) ); ?>
		</div>
		<h3 class="c-technical-image__title"><?php echo esc_html( get_the_title() ); ?></h3>
	</a>
</div>
