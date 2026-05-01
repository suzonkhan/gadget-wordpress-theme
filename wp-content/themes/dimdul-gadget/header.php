<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dimdul_Gadget
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'dimdul-gadget'); ?></a>

    <header id="masthead" class="site-header">
        <!-- ══ DESKTOP TICKER (hidden mobile) ══ -->
        <div class="hidden md:block bg-black text-white text-xs py-1.5 overflow-hidden">
            <div class="ticker-inner">
                <span>🚀 Free Delivery on orders over $99</span><span>|</span>
                <span>Use code <strong class="text-yellow-400">SAVE10</strong></span><span>|</span>
                <span>📦 Same-day dispatch on orders before 2PM</span><span>|</span>
                <span>⭐ Rated 4.9/5 by 50,000+ customers</span><span>|</span>
                <span>🔒 Secure payments &amp; buyer protection</span><span>|</span>
                <span>🚀 Free Delivery on orders over $99</span><span>|</span>
                <span>Use code <strong class="text-yellow-400">SAVE10</strong></span><span>|</span>
                <span>📦 Same-day dispatch on orders before 2PM</span><span>|</span>
                <span>⭐ Rated 4.9/5 by 50,000+ customers</span><span>|</span>
                <span>🔒 Secure payments &amp; buyer protection</span>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <div class="main-header-row">
                    <div class="site-branding">
                        <?php
                        the_custom_logo();
                        ?>
                    </div><!-- .site-branding -->
                    <div class="search-bar">
                        <input type="search" placeholder="Search products…"
                               class="w-full rounded-md border border-gray-200 bg-white px-6 py-3 text-sm placeholder:text-gray-400 focus:border-gpOrange focus:outline-none"/>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <!-- Account -->
                        <div class="flex gap-2  items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div class="flex flex-col gap-[2px]">
                                <span class="text-xs mt-1 font-semibold">MY ACCOUNT</span>
                                <span class="text-xs">Login / Create</span>
                            </div>

                        </div>

                        <!-- Checkout -->
                        <div class="flex gap-2 items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div class="flex flex-col gap-[2px]">
                                <span class="text-xs mt-1 font-semibold">CHECKOUT</span>
                                <span class="text-xs">$0.00</span>
                            </div>
                            <a class="cart-customlocation" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'woocommerce' ); ?>">
                                <div class="flex gap-2 items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
                                            <?php echo WC()->cart->get_cart_total(); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <nav id="site-navigation" class="main-navigation border border-gray-200 border-tb">
<!--            <button class="menu-toggle" aria-controls="primary-menu"-->
<!--                    aria-expanded="false">--><?php //esc_html_e('Primary Menu', 'dimdul-gadget'); ?><!--</button>-->
            <?php
            wp_nav_menu(
                    array(
                            'theme_location' => 'menu-1',
                            'menu_id' => 'primary-menu',
                    )
            );
            ?>
        </nav><!-- #site-navigation -->
    </header><!-- #masthead -->
