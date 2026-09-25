<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Sent to the distributor N days after an approved event (see
 * Bis_Core_Lunch_Learn_Legacy::remind_pending_invoice_after_event()), reminding them to
 * submit their invoice.
 */
class Bis_Core_Email_Lunch_Learn_Invoice_Reminder_Legacy extends Bis_Core_Email_Lunch_Learn_Legacy {

	public function __construct() {
		$this->id             = Bis_Core_Lunch_Learn_Legacy::EMAIL_INVOICE_REMINDER;
		$this->title          = __( 'Remember to upload invoice — Lunch & Learn request', 'parklex-core' );
		$this->customer_email = true;
		$this->description    = __( 'Sent to the distributor as a reminder to upload their Lunch & Learn invoice.', 'parklex-core' );
		$this->heading        = __( 'Remember to upload invoice — Lunch & Learn request {site_title}', 'parklex-core' );
		$this->subject        = __( 'Remember to upload invoice — Lunch & Learn request {site_title}', 'parklex-core' );

		parent::__construct();
	}

	public function trigger( $request_id, $user_id = null ) {
		$user_id   = $user_id ?: get_field( 'user_id', $request_id );
		$user_data = $user_id ? get_userdata( $user_id ) : false;
		$this->send_to( $user_data ? $user_data->user_email : '', $request_id );
	}
}

return new Bis_Core_Email_Lunch_Learn_Invoice_Reminder_Legacy();
