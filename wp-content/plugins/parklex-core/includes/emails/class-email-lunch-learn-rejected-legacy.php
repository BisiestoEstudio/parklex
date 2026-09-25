<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Sent to the distributor when their Lunch & Learn request is rejected (post moved to draft).
 */
class Bis_Core_Email_Lunch_Learn_Rejected_Legacy extends Bis_Core_Email_Lunch_Learn_Legacy {

	public function __construct() {
		$this->id             = Bis_Core_Lunch_Learn_Legacy::EMAIL_REJECTED;
		$this->title          = __( 'Denied Lunch & Learn request', 'parklex-core' );
		$this->customer_email = true;
		$this->description    = __( 'Sent to the distributor when their Lunch & Learn request is denied.', 'parklex-core' );
		$this->heading        = __( 'Denied Lunch & Learn request {site_title}', 'parklex-core' );
		$this->subject        = __( 'Denied Lunch & Learn request {site_title}', 'parklex-core' );

		parent::__construct();
	}

	public function trigger( $request_id, $user_id ) {
		$user_data = get_userdata( $user_id );
		$this->send_to( $user_data ? $user_data->user_email : '', $request_id );
	}
}

return new Bis_Core_Email_Lunch_Learn_Rejected_Legacy();
