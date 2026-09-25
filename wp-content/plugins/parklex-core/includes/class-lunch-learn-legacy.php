<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Meta keys `_publish_lunch_learn`, `_draft_lunch_learn`, `_remember_date_lunch_learn`,
 * `_remember_date_lunch_learn_invoice` and `_user_lunch_learn_files` are kept with their
 * exact original names on purpose: ~150 real "Lunch & Learn" requests already carry this
 * data in the shared production database. Renaming them would make this code think no
 * approval/rejection email had ever been sent for existing requests and re-send them.
 */
class Bis_Core_Lunch_Learn_Legacy {

	const ROLE = 'lunch_learn_editor';

	const NONCE_ACTION = 'bis_lunch_learn_request';

	const CREATE_REQUEST_ACTION        = 'bis_lunch_learn_create_request';
	const UPLOAD_INVOICE_FILE_ACTION   = 'bis_lunch_learn_upload_invoice_file';
	const REMOVE_INVOICE_FILE_ACTION   = 'bis_lunch_learn_remove_invoice_file';
	const SEND_INVOICE_FILES_ACTION    = 'bis_lunch_learn_send_invoice_files';
	const SAVE_ASSISTANTS_ACTION       = 'bis_lunch_learn_save_assistants';
	const GENERATE_REPORT_ACTION       = 'bis_lunch_learn_generate_report';
	const DOWNLOAD_REPORT_ACTION       = 'bis_lunch_learn_download_report';

	const APPROVED_EMAIL_SENT_META = '_publish_lunch_learn';
	const REJECTED_EMAIL_SENT_META = '_draft_lunch_learn';
	const REMIND_EVENT_DATE_META   = '_remember_date_lunch_learn';
	const REMIND_INVOICE_DATE_META = '_remember_date_lunch_learn_invoice';
	const USER_FILE_META           = '_user_lunch_learn_files';

	const REMIND_EVENT_TRANSIENT   = 'remember_lanch_learn_distributor_email';
	const REMIND_INVOICE_TRANSIENT = 'repeat_lanch_learn_invoices_email';

	/**
	 * Kept as the ORIGINAL theme's email ids on purpose (not renamed to a "bis_lunch_learn_*"
	 * pattern): each one already has real, hand-written copy saved in the production database
	 * under `woocommerce_{id}_settings` (recipient overrides, subject, and — most importantly —
	 * the "content_email" body text with real business copy, e.g. "IMPORTANT: AIA number is
	 * required"). Renaming the id would silently orphan that content and send blank emails.
	 */
	const EMAIL_CREATE             = 'theme_email_create_lunch_learn';
	const EMAIL_APPROVED           = 'theme_email_lunch_learn_distributor';
	const EMAIL_REJECTED           = 'theme_email_lunch_learn_distributor_reject';
	const EMAIL_INVOICE_SUBMITTED  = 'theme_email_invoice_lunch_learn';
	const EMAIL_INVOICE_RETURNED   = 'theme_email_invoice_return_lunch_learn';
	const EMAIL_INVOICE_REMINDER   = 'theme_email_remember_invoice_lunch_learn';

	/**
	 * Idempotent: safe to run on every activation (add_role() is a no-op if the role
	 * already exists — and it already does, for 5 real users, in the production database
	 * this environment is a copy of). Also seeds "type_of_events" once, carrying over the
	 * real configuration that used to live on the old front-end page (see
	 * fields/options-lunch-learn-settings.php).
	 */
	public static function activate() {
		add_role( self::ROLE, __( 'Lunch & Learn Editor', 'parklex-core' ), array(
			'read'         => true,
			'edit_posts'   => false,
			'delete_posts' => false,
			'publish_posts' => false,
			'upload_files' => true,
		) );

		self::seed_type_of_events_option();
	}

