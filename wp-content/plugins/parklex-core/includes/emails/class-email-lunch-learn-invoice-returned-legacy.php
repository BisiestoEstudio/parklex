<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Sent to the distributor when an admin unchecks "Distributor approved invoices" on an
 * already-approved request, returning the invoice for editing (with the files attached).
 */
class Bis_Core_Email_Lunch_Learn_Invoice_Returned_Legacy extends Bis_Core_Email_Lunch_Learn_Legacy {

	public function __construct() {
		$this->id             = Bis_Core_Lunch_Learn_Legacy::EMAIL_INVOICE_RETURNED;
		$this->title          = __( 'Return invoices — Lunch & Learn request', 'parklex-core' );
		$this->customer_email = true;
		$this->description    = __( 'Sent to the distributor when an admin returns their Lunch & Learn invoice for editing.', 'parklex-core' );
		$this->heading        = __( 'Return invoices — Lunch & Learn request {site_title}', 'parklex-core' );
		$this->subject        = __( 'Return invoices — Lunch & Learn request {site_title}', 'parklex-core' );

		parent::__construct();
	}

	public function trigger( $request_id, $user_id ) {
		$user_data = get_userdata( $user_id );
		$this->send_to(
			$user_data ? $user_data->user_email : '',
			$request_id,
			$this->invoice_file_attachments( $request_id )
		);
	}
}

return new Bis_Core_Email_Lunch_Learn_Invoice_Returned_Legacy();
