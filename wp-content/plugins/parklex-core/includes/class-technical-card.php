<?php
defined( 'ABSPATH' ) || exit;

/**
 * Access gate for the Technical Card archive: logged-out and unregistered visitors
 * are shown the registration form (see fields/options-technical-card.php) instead of
 * the archive content, until the technical_area_registered_user cookie is set to "true".
 * Administrators always have access, regardless of the cookie.
 */
class Bis_Core_Technical_Card {

	const REGISTERED_COOKIE = 'technical_area_registered_user';

	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'gate_archive_access' ) );
	}

	/**
	 * Swaps the archive content for the registration gate template when the visitor
	 * doesn't have the "registered" cookie set to "true" — same URL, no redirect, so
	 * the archive works normally as soon as they register. Administrators skip the
	 * gate entirely.
	 */
	public static function gate_archive_access() {
		if ( ! self::is_gate_active() ) {
			return;
		}

		get_header();
		get_template_part( 'templates/technical-access-gate' );
		get_footer();
		exit;
	}

	/**
	 * Whether the current request is the Technical Card archive and the gate must be
	 * shown — used both by gate_archive_access() and by Bis_Theme_Assets to enqueue
	 * the gate's script only when it's actually rendered.
	 */
	public static function is_gate_active() {
		return is_post_type_archive( 'technical-card' ) && ! self::is_registered_user() && ! current_user_can( 'manage_options' );
	}

	public static function is_registered_user() {
		return isset( $_COOKIE[ self::REGISTERED_COOKIE ] ) && 'true' === $_COOKIE[ self::REGISTERED_COOKIE ];
	}
}
