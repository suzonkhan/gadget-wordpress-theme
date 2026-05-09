<?php
/**
 * Template Name: Product Landing Checkout
 * Template Post Type: page
 * Description: Product landing page template with embedded checkout for WooCommerce products
 *
 * This template displays a WooCommerce product using custom field (landing_product_id)
 * and manages all product data: gallery, title, price, variations, summary, description,
 * reviews, and integrated checkout form with Tailwind CSS styling.
 *
 * Usage: Create a page, add custom field 'landing_product_id' with WooCommerce product ID,
 * select this template from page template dropdown.
 *
 * @package DimdulGadget
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get product ID from custom field
$product_id = absint(get_post_meta(get_the_ID(), 'landing_product_id', true));
$product = wc_get_product($product_id);

// Handle no product found
if (!$product || !$product->exists()) {
    wp_redirect(home_url());
    exit;
}

// Auto-add simple product to cart
$cart_quantity = 1; // Default quantity
if ($product->is_type('simple') && $product->is_in_stock()) {
    // Check if product is already in cart
    $cart = WC()->cart;
    $product_in_cart = false;
    
    if ($cart) {
        foreach ($cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] == $product_id) {
                $product_in_cart = true;
                $cart_quantity = $cart_item['quantity'];
                break;
            }
        }
        
        // Add product to cart if not already present
        if (!$product_in_cart) {
            $cart->add_to_cart($product_id, $cart_quantity);
            wc_add_notice(__('Product automatically added to cart. Please complete your purchase below.', 'dimdul-gadget'), 'success');
        }
    }
}

// Store original global post and product
$original_post = $GLOBALS['post'];
$original_product = $GLOBALS['product'] ?? null;

// Set up global product context
$GLOBALS['product'] = $product;

get_header('shop');

// Display WooCommerce notices
wc_print_notices();

// Filter to keep checkout on same page instead of redirecting
add_filter('woocommerce_get_checkout_url', function($url) {
    // Return current page URL to keep checkout on landing page
    return get_permalink();
});
?>

    <main id="primary" class="site-main py-10">
        <!-- Hero Section with Product -->
        <div class="container">
            <div class="single-product-main-information">
                <!-- Product Gallery -->
                <div class="product-gallery-section">
                    <div class="gallery-main-wrapper">

                        <div class="gallery-main aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4">
                            <?php if ($product->get_image_id()): ?>
                                <img id="main-product-image"
                                     src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'full')); ?>"
                                     alt="<?php echo esc_attr($product->get_name()); ?>"
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            <?php else: ?>
                                <img src="<?php echo esc_url(wc_placeholder_img_src('full')); ?>"
                                     alt="<?php esc_attr_e('Placeholder', 'woocommerce'); ?>"
                                     class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>

                        <!-- Gallery Thumbnails -->
                        <?php
                        $gallery_ids = $product->get_gallery_image_ids();
                        if (!empty($gallery_ids)):
                            ?>
                            <div class="gallery-thumbnails grid grid-cols-4 gap-2">
                                <?php
                                $image_id = $product->get_image_id();
                                if (!empty($image_id)):
                                    $thumb_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                    $full_url = wp_get_attachment_image_url($image_id, 'full');
                                    echo '<div class="gallery-thumb active cursor-pointer border-2 border-transparent rounded-lg overflow-hidden transition-all hover:border-blue-500" data-image="' . esc_url($full_url) . '">';
                                    echo '<img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($product->get_name()) . '" class="w-full h-full object-cover">';
                                    echo '</div>';
                                endif;

                                foreach ($gallery_ids as $gallery_id):
                                    $thumb_url = wp_get_attachment_image_url($gallery_id, 'thumbnail');
                                    $full_url = wp_get_attachment_image_url($gallery_id, 'full');
                                    echo '<div class="gallery-thumb cursor-pointer border-2 border-transparent rounded-lg overflow-hidden transition-all hover:border-blue-500" data-image="' . esc_url($full_url) . '">';
                                    echo '<img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($product->get_name()) . '" class="w-full h-full object-cover">';
                                    echo '</div>';
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Information & Checkout -->
                <div class="product-info-section">
                    <!-- Product Title -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <?php echo esc_html($product->get_name()); ?>
                    </h1>

                    <!-- Rating & Reviews -->
                    <?php if ($product->get_review_count() > 0): ?>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center">
                                <?php
                                $rating = $product->get_average_rating();
                                for ($i = 1; $i <= 5; $i++):
                                    echo $i <= round($rating) ? '<span class="text-yellow-400">★</span>' : '<span class="text-gray-300">★</span>';
                                endfor;
                                ?>
                            </div>
                            <span class="text-sm text-gray-600">
                            <?php echo esc_html($product->get_review_count()); ?><?php esc_html_e('reviews', 'woocommerce'); ?>
                        </span>
                        </div>
                    <?php endif; ?>

                    <!-- Price -->
                    <div class="price-section mb-6">
                        <?php if ($product->is_on_sale()): ?>
                            <div class="flex items-center gap-3">
                            <span class="text-3xl font-bold text-red-600">
                                <?php echo wc_price($product->get_sale_price()); ?>
                            </span>
                                <span class="text-xl text-gray-400 line-through">
                                <?php echo wc_price($product->get_regular_price()); ?>
                            </span>
                                <?php
                                $discount = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                                echo '<span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-semibold">-' . $discount . '%</span>';
                                ?>
                            </div>
                        <?php else: ?>
                            <span class="text-3xl font-bold text-gray-900">
                            <?php echo wc_price($product->get_price()); ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Short Description -->
                    <?php if ($product->get_short_description()): ?>
                        <div class="short-description text-gray-600 mb-6">
                            <?php echo wp_kses_post($product->get_short_description()); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Product Meta -->
                    <div class="product-meta text-sm text-gray-500 mb-6">
                        <?php if ($product->get_sku()): ?>
                            <div class="mb-2">
                                <strong><?php esc_html_e('SKU:', 'woocommerce'); ?></strong>
                                <span><?php echo esc_html($product->get_sku()); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($product->get_stock_status()): ?>
                            <div class="mb-2">
                                <strong><?php esc_html_e('Availability:', 'woocommerce'); ?></strong>
                                <span class="<?php echo $product->is_in_stock() ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php
                                $stock_status = $product->get_stock_status();
                                if ($stock_status === 'instock') {
                                    esc_html_e('In stock', 'woocommerce');
                                } elseif ($stock_status === 'outofstock') {
                                    esc_html_e('Out of stock', 'woocommerce');
                                } elseif ($stock_status === 'onbackorder') {
                                    esc_html_e('On backorder', 'woocommerce');
                                } else {
                                    echo esc_html(ucfirst($stock_status));
                                }
                                ?>
                            </span>
                            </div>
                        <?php endif; ?>

                        <?php
                        $categories = get_the_terms($product_id, 'product_cat');
                        if ($categories && !is_wp_error($categories)):
                            ?>
                            <div class="mb-2">
                                <strong><?php esc_html_e('Category:', 'woocommerce'); ?></strong>
                                <?php
                                $category_links = array();
                                foreach ($categories as $category) {
                                    $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                                }
                                echo implode(', ', $category_links);
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Quantity Selector (for simple products) -->
                    <?php if ($product->is_type('simple') && $product->is_in_stock()): ?>
                        <div class="quantity-section mb-6">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Quantity:', 'dimdul-gadget'); ?></h3>
                            <div class="quantity-selector flex items-center gap-4">
                                <div class="flex items-center border rounded-lg">
                                    <button type="button" id="qty-decrement" 
                                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" id="product-quantity" name="quantity" value="<?php echo esc_attr($cart_quantity); ?>" min="1" max="<?php echo esc_attr($product->get_stock_quantity() ?: 999); ?>" 
                                           class="w-16 text-center border-0 focus:ring-0">
                                    <button type="button" id="qty-increment" 
                                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                                <button type="button" id="update-cart-btn" 
                                        class="bg-blue-600 text-white py-2 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    <?php esc_html_e('Update Cart', 'dimdul-gadget'); ?>
                                </button>
                            </div>
                            <div id="quantity-message" class="mt-3 text-sm"></div>
                        </div>
                    <?php endif; ?>

                    <!-- Variable Product Variations -->
                    <?php if ($product->is_type('variable')): ?>
                        <div class="variations-section mb-6">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Select Variation:', 'dimdul-gadget'); ?></h3>
                            <div class="variations-form">
                                <?php
                                $variations = $product->get_available_variations();
                                $attributes = $product->get_variation_attributes();
                                
                                if (!empty($variations)):
                                    foreach ($variations as $variation):
                                        $variation_obj = wc_get_product($variation['variation_id']);
                                        if (!$variation_obj || !$variation_obj->is_in_stock()) {
                                            continue;
                                        }
                                        ?>
                                        <div class="variation-option border rounded-lg p-4 mb-3 cursor-pointer transition-all hover:border-blue-500" 
                                             data-variation-id="<?php echo esc_attr($variation['variation_id']); ?>"
                                             data-price="<?php echo esc_attr($variation['display_price']); ?>"
                                             data-regular-price="<?php echo esc_attr($variation['display_regular_price']); ?>">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="variation_selection" value="<?php echo esc_attr($variation['variation_id']); ?>" 
                                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                                <div class="flex-1">
                                                    <div class="font-medium">
                                                        <?php
                                                        $variation_attributes = array();
                                                        foreach ($variation['attributes'] as $attr_name => $attr_value) {
                                                            $taxonomy = str_replace('attribute_', '', $attr_name);
                                                            $label = wc_attribute_label($taxonomy, $product);
                                                            if ($attr_value) {
                                                                $variation_attributes[] = $label . ': ' . wc_attribute_label($attr_value, $product);
                                                            }
                                                        }
                                                        echo esc_html(implode(', ', $variation_attributes));
                                                        ?>
                                                    </div>
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        <?php echo wc_price($variation['display_price']); ?>
                                                        <?php if ($variation['display_regular_price'] > $variation['display_price']): ?>
                                                            <span class="line-through text-gray-400 ml-2">
                                                                <?php echo wc_price($variation['display_regular_price']); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($variation_obj->get_stock_quantity()): ?>
                                                        <div class="text-xs text-green-600 mt-1">
                                                            <?php echo esc_html(sprintf(__('In stock: %d available', 'dimdul-gadget'), $variation_obj->get_stock_quantity())); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </label>
                                        </div>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            
                            <!-- Quantity Selector for Variable Products -->
                            <div class="quantity-section mb-4">
                                <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Quantity:', 'dimdul-gadget'); ?></h3>
                                <div class="quantity-selector flex items-center gap-4">
                                    <div class="flex items-center border rounded-lg">
                                        <button type="button" id="qty-decrement" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" id="product-quantity" name="quantity" value="1" min="1" max="999" 
                                               class="w-16 text-center border-0 focus:ring-0">
                                        <button type="button" id="qty-increment" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <button type="button" id="add-to-cart-btn" 
                                            class="bg-green-600 text-white py-2 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                            disabled>
                                        <?php esc_html_e('Add to Cart', 'dimdul-gadget'); ?>
                                    </button>
                                </div>
                                <div id="quantity-message" class="mt-3 text-sm"></div>
                            </div>
                            
                            <button type="button" id="buy-now-btn" 
                                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    disabled>
                                <?php esc_html_e('Buy Now', 'dimdul-gadget'); ?>
                            </button>
                            
                            <div id="variation-message" class="mt-3 text-sm"></div>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- Trust Badges -->
                <div class="trust-badges">
                    <div class="text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/brand.png"
                             alt="<?php esc_attr_e('Brand', 'dimdul-gadget'); ?>"
                             class="mx-auto mb-2 h-12 object-contain">

                    </div>
                    <div class="text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/free-shipping.png"
                             alt="<?php esc_attr_e('Free Shipping', 'dimdul-gadget'); ?>"
                             class="mx-auto mb-2 h-12 object-contain">

                    </div>
                    <div class="text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/warranty.png"
                             alt="<?php esc_attr_e('Warranty', 'dimdul-gadget'); ?>"
                             class="mx-auto mb-2 h-12 object-contain">

                    </div>
                </div>
            </div>

        </div>

        <!-- Checkout Section -->
        <section class="checkout-section py-12">
            <div class="container">
                <div class="">
                    <div class="bg-white p-6  border border-gray-200 mb-8">
                        <h2 class="text-2xl font-bold text-center "><?php esc_html_e('Complete Your Purchase', 'dimdul-gadget'); ?></h2>
                    </div>

                    <div class="">
                        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Description Section -->
        <?php if ($product->get_description()): ?>
            <section class="description-section  py-12">
                <div class="container bg-white  border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Product Description', 'woocommerce'); ?></h2>
                        <div class="prose prose-lg max-w-none">
                            <?php echo wp_kses_post($product->get_description()); ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- TRUST BADGES -->
        <section class="bg-white rounded-2xl py-5 slide-up border border-gray-200 border-y d5">
            <div class="container">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                        <div><p class="font-bold text-sm" style="font-family:'Syne',sans-serif;">Free Shipping</p>
                            <p class="text-xs text-gray-400">On orders over $99</p></div>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div><p class="font-bold text-sm" style="font-family:'Syne',sans-serif;">Free Returns</p>
                            <p class="text-xs text-gray-400">30-day return policy</p></div>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div><p class="font-bold text-sm" style="font-family:'Syne',sans-serif;">Secure Payments</p>
                            <p class="text-xs text-gray-400">SSL encrypted checkout</p></div>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div><p class="font-bold text-sm" style="font-family:'Syne',sans-serif;">24/7 Support</p>
                            <p class="text-xs text-gray-400">Always here to help</p></div>
                    </div>
                </div>
            </div>

        </section>


        <!-- Reviews Section -->
        <div class="reviews-section py-12">
            <div class="container bg-white border border-gray-200">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Customer Reviews', 'woocommerce'); ?></h2>

                    <?php
                    // Get reviews using WordPress comment functions instead of non-existent get_reviews()
                    $args = array(
                        'post_id' => $product_id,
                        'status' => 'approve',
                        'post_type' => 'product',
                        'number' => 10,
                        'comment_type' => 'review'
                    );
                    $reviews = get_comments($args);

                    if (!empty($reviews)):
                        foreach ($reviews as $review):
                            $rating = get_comment_meta($review->comment_ID, 'rating', true);
                            ?>
                            <div class="review-item bg-white rounded-lg p-6 mb-4 shadow">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="font-semibold"><?php echo esc_html($review->comment_author); ?></div>
                                        <div class="flex items-center mt-1">
                                            <?php
                                            $rating = intval($rating);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '<span class="text-yellow-400">★</span>' : '<span class="text-gray-300">★</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?php echo esc_html(date('M d, Y', strtotime($review->comment_date))); ?>
                                    </div>
                                </div>
                                <div class="text-gray-700">
                                    <?php echo esc_html($review->comment_content); ?>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <p class="text-center text-gray-600">
                            <?php esc_html_e('No reviews yet. Be the first to review this product!', 'woocommerce'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <?php
        // Use FAQ from WooCommerce functions if available
        $faqs = get_post_meta($product_id, '_eg_product_faqs', true);
        if (!empty($faqs) && is_array($faqs)):
        ?>
            <section class="faq-section">
                <div class="container bg-white border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Frequently Asked Questions', 'dimdul-gadget'); ?></h2>

                        <?php
                        foreach ($faqs as $faq):
                            $question = isset($faq['question']) ? $faq['question'] : '';
                            $answer = isset($faq['answer']) ? $faq['answer'] : '';

                            if (!empty($question) || !empty($answer)):
                        ?>
                            <div class="faq-item bg-white rounded-lg p-6 mb-4 shadow">
                                <h3 class="font-semibold text-lg mb-2"><?php echo esc_html($question); ?></h3>
                                <div class="text-gray-700">
                                    <?php echo wp_kses_post(wpautop($answer)); ?>
                                </div>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php
        endif;
        ?>


    </main>

<?php
// Restore original global post and product
$GLOBALS['post'] = $original_post;
$GLOBALS['product'] = $original_product;

get_footer('shop');
?>

<script>
jQuery(document).ready(function($) {
    // Quantity controls
    function updateQuantity() {
        var $input = $('#product-quantity');
        var value = parseInt($input.val());
        var min = parseInt($input.attr('min'));
        var max = parseInt($input.attr('max'));
        
        // Ensure value is within bounds
        if (value < min) value = min;
        if (value > max) value = max;
        
        $input.val(value);
    }
    
    // Function to update cart for variable products
    function updateVariableCart() {
        var selectedVariation = $('input[name="variation_selection"]:checked');
        
        if (!selectedVariation.length) {
            console.log('No variation selected');
            return false;
        }
        
        var variationId = selectedVariation.val();
        var quantity = parseInt($('#product-quantity').val());
        var $message = $('#quantity-message');
        
        // Validate quantity
        if (isNaN(quantity) || quantity < 1) {
            $message.html('<span class="text-red-600"><?php esc_html_e('Invalid quantity', 'dimdul-gadget'); ?></span>');
            return false;
        }
        
        // Show loading state
        $message.html('<span class="text-blue-600"><?php esc_html_e('Updating cart...', 'dimdul-gadget'); ?></span>');
        
        // Check if wc_add_to_cart_params is defined
        var ajaxUrl = '/?wc-ajax=add_to_cart';
        if (typeof wc_add_to_cart_params !== 'undefined') {
            ajaxUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart');
        }
        
        // Update cart via AJAX
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'woocommerce_add_to_cart',
                product_id: '<?php echo esc_js($product_id); ?>',
                variation_id: variationId,
                quantity: quantity
            },
            success: function(response) {
                console.log('AJAX Response:', response);
                
                if (response.error) {
                    if (response.product_url) {
                        window.location = response.product_url;
                        return;
                    }
                    $message.html('<span class="text-red-600">' + response.error + '</span>');
                    return;
                }
                
                // Show success message
                $message.html('<span class="text-green-600"><?php esc_html_e('Cart updated successfully!', 'dimdul-gadget'); ?></span>');
                
                // Clear message after 2 seconds
                setTimeout(function() {
                    $message.html('');
                }, 2000);
                
                // Trigger cart update events to refresh order summary
                $(document.body).trigger('wc_fragment_refresh');
                $(document.body).trigger('update_checkout');
                $(document.body).trigger('updated_cart_totals');
                
                // Refresh the page after a short delay to ensure cart is updated
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                $message.html('<span class="text-red-600"><?php esc_html_e('Error updating cart. Please try again.', 'dimdul-gadget'); ?></span>');
            }
        });
        
        return true;
    }
    
    // Increment quantity
    $('#qty-increment').on('click', function(e) {
        e.preventDefault();
        var $input = $('#product-quantity');
        var value = parseInt($input.val()) + 1;
        var max = parseInt($input.attr('max'));
        
        if (value <= max) {
            $input.val(value);
            
            // Auto-update cart for variable products only if variation is selected
            if ($('.variation-option').length > 0 && $('input[name="variation_selection"]:checked').length > 0) {
                updateVariableCart();
            }
        }
    });
    
    // Decrement quantity
    $('#qty-decrement').on('click', function(e) {
        e.preventDefault();
        var $input = $('#product-quantity');
        var value = parseInt($input.val()) - 1;
        var min = parseInt($input.attr('min'));
        
        if (value >= min) {
            $input.val(value);
            
            // Auto-update cart for variable products only if variation is selected
            if ($('.variation-option').length > 0 && $('input[name="variation_selection"]:checked').length > 0) {
                updateVariableCart();
            }
        }
    });
    
    // Handle manual input
    $('#product-quantity').on('change', function() {
        updateQuantity();
        
        // Auto-update cart for variable products only if variation is selected
        if ($('.variation-option').length > 0 && $('input[name="variation_selection"]:checked').length > 0) {
            updateVariableCart();
        }
    });
    
    // Update cart for simple products
    $('#update-cart-btn').on('click', function(e) {
        e.preventDefault();
        
        var quantity = parseInt($('#product-quantity').val());
        var $button = $(this);
        var $message = $('#quantity-message');
        
        // Show loading state
        $button.prop('disabled', true).html('<?php esc_html_e('Updating...', 'dimdul-gadget'); ?>');
        $message.html('<span class="text-blue-600"><?php esc_html_e('Updating cart...', 'dimdul-gadget'); ?></span>');
        
        // Check if wc_add_to_cart_params is defined
        var ajaxUrl = '/?wc-ajax=add_to_cart';
        if (typeof wc_add_to_cart_params !== 'undefined') {
            ajaxUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart');
        }
        
        // Update cart via AJAX
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'woocommerce_add_to_cart',
                product_id: '<?php echo esc_js($product_id); ?>',
                quantity: quantity
            },
            success: function(response) {
                console.log('Simple Product AJAX Response:', response);
                
                if (response.error) {
                    if (response.product_url) {
                        window.location = response.product_url;
                        return;
                    }
                    $message.html('<span class="text-red-600">' + response.error + '</span>');
                    return;
                }
                
                // Show success message
                $message.html('<span class="text-green-600"><?php esc_html_e('Cart updated successfully!', 'dimdul-gadget'); ?></span>');
                
                // Trigger cart update events to refresh order summary
                $(document.body).trigger('wc_fragment_refresh');
                $(document.body).trigger('update_checkout');
                $(document.body).trigger('updated_cart_totals');
                
                // Refresh page after a short delay to ensure cart is updated
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            },
            error: function() {
                $button.prop('disabled', false).html('<?php esc_html_e('Update Cart', 'dimdul-gadget'); ?>');
                $message.html('<span class="text-red-600"><?php esc_html_e('Error updating cart. Please try again.', 'dimdul-gadget'); ?></span>');
            }
        });
    });
    
    // Handle variation selection
    $('.variation-option').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all options
        $('.variation-option').removeClass('border-blue-500 bg-blue-50');
        
        // Add active class to selected option
        $(this).addClass('border-blue-500 bg-blue-50');
        
        // Check the radio button
        $(this).find('input[type="radio"]').prop('checked', true);
        
        // Enable Buy Now and Add to Cart buttons
        $('#buy-now-btn').prop('disabled', false);
        $('#add-to-cart-btn').prop('disabled', false);
        
        // Clear any previous messages
        $('#variation-message').html('');
        $('#quantity-message').html('');
        
        console.log('Variation selected:', $(this).find('input[type="radio"]').val());
    });
    
    // Also handle radio button change directly
    $('input[name="variation_selection"]').on('change', function() {
        var $parent = $(this).closest('.variation-option');
        
        // Remove active class from all options
        $('.variation-option').removeClass('border-blue-500 bg-blue-50');
        
        // Add active class to selected option
        $parent.addClass('border-blue-500 bg-blue-50');
        
        // Enable Buy Now and Add to Cart buttons
        $('#buy-now-btn').prop('disabled', false);
        $('#add-to-cart-btn').prop('disabled', false);
        
        // Clear any previous messages
        $('#variation-message').html('');
        $('#quantity-message').html('');
        
        console.log('Variation changed:', $(this).val());
    });
    
    // Handle Add to Cart button click
    $('#add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        
        var selectedVariation = $('input[name="variation_selection"]:checked');
        
        if (!selectedVariation.length) {
            $('#quantity-message').html('<span class="text-red-600"><?php esc_html_e('Please select a variation', 'dimdul-gadget'); ?></span>');
            return;
        }
        
        updateVariableCart();
    });
    
    // Handle Buy Now button click
    $('#buy-now-btn').on('click', function(e) {
        e.preventDefault();
        
        var selectedVariation = $('input[name="variation_selection"]:checked');
        
        if (!selectedVariation.length) {
            $('#variation-message').html('<span class="text-red-600"><?php esc_html_e('Please select a variation', 'dimdul-gadget'); ?></span>');
            return;
        }
        
        var variationId = selectedVariation.val();
        var quantity = parseInt($('#product-quantity').val());
        var $button = $(this);
        var $message = $('#variation-message');
        
        // Show loading state
        $button.prop('disabled', true).html('<?php esc_html_e('Adding to cart...', 'dimdul-gadget'); ?>');
        $message.html('<span class="text-blue-600"><?php esc_html_e('Processing...', 'dimdul-gadget'); ?></span>');
        
        // Add variation to cart via AJAX
        $.ajax({
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
            type: 'POST',
            data: {
                action: 'woocommerce_add_to_cart',
                product_id: '<?php echo esc_js($product_id); ?>',
                variation_id: variationId,
                quantity: quantity
            },
            success: function(response) {
                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }
                
                // Redirect to refresh the page to show updated cart
                window.location.reload();
            },
            error: function() {
                $button.prop('disabled', false).html('<?php esc_html_e('Buy Now', 'dimdul-gadget'); ?>');
                $message.html('<span class="text-red-600"><?php esc_html_e('Error adding to cart. Please try again.', 'dimdul-gadget'); ?></span>');
            }
        });
    });
});
</script>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
