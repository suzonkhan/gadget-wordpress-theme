<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Dimdul_Gadget
 */
add_action('after_setup_theme', 'setup_woocommerce_support');

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
function dimdul_gadget_woocommerce_setup()
{
    add_theme_support(
            'woocommerce',
            array(
                    'thumbnail_image_width' => 150,
                    'single_image_width' => 300,
                    'product_grid' => array(
                            'default_rows' => 3,
                            'min_rows' => 1,
                            'default_columns' => 4,
                            'min_columns' => 1,
                            'max_columns' => 6,
                    ),
            )
    );
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

add_action('after_setup_theme', 'dimdul_gadget_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function dimdul_gadget_woocommerce_scripts()
{
    wp_enqueue_style('dimdul-gadget-woocommerce-style', get_template_directory_uri() . '/woocommerce.css', array(), _S_VERSION);

    $font_path = WC()->plugin_url() . '/assets/fonts/';
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

    wp_add_inline_style('dimdul-gadget-woocommerce-style', $inline_font);
}

add_action('wp_enqueue_scripts', 'dimdul_gadget_woocommerce_scripts');

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
 * @param array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function dimdul_gadget_woocommerce_active_body_class($classes)
{
    $classes[] = 'woocommerce-active';

    return $classes;
}

add_filter('body_class', 'dimdul_gadget_woocommerce_active_body_class');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function dimdul_gadget_woocommerce_related_products_args($args)
{
    $defaults = array(
            'posts_per_page' => 3,
            'columns' => 3,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}

//add_filter( 'woocommerce_output_related_products_args', 'dimdul_gadget_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!function_exists('dimdul_gadget_woocommerce_wrapper_before')) {
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
            add_action('woocommerce_before_main_content', 'dimdul_gadget_woocommerce_wrapper_before');

            if (!function_exists('dimdul_gadget_woocommerce_wrapper_after')) {
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
add_action('woocommerce_after_main_content', 'dimdul_gadget_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'dimdul_gadget_woocommerce_header_cart' ) ) {
 * dimdul_gadget_woocommerce_header_cart();
 * }
 * ?>
 */

if (!function_exists('dimdul_gadget_woocommerce_cart_link_fragment')) {
    /**
     * Cart Fragments.
     *
     * Ensure cart contents update when products are added to the cart via AJAX.
     *
     * @param array $fragments Fragments to refresh via AJAX.
     * @return array Fragments to refresh via AJAX.
     */
    function dimdul_gadget_woocommerce_cart_link_fragment($fragments)
    {
        ob_start();
        dimdul_gadget_woocommerce_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'dimdul_gadget_woocommerce_cart_link_fragment');

if (!function_exists('dimdul_gadget_woocommerce_cart_link')) {
    /**
     * Cart Link.
     *
     * Displayed a link to the cart including the number of items present and the cart total.
     *
     * @return void
     */
    function dimdul_gadget_woocommerce_cart_link()
    {
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'dimdul-gadget'); ?>">
            <?php
            $item_count_text = sprintf(
            /* translators: number of items in the mini cart. */
                    _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'dimdul-gadget'),
                    WC()->cart->get_cart_contents_count()
            );
            ?>
            <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span
                    class="count"><?php echo esc_html($item_count_text); ?></span>
        </a>
        <?php
    }
}

if (!function_exists('dimdul_gadget_woocommerce_header_cart')) {
    /**
     * Display Header Cart.
     *
     * @return void
     */
    function dimdul_gadget_woocommerce_header_cart()
    {
        if (is_cart()) {
            $class = 'current-menu-item';
        } else {
            $class = '';
        }
        ?>
        <ul id="site-header-cart" class="site-header-cart">
            <li class="<?php echo esc_attr($class); ?>">
                <?php dimdul_gadget_woocommerce_cart_link(); ?>
            </li>
            <li>
                <?php
                $instance = array(
                        'title' => '',
                );

                the_widget('WC_Widget_Cart', $instance);
                ?>
            </li>
        </ul>
        <?php
    }
}

add_filter('woocommerce_product_loop_start', function ($html) {
    // This replaces the default <ul> or <div> completely
    return '<div class="products-grid no-scroll">';
});

add_filter('woocommerce_product_loop_end', function ($html) {
    return '</div>';
});


/**
 * Show cart contents / total Ajax
 */
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
add_filter('woocommerce_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

function woocommerce_header_add_to_cart_fragment($fragments)
{
    ob_start();

    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>

    <a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>"
       title="<?php esc_attr_e('View your shopping cart', 'woocommerce'); ?>">
        <div class="flex gap-2 items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 014 0z"></path>
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
                    <?php echo WC()->cart ? WC()->cart->get_cart_total() : wc_price(0); ?>
                </span>
            </div>
        </div>
    </a>

    <?php

    $fragments['a.cart-customlocation'] = ob_get_clean();

    return $fragments;
}

/**
 * Add Product FAQ tab inside WooCommerce product data panel.
 */
add_filter('woocommerce_product_data_tabs', 'eg_add_product_faq_tab');
function eg_add_product_faq_tab($tabs)
{
    $tabs['eg_product_faq'] = array(
            'label' => __('Product FAQ', 'woocommerce'),
            'target' => 'eg_product_faq_data',
            'class' => array(),
            'priority' => 80,
    );

    return $tabs;
}

/**
 * Add FAQ fields inside the custom tab.
 */
add_action('woocommerce_product_data_panels', 'eg_add_product_faq_fields');
function eg_add_product_faq_fields()
{
    global $post;

    $faqs = get_post_meta($post->ID, '_eg_product_faqs', true);

    if (!is_array($faqs)) {
        $faqs = array();
    }

    ?>
    <div id="eg_product_faq_data" class="panel woocommerce_options_panel hidden">
        <div class="options_group">
            <p class="form-field">
                <strong><?php esc_html_e('Product FAQs', 'woocommerce'); ?></strong>
            </p>

            <div id="eg-faq-wrapper">
                <?php
                if (!empty($faqs)) :
                    foreach ($faqs as $index => $faq) :
                        $question = isset($faq['question']) ? $faq['question'] : '';
                        $answer = isset($faq['answer']) ? $faq['answer'] : '';
                        ?>
                        <div class="eg-faq-row"
                             style="padding:15px; margin:10px 0; border:1px solid #ddd; background:#fff;">
                            <p>
                                <label><?php esc_html_e('Question', 'woocommerce'); ?></label>
                                <input type="text" name="eg_product_faqs[<?php echo esc_attr($index); ?>][question]"
                                       value="<?php echo esc_attr($question); ?>" style="width:100%;"/>
                            </p>

                            <p>
                                <label><?php esc_html_e('Answer', 'woocommerce'); ?></label>
                                <textarea name="eg_product_faqs[<?php echo esc_attr($index); ?>][answer]" rows="4"
                                          style="width:100%;"><?php echo esc_textarea($answer); ?></textarea>
                            </p>

                            <button type="button"
                                    class="button eg-remove-faq"><?php esc_html_e('Remove FAQ', 'woocommerce'); ?></button>
                        </div>
                    <?php
                    endforeach;
                endif;
                ?>
            </div>

            <p>
                <button type="button" class="button button-primary" id="eg-add-faq">
                    <?php esc_html_e('Add FAQ', 'woocommerce'); ?>
                </button>
            </p>
        </div>
    </div>

    <script>
        jQuery(function ($) {
            let faqIndex = $('#eg-faq-wrapper .eg-faq-row').length;

            $('#eg-add-faq').on('click', function () {
                let html = `
                    <div class="eg-faq-row" style="padding:15px; margin:10px 0; border:1px solid #ddd; background:#fff;">
                        <p>
                            <label>Question</label>
                            <input type="text" name="eg_product_faqs[` + faqIndex + `][question]" value="" style="width:100%;" />
                        </p>

                        <p>
                            <label>Answer</label>
                            <textarea name="eg_product_faqs[` + faqIndex + `][answer]" rows="4" style="width:100%;"></textarea>
                        </p>

                        <button type="button" class="button eg-remove-faq">Remove FAQ</button>
                    </div>
                `;

                $('#eg-faq-wrapper').append(html);
                faqIndex++;
            });

            $(document).on('click', '.eg-remove-faq', function () {
                $(this).closest('.eg-faq-row').remove();
            });
        });
    </script>
    <?php
}

/**
 * Save FAQ data.
 */
add_action('woocommerce_process_product_meta', 'eg_save_product_faq_fields');
function eg_save_product_faq_fields($post_id)
{
    if (isset($_POST['eg_product_faqs']) && is_array($_POST['eg_product_faqs'])) {
        $clean_faqs = array();

        foreach ($_POST['eg_product_faqs'] as $faq) {
            $question = isset($faq['question']) ? sanitize_text_field($faq['question']) : '';
            $answer = isset($faq['answer']) ? wp_kses_post($faq['answer']) : '';

            if (!empty($question) || !empty($answer)) {
                $clean_faqs[] = array(
                        'question' => $question,
                        'answer' => $answer,
                );
            }
        }

        update_post_meta($post_id, '_eg_product_faqs', $clean_faqs);
    } else {
        delete_post_meta($post_id, '_eg_product_faqs');
    }
}

/**
 * Add FAQ as a WooCommerce product tab after Description.
 */
add_filter('woocommerce_product_tabs', 'eg_add_faq_product_tab');

function eg_add_faq_product_tab($tabs)
{
    global $product;

    if (!$product) {
        return $tabs;
    }

    $faqs = get_post_meta($product->get_id(), '_eg_product_faqs', true);

    if (empty($faqs) || !is_array($faqs)) {
        return $tabs;
    }

    $tabs['eg_product_faq_tab'] = array(
            'title' => __('FAQ', 'woocommerce'),
            'priority' => 15,
            'callback' => 'eg_product_faq_tab_content',
    );

    return $tabs;
}

/**
 * FAQ tab content.
 */
function eg_product_faq_tab_content()
{
    global $product;

    if (!$product) {
        return;
    }

    $faqs = get_post_meta($product->get_id(), '_eg_product_faqs', true);

    if (empty($faqs) || !is_array($faqs)) {
        return;
    }

    echo '<div class="eg-product-faq-tab-content">';
    echo '<h2>Frequently Asked Questions</h2>';

    foreach ($faqs as $faq) {
        $question = isset($faq['question']) ? $faq['question'] : '';
        $answer = isset($faq['answer']) ? $faq['answer'] : '';

        if (!empty($question) || !empty($answer)) {
            echo '<details class="eg-product-faq-item">';
            echo '<summary>' . esc_html($question) . '</summary>';
            echo '<div class="eg-product-faq-answer">' . wpautop(wp_kses_post($answer)) . '</div>';
            echo '</details>';
        }
    }

    echo '</div>';
}


add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_checkout_after_add_to_cart');

function redirect_to_checkout_after_add_to_cart()
{
    return wc_get_checkout_url();
}

function custom_wc_product_cta_shortcode($atts)
{
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
            'id' => '',
    ), $atts, 'product_cta');

    if (empty($atts['id'])) {
        return 'Please provide a Product ID.';
    }

    $product = wc_get_product($atts['id']);

    // Check if product exists and is NOT a variable product
    if (!$product || $product->is_type('variable')) {
        return '';
    }

    ob_start();

    // Set the global $product object so WooCommerce templates work correctly
    global $post, $product;
    $original_product = $product;
    $product = wc_get_product($atts['id']);

    ?>
    <div class="product-cta-wrapper">
        <div class="container">
            <div class="product-cta-box">
                <div class="product-cta-thumb">
                    <?php echo $product->get_image('thumbnail'); ?>
                </div>
                <div class="product-cta-info">
                    <h3 class="product-cta-title">
                        <?php echo $product->get_name(); ?>
                    </h3>
                    <div class="product-cta-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                </div>

                <div class="product-cta-button">
                    <?php
                    // This triggers the standard "Add to Cart" button and quantity input
                    woocommerce_template_single_add_to_cart();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php

    // Restore the original global product to prevent breaking the rest of the page
    $product = $original_product;

    return ob_get_clean();
}

add_shortcode('product_cta', 'custom_wc_product_cta_shortcode');

// Change WooCommerce "Add to cart" button text to "Buy Now"

// Single product page button text
add_filter('woocommerce_product_single_add_to_cart_text', 'dimdul_single_add_to_cart_text');

function dimdul_single_add_to_cart_text()
{
    return __('Buy Now', 'dimdul-gadget');
}


// Shop / archive / loop product button text
add_filter('woocommerce_product_add_to_cart_text', 'dimdul_loop_add_to_cart_text', 10, 2);

function dimdul_loop_add_to_cart_text($text, $product)
{

    if (!$product) {
        return $text;
    }

    // Only simple products show "Buy Now"
    if ($product->is_type('simple')) {
        return __('Buy Now', 'dimdul-gadget');
    }

    // Variable, grouped, external, etc.
    return __('Details', 'dimdul-gadget');
}


add_filter('woocommerce_checkout_fields', 'custom_reorder_and_minimize_checkout_fields');

function custom_reorder_and_minimize_checkout_fields($fields)
{
    // 1. Define the fields we want to keep and their new order/properties
    $keep_fields = array(
            'billing_first_name' => array(
                    'label' => 'নাম',
                    'placeholder' => '',
                    'priority' => 10,
                    'class' => array('form-row-wide'),
                    'required' => true
            ),
            'billing_phone' => array(
                    'label' => 'মোবাইল নম্বর',
                    'placeholder' => '',
                    'priority' => 20,
                    'class' => array('form-row-wide'),
                    'required' => true
            ),
            'billing_email' => array(
                    'label' => 'ইমেল',
                    'placeholder' => '',
                    'priority' => 30,
                    'class' => array('form-row-wide'),
                    'required' => false // Email is now optional
            ),
            'billing_country' => array(
                    'type' => 'hidden',
                    'label' => 'দেশ',
                    'placeholder' => '',
                    'priority' => 30,
                    'class' => array('form-row-wide'),
                    'default' => 'BD',
                    'required' => false // Email is now optional
            ),
            'billing_address_1' => array(
                    'type' => 'textarea', // Changed from default to textarea
                    'label' => 'পূর্ণ ঠিকানা',
                    'placeholder' => 'আপনার সম্পূর্ণ ঠিকানা এখানে লিখুন...',
                    'priority' => 40,
                    'class' => array('form-row-wide'),
                    'required' => true,
                    'custom_attributes' => array('rows' => 3) // Controls the height
            )
    );

    // 2. Clear out all existing billing fields
    $fields['billing'] = array();

    // 3. Re-insert only our allowed fields with the custom settings
    foreach ($keep_fields as $key => $props) {
        $fields['billing'][$key] = $props;
    }

    // 4. Remove the Order Notes if you don't need them
    unset($fields['order']['order_comments']);

    return $fields;
}

// 3. Force the country value to BD during the checkout process
//add_action('woocommerce_checkout_update_order_review', function($post_data) {
//    parse_str($post_data, $post_data_array);
//    $post_data_array['billing_country'] = 'BD';
//    $post_data_array['shipping_country'] = 'BD';
//});

/**
 * AJAX Product Search Handler
 */
function dimdul_gadget_product_search()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'product_search_nonce')) {
        wp_die('Security check failed');
    }

    // Get search query
    $query = sanitize_text_field($_POST['query']);

    if (empty($query)) {
        wp_send_json_error('No search query provided');
    }

    // Search products
    $args = array(
            'post_type' => 'product',
            'posts_per_page' => 8,
            's' => $query,
            'meta_query' => array(
                    'relation' => 'OR',
                    array(
                            'key' => '_sku',
                            'value' => $query,
                            'compare' => 'LIKE'
                    )
            )
    );

    $products = new WP_Query($args);

    $html = '';

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $product;

            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product_url = get_permalink();
            $product_price = $product->get_price_html();
            $product_image = get_the_post_thumbnail_url($product_id, 'thumbnail') ?: wc_placeholder_img_src();

            $html .= '<div class="search-result-item" data-url="' . esc_url($product_url) . '">';
            $html .= '<div class="search-result-image">';
            $html .= '<img src="' . esc_url($product_image) . '" alt="' . esc_attr($product_title) . '">';
            $html .= '</div>';
            $html .= '<div class="search-result-content">';
            $html .= '<h4 class="search-result-title">' . esc_html($product_title) . '</h4>';
            $html .= '<div class="search-result-price">' . $product_price . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html = '<div class="search-no-results">No products found</div>';
    }

    wp_reset_postdata();

    wp_send_json_success(array('html' => $html));
}

