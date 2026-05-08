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



                </div>
                <!-- Trust Badges -->
                <div class="trust-badges grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/brand.png"
                             alt="<?php esc_attr_e('Brand', 'dimdul-gadget'); ?>"
                             class="mx-auto mb-2 h-12 object-contain">
                        <p class="text-xs text-gray-600"><?php esc_html_e('Official Brand', 'dimdul-gadget'); ?></p>
                    </div>
                    <div class="text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/free-shipping.png"
                             alt="<?php esc_attr_e('Free Shipping', 'dimdul-gadget'); ?>"
                             class="mx-auto mb-2 h-12 object-contain">
                        <p class="text-xs text-gray-600"><?php esc_html_e('Free Shipping', 'dimdul-gadget'); ?></p>
                    </div>
                    <div class="text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/warranty.png"
                             alt="<?php esc_attr_e('Warranty', 'dimdul-gadget'); ?>"
                             class="mx-auto mb-2 h-12 object-contain">
                        <p class="text-xs text-gray-600"><?php esc_html_e('Warranty', 'dimdul-gadget'); ?></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Checkout Section -->
        <div class="checkout-section bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Complete Your Purchase', 'dimdul-gadget'); ?></h2>
                    
                    <div class="bg-white rounded-lg shadow-lg p-6 lg:p-8">
                        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description Section -->
        <?php if ($product->get_description()): ?>
            <div class="description-section py-12">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Product Description', 'woocommerce'); ?></h2>
                        <div class="prose prose-lg max-w-none">
                            <?php echo wp_kses_post($product->get_description()); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Product CTA Section -->
        <?php
        // Use the existing product-cta shortcode if available
        if (function_exists('custom_wc_product_cta_shortcode') && $product && !$product->is_type('variable')):
            echo do_shortcode('[product_cta id="' . $product_id . '"]');
        endif;
        ?>

        <!-- Reviews Section -->
        <div class="reviews-section bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
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
            <div class="faq-section py-12">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Frequently Asked Questions', 'dimdul-gadget'); ?></h2>

                        <?php
                        foreach ($faqs as $faq):
                            $question = isset($faq['question']) ? $faq['question'] : '';
                            $answer = isset($faq['answer']) ? $faq['answer'] : '';

                            if (!empty($question) || !empty($answer)):
                        ?>
                            <div class="faq-item bg-white rounded-lg p-6 mb-4 shadow">
                                <h3 class="font-semibold mb-2"><?php echo esc_html($question); ?></h3>
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
            </div>
        <?php
        else:
            // Fallback to default FAQs if no product-specific FAQs
        ?>
            <div class="faq-section py-12">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Frequently Asked Questions', 'dimdul-gadget'); ?></h2>

                        <div class="faq-item bg-white rounded-lg p-6 mb-4 shadow">
                            <h3 class="font-semibold mb-2"><?php esc_html_e('What payment methods do you accept?', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('We accept all major credit cards, debit cards, and popular online payment methods. All transactions are secure and encrypted.', 'dimdul-gadget'); ?></p>
                        </div>

                        <div class="faq-item bg-white rounded-lg p-6 mb-4 shadow">
                            <h3 class="font-semibold mb-2"><?php esc_html_e('How long does shipping take?', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('Standard shipping typically takes 5-7 business days. Express shipping options are available at checkout.', 'dimdul-gadget'); ?></p>
                        </div>

                        <div class="faq-item bg-white rounded-lg p-6 mb-4 shadow">
                            <h3 class="font-semibold mb-2"><?php esc_html_e('What is your return policy?', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('We offer a 30-day return policy on all unused items in their original packaging. Please contact our customer service team to initiate a return.', 'dimdul-gadget'); ?></p>
                        </div>

                        <div class="faq-item bg-white rounded-lg p-6 mb-4 shadow">
                            <h3 class="font-semibold mb-2"><?php esc_html_e('Is my personal information secure?', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('Yes, we use industry-standard SSL encryption to protect your personal and payment information. Your privacy and security are our top priorities.', 'dimdul-gadget'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endif;
        ?>

        <!-- Benefits Section -->
        <div class="benefits-section bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-center mb-8"><?php esc_html_e('Why Choose Us', 'dimdul-gadget'); ?></h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="benefit-card bg-white rounded-lg p-6 text-center shadow">
                            <div class="text-3xl mb-4">🚚</div>
                            <h3 class="font-semibold mb-2"><?php esc_html_e('Fast Shipping', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('Quick delivery to your doorstep with tracking available.', 'dimdul-gadget'); ?></p>
                        </div>

                        <div class="benefit-card bg-white rounded-lg p-6 text-center shadow">
                            <div class="text-3xl mb-4">✓</div>
                            <h3 class="font-semibold mb-2"><?php esc_html_e('Quality Guarantee', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('All products are tested for quality and authenticity.', 'dimdul-gadget'); ?></p>
                        </div>

                        <div class="benefit-card bg-white rounded-lg p-6 text-center shadow">
                            <div class="text-3xl mb-4">🔒</div>
                            <h3 class="font-semibold mb-2"><?php esc_html_e('Secure Shopping', 'dimdul-gadget'); ?></h3>
                            <p class="text-gray-700"><?php esc_html_e('Safe and secure checkout process with multiple payment options.', 'dimdul-gadget'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
// Restore original global post and product
$GLOBALS['post'] = $original_post;
$GLOBALS['product'] = $original_product;

get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
