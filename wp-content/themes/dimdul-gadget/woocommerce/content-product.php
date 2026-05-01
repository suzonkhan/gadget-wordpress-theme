<?php
defined('ABSPATH') || exit;
global $product;

// Ensure product is valid
if (empty($product) || !$product->is_visible()) return;
?>

<div <?php wc_product_class('product-card bg-white rounded-2xl shadow-sm overflow-hidden', $product); ?>>
    <div class="relative">
        <a href="<?php the_permalink(); ?>">
            <?php echo $product->get_image('woocommerce_medium', ['class' => 'w-full aspect-square object-cover']); ?>
        </a>
        <?php if ($product->is_on_sale()) : ?>
            <span class="ribbon absolute top-2 left-2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">SALE</span>
        <?php endif; ?>

        <button class="absolute top-2 right-2 bg-white rounded-full w-7 h-7 flex items-center justify-center shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
            </svg>
        </button>
    </div>

    <div class="p-3">
        <p class="text-xs text-gray-400 mb-0.5">
            <?php echo wc_get_product_category_list($product->get_id(), ', ', '', ''); ?>
        </p>

        <p class="text-sm font-semibold mb-2" style="font-family:'Syne',sans-serif;">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </p>

        <div class="flex items-center justify-between mb-2">
            <span class="font-bold" style="font-family:'Syne',sans-serif;">
                <?php echo $product->get_price_html(); ?>
            </span>

            <div class="flex items-center gap-0.5">
                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-xs text-gray-500"><?php echo $product->get_average_rating(); ?></span>
            </div>
        </div>

        <?php woocommerce_template_loop_add_to_cart(); ?>
    </div>
</div>