add_action('wp_ajax_product_search', 'dimdul_gadget_product_search');
add_action('wp_ajax_nopriv_product_search', 'dimdul_gadget_product_search');

/**
 * Landing checkout helpers.
 */
function theme_landing_wc_ready()
{
    return class_exists('WooCommerce') && function_exists('WC');
}

function theme_landing_ensure_cart()
{
    if (!theme_landing_wc_ready()) {
        return false;
    }

    if (null === WC()->cart && function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    return WC()->cart instanceof WC_Cart;
}

function theme_get_landing_product_id($page_id = 0)
{
    $page_id = absint($page_id);
    if (!$page_id) {
        $page_id = get_the_ID();
    }

    $product_id = get_post_meta($page_id, 'landing_product_id', true);
    return absint($product_id);
}

function theme_get_landing_product($product_id)
{
    if (!theme_landing_wc_ready()) {
        return false;
    }

    $product_id = absint($product_id);
    if (!$product_id) {
        return false;
    }

    $product = wc_get_product($product_id);
    if (!$product || 'publish' !== get_post_status($product_id)) {
        return false;
    }

    if (!$product->is_purchasable() || !$product->exists()) {
        return false;
    }

    return $product;
}

function theme_is_product_in_cart($product_id, $variation_id = 0)
{
    if (!theme_landing_ensure_cart()) {
        return false;
    }

    $product_id = absint($product_id);
    $variation_id = absint($variation_id);

    foreach (WC()->cart->get_cart() as $cart_item) {
        $cart_product_id = isset($cart_item['product_id']) ? absint($cart_item['product_id']) : 0;
        $cart_variation_id = isset($cart_item['variation_id']) ? absint($cart_item['variation_id']) : 0;

        if ($variation_id > 0) {
            if ($cart_product_id === $product_id && $cart_variation_id === $variation_id) {
                return true;
            }
        } elseif ($cart_product_id === $product_id) {
            return true;
        }
    }

    return false;
}

function theme_get_landing_cart_item_key($product_id, $variation_id = 0)
{
    if (!theme_landing_ensure_cart()) {
        return '';
    }

    $product_id = absint($product_id);
    $variation_id = absint($variation_id);

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $cart_product_id = isset($cart_item['product_id']) ? absint($cart_item['product_id']) : 0;
        $cart_variation_id = isset($cart_item['variation_id']) ? absint($cart_item['variation_id']) : 0;

        if ($variation_id > 0) {
            if ($cart_product_id === $product_id && $cart_variation_id === $variation_id) {
                return $cart_item_key;
            }
        } elseif ($cart_product_id === $product_id) {
            return $cart_item_key;
        }
    }

    return '';
}

