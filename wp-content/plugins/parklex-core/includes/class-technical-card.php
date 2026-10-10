<?php
defined( 'ABSPATH' ) || exit;

/**
 * Access gate for the Technical Card archive: logged-out and unregistered visitors
 * are shown the registration form (see fields/options-technical-card.php) instead of
 * the archive content, until the technical_area_registered_user cookie is set to "true".
 * Administrators always have access, regardless of the cookie.
 *
 * Also disables the single pages (404) and keeps the post type and its taxonomies out
 * of the Yoast sitemap.
 */
class Bis_Core_Technical_Card {

	const REGISTERED_COOKIE  = 'technical_area_registered_user';
	const LOGIN_ACTION       = 'bis_technical_area_login';
	const LOGIN_NONCE_ACTION = 'bis_technical_area_login';

	const TAXONOMIES = array( 'category_technical_card', 'classification_technical_card' );

	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'disable_single' ), 1 );
		add_action( 'template_redirect', array( __CLASS__, 'gate_archive_access' ) );
		add_action( 'wp_ajax_' . self::LOGIN_ACTION, array( __CLASS__, 'handle_login' ) );
		add_action( 'wp_ajax_nopriv_' . self::LOGIN_ACTION, array( __CLASS__, 'handle_login' ) );
		add_filter( 'wpseo_sitemap_exclude_post_type', array( __CLASS__, 'exclude_post_type_from_sitemap' ), 10, 2 );
		add_filter( 'wpseo_sitemap_exclude_taxonomy', array( __CLASS__, 'exclude_taxonomy_from_sitemap' ), 10, 2 );
	}

	/**
	 * Technical cards only exist as items listed in the archive (their files are downloaded
	 * directly) — their single pages must not be reachable, so they always return a 404.
	 * The post type stays public/publicly_queryable because the archive needs it.
	 */
	public static function disable_single() {
		if ( ! is_singular( 'technical-card' ) ) {
			return;
		}

		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}

	/**
	 * Removes the technical-card post type sitemap from Yoast (single pages are disabled
	 * and the archive sits behind the registration gate).
	 */
	public static function exclude_post_type_from_sitemap( $excluded, $post_type ) {
		return 'technical-card' === $post_type ? true : $excluded;
	}

	/**
	 * Removes the technical-card taxonomies' sitemaps from Yoast — they have no term
	 * archives, they're only used as filters inside the technical-card archive.
	 */
	public static function exclude_taxonomy_from_sitemap( $excluded, $taxonomy ) {
		return in_array( $taxonomy, self::TAXONOMIES, true ) ? true : $excluded;
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

	/**
	 * Handles the email-only login form (templates/technical-login-form.php), the
	 * alternative to the HubSpot registration form on the same gate. Looks the email
	 * up in HubSpot and only marks the visitor as registered if a matching contact
	 * exists there.
	 */
	public static function handle_login() {
		check_ajax_referer( self::LOGIN_NONCE_ACTION, 'nonce' );

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Introduce un email válido.', 'parklex-core' ) ) );
		}

		$is_registered = self::is_email_registered_in_hubspot( $email );

		if ( is_wp_error( $is_registered ) ) {
			// Logs the real reason (missing/invalid API key, network failure, unexpected
			// HubSpot response...) server-side, but the visitor only ever sees the
			// generic message below.
			error_log( 'Bis_Core_Technical_Card::handle_login HubSpot lookup failed: ' . $is_registered->get_error_message() ); // phpcs:ignore -- intentional error logging, no sensitive data exposed to the visitor.
			wp_send_json_error( array( 'message' => __( 'Ha ocurrido un error al comprobar el registro. Inténtalo de nuevo.', 'parklex-core' ) ) );
		}

		if ( ! $is_registered ) {
			wp_send_json_error( array( 'message' => __( 'Este email no está registrado. Regístrate para acceder a la zona técnica.', 'parklex-core' ) ) );
		}

		self::set_registered_cookie();

		wp_send_json_success();
	}

	/**
	 * Looks up a contact by email in HubSpot (CRM API: GET /crm/v3/objects/contacts/{email}
	 * ?idProperty=email — 200 if the contact exists, 404 if it doesn't). Returns true/false,
	 * or a WP_Error if the API key isn't configured or the request itself fails.
	 */
	public static function is_email_registered_in_hubspot( $email ) {
		$api_key = get_field( 'hubspot_api_key', 'option' );

		if ( ! $api_key ) {
			return new WP_Error( 'bis_technical_card_missing_api_key', __( 'No se ha configurado la API Key de HubSpot.', 'parklex-core' ) );
		}

		$response = wp_remote_get(
			'https://api.hubapi.com/crm/v3/objects/contacts/' . rawurlencode( $email ) . '?idProperty=email',
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $api_key,
				),
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $status_code ) {
			return true;
		}

		if ( in_array( $status_code, array( 400, 404 ), true ) ) {
			return false;
		}

		return new WP_Error(
			'bis_technical_card_hubspot_error',
			sprintf(
				/* translators: %1$d: HTTP status code, %2$s: response body */
				__( 'Respuesta inesperada de la API de HubSpot (código %1$d): %2$s', 'parklex-core' ),
				$status_code,
				wp_remote_retrieve_body( $response )
			),
			array( 'status' => $status_code )
		);
	}

	public static function set_registered_cookie() {
		setcookie( self::REGISTERED_COOKIE, 'true', time() + YEAR_IN_SECONDS, '/' );
	}
}
