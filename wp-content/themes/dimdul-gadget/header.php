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
    <!--    <script src="https://cdn.tailwindcss.com"></script>-->
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
                    <div class="search-bar relative">
                        <form role="search" method="get" class="woocommerce-product-search"
                              action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="search"
                                   id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"
                                   class="search-field w-full rounded-md border border-gray-200 bg-white px-6 py-3 text-sm placeholder:text-gray-400 focus:border-gpOrange focus:outline-none"
                                   placeholder="Search products…" value="<?php echo get_search_query(); ?>" name="s"/>
                            <input type="hidden" name="post_type" value="product"/>
                        </form>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <!-- Account -->

                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                           class="flex gap-2  items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div class=" flex-col gap-[2px] hidden lg:flex">
                                <span class="text-xs mt-1 font-semibold">MY ACCOUNT</span>
                                <span class="text-xs">Login / Create</span>
                            </div>

                        </a>

                        <!-- Cart Summary -->
                        <a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>"
                           title="<?php esc_attr_e('View your shopping cart', 'woocommerce'); ?>">
                            <div class="flex gap-2 items-center text-gray-600 hover:text-vibrant-orange cursor-pointer transition-colors">
                                <div class="relative">
                                    <span class="lg:hidden flex text-xs absolute bg-red-500 text-white rounded-full p-1 w-5 h-5 -top-2 -right-2  items-center justify-center">
                                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                                    </span>

                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>


                                <div class="flex flex-col gap-[2px] hidden lg:flex">
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


        <nav id="site-navigation" class="main-navigation border border-gray-200 border-tb">
            <div class="container">
                <div class="main-navigation-inner">
                    <?php
                    wp_nav_menu(
                            array(
                                    'theme_location' => 'menu-1',
                                    'menu_id' => 'primary-menu',

                            )
                    );
                    ?>
                    <!-- Trigger Button -->
                    <!-- Trigger Button with Animated Hamburger -->
                    <button
                            id="drawer-toggle"
                            class="relative group z-50 p-2 focus:outline-none menu-toggle"
                            aria-controls="primary-menu"
                            aria-expanded="false"
                    >

                        <div class="w-6 h-5 flex flex-col justify-between items-center relative">
                            <!-- Top Bar -->
                            <span id="bar-1"
                                  class="w-full h-0.5 bg-slate-900 rounded-full transform transition-all duration-300 origin-left"></span>
                            <!-- Middle Bar -->
                            <span id="bar-2"
                                  class="w-full h-0.5 bg-slate-900 rounded-full transition-all duration-300"></span>
                            <!-- Bottom Bar -->
                            <span id="bar-3"
                                  class="w-full h-0.5 bg-slate-900 rounded-full transform transition-all duration-300 origin-left"></span>
                        </div>
                    </button>

                </div>

            </div>

        </nav><!-- #site-navigation -->
    </header><!-- #masthead -->
    <!-- Drawer Overlay -->
    <div id="drawer-overlay"
         class="fixed inset-0 bg-black/50 z-40 hidden opacity-0 transition-opacity duration-300"></div>
    <!-- Drawer Menu -->
    <div
            id="mobile-menu-container"
            class="fixed top-0 left-0 h-full w-80 bg-white shadow-2xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out "
    >
        <div class="p-5 flex justify-between items-center border-b border-gray-200">
            <div class="max-w-50">
                <?php
                the_custom_logo();
                ?>
            </div>
            <button id="drawer-close" class="text-gray-500 hover:text-black text-2xl">&times;</button>
        </div>

        <nav id="mobile-menu" class="p-4 mobile-menu">
            <?php
            wp_nav_menu(
                    array(
                            'theme_location' => 'menu-1',
                            'menu_id' => 'mobile-menu-list',
                            'container' => false,
                            'menu_class' => 'flex flex-col gap-4 text-lg font-medium text-gray-700',
                    )
            );
            ?>
        </nav>
    </div>