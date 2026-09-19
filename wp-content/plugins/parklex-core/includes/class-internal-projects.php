<?php
defined( 'ABSPATH' ) || exit;

class Bis_Core_Internal_Projects {

	const SUBMIT_PAGE_TEMPLATE = 'page-submit-internal-project.php';

	const IMAGES_IDS_FIELD_KEY     = 'bisiesto_cpt_project_internal_images_ids';
	const FEATURED_IMAGE_FIELD_KEY = 'bisiesto_cpt_project_internal_featured_image';
	const IMAGE_GALLERY_FIELD_KEY  = 'bisiesto_cpt_project_internal_image_gallery';
	const UPLOAD_ACTION            = 'bis_internal_project_upload_image';
	const UPLOAD_NONCE_ACTION      = 'bis_internal_project_upload';

	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'gate_frontend_access' ) );
		add_action( 'acf/save_post', array( __CLASS__, 'sync_post_title_from_project_name' ), 20 );
		add_action( 'acf/save_post', array( __CLASS__, 'sync_gallery_from_images_ids' ), 20 );
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_archive_query' ) );
		add_action( 'admin_menu', array( __CLASS__, 'render_admin_pending_bubble' ) );
		add_action( 'acf/render_field/type=message', array( __CLASS__, 'render_gallery_uploader_field' ) );
		add_action( 'wp_ajax_' . self::UPLOAD_ACTION, array( __CLASS__, 'handle_gallery_image_upload' ) );
		add_action( 'admin_head-post.php', array( __CLASS__, 'hide_gallery_uploader_field_in_admin' ) );
		add_action( 'admin_head-post-new.php', array( __CLASS__, 'hide_gallery_uploader_field_in_admin' ) );

		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_my_account_menu_item' ) );
			add_filter( 'woocommerce_get_endpoint_url', array( __CLASS__, 'redirect_my_account_menu_item_url' ), 10, 2 );
		}
	}

	/**
	 * Add "Internal Projects" to the My Account menu, right before "Log out" — only for
	 * users with the "allow_internal_projects" permission (same gate as the front-end).
	 */
	public static function add_my_account_menu_item( $items ) {
		if ( ! get_field( 'allow_internal_projects', 'user_' . get_current_user_id() ) ) {
			return $items;
		}

		if ( ! isset( $items['customer-logout'] ) ) {
			$items['internal-projects'] = __( 'Internal Projects', 'parklex-core' );
			return $items;
		}

		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		$items['internal-projects'] = __( 'Internal Projects', 'parklex-core' );
		$items['customer-logout']   = $logout;

		return $items;
	}

	/**
	 * "Internal Projects" isn't a real My Account endpoint/tab — it's a plain link to the
	 * CPT archive, same as the original theme (which achieved this by overriding the whole
	 * myaccount/navigation.php template; this filter is the same result without that).
	 */
	public static function redirect_my_account_menu_item_url( $url, $endpoint ) {
		if ( 'internal-projects' === $endpoint ) {
			return get_post_type_archive_link( 'project_internal' );
		}

		return $url;
	}

	/**
	 * The drag&drop uploader (rendered via render_gallery_uploader_field()) only makes
	 * sense on the front-end submission form; in wp-admin it's noise next to the real
	 * "Image gallery" repeater, which already shows the synced thumbnails. Same approach
	 * as the original theme (CSS injected in admin_head).
	 */
	public static function hide_gallery_uploader_field_in_admin() {
		if ( 'project_internal' !== get_current_screen()->post_type ) {
			return;
		}

		echo '<style>#bis-internal-project-custom-gallery,.bis-internal-project-images-ids{display:none;}</style>';
	}

	/**
	 * Render the drag&drop gallery uploader UI in place of the "custom_gallery" message
	 * field. JS (assets/js/internal-project-form.js) drives the uploads and writes the
	 * resulting attachment IDs into the hidden "images_ids" field.
	 */
	public static function render_gallery_uploader_field( $field ) {
		if ( empty( $field['wrapper']['id'] ) || 'bis-internal-project-custom-gallery' !== $field['wrapper']['id'] ) {
			return;
		}

		$max_images = (int) get_field( 'max_upload_count_images', 'option' ) ?: 20;
		?>
		<div class="c-internal-project-form__upload-zone" data-role="featured-drop">
			<img class="c-internal-project-form__preview-img" src="" alt="" hidden>
			<label class="c-internal-project-form__upload-label">
				<input type="file" class="c-internal-project-form__file" data-role="featured-input" accept="image/*" multiple>
				<span><?php esc_html_e( 'Upload photos with drag-and-drop', 'parklex-core' ); ?></span>
				<span class="btn"><?php esc_html_e( 'Select local files', 'parklex-core' ); ?></span>
			</label>
		</div>

		<div class="c-internal-project-form__grid" data-role="gallery-grid">
			<?php for ( $i = 0; $i < $max_images; $i++ ) : ?>
				<div class="c-internal-project-form__item" data-role="gallery-item">
					<div class="c-internal-project-form__item-inner">
						<img class="c-internal-project-form__item-img" src="" alt="" hidden>
						<input type="file" class="c-internal-project-form__file" data-role="gallery-input" accept="image/*" multiple>
					</div>
				</div>
			<?php endfor; ?>
		</div>
		<?php
	}

	/**
	 * AJAX endpoint the front-end uploader posts each selected file to. Logged-in only
	 * (the front-end form itself is already gated by gate_frontend_access()).
	 */
	public static function handle_gallery_image_upload() {
		check_ajax_referer( self::UPLOAD_NONCE_ACTION, 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		add_filter( 'upload_mimes', array( __CLASS__, 'restrict_upload_mimes_to_images' ) );
		$attachment_id = media_handle_upload( 'file', 0 );
		remove_filter( 'upload_mimes', array( __CLASS__, 'restrict_upload_mimes_to_images' ) );

		if ( is_wp_error( $attachment_id ) ) {
			wp_send_json_error( array( 'message' => $attachment_id->get_error_message() ) );
		}

		wp_send_json_success( array(
			'id'       => $attachment_id,
			'url'      => wp_get_attachment_image_url( $attachment_id, 'thumbnail' ),
			'url_full' => wp_get_attachment_url( $attachment_id ),
		) );
	}

	public static function restrict_upload_mimes_to_images( $mimes ) {
		return array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
		);
	}

	/**
	 * Split the comma-separated "images_ids" (built client-side by the uploader) into
	 * "featured_image" (first ID) and the "image_gallery" repeater (the rest) — same
	 * logic as the original theme's post-save hook.
	 */
	public static function sync_gallery_from_images_ids( $post_id ) {
		if ( is_admin() || wp_is_post_revision( $post_id ) || 'project_internal' !== get_post_type( $post_id ) ) {
			return;
		}

		$images_ids_raw = get_field( self::IMAGES_IDS_FIELD_KEY, $post_id );

		if ( empty( $images_ids_raw ) || 'none' === $images_ids_raw ) {
			return;
		}

		$image_ids = array_filter( explode( ',', $images_ids_raw ) );

		if ( empty( $image_ids ) ) {
			return;
		}

		$featured_id = array_shift( $image_ids );
		update_field( self::FEATURED_IMAGE_FIELD_KEY, $featured_id, $post_id );

		$gallery_rows = array_map( function ( $image_id ) {
			return array( 'image' => $image_id );
		}, $image_ids );

		update_field( self::IMAGE_GALLERY_FIELD_KEY, $gallery_rows, $post_id );
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
