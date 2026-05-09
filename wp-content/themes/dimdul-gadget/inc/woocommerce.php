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
add_filter( 'woocommerce_product_single_add_to_cart_text', 'dimdul_single_add_to_cart_text' );

function dimdul_single_add_to_cart_text() {
    return __( 'Buy Now', 'dimdul-gadget' );
}


// Shop / archive / loop product button text
add_filter( 'woocommerce_product_add_to_cart_text', 'dimdul_loop_add_to_cart_text', 10, 2 );

function dimdul_loop_add_to_cart_text( $text, $product ) {

    if ( ! $product ) {
        return $text;
    }

    // Only simple products show "Buy Now"
    if ( $product->is_type( 'simple' ) ) {
        return __( 'Buy Now', 'dimdul-gadget' );
    }

    // Variable, grouped, external, etc.
    return __( 'Details', 'dimdul-gadget' );
}


add_filter( 'woocommerce_checkout_fields' , 'custom_reorder_and_minimize_checkout_fields' );

function custom_reorder_and_minimize_checkout_fields( $fields ) {
    // 1. Define the fields we want to keep and their new order/properties
    $keep_fields = array(
            'billing_first_name' => array(
                    'label'       => 'নাম',
                    'placeholder' => '',
                    'priority'    => 10,
                    'class'       => array('form-row-wide'),
                    'required'    => true
            ),
            'billing_phone' => array(
                    'label'       => 'মোবাইল নম্বর',
                    'placeholder' => '',
                    'priority'    => 20,
                    'class'       => array('form-row-wide'),
                    'required'    => true
            ),
            'billing_email' => array(
                    'label'       => 'ইমেল',
                    'placeholder' => '',
                    'priority'    => 30,
                    'class'       => array('form-row-wide'),
                    'required'    => false // Email is now optional
            ),
            'billing_country' => array(
                    'type'        => 'hidden',
                    'label'       => 'দেশ',
                    'placeholder' => '',
                    'priority'    => 30,
                    'class'       => array('form-row-wide'),
                    'default'     => 'BD',
                    'required'    => false // Email is now optional
            ),
            'billing_address_1' => array(
                    'type'        => 'textarea', // Changed from default to textarea
                    'label'       => 'পূর্ণ ঠিকানা',
                    'placeholder' => 'আপনার সম্পূর্ণ ঠিকানা এখানে লিখুন...',
                    'priority'    => 40,
                    'class'       => array('form-row-wide'),
                    'required'    => true,
                    'custom_attributes' => array('rows' => 3) // Controls the height
            )
    );

    // 2. Clear out all existing billing fields
    $fields['billing'] = array();

    // 3. Re-insert only our allowed fields with the custom settings
    foreach ( $keep_fields as $key => $props ) {
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
 * AJAX handler for getting variation data
 */
function dimdul_gadget_get_variation_data() {
    check_ajax_referer('add_to_cart_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    $variations = $_POST['variations'];
    
    $product = wc_get_product($product_id);
    
    if (!$product || !$product->is_type('variable')) {
        wp_send_json_error(array('message' => 'Invalid product'));
    }
    
    $data_store = WC_Data_Store::load('product-variation');
    $matching_variations = $data_store->find_matching_variations($product, $variations);
    
    if (empty($matching_variations)) {
        wp_send_json_error(array('message' => 'No matching variation found'));
    }
    
    $variation_id = $matching_variations[0];
    $variation = wc_get_product($variation_id);
    
    if (!$variation) {
        wp_send_json_error(array('message' => 'Variation not found'));
    }
    
    $response = array(
        'variation_id' => $variation_id,
        'price' => $variation->get_price_html(),
        'regular_price' => wc_price($variation->get_regular_price()),
        'stock_status' => $variation->is_in_stock() ? __('In stock', 'woocommerce') : __('Out of stock', 'woocommerce'),
        'is_in_stock' => $variation->is_in_stock(),
        'is_purchasable' => $variation->is_purchasable(),
        'max_quantity' => $variation->get_max_purchase_quantity(),
        'attributes' => $variation->get_attributes()
    );
    
    wp_send_json_success($response);
}

add_action('wp_ajax_get_variation_data', 'dimdul_gadget_get_variation_data');
add_action('wp_ajax_nopriv_get_variation_data', 'dimdul_gadget_get_variation_data');

/**
 * Enqueue scripts and localize AJAX variables for landing page
 */
function dimdul_gadget_enqueue_landing_scripts() {
    // Only enqueue on landing page template
    if (is_page_template('page-product-landing.php')) {
        wp_enqueue_script('landing-page-js', get_template_directory_uri() . '/js/landing-page.js', array('jquery'), _S_VERSION, true);
        
        wp_localize_script('landing-page-js', 'landing_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce_add_to_cart' => wp_create_nonce('add_to_cart_nonce')
        ));
    }
}

add_action('wp_enqueue_scripts', 'dimdul_gadget_enqueue_landing_scripts');