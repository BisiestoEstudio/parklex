<?php
defined( 'ABSPATH' ) || exit;

/**
 * Layout for the WooCommerce shop archive (/shop/ and product_cat archives).
 *
 * Replaces WooCommerce's default content wrapper and sidebar with the theme's own
 * markup, shows category filters and paginates the grid.
 */
class Bis_Theme_Woocommerce {

	const PER_PAGE = 16;

	public static function init() {
		add_action( 'wp', array( __CLASS__, 'setup_layout' ) );
		add_filter( 'loop_shop_per_page', array( __CLASS__, 'per_page' ), 20 );
		add_filter( 'loop_shop_columns', array( __CLASS__, 'columns' ), 20 );
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		add_filter( 'single_product_archive_thumbnail_size', array( __CLASS__, 'thumbnail_size' ) );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
	}

	public static function is_shop_archive() {
		return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
	}

	public static function setup_layout() {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		// No sidebar anywhere in WooCommerce.
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		if ( ! self::is_shop_archive() ) {
			return;
		}

		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

		add_action( 'woocommerce_before_main_content', array( __CLASS__, 'open_wrapper' ), 10 );
		add_action( 'woocommerce_after_main_content', array( __CLASS__, 'close_wrapper' ), 10 );
		add_action( 'woocommerce_after_shop_loop', array( __CLASS__, 'paint_pagination' ), 10 );
	}

	public static function per_page() {
		return self::PER_PAGE;
	}

	public static function columns() {
		return 4;
	}

	public static function thumbnail_size() {
		return 'medium_large';
	}

	public static function body_class( $classes ) {
		if ( self::is_shop_archive() ) {
			$classes[] = 'is-shop-archive';
		}

		return $classes;
	}

	public static function open_wrapper() {
		$title = woocommerce_page_title( false );
		?>
		<main id="main" class="c-shop">
			<div class="c-shop__content is-layout-constrained has-global-padding">
				<div class="c-shop__inner alignwide">
					<header class="c-shop__header">
						<h1 class="c-shop__title has-display-l-font-size"><?php echo esc_html( $title ); ?></h1>
						<?php if ( is_product_taxonomy() ) : ?>
							<?php echo wp_kses_post( wc_format_content( term_description() ) ); ?>
						<?php endif; ?>
					</header>
					<?php self::paint_filters(); ?>
		<?php
	}

	public static function close_wrapper() {
		?>
				</div>
			</div>
		</main>
		<?php
	}

	/**
	 * Top-level categories as pills; when inside a category with children, a second
	 * row with its subcategories.
	 */
	public static function paint_filters() {
		$default_cat = (int) get_option( 'default_product_cat' );
		$top_terms   = get_terms( array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => true,
			'exclude'    => $default_cat ? array( $default_cat ) : array(),
		) );

		if ( empty( $top_terms ) || is_wp_error( $top_terms ) ) {
			return;
		}

		$current     = is_product_category() ? get_queried_object() : null;
		$current_top = 0;
		$children    = array();

		if ( $current ) {
			$ancestors   = get_ancestors( $current->term_id, 'product_cat', 'taxonomy' );
			$current_top = $ancestors ? (int) end( $ancestors ) : (int) $current->term_id;
			$children    = get_terms( array(
				'taxonomy'   => 'product_cat',
				'parent'     => $current_top,
				'hide_empty' => true,
			) );
		}

		$items = array(
			array(
				'name'   => __( 'Todos', 'parklex' ),
				'url'    => wc_get_page_permalink( 'shop' ),
				'active' => ! $current,
			),
		);
		foreach ( $top_terms as $term ) {
			$items[] = array(
				'name'   => $term->name,
				'url'    => get_term_link( $term ),
				'active' => (int) $term->term_id === $current_top,
			);
		}

		self::paint_pills( $items, __( 'Categorías', 'parklex' ) );

		if ( ! empty( $children ) && ! is_wp_error( $children ) ) {
			$sub_items = array();
			foreach ( $children as $term ) {
				$sub_items[] = array(
					'name'   => $term->name,
					'url'    => get_term_link( $term ),
					'active' => $current && (int) $term->term_id === (int) $current->term_id,
				);
			}
			self::paint_pills( $sub_items, __( 'Subcategorías', 'parklex' ), true );
		}
	}

	private static function paint_pills( $items, $label, $is_sub = false ) {
		?>
		<nav class="c-shop__filters<?php echo $is_sub ? ' c-shop__filters--sub' : ''; ?>" aria-label="<?php echo esc_attr( $label ); ?>">
			<ul class="c-shop__filters-list">
				<?php foreach ( $items as $item ) : ?>
					<?php if ( is_wp_error( $item['url'] ) ) { continue; } ?>
					<li class="c-shop__filter<?php echo $item['active'] ? ' is-active' : ''; ?>">
						<a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $item['active'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $item['name'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php
	}

	public static function paint_pagination() {
		if ( function_exists( 'bis_paint_pagination' ) ) {
			echo '<div class="c-shop__pagination">';
			bis_paint_pagination();
			echo '</div>';
		}
	}
}

Bis_Theme_Woocommerce::init();
