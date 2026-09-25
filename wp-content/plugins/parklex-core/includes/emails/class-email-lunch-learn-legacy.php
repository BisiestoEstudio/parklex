<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Shared base for the 6 Lunch & Learn WC_Email subclasses (Bis_Core_Email_Lunch_Learn_*_Legacy,
 * one per file in this directory). The original theme had ~120 lines of near-identical WC_Email
 * boilerplate PLUS an even more duplicated ~150-line "request details" HTML table repeated
 * verbatim across every one of its 6 email templates — consolidated here into one shared
 * constructor/content flow and one shared body template (templates/emails/lunch-learn-request.php),
 * used by every subclass. The "official" field (CEU/RIBA/CPD) is dropped from the details table:
 * see fields/cpt-lunch-learn-request.php.
 */
abstract class Bis_Core_Email_Lunch_Learn_Legacy extends WC_Email {

	public $request_id;

	/**
	 * True for the 2 admin-facing emails (create, invoice submitted) that let the admin
	 * override the recipient from wp-admin. The 4 distributor-facing emails always resolve
	 * to that distributor's address, so the field is hidden for them.
	 */
	protected $configurable_recipient = false;

	public function __construct() {
		$this->template_html = 'emails/lunch-learn-request.php';
		$this->template_base = BIS_CORE_DIR . 'templates/';
		$this->content        = '';

		parent::__construct();
	}

	/**
	 * Builds the email content/object and sends it. Subclasses' trigger() methods resolve
	 * who $recipient is (admin option, or the request's distributor) and call this.
	 */
	protected function send_to( $recipient, $request_id, array $attachments = array() ) {
		if ( ! $this->is_enabled() || ! $recipient ) {
			return;
		}

		$this->request_id = $request_id;

		$user_id   = get_field( 'user_id', $request_id );
		$user_data = $user_id ? get_userdata( $user_id ) : false;

		$this->object = array(
			'content'              => $this->get_option( 'content_email', '' ) ?: $this->content,
			'request_id'           => $request_id,
			'user_name'            => $user_data ? trim( $user_data->first_name . ' ' . $user_data->last_name ) : '',
			'user_email'           => $user_data ? $user_data->user_email : '',
			'request_name'         => get_the_title( $request_id ),
			'admin_link'           => admin_url( 'post.php?post=' . $request_id . '&action=edit' ),
			'invoice_account_link' => add_query_arg( array( 'lrequest-id' => $request_id ), wc_get_endpoint_url( 'lunch-learn', '', wc_get_page_permalink( 'myaccount' ) ) ),
			'request_details'      => $this->build_request_details_table( $request_id ),
		);

		$subject = str_replace( '{request_id}', $request_id, $this->get_subject() );

		$this->send( $recipient, $subject, $this->get_content(), $this->get_headers(), $attachments );
	}

	/**
	 * File paths from the request's "invoices_files" repeater, for emails that attach them.
	 */
	protected function invoice_file_attachments( $request_id ) {
		$attachments    = array();
		$invoices_files = get_field( 'invoices_files', $request_id );

		if ( ! $invoices_files ) {
			return $attachments;
		}

		foreach ( $invoices_files as $invoice_file ) {
			if ( ! empty( $invoice_file['file'] ) ) {
				$attachments[] = get_attached_file( $invoice_file['file'] );
			}
		}

		return $attachments;
	}

	public function get_content_html() {
		$heading = str_replace( '{request_id}', $this->request_id, $this->get_heading() );

		return wc_get_template_html( $this->template_html, array(
			'email_heading' => $heading,
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'         => $this,
		), '', $this->template_base );
	}

