<?php
/**
 * Template Name: Product Landing Page
 * Template Post Type: page
 * Description: Product landing page template with inline checkout powered by custom fields
 *
 * This template displays a single WooCommerce product using custom field (product_id)
 * and manages all product data: gallery, title, price, variations, summary, description,
 * reviews, and integrated checkout form with Tailwind CSS styling.
 *
 * Usage: Create a page, add custom field 'product_id' with WooCommerce product ID,
 * select this template from page template dropdown.
 *
 * @package ProductLandingTheme
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get product ID from custom field
$product_id = get_field('product_id') ?: get_the_ID();
$product = wc_get_product($product_id);

// Handle no product found
if (!$product || !$product->exists()) {
    wp_redirect(home_url());
    exit;
}

get_header();
?>

<!-- Enqueue Tailwind CSS & Additional Scripts -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap');
    
    :root {
        --primary: #ff6b35;
        --primary-dark: #e55a24;
        --secondary: #004e89;
        --accent: #f76c5e;
        --light: #f8f9fa;
        --dark: #1a1a1a;
        --border: #e0e0e0;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: #2d3436;
        line-height: 1.6;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes pulse-scale {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    .animate-fade-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .animate-slide-right {
        animation: slideInRight 0.6s ease-out;
    }
    
    .animate-slide-left {
        animation: slideInLeft 0.6s ease-out;
    }
    
    /* Custom Utility Classes */
    .bg-gradient-premium {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .shadow-premium {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .shadow-hover {
        transition: box-shadow 0.3s ease;
    }
    
    .shadow-hover:hover {
        box-shadow: 0 15px 50px rgba(255, 107, 53, 0.15);
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        font-size: 16px;
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
    }
    
    .btn-secondary {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: var(--primary);
        color: white;
    }
    
    /* Product Gallery */
    .product-gallery {
        position: relative;
    }
    
    .gallery-main {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        background: var(--light);
        position: relative;
        aspect-ratio: 1;
        margin-bottom: 16px;
    }
    
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-main img:hover {
        transform: scale(1.05);
    }
    
    .gallery-thumbnails {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    
    .gallery-thumb {
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        aspect-ratio: 1;
    }
    
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-thumb:hover {
        border-color: var(--primary);
    }
    
    .gallery-thumb.active {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }
    
    .gallery-thumb img:hover {
        transform: scale(1.1);
    }
    
    /* Product Info */
    .product-badge {
        display: inline-block;
        background: var(--accent);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    
    .product-title {
        font-size: 42px;
        line-height: 1.2;
        margin-bottom: 16px;
        color: var(--dark);
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .star-rating {
        display: flex;
        gap: 4px;
    }
    
    .star {
        color: #ffc107;
        font-size: 18px;
    }
    
    .product-price {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }
    
    .product-price-original {
        font-size: 20px;
        color: #999;
        text-decoration: line-through;
        margin-right: 12px;
    }
    
    .product-discount {
        display: inline-block;
        background: var(--accent);
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .product-summary {
        font-size: 16px;
        color: #555;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border);
    }
    
    /* Variations */
    .variations-group {
        margin-bottom: 24px;
    }
    
    .variation-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 12px;
        display: block;
    }
    
    .variation-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .variation-option {
        padding: 10px 18px;
        border: 2px solid var(--border);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        font-size: 14px;
        font-weight: 500;
    }
    
    .variation-option:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .variation-option.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .variation-color {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 2px solid var(--border);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .variation-color:hover {
        border-color: var(--primary);
    }
    
    .variation-color.active {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.2);
    }
    
    /* Quantity & Add to Cart */
    .quantity-input-group {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 24px;
        border: 1px solid var(--border);
        border-radius: 8px;
        width: fit-content;
    }
    
    .quantity-input-group button {
        background: var(--light);
        border: none;
        width: 44px;
        height: 44px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .quantity-input-group button:hover {
        background: var(--primary);
        color: white;
    }
    
    .quantity-input-group input {
        border: none;
        width: 60px;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        outline: none;
    }
    
    /* Trust Badges */
    .trust-badges {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin: 32px 0;
        padding: 24px;
        background: var(--light);
        border-radius: 12px;
    }
    
    .trust-badge {
        text-align: center;
    }
    
    .trust-badge-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    
    .trust-badge h4 {
        font-size: 14px;
        margin-bottom: 4px;
        color: var(--dark);
    }
    
    .trust-badge p {
        font-size: 12px;
        color: #888;
    }
    
    /* Checkout Form */
    .checkout-form-wrapper {
        background: white;
        padding: 32px;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-top: 32px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .form-required::after {
        content: ' *';
        color: var(--accent);
    }
    
    /* Product Description Tabs */
    .product-tabs {
        margin-top: 48px;
    }
    
    .tabs-header {
        display: flex;
        border-bottom: 2px solid var(--border);
        gap: 32px;
        margin-bottom: 32px;
    }
    
    .tab-button {
        background: none;
        border: none;
        font-size: 16px;
        font-weight: 600;
        color: #888;
        cursor: pointer;
        padding-bottom: 16px;
        position: relative;
        transition: color 0.3s ease;
    }
    
    .tab-button:hover {
        color: var(--dark);
    }
    
    .tab-button.active {
        color: var(--primary);
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary);
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .product-description {
        font-size: 15px;
        line-height: 1.8;
        color: #555;
    }
    
    .product-description h3 {
        font-size: 20px;
        margin: 24px 0 16px;
        color: var(--dark);
    }
    
    .product-description ul {
        list-style: none;
        padding-left: 0;
        margin: 16px 0;
    }
    
    .product-description li {
        padding-left: 24px;
        position: relative;
        margin-bottom: 8px;
    }
    
    .product-description li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--primary);
        font-weight: bold;
    }
    
    /* Reviews */
    .reviews-section {
        margin-top: 32px;
    }
    
    .review-item {
        padding: 24px;
        background: var(--light);
        border-radius: 8px;
        margin-bottom: 16px;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }
    
    .review-author {
        font-weight: 600;
        color: var(--dark);
    }
    
    .review-date {
        color: #888;
        font-size: 14px;
    }
    
    .review-text {
        font-size: 14px;
        color: #555;
        line-height: 1.6;
    }
    
    /* Related Products */
    .related-products {
        margin-top: 48px;
        padding-top: 48px;
        border-top: 1px solid var(--border);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-top: 24px;
    }
    
    .product-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .product-card img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover img {
        transform: scale(1.1);
    }
    
    .product-card-content {
        padding: 16px;
    }
    
    .product-card-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
    }
    
    .product-card-price {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .product-title {
            font-size: 32px;
        }
        
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .trust-badges {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .product-title {
            font-size: 28px;
        }
        
        .product-price {
            font-size: 28px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .gallery-thumbnails {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .trust-badges {
            grid-template-columns: 1fr;
        }
        
        .tabs-header {
            gap: 16px;
        }
        
        .tab-button {
            font-size: 14px;
            gap: 16px;
        }
        
        .checkout-form-wrapper {
            padding: 20px;
        }
    }
    
    @media (max-width: 640px) {
        .product-title {
            font-size: 24px;
        }
        
        .gallery-thumbnails {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="min-h-screen bg-white">
    <!-- Hero Section with Product -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 animate-fade-up">
            
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="gallery-main">
                    <img id="main-image" 
                         src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'full')); ?>" 
                         alt="<?php echo esc_attr($product->get_name()); ?>"
                         loading="eager">
                </div>
                
                <!-- Thumbnails -->
                <div class="gallery-thumbnails">
                    <?php
                    $gallery_ids = $product->get_gallery_image_ids();
                    $image_id = $product->get_image_id();
                    
                    if (!empty($image_id)) {
                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                        echo '<div class="gallery-thumb active" data-image="' . esc_url(wp_get_attachment_image_url($image_id, 'full')) . '">';
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                        echo '</div>';
                    }
                    
                    if (!empty($gallery_ids)) {
                        foreach ($gallery_ids as $gallery_id) {
                            $thumb_url = wp_get_attachment_image_url($gallery_id, 'thumbnail');
                            $full_url = wp_get_attachment_image_url($gallery_id, 'full');
                            echo '<div class="gallery-thumb" data-image="' . esc_url($full_url) . '">';
                            echo '<img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            
            <!-- Product Information -->
            <div class="animate-slide-right">
                <!-- Badge -->
                <?php if ($product->is_on_sale()): ?>
                    <div class="product-badge">
                        <?php esc_html_e('On Sale', 'woocommerce'); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Title -->
                <h1 class="product-title">
                    <?php echo esc_html($product->get_name()); ?>
                </h1>
                
                <!-- Rating -->
                <div class="product-rating">
                    <div class="star-rating">
                        <?php
                        $rating = $product->get_average_rating();
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= round($rating) ? '<span class="star">★</span>' : '<span class="star" style="color: #ddd;">★</span>';
                        }
                        ?>
                    </div>
                    <span style="color: #888; font-size: 14px;">
                        (<?php echo $product->get_review_count(); ?> <?php esc_html_e('reviews', 'woocommerce'); ?>)
                    </span>
                </div>
                
                <!-- Price -->
                <div class="product-price-wrapper">
                    <div class="product-price">
                        <?php
                        if ($product->is_on_sale()) {
                            echo '<span class="product-price-original">' . wc_price($product->get_regular_price()) . '</span>';
                            echo wc_price($product->get_sale_price());
                            $discount = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                            echo '<span class="product-discount">-' . $discount . '%</span>';
                        } else {
                            echo wc_price($product->get_price());
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Summary -->
                <?php if ($product->get_short_description()): ?>
                    <div class="product-summary">
                        <?php echo wp_kses_post($product->get_short_description()); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Variations -->
                <?php
                if ($product->is_type('variable')) {
                    $attributes = $product->get_attributes();
                    foreach ($attributes as $attribute) {
                        $attribute_name = $attribute->get_name();
                        $attribute_options = $attribute->get_options();
                        ?>
                        <div class="variations-group">
                            <label class="variation-label">
                                <?php echo esc_html(wc_attribute_label($attribute_name)); ?>
                            </label>
                            <div class="variation-options">
                                <?php
                                foreach ($attribute_options as $option) {
                                    echo '<button class="variation-option" data-attribute="' . esc_attr($attribute_name) . '" data-value="' . esc_attr($option) . '">';
                                    echo esc_html($option);
                                    echo '</button>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                
                <!-- Quantity & Add to Cart -->
                <div style="display: flex; gap: 16px; margin-bottom: 32px;">
                    <div class="quantity-input-group">
                        <button type="button" id="qty-minus">−</button>
                        <input type="number" id="product-qty" name="quantity" value="1" min="1">
                        <button type="button" id="qty-plus">+</button>
                    </div>
                    <button class="btn-primary" id="add-to-cart-btn" style="flex: 1;">
                        <?php esc_html_e('Add to Cart', 'woocommerce'); ?>
                    </button>
                </div>
                
                <!-- Trust Badges -->
                <div class="trust-badges">
                    <div class="trust-badge">
                        <div class="trust-badge-icon">🚚</div>
                        <h4><?php esc_html_e('Free Shipping', 'woocommerce'); ?></h4>
                        <p><?php esc_html_e('On orders over $50', 'woocommerce'); ?></p>
                    </div>
                    <div class="trust-badge">
                        <div class="trust-badge-icon">✓</div>
                        <h4><?php esc_html_e('Guarantee', 'woocommerce'); ?></h4>
                        <p><?php esc_html_e('30-day returns', 'woocommerce'); ?></p>
                    </div>
                    <div class="trust-badge">
                        <div class="trust-badge-icon">🔒</div>
                        <h4><?php esc_html_e('Secure', 'woocommerce'); ?></h4>
                        <p><?php esc_html_e('Safe checkout', 'woocommerce'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Description Tabs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 product-tabs">
        <div class="tabs-header">
            <button class="tab-button active" data-tab="description">
                <?php esc_html_e('Description', 'woocommerce'); ?>
            </button>
            <button class="tab-button" data-tab="specifications">
                <?php esc_html_e('Specifications', 'woocommerce'); ?>
            </button>
            <button class="tab-button" data-tab="reviews">
                <?php esc_html_e('Reviews', 'woocommerce'); ?>
            </button>
        </div>
        
        <!-- Description Tab -->
        <div id="description" class="tab-content active product-description">
            <?php echo wp_kses_post($product->get_description() ?: $product->get_short_description()); ?>
        </div>
        
        <!-- Specifications Tab -->
        <div id="specifications" class="tab-content">
            <div class="product-description">
                <?php
                $attributes = $product->get_attributes();
                if (!empty($attributes)) {
                    echo '<table style="width: 100%; border-collapse: collapse;">';
                    foreach ($attributes as $attr) {
                        echo '<tr style="border-bottom: 1px solid var(--border);">';
                        echo '<td style="padding: 12px; font-weight: 600; width: 30%;">' . esc_html(wc_attribute_label($attr->get_name())) . '</td>';
                        echo '<td style="padding: 12px;">' . esc_html(implode(', ', $attr->get_options())) . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>' . esc_html__('No specifications available.', 'woocommerce') . '</p>';
                }
                ?>
            </div>
        </div>
        
        <!-- Reviews Tab -->
        <div id="reviews" class="tab-content reviews-section">
            <?php
            $reviews = $product->get_reviews([
                'approve' => 'approve',
                'number' => 10
            ]);
            
            if (!empty($reviews)) {
                foreach ($reviews as $review) {
                    ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div>
                                <div class="review-author"><?php echo esc_html($review->get_reviewer()); ?></div>
                                <div class="star-rating" style="margin-top: 4px;">
                                    <?php
                                    $rating = $review->get_rating();
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '<span class="star" style="font-size: 14px;">★</span>' : '<span class="star" style="color: #ddd; font-size: 14px;">★</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="review-date"><?php echo esc_html($review->get_date_created()->date('M d, Y')); ?></div>
                        </div>
                        <p class="review-text"><?php echo esc_html($review->get_comment_text()); ?></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p>' . esc_html__('No reviews yet. Be the first to review this product!', 'woocommerce') . '</p>';
            }
            ?>
        </div>
    </div>
    
    <!-- Checkout Form Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="checkout-form-wrapper">
            <h2 style="font-size: 28px; margin-bottom: 32px; color: var(--dark);">
                <?php esc_html_e('Get Your Order', 'woocommerce'); ?>
            </h2>
            
            <form id="product-checkout-form" method="post" action="<?php echo esc_url(wc_get_checkout_url()); ?>">
                
                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: var(--dark);">
                    <?php esc_html_e('Billing Information', 'woocommerce'); ?>
                </h3>
                
                <!-- Name Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-required"><?php esc_html_e('First Name', 'woocommerce'); ?></label>
                        <input type="text" name="billing_first_name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-required"><?php esc_html_e('Last Name', 'woocommerce'); ?></label>
                        <input type="text" name="billing_last_name" required>
                    </div>
                </div>
                
                <!-- Email & Phone -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-required"><?php esc_html_e('Email Address', 'woocommerce'); ?></label>
                        <input type="email" name="billing_email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-required"><?php esc_html_e('Phone Number', 'woocommerce'); ?></label>
                        <input type="tel" name="billing_phone" required>
                    </div>
                </div>
                
                <!-- Country -->
                <div class="form-group">
                    <label class="form-required"><?php esc_html_e('Country', 'woocommerce'); ?></label>
                    <select name="billing_country" required>
                        <option value=""><?php esc_html_e('Select Country', 'woocommerce'); ?></option>
                        <?php
                        $countries = WC()->countries->get_countries();
                        foreach ($countries as $code => $name) {
                            echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Address -->
                <div class="form-group">
                    <label class="form-required"><?php esc_html_e('Street Address', 'woocommerce'); ?></label>
                    <input type="text" name="billing_address_1" required>
                </div>
                
                <!-- City & Postal Code -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-required"><?php esc_html_e('City', 'woocommerce'); ?></label>
                        <input type="text" name="billing_city" required>
                    </div>
                    <div class="form-group">
                        <label class="form-required"><?php esc_html_e('Postal Code', 'woocommerce'); ?></label>
                        <input type="text" name="billing_postcode" required>
                    </div>
                </div>
                
                <!-- State (if needed) -->
                <div class="form-group">
                    <label><?php esc_html_e('State/Province', 'woocommerce'); ?></label>
                    <input type="text" name="billing_state">
                </div>
                
                <!-- Hidden Fields -->
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>">
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 32px;">
                    <?php esc_html_e('Proceed to Payment', 'woocommerce'); ?> →
                </button>
                
                <p style="text-align: center; margin-top: 16px; color: #888; font-size: 14px;">
                    <?php esc_html_e('Secure checkout. Your information is safe with us.', 'woocommerce'); ?>
                </p>
            </form>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php
    $related_ids = $product->get_related_products();
    if (!empty($related_ids)) {
        ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 related-products">
            <h2 style="font-size: 28px; margin-bottom: 8px; color: var(--dark);">
                <?php esc_html_e('You Might Also Like', 'woocommerce'); ?>
            </h2>
            <p style="color: #888; margin-bottom: 32px;">
                <?php esc_html_e('Check out these popular products', 'woocommerce'); ?>
            </p>
            
            <div class="products-grid">
                <?php
                foreach (array_slice($related_ids, 0, 4) as $related_id) {
                    $related_product = wc_get_product($related_id);
                    ?>
                    <div class="product-card shadow-hover">
                        <img src="<?php echo esc_url(wp_get_attachment_image_url($related_product->get_image_id(), 'medium')); ?>" 
                             alt="<?php echo esc_attr($related_product->get_name()); ?>">
                        <div class="product-card-content">
                            <h3 class="product-card-title"><?php echo esc_html($related_product->get_name()); ?></h3>
                            <div class="product-card-price"><?php echo wc_price($related_product->get_price()); ?></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</main>

<!-- JavaScript for Interactivity -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gallery Thumbnails
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const mainImage = document.getElementById('main-image');
    
    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const imageUrl = this.dataset.image;
            mainImage.src = imageUrl;
            
            galleryThumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Quantity Controls
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');
    const qtyInput = document.getElementById('product-qty');
    
    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            const current = parseInt(qtyInput.value) || 1;
            if (current > 1) qtyInput.value = current - 1;
        });
        
        qtyPlus.addEventListener('click', () => {
            const current = parseInt(qtyInput.value) || 1;
            qtyInput.value = current + 1;
        });
    }
    
    // Variation Selection
    const variationButtons = document.querySelectorAll('.variation-option');
    variationButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const attribute = this.dataset.attribute;
            document.querySelectorAll(`[data-attribute="${attribute}"]`).forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // Add to Cart
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const productQty = document.getElementById('product-qty');
    
    if (addToCartBtn && productQty) {
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = '<?php echo esc_js($product_id); ?>';
            const quantity = parseInt(productQty.value) || 1;
            
            fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    nonce: wc_add_to_cart_params.nonce,
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('<?php esc_html_e('Product added to cart!', 'woocommerce'); ?>');
                    window.location.href = wc_add_to_cart_params.cart_url;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            
            document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        });
    });
    
    // Checkout Form Submission
    const checkoutForm = document.getElementById('product-checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const quantity = productQty.value;
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = quantity;
            this.appendChild(quantityInput);
        });
    }
});
</script>

<?php
get_footer();
