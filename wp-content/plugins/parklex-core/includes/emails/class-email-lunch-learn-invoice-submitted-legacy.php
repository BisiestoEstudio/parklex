<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Sent to the admin when a distributor submits (or is reminded about, see
 * Bis_Core_Lunch_Learn_Legacy::remind_pending_invoice_followup()) their invoice files,
 * with the files attached.
 */
class Bis_Core_Email_Lunch_Learn_Invoice_Submitted_Legacy extends Bis_Core_Email_Lunch_Learn_Legacy {

	protected $configurable_recipient = true;

	public function __construct() {
		$this->id          = Bis_Core_Lunch_Learn_Legacy::EMAIL_INVOICE_SUBMITTED;
		$this->title       = __( 'Send invoices — Lunch & Learn request', 'parklex-core' );
		$this->description = __( 'Sent to the admin when a distributor submits their Lunch & Learn invoice files.', 'parklex-core' );
		$this->heading     = __( 'Send invoices — Lunch & Learn request {site_title}', 'parklex-core' );
		$this->subject     = __( 'Send invoices — Lunch & Learn request {site_title}', 'parklex-core' );

		parent::__construct();
	}

	public function trigger( $request_id, $user_id = null ) {
		$this->send_to(
			$this->get_option( 'recipient', get_option( 'admin_email' ) ),
			$request_id,
			$this->invoice_file_attachments( $request_id )
		);
	}
}

return new Bis_Core_Email_Lunch_Learn_Invoice_Submitted_Legacy();
