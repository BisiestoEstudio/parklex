<?php
/**
 * Registration gate shown instead of the Technical Card archive content while the
 * visitor doesn't have the technical_area_registered_user cookie set to "true" (see
 * Bis_Core_Technical_Card::gate_archive_access()).
 */
defined( 'ABSPATH' ) || exit;

$access_gate_title = get_field( 'access_gate_title', 'option' );
$access_gate_text  = get_field( 'access_gate_text', 'option' );
$access_gate_image = get_field( 'access_gate_image', 'option' );
$registration_form = get_field( 'registration_form', 'option' );
?>

<main class="c-technical-access-gate alignfull">
	<div class="c-technical-access-gate__media">
		<?php if ( $access_gate_image ) : ?>
			<?php echo wp_get_attachment_image( $access_gate_image['ID'], 'full', false, array( 'class' => 'c-technical-access-gate__image' ) ); ?>
		<?php endif; ?>
		<div class="c-technical-access-gate__overlay">
			<?php if ( $access_gate_title ) : ?>
				<h1 class="c-technical-access-gate__title has-display-s-font-size"><?php echo esc_html( $access_gate_title ); ?></h1>
			<?php endif; ?>
			<?php if ( $access_gate_text ) : ?>
				<div class="c-technical-access-gate__text"><?php echo wp_kses_post( $access_gate_text ); ?></div>
			<?php endif; ?>
		</div>
	</div>
	<div class="c-technical-access-gate__form">
		<?php if ( $registration_form ) : ?>
			<?php echo $registration_form; // phpcs:ignore -- trusted admin-entered embed script. ?>
		<?php endif; ?>
	</div>
</main>