function theme_auto_add_simple_landing_product($product)
{
    if (!$product instanceof WC_Product || !$product->is_type('simple')) {
        return;
    }

    if (!theme_landing_ensure_cart()) {
        return;
    }

    $product_id = $product->get_id();
    if (theme_is_product_in_cart($product_id)) {
        return;
    }

    if (!$product->is_in_stock() || !$product->is_purchasable()) {
        return;
    }

    WC()->cart->add_to_cart($product_id, 1);
}

function theme_render_landing_product_summary($product)
{
    if (!$product instanceof WC_Product) {
        return;
    }

    $gallery_ids = $product->get_gallery_image_ids();
    $image_id = $product->get_image_id();
    ?>
    <div class="space-y-4 single-product-main-information">
        <div class="space-y-3">
            <?php if ($image_id) : ?>
                <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">
                    <?php echo wp_kses_post(wp_get_attachment_image($image_id, 'large', false, array('class' => 'w-full h-auto object-cover'))); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($gallery_ids)) : ?>
                <div class="grid grid-cols-4 gap-3">
                    <?php foreach (array_slice($gallery_ids, 0, 4) as $gallery_id) : ?>
                        <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                            <?php echo wp_kses_post(wp_get_attachment_image($gallery_id, 'thumbnail', false, array('class' => 'w-full h-auto object-cover'))); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo esc_html($product->get_name()); ?></h1>
            <div class="text-xl font-semibold text-gray-900"><?php echo wp_kses_post($product->get_price_html()); ?></div>

            <?php if ($product->get_short_description()) : ?>
                <div class="text-gray-600 leading-relaxed">
                    <?php echo wp_kses_post(wpautop($product->get_short_description())); ?>
                </div>
            <?php endif; ?>

            <!--Inset Here-->
            <?php 
            $is_variable = $product->is_type('variable');
            $landing_item_key = theme_get_landing_cart_item_key($product->get_id());
            $landing_quantity = $landing_item_key ? WC()->cart->get_cart_item($landing_item_key)['quantity'] : 1;
            ?>
            
            <?php if ( $is_variable ) : ?>
                <?php if ( function_exists( 'theme_render_landing_variable_form' ) ) : ?>
                   <?php theme_render_landing_variable_form( $product ); ?>
                <?php endif; ?>
            <?php else : ?>
                <div class="mt-6">
                   <h3 class="text-lg font-semibold text-gray-900 mb-3"><?php esc_html_e( 'Quantity', 'dimdul-gadget' ); ?></h3>
                   <?php if ( function_exists( 'theme_render_landing_quantity_control' ) ) : ?>
                      <?php theme_render_landing_quantity_control( $landing_item_key, $landing_quantity, true ); ?>
                   <?php endif; ?>
                </div>
            <?php endif; ?>

            <div id="landing-quantity-wrap" class="<?php echo $is_variable ? 'mt-6 hidden' : 'hidden'; ?>">
                <h3 class="text-lg font-semibold text-gray-900 mb-3"><?php esc_html_e( 'Quantity', 'dimdul-gadget' ); ?></h3>
                <div id="landing-quantity-control-target"></div>
            </div>
        </div>
        <div class="text-center product-page-right-sidebar">
            <div class=""><img class="mx-auto mb-3"
                               src="<?php echo get_template_directory_uri(); ?>/images/brand.png"
                               alt="Brand Logo"></div>
            <div class=""><img class="mx-auto mb-3"
                               src="<?php echo get_template_directory_uri(); ?>/images/free-shipping.png"
                               alt="Brand Logo"></div>
            <div class=""><img class="mx-auto mb-3"
                               src="<?php echo get_template_directory_uri(); ?>/images/warranty.png"
                               alt="Brand Logo"></div>
        </div>
    </div>
    <?php
}

