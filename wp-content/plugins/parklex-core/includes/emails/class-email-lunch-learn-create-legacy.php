<?php
defined( 'ABSPATH' ) || exit;

// ! alert: lo incluído en este archivo corresponde a funcionalidades que se han traspasado tal cual del theme antiguo.
/**
 * Sent to the admin when a distributor submits a new Lunch & Learn request.
 */
class Bis_Core_Email_Lunch_Learn_Create_Legacy extends Bis_Core_Email_Lunch_Learn_Legacy {

	protected $configurable_recipient = true;

	public function __construct() {
		$this->id          = Bis_Core_Lunch_Learn_Legacy::EMAIL_CREATE;
		$this->title       = __( 'Create Lunch & Learn request', 'parklex-core' );
		$this->description = __( 'Sent to the admin when a distributor creates a Lunch & Learn request.', 'parklex-core' );
		$this->heading     = __( 'Create Lunch & Learn request {site_title}', 'parklex-core' );
		$this->subject     = __( 'Create Lunch & Learn request {site_title}', 'parklex-core' );

		parent::__construct();
	}

	public function trigger( $request_id, $user_id ) {
		$this->send_to( $this->get_option( 'recipient', get_option( 'admin_email' ) ), $request_id );
	}
}

return new Bis_Core_Email_Lunch_Learn_Create_Legacy();
