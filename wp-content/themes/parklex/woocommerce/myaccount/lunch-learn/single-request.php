<?php
/**
 * Lunch & Learn request detail — My Account (?lrequest-id=).
 * Ownership already verified by Bis_Core_Lunch_Learn::render_my_account_content().
 *
 * @var int $request_id
 */
defined( 'ABSPATH' ) || exit;

$approved   = get_field( 'distributor_approved', $request_id );
$assistants = get_field( 'assistants', $request_id ) ?: array();
$type_event = get_field( 'type-event', $request_id );
?>

<div class="c-lunch-learn-my-account c-lunch-learn-my-account--single" data-role="single-request" data-request-id="<?php echo (int) $request_id; ?>" data-type-event="<?php echo esc_attr( $type_event ); ?>" data-approved="<?php echo $approved ? '1' : '0'; ?>">
	<?php if ( $intro = get_field( 'text_lunch_learn_single', 'option' ) ) : ?>
		<div class="c-lunch-learn-my-account__intro"><?php echo wp_kses_post( $intro ); ?></div>
	<?php endif; ?>

	<div class="c-lunch-learn-my-account__back">
		<a class="btn btn-dark" href="<?php echo esc_url( wc_get_endpoint_url( 'lunch-learn', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Back to requests', 'parklex' ); ?></a>
	</div>

	<h3>#<?php echo (int) $request_id; ?> — <?php echo esc_html( get_the_title( $request_id ) ); ?></h3>

	<section class="c-lunch-learn-my-account__assistants" data-role="assistants-section">
		<h4>
			<?php esc_html_e( 'Attendees', 'parklex' ); ?>
			<?php if ( $expected = get_field( 'expected-assistants', $request_id ) ) : ?>
				(<?php esc_html_e( 'Number of expected attendees', 'parklex' ); ?>: <?php echo (int) $expected; ?>)
			<?php endif; ?>
		</h4>

		<div data-role="assistants-rows">
			<?php if ( $approved ) : ?>
				<?php foreach ( $assistants as $assistant ) : ?>
					<div class="c-lunch-learn-my-account__assistant-row">
						<span><?php echo esc_html( $assistant['name'] . ' ' . $assistant['surname'] . ' (' . $assistant['email'] . ')' ); ?></span>
						<?php if ( ! empty( $assistant['associate_number'] ) ) : ?><span><?php esc_html_e( 'Associate number', 'parklex' ); ?>: <?php echo esc_html( $assistant['associate_number'] ); ?></span><?php endif; ?>
						<?php if ( ! empty( $assistant['certificate'] ) ) : ?><span><?php esc_html_e( 'Certificate', 'parklex' ); ?>: <?php echo esc_html( $assistant['certificate'] ); ?></span><?php endif; ?>
						<?php if ( ! empty( $assistant['comments'] ) ) : ?><p><?php echo esc_html( $assistant['comments'] ); ?></p><?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php if ( ! $approved ) : ?>
			<template data-role="assistant-row-template">
				<div class="c-lunch-learn-my-account__assistant-row" data-role="assistant-row">
					<button type="button" class="c-lunch-learn-my-account__remove-row" data-role="remove-assistant" aria-label="<?php esc_attr_e( 'Remove', 'parklex' ); ?>">&times;</button>
					<label><span><?php esc_html_e( 'Name', 'parklex' ); ?></span><input type="text" data-field="name"></label>
					<label><span><?php esc_html_e( 'Surname', 'parklex' ); ?></span><input type="text" data-field="surname"></label>
					<label><span><?php esc_html_e( 'Email', 'parklex' ); ?></span><input type="email" data-field="email"></label>
					<label><span><?php esc_html_e( 'Associate number', 'parklex' ); ?></span><input type="text" data-field="associate_number"></label>
					<label><span><?php esc_html_e( 'Certificate', 'parklex' ); ?></span>
						<select data-field="certificate" data-role="certificate-select">
							<option value=""><?php esc_html_e( 'Certificate', 'parklex' ); ?></option>
						</select>
					</label>
					<label><span><?php esc_html_e( 'Comments', 'parklex' ); ?></span><textarea data-field="comments" rows="2"></textarea></label>
				</div>
			</template>

			<script type="application/json" data-role="assistants-data"><?php echo wp_json_encode( $assistants ); ?></script>

			<div class="c-lunch-learn-my-account__actions">
				<button type="button" class="btn btn-outline-dark" data-role="add-assistant"><?php esc_html_e( 'Add Attendee', 'parklex' ); ?></button>
				<button type="button" class="btn btn-dark" data-role="save-assistants"><?php esc_html_e( 'Save Attendee info', 'parklex' ); ?></button>
			</div>
		<?php endif; ?>
	</section>

	<section class="c-lunch-learn-my-account__invoices">
		<h4><?php esc_html_e( 'Invoices', 'parklex' ); ?></h4>
		<p>
			<?php esc_html_e( 'If you have organized a Lunch & Learn, upload your invoice, the catering invoice, and the list of attendees.', 'parklex' ); ?><br>
			<?php esc_html_e( 'In the case of organizing a show, upload the event invoice and your personal invoice.', 'parklex' ); ?>
		</p>

		<div class="c-lunch-learn-my-account__file-list" data-role="file-list">
			<?php echo Bis_Core_Lunch_Learn_Legacy::render_invoice_file_list( $request_id ); // phpcs:ignore ?>
		</div>

		<?php if ( ! $approved ) : ?>
			<div class="c-lunch-learn-my-account__upload">
				<input type="file" data-role="invoice-file-input" accept=".xlsx,.xls,image/*,.doc,.docx,.ppt,.pptx,.txt,.pdf" multiple>
			</div>

			<div class="c-lunch-learn-my-account__message" data-role="message" hidden></div>

			<div class="c-lunch-learn-my-account__actions">
				<button type="button" class="btn btn-dark" data-role="send-invoice"><?php esc_html_e( 'Send info', 'parklex' ); ?></button>
			</div>
		<?php endif; ?>
	</section>
</div>