function theme_render_landing_variable_form($wc_product)
{
    if (!$wc_product instanceof WC_Product || !$wc_product->is_type('variable')) {
        return;
    }

    global $product;
    $previous_product = $product;
    $product = wc_get_product($wc_product->get_id());

    echo '<div class="mt-6 landing-variable-form">';
    woocommerce_variable_add_to_cart();
    echo '</div>';

    $product = $previous_product;
}

function theme_render_landing_quantity_control($cart_item_key = '', $quantity = 1, $visible = true)
{
    $quantity = max(0, absint($quantity));
    $classes = $visible ? '' : 'hidden';
    ?>
    <div class="landing-qty-control flex items-center gap-3 <?php echo esc_attr($classes); ?>"
         data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
        <button type="button"
                class="landing-qty-btn landing-qty-minus rounded-xl px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100">
            -
        </button>
        <input type="number" min="0" step="1"
               class="landing-qty-input w-24 rounded-xl border border-gray-300 px-3 py-2 text-center"
               value="<?php echo esc_attr($quantity); ?>"/>
        <button type="button"
                class="landing-qty-btn landing-qty-plus rounded-xl px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100">
            +
        </button>
    </div>
    <?php
}

function theme_render_landing_checkout()
{
    echo '<div class="landing-checkout-area">';
    echo '<h2 class="text-2xl font-bold text-gray-900 mb-4">' . esc_html__('Complete Your Order', 'dimdul-gadget') . '</h2>';
    echo do_shortcode('[woocommerce_checkout]');
    echo '<wc-order-attribution-inputs></wc-order-attribution-inputs>';
    echo '</div>';
}

