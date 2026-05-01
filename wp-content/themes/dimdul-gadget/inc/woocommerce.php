<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Dimdul_Gadget
 */
add_action( 'after_setup_theme', 'setup_woocommerce_support' );

function setup_woocommerce_support()
{
    add_theme_support('woocommerce');
}
// Remove all WooCommerce default CSS
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function dimdul_gadget_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 150,
			'single_image_width'    => 300,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'dimdul_gadget_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function dimdul_gadget_woocommerce_scripts() {
	wp_enqueue_style( 'dimdul-gadget-woocommerce-style', get_template_directory_uri() . '/woocommerce.css', array(), _S_VERSION );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'dimdul-gadget-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'dimdul_gadget_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function dimdul_gadget_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'dimdul_gadget_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function dimdul_gadget_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
//add_filter( 'woocommerce_output_related_products_args', 'dimdul_gadget_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'dimdul_gadget_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function dimdul_gadget_woocommerce_wrapper_before() {
		?>
			<main id="primary" class="site-main py-10">
                <div class="container">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'dimdul_gadget_woocommerce_wrapper_before' );

if ( ! function_exists( 'dimdul_gadget_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function dimdul_gadget_woocommerce_wrapper_after() {
		?>
                </div>
			</main><!-- #main -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'dimdul_gadget_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'dimdul_gadget_woocommerce_header_cart' ) ) {
			dimdul_gadget_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'dimdul_gadget_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function dimdul_gadget_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		dimdul_gadget_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'dimdul_gadget_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'dimdul_gadget_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function dimdul_gadget_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'dimdul-gadget' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'dimdul-gadget' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'dimdul_gadget_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function dimdul_gadget_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php dimdul_gadget_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

add_filter( 'woocommerce_product_loop_start', function( $html ) {
    // This replaces the default <ul> or <div> completely
    return '<div class="products-grid no-scroll">';
});

add_filter( 'woocommerce_product_loop_end', function( $html ) {
    return '</div>';
});


/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
    ob_start();

    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>

    <a class="cart-customlocation" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'woocommerce' ); ?>">
        <div class="flex gap-2 items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 014 0z"></path>
            </svg>

            <div class="flex flex-col gap-[2px]">
                <span class="text-xs mt-1 font-semibold">
                    <?php
                    echo sprintf(
                            _n(
                                    '%d item',
                                    '%d items',
                                    WC()->cart->get_cart_contents_count(),
                                    'woocommerce'
                            ),
                            WC()->cart->get_cart_contents_count()
                    );
                    ?>
                </span>

                <span class="text-xs">
                    <?php echo WC()->cart ? WC()->cart->get_cart_total() : wc_price( 0 ); ?>
                </span>
            </div>
        </div>
    </a>

    <?php

    $fragments['a.cart-customlocation'] = ob_get_clean();

    return $fragments;
}
add_action( 'wp_footer', 'custom_refresh_header_cart_on_cart_update' );

function custom_refresh_header_cart_on_cart_update() {
    if ( ! is_cart() && ! is_checkout() ) {
        return;
    }
    ?>
    <script>
        jQuery(function($) {
            $(document.body).on(
                'updated_wc_div updated_cart_totals removed_from_cart wc_cart_emptied',
                function() {
                    $(document.body).trigger('wc_fragment_refresh');
                }
            );
        });
    </script>
    <?php
}