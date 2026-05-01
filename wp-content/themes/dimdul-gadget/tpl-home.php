<?php
/**
 * Template Name: Home
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dimdul_Gadget
 */

get_header();
?>

    <main id="primary" class="site-main">

        <!-- HERO GRID -->
        <section class="slide-up d1 py-5">
            <div class="container">
                <div class="hero-card-wrapper">
                    <div class="hero-card">
                        <img src="https://images.unsplash.com/photo-1610438235354-a6ae5528385c?w=500&q=80" alt="Earbuds"
                             class="  aspect-square w-full"/>
                        <div class="img-overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-3 md:p-4 text-white"><p class="text-xs text-gray-300">
                                Earbuds</p>
                            <p style="font-family:'Syne',sans-serif;font-size:clamp(14px,2vw,18px);font-weight:700;">
                                EB525</p></div>
                        <span class="ribbon absolute top-2 left-2">NEW</span>
                    </div>
                    <div class="hero-card">
                        <img src="https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=700&q=80" alt="Speaker"
                             class="  aspect-square w-full"/>
                        <div class="img-overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-4 md:p-6 text-white">
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-300 mb-1">New
                                Arrival</p>
                            <h2 style="font-family:'Syne',sans-serif;font-size:clamp(20px,3vw,32px);font-weight:800;line-height:1.1;">
                                Portable<br/>Speaker</h2>
                            <p class="text-xs text-gray-300 mt-1 mb-3">Portable voice control sound</p>
                            <a class="bg-white text-black text-xs font-bold px-5 py-2 rounded-full hover:bg-gray-100 transition-colors"
                               style="font-family:'Syne',sans-serif;">SHOP NOW →
                            </a>
                        </div>
                    </div>
                    <div class="hero-card">
                        <img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&q=80" alt="iPad"
                             class="  aspect-square w-full"/>
                        <div class="img-overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-3 md:p-4 text-white"><p class="text-xs text-gray-300">
                                Apple</p>
                            <p style="font-family:'Syne',sans-serif;font-size:clamp(14px,2vw,18px);font-weight:700;">
                                iPad
                                Pro</p></div>
                    </div>

                    <div class="hero-card">
                        <img src="https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=500&q=80"
                             alt="Air Fryer"
                             class="  aspect-square w-full"/>
                        <div class="img-overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-3 md:p-4 text-white"><p class="text-xs text-gray-300">Air
                                Fryer</p>
                            <p style="font-family:'Syne',sans-serif;font-size:clamp(14px,2vw,18px);font-weight:700;">
                                NA120</p></div>
                    </div>
                    <div class="hero-card">
                        <img src="https://images.unsplash.com/photo-1620625515032-6ed0c1790c75?w=500&q=80" alt="Watch"
                             class="  aspect-square w-full"/>
                        <div class="img-overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-3 md:p-4 text-white"><p class="text-xs text-gray-300">
                                Fastrack</p>
                            <p style="font-family:'Syne',sans-serif;font-size:clamp(14px,2vw,18px);font-weight:700;">
                                MYND</p></div>
                        <span class="ribbon pulse absolute top-2 left-2">SALE</span>
                    </div>
                </div>

            </div>

        </section>

        <!-- CATEGORIES -->
        <section class="slide-up d2 pt-5 pb-10">
            <div class="container">
                <div class="flex items-center justify-between mb-4">
                    <div><span class="section-tag">BROWSE</span>
                        <h2 style="font-family:'Syne',sans-serif;font-size:clamp(16px,2.5vw,22px);font-weight:700;">
                            Categories</h2></div>
                    <a href="#" class="text-xs font-semibold text-red-600 underline underline-offset-2">See All</a>
                </div>
                <div class="">
                    <?php
                    $args = array(
                            'taxonomy'     => 'product_cat',
                            'orderby'      => 'name',
                            'hide_empty'   => 1, // Set to 1 to hide categories with no products
                    );

                    $all_categories = get_terms($args);

                    if (!empty($all_categories) && !is_wp_error($all_categories)) : ?>
                        <div class="cat-row flex gap-3 overflow-x-auto no-scroll pb-1">
                            <?php foreach ($all_categories as $cat) :
                                // 1. Get the Thumbnail ID
                                $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);

                                // 2. Get the Image URL, fallback to placeholder if empty
                                $image_url = wp_get_attachment_url($thumbnail_id);
                                if (!$image_url) {
                                    $image_url = wc_placeholder_img_src();
                                }

                                // 3. Get the Category Link
                                $cat_link = get_term_link($cat);
                                ?>

                                <a href="<?php echo esc_url($cat_link); ?>" class="group">
                                    <div class="flex flex-col items-center gap-1.5 flex-shrink-0">
                                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl overflow-hidden shadow bg-white p-1 transition-transform group-hover:scale-105">
                                            <img src="<?php echo esc_url($image_url); ?>"
                                                 alt="<?php echo esc_attr($cat->name); ?>"
                                                 class="w-full h-full aspect-square rounded-xl object-cover" />
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 text-center">
                                            <?php echo esc_html($cat->name); ?>
                                        </span>
                                    </div>
                                </a>

                            <?php endforeach; ?>

                        </div>
                    <?php endif; ?>


                </div>
            </div>

        </section>
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


        <!-- DEAL OF THE DAY -->
        <section class="slide-up py-8 d3">
            <div class="container">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <div><span class="section-tag">LIMITED TIME</span>
                        <h2 style="font-family:'Syne',sans-serif;font-size:clamp(16px,2.5vw,22px);font-weight:700;">Deal
                            of
                            the Day!</h2></div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold text-gray-500 mr-1 hidden sm:inline">Ends in</span>
                        <div class="timer-digit" id="h">08</div>
                        <span class="font-bold text-gray-400">:</span>
                        <div class="timer-digit" id="m">42</div>
                        <span class="font-bold text-gray-400">:</span>
                        <div class="timer-digit" id="s">15</div>
                    </div>
                </div>
                <?php echo do_shortcode('[products limit="5" columns="5" on_sale="true"]'); ?>
            </div>

        </section>

        <!-- MEGA SALE BANNER -->
        <section class="slide-up d4">
            <div class="container">
                <div class="mega-sale rounded-2xl overflow-hidden relative" style="min-height:140px;">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=900&q=80" alt=""
                         class="absolute inset-0 w-full h-full  aspect-square opacity-15"/>
                    <div class="relative z-10 flex items-center justify-between px-6 md:px-12 lg:px-20 py-8">
                        <div class="text-white"><p
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Limited
                                Offer</p>
                            <p class="font-black leading-none"
                               style="font-family:'Syne',sans-serif;color:#e63329;font-size:clamp(26px,4vw,48px);">UP
                                TO</p>
                            <p class="font-black" style="font-family:'Syne',sans-serif;font-size:clamp(18px,3vw,36px);">
                                35%
                                OFF</p></div>
                        <div class="text-center"><p class="text-white font-black tracking-tight"
                                                    style="font-family:'Syne',sans-serif;font-size:clamp(22px,4vw,52px);line-height:1;">
                                MEGA<br/>SALE</p>
                            <button class="mt-3 bg-red-600 text-white font-bold px-6 py-2 rounded-full hover:bg-red-700 transition-colors"
                                    style="font-family:'Syne',sans-serif;font-size:clamp(11px,1.4vw,14px);">SHOP NOW
                            </button>
                        </div>
                        <div class="text-right text-white"><p
                                    class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">
                                Special</p>
                            <p class="font-black leading-none"
                               style="font-family:'Syne',sans-serif;color:#f5a623;font-size:clamp(26px,4vw,48px);">UP
                                TO</p>
                            <p class="font-black" style="font-family:'Syne',sans-serif;font-size:clamp(18px,3vw,36px);">
                                45%
                                OFF</p></div>
                    </div>
                    <div class="absolute -top-8 -left-8 w-32 h-32 rounded-full border-2 border-red-600 opacity-20"></div>
                    <div class="absolute -bottom-6 -right-6 w-28 h-28 rounded-full border-2 border-yellow-500 opacity-20"></div>
                </div>
            </div>

        </section>

        <!-- TRENDING NOW -->
        <section class="slide-up py-8 d5">
            <div class="container">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <div><span class="section-tag">HOT</span>
                        <h2 style="font-family:'Syne',sans-serif;font-size:clamp(16px,2.5vw,22px);font-weight:700;">
                            Trending
                            Now</h2></div>
                    <a href="#" class="text-xs font-semibold text-red-600 underline underline-offset-2">See All</a>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    <button class="cat-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-red-600 bg-red-600 text-white active">
                        All
                    </button>
                    <button class="cat-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 text-gray-600 bg-white">
                        Phones
                    </button>
                    <button class="cat-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 text-gray-600 bg-white">
                        Audio
                    </button>
                    <button class="cat-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 text-gray-600 bg-white">
                        Wearables
                    </button>
                    <button class="cat-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 text-gray-600 bg-white">
                        Laptops
                    </button>
                    <button class="cat-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 text-gray-600 bg-white">
                        Cameras
                    </button>
                </div>
                <?php echo do_shortcode('[products limit="10" columns="5" best_selling="true" ]'); ?>
            </div>

        </section>


    </main><!-- #main -->

<?php
//get_sidebar();
get_footer();