function theme_enqueue_landing_checkout_assets()
{
    if (!is_page_template('page-templates/template-landing-checkout.php')) {
        return;
    }

    if (!theme_landing_wc_ready()) {
        return;
    }

    $product_id = theme_get_landing_product_id(get_the_ID());
    $product = theme_get_landing_product($product_id);

    if (!$product) {
        return;
    }

    $is_variable = $product->is_type('variable');
    $is_simple = $product->is_type('simple');

    $cart_item_key = theme_get_landing_cart_item_key($product_id);
    $initial_qty = 1;

    if ($cart_item_key && theme_landing_ensure_cart()) {
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        $initial_qty = !empty($cart_item['quantity']) ? absint($cart_item['quantity']) : 1;
    }

    if ($is_variable) {
        wp_enqueue_script('wc-add-to-cart-variation');
    }

    wp_enqueue_script('wc-cart-fragments');
    wp_enqueue_script('wc-checkout');
    wp_enqueue_script(
            'theme-landing-checkout',
            get_template_directory_uri() . '/js/landing-checkout.js',
            array('jquery', 'wc-checkout', 'wc-cart-fragments', 'wc-add-to-cart-variation'),
            _S_VERSION,
            true
    );

    wp_localize_script(
            'theme-landing-checkout',
            'themeLandingCheckout',
            array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('theme_landing_checkout_nonce'),
                    'product_id' => $product_id,
                    'is_variable' => $is_variable,
                    'is_simple' => $is_simple,
                    'cart_item_key' => $cart_item_key,
                    'initial_qty' => $initial_qty,
                    'strings' => array(
                            'select_variation' => __('Please select all variation options.', 'dimdul-gadget'),
                            'adding' => __('Adding...', 'dimdul-gadget'),
                            'added' => __('Product added. You can complete checkout below.', 'dimdul-gadget'),
                            'add_error' => __('Unable to add product. Please try again.', 'dimdul-gadget'),
                            'qty_updated' => __('Quantity updated.', 'dimdul-gadget'),
                            'qty_error' => __('Unable to update quantity. Please try again.', 'dimdul-gadget'),
                            'removed' => __('Product removed from cart.', 'dimdul-gadget'),
                            'variation_ready' => __('Variation selected. You can now add to cart.', 'dimdul-gadget'),
                    ),
            )
    );
}

