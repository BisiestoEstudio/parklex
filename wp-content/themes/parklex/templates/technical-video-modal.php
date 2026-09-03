<?php
/**
 * Shared modal that plays a Vimeo video, triggered by .js-technical-video-trigger buttons.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c-technical-video-modal" data-technical-video-modal hidden>
	<div class="c-technical-video-modal__backdrop" data-technical-video-modal-close></div>
	<div class="c-technical-video-modal__dialog" role="dialog" aria-modal="true">
		<button type="button" class="c-technical-video-modal__close" data-technical-video-modal-close aria-label="<?php esc_attr_e( 'Cerrar', 'parklex' ); ?>">&times;</button>
		<div class="c-technical-video-modal__embed">
			<iframe
				class="c-technical-video-modal__iframe"
				data-technical-video-modal-iframe
				src=""
				title="<?php esc_attr_e( 'Vídeo', 'parklex' ); ?>"
				allow="autoplay; fullscreen; picture-in-picture"
				allowfullscreen
			></iframe>
		</div>
	</div>
</div>
