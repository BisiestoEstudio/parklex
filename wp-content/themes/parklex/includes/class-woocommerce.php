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
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
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
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		// Cards are not linked to the single product page: swap the <a> wrapper for a <div>.
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		add_action( 'woocommerce_before_main_content', array( __CLASS__, 'open_wrapper' ), 10 );
		add_action( 'woocommerce_after_main_content', array( __CLASS__, 'close_wrapper' ), 10 );
		add_action( 'woocommerce_after_shop_loop', array( __CLASS__, 'paint_pagination' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'paint_card_form' ), 10 );
		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'open_card_media' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'close_card_media' ), 5 );
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

	public static function enqueue_scripts() {
		if ( ! self::is_shop_archive() ) {
			return;
		}

		wp_enqueue_script( 'bis-theme-shop-card', BIS_THEME_URI . '/assets/js/shop-card.js', array(), BIS_THEME_VERSION, true );
		wp_localize_script( 'bis-theme-shop-card', 'bisShopCard', array(
			'ajaxUrl' => WC_AJAX::get_endpoint( 'add_to_cart' ),
			'i18n'    => array(
				'adding' => __( 'Adding…', 'parklex' ),
				'added'  => __( 'Added', 'parklex' ),
				'add'    => __( 'Add to bag', 'parklex' ),
			),
		) );
	}

	/**
	 * Add-to-cart form inside each card: size chips (variable products), quantity and
	 * button. Posts to the product URL as a no-JS fallback; shop-card.js sends it via
	 * WooCommerce's add_to_cart AJAX endpoint.
	 * Other product types, or unavailable products, keep WooCommerce's default button.
	 */
	public static function paint_card_form() {
		global $product;

		if ( ! $product || ! $product->is_in_stock() || ! $product->is_purchasable() || ! $product->is_type( array( 'simple', 'variable' ) ) ) {
			woocommerce_template_loop_add_to_cart();
			return;
		}

		$is_variable = $product->is_type( 'variable' );
		$variations  = array();

		if ( $is_variable ) {
			foreach ( $product->get_available_variations() as $variation ) {
				if ( empty( $variation['is_purchasable'] ) || empty( $variation['is_in_stock'] ) ) {
					continue;
				}
				$variations[] = array(
					'id'         => $variation['variation_id'],
					'attributes' => $variation['attributes'],
					'price_html' => $variation['price_html'],
					'max_qty'    => $variation['max_qty'],
				);
			}

			if ( empty( $variations ) ) {
				woocommerce_template_loop_add_to_cart();
				return;
			}
		}
		?>
		<form class="c-shop__cart" method="post" action="<?php echo esc_url( get_permalink() ); ?>"<?php echo $is_variable ? ' data-variations="' . esc_attr( wp_json_encode( $variations ) ) . '"' : ''; ?>>
			<?php if ( $is_variable ) : ?>
				<?php foreach ( $product->get_variation_attributes() as $attribute_name => $options ) : ?>
					<?php
					$field_name = 'attribute_' . sanitize_title( $attribute_name );
					$available  = array_column( array_column( $variations, 'attributes' ), $field_name );
					?>
					<fieldset class="c-shop__chips">
						<legend class="screen-reader-text"><?php echo esc_html( wc_attribute_label( $attribute_name, $product ) ); ?></legend>
						<?php foreach ( $options as $option ) : ?>
							<?php
							$term     = taxonomy_exists( $attribute_name ) ? get_term_by( 'slug', $option, $attribute_name ) : false;
							$label    = $term ? $term->name : $option;
							$in_stock = in_array( '', $available, true ) || in_array( $option, $available, true );
							?>
							<label class="c-shop__chip<?php echo $in_stock ? '' : ' is-disabled'; ?>">
								<input class="c-shop__chip-input" type="radio" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $option ); ?>"<?php echo $in_stock ? '' : ' disabled'; ?>>
								<span class="c-shop__chip-label"><?php echo esc_html( $label ); ?></span>
							</label>
						<?php endforeach; ?>
					</fieldset>
				<?php endforeach; ?>
				<input type="hidden" name="variation_id" value="0">
			<?php endif; ?>

			<div class="c-shop__cart-row">
				<?php $max_qty = $product->is_sold_individually() ? 1 : $product->get_max_purchase_quantity(); ?>
				<div class="c-shop__qty">
					<button type="button" class="c-shop__qty-btn" data-step="-1" aria-label="<?php esc_attr_e( 'Menos', 'parklex' ); ?>">&minus;</button>
					<input class="c-shop__qty-input" type="number" name="quantity" value="1" min="1"<?php echo $max_qty > 0 ? ' max="' . esc_attr( $max_qty ) . '"' : ''; ?> step="1" inputmode="numeric" aria-label="<?php esc_attr_e( 'Cantidad', 'parklex' ); ?>">
					<button type="button" class="c-shop__qty-btn" data-step="1" aria-label="<?php esc_attr_e( 'Más', 'parklex' ); ?>">+</button>
				</div>
				<button type="submit" class="button c-shop__add"<?php echo $is_variable ? ' disabled' : ''; ?>><?php esc_html_e( 'Add to bag', 'parklex' ); ?></button>
			</div>
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
			<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
		</form>
		<?php
	}

	public static function open_card_media() {
		echo '<div class="c-shop__product">';
	}

	public static function close_card_media() {
		echo '</div>';
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