add_action('wp_enqueue_scripts', 'theme_enqueue_landing_checkout_assets', 30);

function theme_sanitize_landing_attributes($raw_attributes)
{
    $attributes = array();

    if (!is_array($raw_attributes)) {
        return $attributes;
    }

    foreach ($raw_attributes as $key => $value) {
        $clean_key = wc_clean(wp_unslash($key));
        $clean_val = wc_clean(wp_unslash($value));

        if (0 !== strpos($clean_key, 'attribute_')) {
            continue;
        }

        $attributes[$clean_key] = $clean_val;
    }

    return $attributes;
}

function theme_ajax_landing_add_to_cart()
{
    if (!theme_landing_wc_ready()) {
        wp_send_json_error(array('message' => __('WooCommerce is not available.', 'dimdul-gadget')), 400);
    }

    check_ajax_referer('theme_landing_checkout_nonce', 'nonce');

    if (function_exists('woocommerce_maybe_define_constant')) {
        woocommerce_maybe_define_constant('WOOCOMMERCE_CART', true);
    }

    if (!theme_landing_ensure_cart()) {
        wp_send_json_error(array('message' => __('Cart is not ready.', 'dimdul-gadget')), 400);
    }

    $product_id = isset($_POST['product_id']) ? absint(wp_unslash($_POST['product_id'])) : 0;
    $variation_id = isset($_POST['variation_id']) ? absint(wp_unslash($_POST['variation_id'])) : 0;
    $quantity = isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : 1;
    $attributes = isset($_POST['attributes']) ? theme_sanitize_landing_attributes($_POST['attributes']) : array();

    $product = theme_get_landing_product($product_id);

    if (!$product) {
        wp_send_json_error(array('message' => __('Invalid product.', 'dimdul-gadget')), 400);
    }

    if ($quantity < 1) {
        $quantity = 1;
    }

    if ($product->is_type('simple')) {
        if (!$product->is_in_stock()) {
            wp_send_json_error(array('message' => __('This product is out of stock.', 'dimdul-gadget')), 400);
        }

        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
        if (!$cart_item_key) {
            wp_send_json_error(array('message' => __('Could not add product to cart.', 'dimdul-gadget')), 400);
        }

        WC()->cart->calculate_totals();

        wp_send_json_success(
                array(
                        'message' => __('Product added successfully.', 'dimdul-gadget'),
                        'cart_item_key' => $cart_item_key,
                        'quantity' => $quantity,
                )
        );
    }

    if (!$product->is_type('variable')) {
        wp_send_json_error(array('message' => __('Unsupported product type.', 'dimdul-gadget')), 400);
    }

    $variation = wc_get_product($variation_id);
    if (!$variation || !$variation instanceof WC_Product_Variation) {
        wp_send_json_error(array('message' => __('Please select a valid variation.', 'dimdul-gadget')), 400);
    }

    if (absint($variation->get_parent_id()) !== $product_id) {
        wp_send_json_error(array('message' => __('Variation does not belong to this product.', 'dimdul-gadget')), 400);
    }

    if (!$variation->is_purchasable() || !$variation->is_in_stock()) {
        wp_send_json_error(array('message' => __('Selected variation is unavailable.', 'dimdul-gadget')), 400);
    }

    // Keep one landing variation in cart for this parent product.
    foreach (WC()->cart->get_cart() as $existing_key => $cart_item) {
        $cart_product_id = isset($cart_item['product_id']) ? absint($cart_item['product_id']) : 0;
        if ($cart_product_id === $product_id) {
            WC()->cart->remove_cart_item($existing_key);
        }
    }

    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $attributes);
    if (!$cart_item_key) {
        wp_send_json_error(array('message' => __('Could not add selected variation.', 'dimdul-gadget')), 400);
    }

    WC()->cart->calculate_totals();

    wp_send_json_success(
            array(
                    'message' => __('Variation added successfully.', 'dimdul-gadget'),
                    'cart_item_key' => $cart_item_key,
                    'quantity' => $quantity,
            )
    );
}

