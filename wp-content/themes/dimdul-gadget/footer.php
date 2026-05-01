<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dimdul_Gadget
 */

?>
<!-- FOOTER (tablet/desktop) -->
<footer  id="colophon" class=" site-footer hidden md:block border-t border-gray-200 pt-8">
    <div class="container">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-6">
            <div>
                <div class="flex items-center gap-1 mb-3">
                    <?php
                    the_custom_logo();
                    ?>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed">Your one-stop digital home for the latest
                    electronics at unbeatable prices.</p></div>
            <div><h4 class="font-bold mb-3" style="font-family:'Syne',sans-serif;">Quick Links</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Home</li>
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Shop</li>
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Deals</li>
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Blog</li>
                </ul>
            </div>
            <div><h4 class="font-bold mb-3" style="font-family:'Syne',sans-serif;">Customer Service</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Contact Us</li>
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Returns</li>
                    <li class="hover:text-red-600 cursor-pointer transition-colors">Shipping Info</li>
                    <li class="hover:text-red-600 cursor-pointer transition-colors">FAQ</li>
                </ul>
            </div>
            <div><h4 class="font-bold mb-3" style="font-family:'Syne',sans-serif;">Newsletter</h4>
                <p class="text-sm text-gray-500 mb-3">Get deals straight to your inbox.</p>
                <div class="flex gap-2"><input type="email" placeholder="your@email.com"
                                               class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-red-400 min-w-0"/>
                    <button class="bg-red-600 text-white px-3 py-2 rounded-xl font-bold hover:bg-red-700 transition-colors text-sm">
                        →
                    </button>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-400">
            <span>© 2026 AVONE Store. All rights reserved.</span>
            <span>Privacy Policy · Terms of Service · Sitemap</span>
        </div>
    </div>

</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
