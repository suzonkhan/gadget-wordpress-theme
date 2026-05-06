<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the product object
global $product;
$product = wc_get_product(get_the_ID());

get_header('shop'); ?>
<?php
/**
 * Hook: woocommerce_before_main_content
 *
 * Expected output by default:
 * - Opening wrapper/container HTML
 * - Breadcrumbs
 *
 * Default callbacks:
 * - woocommerce_output_content_wrapper
 * - woocommerce_breadcrumb
 */
do_action('woocommerce_before_main_content');

while (have_posts()) : ?>
    <?php the_post(); ?>

    <?php
    /**
     * Hook: woocommerce_before_single_product
     *
     * Expected output:
     * - Notices, errors, success messages
     *
     * Example:
     * "Product added to cart"
     */
    do_action('woocommerce_before_single_product');

    if (post_password_required()) {
        echo get_the_password_form();
        return;
    }
    ?>

    <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
        <div class="single-product-main-information">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary
             *
             * Expected output by default:
             * - Sale badge
             * - Product images/gallery
             *
             * Default callbacks:
             * - woocommerce_show_product_sale_flash
             * - woocommerce_show_product_images
             */
            do_action('woocommerce_before_single_product_summary');
            ?>

            <div class="summary entry-summary">

                <?php
                /**
                 * Hook: woocommerce_single_product_summary
                 *
                 * Expected output by default:
                 * 1. Product title
                 * 2. Rating
                 * 3. Price
                 * 4. Short description
                 * 5. Add to cart form
                 * 6. Product meta: SKU, category, tags
                 * 7. Sharing
                 *
                 * Default callbacks:
                 * - woocommerce_template_single_title
                 * - woocommerce_template_single_rating
                 * - woocommerce_template_single_price
                 * - woocommerce_template_single_excerpt
                 * - woocommerce_template_single_add_to_cart
                 * - woocommerce_template_single_meta
                 * - woocommerce_template_single_sharing
                 */
                do_action('woocommerce_single_product_summary');
                ?>

            </div>
            <div class="text-center product-page-right-sidebar">
                <img class="mx-auto mb-3" src="<?php echo get_template_directory_uri(); ?>/images/brand.png"
                     alt="Brand Logo">
                <img class="mx-auto mb-3" src="<?php echo get_template_directory_uri(); ?>/images/free-shipping.png"
                     alt="Brand Logo">
                <img class="mx-auto mb-3" src="<?php echo get_template_directory_uri(); ?>/images/warranty.png"
                     alt="Brand Logo">
            </div>

        </div>
        <?php
        // Get the current global product ID dynamically
        $product_id = get_the_ID();

        // Pass the ID into your shortcode
        echo do_shortcode('[product_cta id="' . $product_id . '"]');
        ?>
        <?php
        /**
         * Hook: woocommerce_after_single_product_summary
         *
         * Expected output by default:
         * - Product tabs
         *   - Description
         *   - Additional information
         *   - Reviews
         * - Upsells
         * - Related products
         *
         * Default callbacks:
         * - woocommerce_output_product_data_tabs
         * - woocommerce_upsell_display
         * - woocommerce_output_related_products
         */
        do_action('woocommerce_after_single_product_summary');
        ?>

    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product
     *
     * Expected output:
     * - Usually empty by default
     * - Useful for custom landing-page sections
     */
    do_action('woocommerce_after_single_product');
    ?>

<?php endwhile; ?>

<?php
/**
 * Hook: woocommerce_after_main_content
 *
 * Expected output:
 * - Closing wrapper/container HTML
 *
 * Default callback:
 * - woocommerce_output_content_wrapper_end
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar
 *
 * Expected output:
 * - WooCommerce sidebar if your theme supports it
 *
 * Default callback:
 * - woocommerce_get_sidebar
 */
//do_action( 'woocommerce_sidebar' );?>
<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
