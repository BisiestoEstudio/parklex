<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_Internal_Projects {

	const SUBMIT_PAGE_TEMPLATE = 'page-submit-internal-project.php';

	public static function init() {
		add_action( 'init', array( __CLASS__, 'ensure_distributor_can_upload' ) );
		add_action( 'template_redirect', array( __CLASS__, 'gate_frontend_access' ) );
		add_action( 'acf/save_post', array( __CLASS__, 'sync_post_title_from_project_name' ), 20 );
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_archive_query' ) );
		add_action( 'admin_menu', array( __CLASS__, 'render_admin_pending_bubble' ) );
	}

	/**
	 * The "distributor" role (most users with allow_internal_projects=1) only has the
	 * "read" capability by default, which blocks the WP media modal the front-end
	 * submission form's gallery field needs. Grant "upload_files" once, idempotently.
	 */
	public static function ensure_distributor_can_upload() {
		$role = get_role( 'distributor' );

		if ( $role && ! $role->has_cap( 'upload_files' ) ) {
			$role->add_cap( 'upload_files' );
		}
	}

	/**
	 * The CPT only supports a native "title", but the front-end submission form doesn't
	 * expose it directly — copy the ACF "project_name" field into post_title instead.
	 * Frontend only: admins editing the title directly in wp-admin shouldn't be overridden.
	 */
	public static function sync_post_title_from_project_name( $post_id ) {
		if ( is_admin() || wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( 'project_internal' !== get_post_type( $post_id ) ) {
			return;
		}

		$project_name = get_field( 'project_name', $post_id );

		if ( $project_name ) {
			wp_update_post( array(
				'ID'         => $post_id,
				'post_title' => $project_name,
			) );
		}
	}

	/**
	 * Block access to the Internal Projects archive, single posts, and the submission
	 * page for logged-out users and for users without the "allow_internal_projects"
	 * permission, redirecting them to My Account instead.
	 */
	public static function gate_frontend_access() {
		$is_internal_projects_page = is_singular( 'project_internal' )
			|| is_post_type_archive( 'project_internal' )
			|| is_page_template( self::SUBMIT_PAGE_TEMPLATE );

		if ( ! $is_internal_projects_page ) {
			return;
		}

		if ( ! is_user_logged_in() || ! get_field( 'allow_internal_projects', 'user_' . get_current_user_id() ) ) {
			nocache_headers();
			wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
			exit;
		}
	}

	/**
	 * Add the meta/tax filters (from $_GET) used by the Internal Projects archive.
	 * Fixes a bug in the original: the "product_name_internal" filter was rendered
	 * in the form but never actually applied to the query.
	 */
	public static function filter_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_archive() || 'project_internal' !== $query->get( 'post_type' ) ) {
			return;
		}

		$query->set( 'posts_per_page', -1 );

		$meta_query = $query->get( 'meta_query' ) ?: array();

		foreach ( array( 'year' => 'years', 'architect' => 'architect', 'studio' => 'studio', 'city' => 'city' ) as $meta_key => $get_key ) {
			if ( ! empty( $_GET[ $get_key ] ) ) {
				$meta_query[] = array(
					'key'     => $meta_key,
					'value'   => sanitize_text_field( wp_unslash( $_GET[ $get_key ] ) ),
					'compare' => '=',
				);
			}
		}

		if ( ! empty( $meta_query ) ) {
			$query->set( 'meta_query', $meta_query );
		}

		$tax_query = $query->get( 'tax_query' ) ?: array();

		$taxonomies = array(
			'country_internal',
			'product_type_internal',
			'product_name_internal',
			'application_internal',
			'work_type_internal',
			'building_type_internal',
			'installation_internal',
			'surface_internal',
			'sustainability_internal',
			'product_internal',
		);

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! empty( $_GET[ $taxonomy ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => array( sanitize_title( wp_unslash( $_GET[ $taxonomy ] ) ) ),
				);
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query->set( 'tax_query', $tax_query );
		}

		if ( ! empty( $_GET['search'] ) ) {
			$query->set( 's', sanitize_text_field( wp_unslash( $_GET['search'] ) ) );
		}
	}

	/**
	 * Distinct, non-empty values for a plain (non-taxonomy) meta key across published
	 * Internal Projects — used to build the archive's year/architect/studio/city filters.
	 */
	public static function get_distinct_meta_values( $meta_key ) {
		global $wpdb;

		$values = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = %s
				AND p.post_status = 'publish'
				AND p.post_type = 'project_internal'
				ORDER BY pm.meta_value",
				$meta_key
			)
		);

		return array_filter( $values );
	}

	/**
	 * Add a pending-count badge to the CPT's admin menu item, same as the original theme.
	 */
	public static function render_admin_pending_bubble() {
		global $menu;

		$pending_count = wp_count_posts( 'project_internal' )->pending;

		if ( ! $pending_count ) {
			return;
		}

		foreach ( $menu as $key => $item ) {
			if ( 'edit.php?post_type=project_internal' === $item[2] ) {
				$menu[ $key ][0] = __( 'Internal Proj.', 'parklex-core' ) . ' <span class="update-plugins count-' . (int) $pending_count . '"><span class="plugin-count">' . (int) $pending_count . '</span></span>';
				break;
			}
		}
	}
}
