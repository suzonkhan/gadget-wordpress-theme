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
<footer  id="colophon" class=" site-footer border-t border-gray-200 py-8">
    <div class="container">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8 mb-6">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </div>
        <div class="border-t border-gray-100 pt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-400">
            <span>© 2026 <?php bloginfo('name'); ?>. All rights reserved.</span>
            <span>Privacy Policy · Terms of Service · Sitemap</span>
        </div>
    </div>

</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
