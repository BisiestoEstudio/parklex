<?php
/**
 * Template Name: Lunch & Learn Request
 */
defined( 'ABSPATH' ) || exit;

get_header();

$required_mark = '<span class="c-lunch-learn-form__required" aria-hidden="true">*</span>';
?>

<main class="entry-content is-layout-constrained has-global-padding">
	<div class="c-lunch-learn-form">
		<div class="c-lunch-learn-form__heading">
			<?php the_content(); ?>
		</div>

		<form id="lunch-learn-request-form" class="c-lunch-learn-form__form" novalidate>
			<p class="c-lunch-learn-form__required-note"><?php echo $required_mark; // phpcs:ignore ?> <?php esc_html_e( 'Required fields', 'parklex' ); ?></p>

			<div class="c-lunch-learn-form__row">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Architectural / Interior design Firm', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="text" name="design-firm-request" required>
				</label>
			</div>

			<div class="c-lunch-learn-form__row c-lunch-learn-form__row--split">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Date', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="date" name="date-request" required>
				</label>
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Time', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="time" name="time-request" required>
				</label>
			</div>

			<div class="c-lunch-learn-form__row c-lunch-learn-form__row--split">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Location', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="text" name="location-request" required>
				</label>
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Address', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="text" name="address-request" required>
				</label>
			</div>

			<div class="c-lunch-learn-form__row c-lunch-learn-form__row--split">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Number of expected attendees', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="number" name="expected-assistants" min="1" required>
				</label>
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Type of event', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<select name="type-event" data-role="type-event" required>
						<option value=""><?php esc_html_e( 'Select a type', 'parklex' ); ?></option>
					</select>
				</label>
			</div>

			<div class="c-lunch-learn-form__row" data-role="name-presentation-row">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Name of the presentation', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="text" name="name-presentation" data-role="name-presentation">
				</label>
			</div>

			<div class="c-lunch-learn-form__row" data-role="name-course-row" hidden>
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Course name', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<select name="name-course" data-role="name-course">
						<option value=""><?php esc_html_e( 'Select a course', 'parklex' ); ?></option>
					</select>
				</label>
			</div>

			<div class="c-lunch-learn-form__row">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Cost of the event per person', 'parklex' ); ?> <?php echo $required_mark; // phpcs:ignore ?></span>
					<input type="number" name="cost-presentation" min="0" step="0.01" required>
				</label>
			</div>

			<div class="c-lunch-learn-form__row">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Related to a project (optional)', 'parklex' ); ?></span>
					<input type="text" name="related-project-presentation">
				</label>
			</div>

			<div class="c-lunch-learn-form__row">
				<label class="c-lunch-learn-form__field">
					<span><?php esc_html_e( 'Comments', 'parklex' ); ?></span>
					<textarea name="request-comments" rows="3"></textarea>
				</label>
			</div>

			<input type="hidden" name="course-name-code" data-role="course-name-code">
			<input type="hidden" name="course-name-text" data-role="course-name-text">

			<div class="c-lunch-learn-form__message" data-role="message" hidden></div>

			<div class="c-lunch-learn-form__actions">
				<button type="submit" class="btn btn-outline-dark"><?php esc_html_e( 'Submit', 'parklex' ); ?></button>
			</div>
		</form>
	</div>
</main>

<?php get_footer(); ?>