	private static function seed_type_of_events_option() {
		if ( get_field( 'type_of_events', 'option' ) ) {
			return;
		}

		update_field( 'type_of_events', array(
			array(
				'type'         => 'Official CEU / AIA',
				'course_names' => array(
					array( 'code' => 'AIACESPCPDINT3', 'name' => 'Interior Engineered Woods - The Next Generation' ),
					array( 'code' => 'AIACESPUSA3', 'name' => 'High pressure laminates in modern building design' ),
				),
			),
			array(
				'type'         => 'Official CPD / RIBA',
				'course_names' => array(
					array( 'code' => 'timber-claddings', 'name' => 'Timber claddings complying with UK Fire Regulations (RIBA Approved)' ),
					array( 'code' => 'maintenance-free-timber', 'name' => 'Maintenance Free Timber Solutions for ventilated façades (RIBA Approved)' ),
					array( 'code' => 'fire-resistance-timber', 'name' => 'Fire resistance timber flooring for Fire Escape applications (SBID Approved)' ),
					array( 'code' => 'installation-wood -based-panels', 'name' => 'Installation of wood based panels for extremely wet areas (SBID Approved)' ),
					array( 'code' => 'wood-flooring-commercial-applications', 'name' => 'Wood flooring for commercial applications (SBID Approved)' ),
				),
			),
			array( 'type' => 'Lunch & Learn', 'course_names' => array() ),
			array( 'type' => 'Show', 'course_names' => array() ),
		), 'option' );
	}

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'grant_role_capabilities' ), 999 );
		add_action( 'admin_init', array( __CLASS__, 'allow_admin_access_for_role' ), 1 );

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'woocommerce_email_classes', array( __CLASS__, 'register_email_classes' ) );
		add_action( 'publish_lunch_learn_request', array( __CLASS__, 'send_approved_email' ) );
		add_action( 'draft_lunch_learn_request', array( __CLASS__, 'send_rejected_email' ) );
		add_filter( 'acf/update_value/name=distributor_approved', array( __CLASS__, 'maybe_send_invoice_return_email' ), 10, 3 );

		add_action( 'init', array( __CLASS__, 'remind_pending_invoice_after_event' ) );
		add_action( 'init', array( __CLASS__, 'remind_pending_invoice_followup' ) );

		add_action( 'wp_ajax_' . self::CREATE_REQUEST_ACTION, array( __CLASS__, 'handle_create_request' ) );
		add_action( 'wp_ajax_' . self::UPLOAD_INVOICE_FILE_ACTION, array( __CLASS__, 'handle_upload_invoice_file' ) );
		add_action( 'wp_ajax_' . self::REMOVE_INVOICE_FILE_ACTION, array( __CLASS__, 'handle_remove_invoice_file' ) );
		add_action( 'wp_ajax_' . self::SEND_INVOICE_FILES_ACTION, array( __CLASS__, 'handle_send_invoice_files' ) );
		add_action( 'wp_ajax_' . self::SAVE_ASSISTANTS_ACTION, array( __CLASS__, 'handle_save_assistants' ) );

		add_action( 'acf/render_field/type=message', array( __CLASS__, 'render_statistics_report_field' ) );
		add_action( 'wp_ajax_' . self::GENERATE_REPORT_ACTION, array( __CLASS__, 'handle_generate_report' ) );
		add_action( 'wp_ajax_' . self::DOWNLOAD_REPORT_ACTION, array( __CLASS__, 'handle_download_report' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_statistics_admin_script' ) );
	}

	/**
	 * CRUD capabilities for the "lunch_learn_request" CPT's custom capability_type
	 * ("llrequest"/"llrequests" — see Bis_Core_CPT_Manager::register_lunch_learn_request()).
	 * Needed even for "editor"/"administrator": a custom capability_type isn't covered by
	 * their default capabilities.
	 */
	public static function grant_role_capabilities() {
		$roles = array( self::ROLE, 'editor', 'administrator' );

		foreach ( $roles as $role_name ) {
			$role = get_role( $role_name );

			if ( ! $role ) {
				continue;
			}

			$role->add_cap( 'read' );
			$role->add_cap( 'read_llrequest' );
			$role->add_cap( 'read_private_llrequests' );
			$role->add_cap( 'edit_llrequest' );
			$role->add_cap( 'edit_llrequests' );
			$role->add_cap( 'edit_others_llrequests' );
			$role->add_cap( 'edit_published_llrequests' );
			$role->add_cap( 'publish_llrequests' );
			$role->add_cap( 'delete_llrequest' );
			$role->add_cap( 'delete_others_llrequests' );
			$role->add_cap( 'delete_private_llrequests' );
			$role->add_cap( 'delete_published_llrequests' );
		}
	}

	/**
	 * "lunch_learn_editor" has no store-management role, so WooCommerce would otherwise
	 * block it from wp-admin entirely.
	 */
	public static function allow_admin_access_for_role() {
		$user = wp_get_current_user();

		if ( ! empty( $user->roles[0] ) && self::ROLE === $user->roles[0] ) {
			add_filter( 'woocommerce_prevent_admin_access', '__return_false', 100 );
		}
	}

	public static function register_email_classes( $email_classes ) {
		require_once BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-legacy.php';

		$email_classes[ self::EMAIL_CREATE ]            = require BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-create-legacy.php';
		$email_classes[ self::EMAIL_APPROVED ]          = require BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-approved-legacy.php';
		$email_classes[ self::EMAIL_REJECTED ]          = require BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-rejected-legacy.php';
		$email_classes[ self::EMAIL_INVOICE_SUBMITTED ] = require BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-invoice-submitted-legacy.php';
		$email_classes[ self::EMAIL_INVOICE_RETURNED ]  = require BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-invoice-returned-legacy.php';
		$email_classes[ self::EMAIL_INVOICE_REMINDER ]  = require BIS_CORE_DIR . 'includes/emails/class-email-lunch-learn-invoice-reminder-legacy.php';

		return $email_classes;
	}

	/**
	 * Fires when a request is approved (post published from wp-admin). Guarded by
	 * APPROVED_EMAIL_SENT_META so re-saving an already-approved post doesn't re-send it.
	 */
	public static function send_approved_email( $post_id ) {
		if ( 'lunch_learn_request' !== get_post_type( $post_id ) ) {
			return;
		}

		$user_id = get_field( 'user_id', $post_id );

		if ( ! $user_id || get_post_meta( $post_id, self::APPROVED_EMAIL_SENT_META, true ) ) {
			return;
		}

		self::trigger_email( self::EMAIL_APPROVED, $post_id, $user_id );
		update_post_meta( $post_id, self::APPROVED_EMAIL_SENT_META, '1' );

		$event_date = get_field( 'date-request', $post_id );

		if ( ! $event_date ) {
			return;
		}

		$remind_date = DateTime::createFromFormat( 'd/m/Y', $event_date );

		if ( ! $remind_date ) {
			return;
		}

		$remind_interval = get_field( 'remember_interval', 'option' ) ?: 7;
		$remind_date->modify( '+' . (int) $remind_interval . ' day' );
		update_post_meta( $post_id, self::REMIND_EVENT_DATE_META, $remind_date->getTimestamp() );
	}

	/**
	 * Fires when a request is rejected (post moved to draft from wp-admin). Guarded by
	 * REJECTED_EMAIL_SENT_META so re-saving an already-rejected post doesn't re-send it.
	 */
	public static function send_rejected_email( $post_id ) {
		if ( 'lunch_learn_request' !== get_post_type( $post_id ) ) {
			return;
		}

		$user_id = get_field( 'user_id', $post_id );

		if ( ! $user_id || get_post_meta( $post_id, self::REJECTED_EMAIL_SENT_META, true ) ) {
			return;
		}

		self::trigger_email( self::EMAIL_REJECTED, $post_id, $user_id );
		update_post_meta( $post_id, self::REJECTED_EMAIL_SENT_META, '1' );
	}

	/**
	 * When an admin unchecks "Distributor approved invoices" on an already-approved
	 * request (from wp-admin), send the invoice back to the distributor for editing.
	 */
	public static function maybe_send_invoice_return_email( $value, $post_id, $field ) {
		$submitted_value = isset( $_POST['acf'][ $field['key'] ] ) ? $_POST['acf'][ $field['key'] ] : '';
		$old_value       = get_field( 'distributor_approved', $post_id, false );

		if ( empty( $submitted_value ) && $old_value ) {
			$user_id = get_field( 'user_id', $post_id );

			if ( $user_id ) {
				self::trigger_email( self::EMAIL_INVOICE_RETURNED, $post_id, $user_id );
			}
		}

		return $value;
	}

	/**
	 * Runs once per day (transient-guarded "fake cron", same mechanism as the original
	 * theme): reminds distributors to submit their invoice once REMIND_EVENT_DATE_META
	 * (set on approval, see send_approved_email()) is due.
	 */
	public static function remind_pending_invoice_after_event() {
		if ( did_action( 'init' ) >= 2 || get_transient( self::REMIND_EVENT_TRANSIENT ) ) {
			return;
		}

		set_transient( self::REMIND_EVENT_TRANSIENT, '1', DAY_IN_SECONDS );

		$request_ids = get_posts( array(
			'post_type'      => 'lunch_learn_request',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => self::REMIND_EVENT_DATE_META,
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		) );

		if ( empty( $request_ids ) ) {
			return;
		}

		$now = time();

		foreach ( $request_ids as $request_id ) {
			$remind_date = (int) get_post_meta( $request_id, self::REMIND_EVENT_DATE_META, true );

			if ( $remind_date && $now > $remind_date ) {
				self::trigger_email( self::EMAIL_INVOICE_REMINDER, $request_id );
				delete_post_meta( $request_id, self::REMIND_EVENT_DATE_META );
			}
		}
	}

	/**
	 * Runs once per day: re-notifies about an invoice that was submitted (see
	 * handle_send_invoice_files()) but, 10 days later, hasn't been processed yet
	 * (REMIND_INVOICE_DATE_META still set). Re-uses the "invoice submitted" email.
	 */
	public static function remind_pending_invoice_followup() {
		if ( did_action( 'init' ) >= 2 || get_transient( self::REMIND_INVOICE_TRANSIENT ) ) {
			return;
		}

		set_transient( self::REMIND_INVOICE_TRANSIENT, '1', DAY_IN_SECONDS );

		$request_ids = get_posts( array(
			'post_type'      => 'lunch_learn_request',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => self::REMIND_INVOICE_DATE_META,
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		) );

		if ( empty( $request_ids ) ) {
			return;
		}

		$now = time();

		foreach ( $request_ids as $request_id ) {
			$remind_date = (int) get_post_meta( $request_id, self::REMIND_INVOICE_DATE_META, true );

			if ( $remind_date && $now > $remind_date ) {
				$user_id = get_field( 'user_id', $request_id );
				self::trigger_email( self::EMAIL_INVOICE_SUBMITTED, $request_id, $user_id );
				delete_post_meta( $request_id, self::REMIND_INVOICE_DATE_META );
			}
		}
	}

	/**
	 * Look up a registered WC_Email by id and trigger it — WooCommerce keys
	 * WC_Emails::get_emails() by class name, not by ->id, so a lookup loop is needed.
	 */
	private static function trigger_email( $email_id, ...$args ) {
		foreach ( WC()->mailer()->get_emails() as $mail ) {
			if ( $email_id === $mail->id ) {
				$mail->trigger( ...$args );
				return;
			}
		}
	}

	/**
	 * AJAX: create a request from the public "Lunch & Learn" form. `$_POST['data']` is a
	 * serialized form string (built client-side the same way jQuery's .serialize() did),
	 * bulk-written as post meta — same technique the original theme used, which is known to
	 * work fine with the ~150 requests already created this way in production.
	 */
	public static function handle_create_request() {
		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) {
			wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'parklex-core' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You must be logged in to create a request.', 'parklex-core' ) );
		}

		$user_id = get_current_user_id();

		if ( ! get_field( 'allow_ll_request', 'user_' . $user_id ) ) {
			wp_send_json_error( __( 'You are not allowed to create a request.', 'parklex-core' ) );
		}

		$fields = array();
		parse_str( wp_unslash( $_POST['data'] ), $fields );

		if ( empty( $fields['date-request'] ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		unset( $fields['assistants'] );

		// The form's <input type="date"> posts ISO "YYYY-MM-DD" — reformat to "d/m/Y" for
		// the post title (matches the ~150 existing request titles) and to "Ymd" for ACF's
		// date_picker internal storage (same format regardless of display settings).
		$date = DateTime::createFromFormat( 'Y-m-d', $fields['date-request'] );

		if ( ! $date ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		// "name-presentation" is empty when the chosen "type-event" has associated courses —
		// the form shows "course-name-text" instead in that case (see lunch-learn-request.js).
		$title_source = ! empty( $fields['name-presentation'] ) ? $fields['name-presentation'] : ( $fields['course-name-text'] ?? '' );
		$title        = $title_source . ' (' . $date->format( 'd/m/Y' ) . ')';
		unset( $fields['date-request'] );

		$fields['user_id'] = $user_id;

		$request_id = wp_insert_post( array(
			'post_title'  => sanitize_text_field( $title ),
			'post_status' => 'pending',
			'post_type'   => 'lunch_learn_request',
			'meta_input'  => $fields,
		) );

		if ( ! $request_id || is_wp_error( $request_id ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		update_field( 'date-request', $date->format( 'Ymd' ), $request_id );

		self::trigger_email( self::EMAIL_CREATE, $request_id, $user_id );

		wp_send_json_success( __( 'Request successfully created. Wait for confirmation.', 'parklex-core' ) );
	}

	/**
	 * AJAX: upload one or more invoice files to a request's "invoices_files" repeater,
	 * from the request's My Account page. Returns the updated file list markup so the JS
	 * can swap it in directly.
	 */
	public static function handle_upload_invoice_file() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) {
			wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'parklex-core' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You must be logged in to upload files.', 'parklex-core' ) );
		}

		if ( empty( $_POST['request_id'] ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		$request_id = absint( $_POST['request_id'] );

		if ( absint( get_field( 'user_id', $request_id ) ) !== get_current_user_id() ) {
			wp_send_json_error( __( 'You are not allowed to upload files for this request.', 'parklex-core' ) );
		}

		if ( empty( $_FILES ) ) {
			wp_send_json_error( __( 'No files found for upload.', 'parklex-core' ) );
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		add_filter( 'upload_mimes', array( __CLASS__, 'restrict_upload_mimes_for_invoices' ) );

		$uploaded_ids = array();

		foreach ( $_FILES as $file_id => $file ) {
			$attachment_id = media_handle_upload( $file_id, 0 );

			if ( is_wp_error( $attachment_id ) ) {
				remove_filter( 'upload_mimes', array( __CLASS__, 'restrict_upload_mimes_for_invoices' ) );
				wp_send_json_error( $attachment_id->get_error_message() );
			}

			update_post_meta( $attachment_id, self::USER_FILE_META, '1' );
			$uploaded_ids[] = $attachment_id;
		}

		remove_filter( 'upload_mimes', array( __CLASS__, 'restrict_upload_mimes_for_invoices' ) );

		$invoices_files = get_field( 'invoices_files', $request_id ) ?: array();

		foreach ( $uploaded_ids as $attachment_id ) {
			$invoices_files[] = array( 'file' => $attachment_id );
		}

		update_field( 'invoices_files', $invoices_files, $request_id );

		wp_send_json_success( array(
			'text'      => __( 'Files successfully uploaded.', 'parklex-core' ),
			'file_list' => self::render_invoice_file_list( $request_id ),
		) );
	}

	public static function restrict_upload_mimes_for_invoices( $mimes ) {
		return array(
			'png'  => 'image/png',
			'jpe'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg'  => 'image/jpeg',
			'gif'  => 'image/gif',
			'xls'  => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'doc'  => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'ppt'  => 'application/vnd.ms-powerpoint',
			'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'txt'  => 'text/plain',
			'pdf'  => 'application/pdf',
		);
	}

	/**
	 * AJAX: remove one invoice file from a request.
	 */
	public static function handle_remove_invoice_file() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) {
			wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'parklex-core' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You must be logged in to remove files.', 'parklex-core' ) );
		}

		if ( empty( $_POST['request_id'] ) || empty( $_POST['atach_id'] ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		$request_id    = absint( $_POST['request_id'] );
		$attachment_id = absint( $_POST['atach_id'] );

		if ( absint( get_field( 'user_id', $request_id ) ) !== get_current_user_id() ) {
			wp_send_json_error( __( 'You are not allowed to remove files for this request.', 'parklex-core' ) );
		}

		$invoices_files = get_field( 'invoices_files', $request_id ) ?: array();

		foreach ( $invoices_files as $index => $invoice_file ) {
			if ( absint( $invoice_file['file'] ) === $attachment_id ) {
				unset( $invoices_files[ $index ] );
			}
		}

		wp_delete_attachment( $attachment_id );
		update_field( 'invoices_files', array_values( $invoices_files ), $request_id );

		wp_send_json_success( self::render_invoice_file_list( $request_id ) );
	}

	/**
	 * AJAX: finalize the invoice — saves the attendee list, sends it to the admin, marks
	 * "distributor_approved", and schedules the 10-day follow-up reminder (see
	 * remind_pending_invoice_followup()).
	 */
	public static function handle_send_invoice_files() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) {
			wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'parklex-core' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You must be logged in to send files.', 'parklex-core' ) );
		}

		if ( empty( $_POST['request_id'] ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		$request_id = absint( $_POST['request_id'] );

		if ( absint( get_field( 'user_id', $request_id ) ) !== get_current_user_id() ) {
			wp_send_json_error( __( 'You are not allowed to send files for this request.', 'parklex-core' ) );
		}

		if ( ! get_field( 'invoices_files', $request_id ) ) {
			wp_send_json_error( __( 'No files found to send.', 'parklex-core' ) );
		}

		$assistants = ! empty( $_POST['data'] ) && is_array( $_POST['data'] ) ? array_values( $_POST['data'] ) : array();
		update_field( 'assistants', $assistants, $request_id );

		self::trigger_email( self::EMAIL_INVOICE_SUBMITTED, $request_id, get_current_user_id() );

		$remind_date = new DateTime();
		$remind_date->modify( '+10 day' );
		update_post_meta( $request_id, self::REMIND_INVOICE_DATE_META, $remind_date->getTimestamp() );

		update_field( 'distributor_approved', 1, $request_id );

		wp_send_json_success();
	}

	/**
	 * AJAX: save the attendee list without finalizing the invoice.
	 */
	public static function handle_save_assistants() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) {
			wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'parklex-core' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You must be logged in to save attendees.', 'parklex-core' ) );
		}

		if ( empty( $_POST['request_id'] ) ) {
			wp_send_json_error( __( 'Unknown error', 'parklex-core' ) );
		}

		$request_id = absint( $_POST['request_id'] );

		if ( absint( get_field( 'user_id', $request_id ) ) !== get_current_user_id() ) {
			wp_send_json_error( __( 'You are not allowed to save attendees for this request.', 'parklex-core' ) );
		}

		if ( empty( $_POST['data'] ) || ! is_array( $_POST['data'] ) ) {
			wp_send_json_error( __( 'No attendees found to save.', 'parklex-core' ) );
		}

		update_field( 'assistants', array_values( $_POST['data'] ), $request_id );

		wp_send_json_success( __( 'Attendee info successfully saved.', 'parklex-core' ) );
	}

	/**
	 * Shared invoice-file-list markup, used on first render of the request's My Account
	 * page and re-rendered by the upload/remove AJAX handlers above — single source of
	 * truth, kept in the theme like any other My Account partial.
	 */
	public static function render_invoice_file_list( $request_id ) {
		return wc_get_template_html( 'myaccount/lunch-learn/invoice-file-list.php', array(
			'request_id'         => $request_id,
			'invoices_files'     => get_field( 'invoices_files', $request_id ) ?: array(),
			'distributor_approved' => get_field( 'distributor_approved', $request_id ),
		) );
	}

	/**
	 * Renders the "Generate report" / "Download report" buttons + result table into the
	 * "report_container" message field on the Statistics options page — same mechanism as
	 * Bis_Core_Internal_Projects::render_gallery_uploader_field().
	 */
	public static function render_statistics_report_field( $field ) {
		if ( empty( $field['wrapper']['id'] ) || 'bis-lunch-learn-statistics-container' !== $field['wrapper']['id'] ) {
			return;
		}
		?>
		<div class="lreport-btn-holder first-holder">
			<a id="lgenerate-report" href="#" class="button button-primary button-large"><?php esc_html_e( 'Generate report', 'parklex-core' ); ?></a>
			<img id="lgenerate-report-loader" style="display:none" src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>">
		</div>
		<div class="lreport-btn-holder second-holder">
			<img id="ldownload-report-loader" style="display:none" src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>">
			<a id="ldownload-report" href="#" class="button button-primary button-large"><?php esc_html_e( 'Download report', 'parklex-core' ); ?></a>
		</div>
		<div id="listing-table">
			<table id="lreport-table" class="wp-list-table widefat fixed striped posts">
				<tr><td colspan="6"><?php esc_html_e( 'Select a "Report type" and click "Generate report".', 'parklex-core' ); ?></td></tr>
			</table>
		</div>
		<?php
	}

	public static function enqueue_statistics_admin_script( $hook ) {
		if ( 'lunch_learn_request_page_acf-options-lunch-learn-statistics' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'bis-core-lunch-learn-statistics',
			BIS_CORE_URL . 'assets/js/lunch-learn-admin-statistics.js',
			array(),
			BIS_CORE_VERSION,
			true
		);

		wp_localize_script( 'bis-core-lunch-learn-statistics', 'bisLunchLearnStatistics', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( self::NONCE_ACTION ),
		) );
	}

	private static function completed_request_ids() {
		return get_posts( array(
			'post_type'      => 'lunch_learn_request',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => 'complited_lrequest',
			'meta_value'     => '1',
		) );
	}

	/**
	 * Groups completed requests for the statistics report, by distributor / studio /
	 * attendee depending on `$type` — shared between handle_generate_report() (HTML) and
	 * handle_download_report() (CSV), which used to duplicate this exact grouping twice.
	 */
	private static function build_report_groups( $type ) {
		$request_ids = self::completed_request_ids();

		if ( empty( $request_ids ) ) {
			return array();
		}

		$groups = array();

		foreach ( $request_ids as $request_id ) {
			$row = array(
				'name-presentation'    => get_field( 'name-presentation', $request_id ),
				'design-firm-request'  => get_field( 'design-firm-request', $request_id ),
				'date-request'         => get_field( 'date-request', $request_id ),
				'location-request'     => get_field( 'location-request', $request_id ),
				'cost-presentation'    => get_field( 'cost-presentation', $request_id ),
				'assistants'           => count( (array) get_field( 'assistants', $request_id ) ),
			);

			if ( 'distributor' === $type ) {
				$user_id = get_field( 'user_id', $request_id );

				if ( ! $user_id ) {
					continue;
				}

				$user_data = get_userdata( $user_id );
				$label     = trim( $user_data->first_name . ' ' . $user_data->last_name ) . ' (' . $user_data->user_email . ')';
				$groups[ $label ][] = $row;
			} elseif ( 'studios' === $type ) {
				$studio = trim( (string) get_field( 'design-firm-request', $request_id ) );

				if ( ! $studio ) {
					continue;
				}

				$row['assistant_column'] = self::request_owner_label( $request_id );
				$groups[ $studio ][]     = $row;
			} elseif ( 'assistants' === $type ) {
				$assistants = get_field( 'assistants', $request_id );

				if ( empty( $assistants ) ) {
					continue;
				}

				foreach ( $assistants as $assistant ) {
					$label                   = trim( $assistant['name'] . ' ' . $assistant['surname'] );
					$row['assistant_column'] = self::request_owner_label( $request_id );
					$groups[ $label ][]      = $row;
				}
			}
		}

		return $groups;
	}

	private static function request_owner_label( $request_id ) {
		$user_data = get_userdata( get_field( 'user_id', $request_id ) );

		if ( ! $user_data ) {
			return '';
		}

		return trim( $user_data->first_name . ' ' . $user_data->last_name ) . ' (' . $user_data->user_email . ')';
	}

	private static function report_headings( $type ) {
		return 'distributor' === $type
			? array( __( 'Name of the presentation', 'parklex-core' ), __( 'Architectural / Interior design Firm', 'parklex-core' ), __( 'Date', 'parklex-core' ), __( 'Location', 'parklex-core' ), __( 'Cost of the event per person', 'parklex-core' ), __( 'Assistants count', 'parklex-core' ) )
			: array( __( 'Name of the presentation', 'parklex-core' ), __( 'User', 'parklex-core' ), __( 'Date', 'parklex-core' ), __( 'Location', 'parklex-core' ), __( 'Cost of the event per person', 'parklex-core' ), __( 'Assistants count', 'parklex-core' ) );
	}

	public static function handle_generate_report() {
		self::authorize_report_request();

		$type   = sanitize_key( $_POST['type'] );
		$groups = self::build_report_groups( $type );

		if ( empty( $groups ) ) {
			wp_send_json_error( __( 'No completed requests found for the report.', 'parklex-core' ) );
		}

		$headings = self::report_headings( $type );
		$distributor_columns = 'distributor' === $type;

		$html  = '<thead><tr>';
		foreach ( $headings as $heading ) {
			$html .= '<th>' . esc_html( $heading ) . '</th>';
		}
		$html .= '</tr></thead><tbody>';

		foreach ( $groups as $label => $rows ) {
			$html .= '<tr><td colspan="6" style="text-align:center;"><strong>' . esc_html( $label ) . '</strong></td></tr>';
			$cost  = 0;

			foreach ( $rows as $row ) {
				$cost += (float) $row['cost-presentation'];
				$name_or_owner = $distributor_columns ? $row['design-firm-request'] : $row['assistant_column'];
				$html .= '<tr><td>' . esc_html( $row['name-presentation'] ) . '</td><td>' . esc_html( $name_or_owner ) . '</td><td>' . esc_html( $row['date-request'] ) . '</td><td>' . esc_html( $row['location-request'] ) . '</td><td>' . esc_html( $row['cost-presentation'] ) . '</td><td>' . esc_html( $row['assistants'] ) . '</td></tr>';
			}

			$html .= '<tr><td colspan="6"><strong>' . esc_html__( 'Total', 'parklex-core' ) . '</strong></td></tr>';
			$html .= '<tr><td>' . esc_html__( 'Events count:', 'parklex-core' ) . ' ' . count( $rows ) . '</td><td></td><td></td><td></td><td>' . esc_html__( 'Count:', 'parklex-core' ) . ' ' . esc_html( $cost ) . '</td><td></td></tr>';
			$html .= '<tr><td colspan="6"><hr></td></tr>';
		}

		$html .= '</tbody>';

		wp_send_json_success( $html );
	}

	public static function handle_download_report() {
		self::authorize_report_request();

		$type   = sanitize_key( $_POST['type'] );
		$groups = self::build_report_groups( $type );

		if ( empty( $groups ) ) {
			wp_send_json_error( __( 'No completed requests found for the report.', 'parklex-core' ) );
		}

		$headings             = self::report_headings( $type );
		$distributor_columns  = 'distributor' === $type;
		$rows_for_csv         = array( $headings );

		foreach ( $groups as $label => $rows ) {
			$cost = 0;

			$rows_for_csv[] = array( '', '', '', '', '', '' );
			$rows_for_csv[] = array( $label, '', '', '', '', '' );

			foreach ( $rows as $row ) {
				$cost += (float) $row['cost-presentation'];
				$name_or_owner = $distributor_columns ? $row['design-firm-request'] : $row['assistant_column'];
				$rows_for_csv[] = array( $row['name-presentation'], $name_or_owner, $row['date-request'], $row['location-request'], $row['cost-presentation'], $row['assistants'] );
			}

			$rows_for_csv[] = array( __( 'Total', 'parklex-core' ), '', '', '', '', '' );
			$rows_for_csv[] = array( __( 'Events count:', 'parklex-core' ) . ' ' . count( $rows ), '', '', '', __( 'Count:', 'parklex-core' ) . ' ' . $cost, '' );
		}

		$upload_dir = wp_get_upload_dir();
		$filename   = 'lunch-learn-' . $type . '-report-' . time() . '.csv';
		$path       = $upload_dir['basedir'] . '/lunch-learn-report/' . $filename;
		$url        = $upload_dir['baseurl'] . '/lunch-learn-report/' . $filename;

		if ( ! self::write_csv_file( $rows_for_csv, $path ) ) {
			wp_send_json_error( __( 'Something went wrong, please try again.', 'parklex-core' ) );
		}

		wp_send_json_success( $url );
	}

	private static function authorize_report_request() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) {
			wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'parklex-core' ) );
		}

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( __( 'You are not allowed to generate reports.', 'parklex-core' ) );
		}
	}

	/**
	 * Same delimiter-escaping logic as the original theme's `theme_create_csv_file()`, but
	 * without its `iconv( 'UTF-8', 'cp1251', ... )` conversion — cp1251 is Cyrillic and
	 * can't represent accented characters (á, ñ, ü…), so any such report silently produced
	 * an empty file. Plain UTF-8 + BOM (for Excel) instead.
	 */
	private static function write_csv_file( array $rows, $path ) {
		if ( ! wp_mkdir_p( dirname( $path ) ) ) {
			return false;
		}

		$csv = '';

		foreach ( $rows as $row ) {
			$columns = array();

			foreach ( $row as $value ) {
				$value = (string) $value;

				if ( '' !== $value && preg_match( '/[",;\r\n]/', $value ) ) {
					$value = str_replace( "\r\n", '\n', $value );
					$value = str_replace( "\r", '', $value );
					$value = '"' . str_replace( '"', '""', $value ) . '"';
				}

				$columns[] = $value;
			}

			$csv .= implode( ';', $columns ) . "\r\n";
		}

		return false !== file_put_contents( $path, "\xEF\xBB\xBF" . $csv );
	}
}