	protected function build_request_details_table( $request_id ) {
		$rows = array(
			__( 'Architectural / Interior design Firm', 'parklex-core' ) => get_field( 'design-firm-request', $request_id ),
			__( 'Date', 'parklex-core' )                          => get_field( 'date-request', $request_id ),
			__( 'Time', 'parklex-core' )                          => get_field( 'time-request', $request_id ),
			__( 'Address', 'parklex-core' )                       => get_field( 'address-request', $request_id ),
			__( 'Location', 'parklex-core' )                      => get_field( 'location-request', $request_id ),
			__( 'Number of expected assistants', 'parklex-core' ) => get_field( 'expected-assistants', $request_id ),
		);

		$html = '<table>';

		foreach ( $rows as $label => $value ) {
			if ( $value ) {
				$html .= '<tr><td>' . esc_html( $label ) . '</td><td>' . esc_html( $value ) . '</td></tr>';
			}
		}

		$assistants = get_field( 'assistants', $request_id );

		if ( $assistants ) {
			foreach ( $assistants as $index => $assistant ) {
				$html .= '<tr><td>' . esc_html__( 'Assistant', 'parklex-core' ) . ' ' . ( $index + 1 ) . '</td><td>' . esc_html( $assistant['name'] . ' ' . $assistant['surname'] . ' (' . $assistant['email'] . ')' ) . '</td></tr>';

				if ( ! empty( $assistant['associate_number'] ) ) {
					$html .= '<tr><td>&nbsp;</td><td>' . esc_html__( 'Associate number', 'parklex-core' ) . ': ' . esc_html( $assistant['associate_number'] ) . '</td></tr>';
				}

				if ( ! empty( $assistant['certificate'] ) ) {
					$html .= '<tr><td>&nbsp;</td><td>' . esc_html__( 'Certificate', 'parklex-core' ) . ': ' . esc_html( $assistant['certificate'] ) . '</td></tr>';
				}

				if ( ! empty( $assistant['comments'] ) ) {
					$html .= '<tr><td>&nbsp;</td><td>' . esc_html( $assistant['comments'] ) . '</td></tr>';
				}
			}
		}

		$rows_after = array(
			__( 'Type of event', 'parklex-core' )                 => get_field( 'type-event', $request_id ),
			__( 'Name of the presentation', 'parklex-core' )      => get_field( 'name-presentation', $request_id ),
			__( 'Cost of the event per person', 'parklex-core' )  => get_field( 'cost-presentation', $request_id ),
			__( 'Related to a project', 'parklex-core' )          => get_field( 'related-project-presentation', $request_id ),
			__( 'Comments', 'parklex-core' )                      => get_field( 'request-comments', $request_id ),
		);

		foreach ( $rows_after as $label => $value ) {
			if ( $value ) {
				$html .= '<tr><td>' . esc_html( $label ) . '</td><td>' . esc_html( $value ) . '</td></tr>';
			}
		}

		$invoices_files = get_field( 'invoices_files', $request_id );

		if ( $invoices_files ) {
			foreach ( $invoices_files as $index => $invoice_file ) {
				if ( empty( $invoice_file['file'] ) ) {
					continue;
				}

				$url   = wp_get_attachment_url( $invoice_file['file'] );
				$html .= '<tr><td>' . esc_html__( 'File', 'parklex-core' ) . ' ' . ( $index + 1 ) . '</td><td><a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( basename( $url ) ) . '</a></td></tr>';
			}
		}

		return $html . '</table>';
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'parklex-core' ),
				'default' => 'yes',
			),
		);

		if ( $this->configurable_recipient ) {
			$this->form_fields['recipient'] = array(
				'title'       => __( 'Recipient(s)', 'woocommerce' ),
				'type'        => 'text',
				/* translators: %s: admin email */
				'description' => sprintf( __( 'Enter recipients (comma separated). Defaults to %s.', 'parklex-core' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
				'default'     => '',
				'placeholder' => '',
			);
		}

		$this->form_fields['subject'] = array(
			'title'       => __( 'Subject', 'woocommerce' ),
			'type'        => 'text',
			/* translators: %s: default subject */
			'description' => sprintf( __( 'Leave blank to use the default subject: <code>%s</code>.', 'parklex-core' ), $this->subject ),
			'default'     => $this->subject,
			'placeholder' => '',
		);

		$this->form_fields['heading'] = array(
			'title'       => __( 'Email heading', 'parklex-core' ),
			'type'        => 'text',
			/* translators: %s: default heading */
			'description' => sprintf( __( 'Leave blank to use the default heading: <code>%s</code>.', 'parklex-core' ), $this->heading ),
			'default'     => $this->heading,
			'placeholder' => '',
		);

		$this->form_fields['email_type'] = array(
			'title'       => __( 'Email type', 'woocommerce' ),
			'type'        => 'select',
			'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
			'default'     => 'html',
			'class'       => 'email_type wc-enhanced-select hidden',
			'options'     => $this->get_email_type_options(),
		);

		$this->form_fields['content_email'] = array(
			'title'       => __( 'Content', 'woocommerce' ),
			'type'        => 'textarea',
			'description' => __( 'This controls the email content.<br>Available placeholders:<br><code>{site_title}</code> <code>{request_id}</code> <code>{user_name}</code> <code>{user_email}</code> <code>{request_name}</code> <code>{admin_link}</code> <code>{invoice_account_link}</code> <code>{request_details}</code>', 'parklex-core' ),
			'default'     => $this->content,
			'placeholder' => '',
		);
	}
}