add_action('wp_ajax_landing_add_to_cart', 'theme_ajax_landing_add_to_cart');
add_action('wp_ajax_nopriv_landing_add_to_cart', 'theme_ajax_landing_add_to_cart');

function theme_ajax_landing_update_qty()
{
    if (!theme_landing_wc_ready()) {
        wp_send_json_error(array('message' => __('WooCommerce is not available.', 'dimdul-gadget')), 400);
    }

    check_ajax_referer('theme_landing_checkout_nonce', 'nonce');

    if (function_exists('woocommerce_maybe_define_constant')) {
        woocommerce_maybe_define_constant('WOOCOMMERCE_CART', true);
    }

    if (!theme_landing_ensure_cart()) {
        wp_send_json_error(array('message' => __('Cart is not ready.', 'dimdul-gadget')), 400);
    }

    $product_id = isset($_POST['product_id']) ? absint(wp_unslash($_POST['product_id'])) : 0;
    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field(wp_unslash($_POST['cart_item_key'])) : '';
    $quantity = isset($_POST['quantity']) ? max(0, wc_stock_amount(wp_unslash($_POST['quantity']))) : 0;

    if (!$cart_item_key) {
        $cart_item_key = theme_get_landing_cart_item_key($product_id);
    }

    if (!$cart_item_key) {
        wp_send_json_error(array('message' => __('Cart item not found.', 'dimdul-gadget')), 404);
    }

    $cart_item = WC()->cart->get_cart_item($cart_item_key);
    if (empty($cart_item)) {
        wp_send_json_error(array('message' => __('Cart item does not exist.', 'dimdul-gadget')), 404);
    }

    if (0 === $quantity) {
        WC()->cart->remove_cart_item($cart_item_key);
        WC()->cart->calculate_totals();
        wp_send_json_success(
                array(
                        'removed' => true,
                        'quantity' => 0,
                        'cart_item_key' => '',
                        'message' => __('Product removed from cart.', 'dimdul-gadget'),
                )
        );
    }

    $updated = WC()->cart->set_quantity($cart_item_key, $quantity, true);
    if (false === $updated) {
        wp_send_json_error(array('message' => __('Could not update quantity.', 'dimdul-gadget')), 400);
    }

    WC()->cart->calculate_totals();

    wp_send_json_success(
            array(
                    'removed' => false,
                    'quantity' => $quantity,
                    'cart_item_key' => $cart_item_key,
                    'message' => __('Quantity updated.', 'dimdul-gadget'),
            )
    );
}

add_action('wp_ajax_landing_update_qty', 'theme_ajax_landing_update_qty');
add_action('wp_ajax_nopriv_landing_update_qty', 'theme_ajax_landing_update_qty');

function theme_ajax_landing_get_checkout_html()
{
    if (!theme_landing_wc_ready()) {
        wp_send_json_error(array('message' => __('WooCommerce is not available.', 'dimdul-gadget')), 400);
    }

    check_ajax_referer('theme_landing_checkout_nonce', 'nonce');

    if (!theme_landing_ensure_cart()) {
        wp_send_json_error(array('message' => __('Cart is not ready.', 'dimdul-gadget')), 400);
    }

    ob_start();
    theme_render_landing_checkout();
    $checkout_html = ob_get_clean();

    wp_send_json_success(
            array(
                    'checkout_html' => $checkout_html,
            )
    );
}

add_action('wp_ajax_landing_get_checkout_html', 'theme_ajax_landing_get_checkout_html');
add_action('wp_ajax_nopriv_landing_get_checkout_html', 'theme_ajax_landing_get_checkout_html');
