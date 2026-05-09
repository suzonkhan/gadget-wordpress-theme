<?php
/**
 * Dimdul Gadget functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dimdul_Gadget
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.1' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dimdul_gadget_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Dimdul Gadget, use a find and replace
		* to change 'dimdul-gadget' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'dimdul-gadget', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'dimdul-gadget' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'dimdul_gadget_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'dimdul_gadget_setup' );

add_filter('use_block_editor_for_post', '__return_false', 10);
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dimdul_gadget_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dimdul_gadget_content_width', 640 );
}
add_action( 'after_setup_theme', 'dimdul_gadget_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dimdul_gadget_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'dimdul-gadget' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'dimdul-gadget' ),
			'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'dimdul_gadget_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dimdul_gadget_scripts() {
	wp_enqueue_style( 'dimdul-gadget-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_style( 'dimdul-tailwind-style', get_template_directory_uri(). '/css/tailwind.css', array(), _S_VERSION);
	wp_enqueue_script( 'dimdul-gadget-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), _S_VERSION, true );

	// Localize script for AJAX
	wp_localize_script( 'dimdul-gadget-navigation', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'product_search_nonce' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dimdul_gadget_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

// Add this line to your theme's functions.php
//require_once get_template_directory() . '/inc/custom-checkout-fields.php';

/**
 * AJAX Product Search Handler
 */
function dimdul_gadget_product_search() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'product_search_nonce')) {
        wp_die('Security check failed');
    }

    // Get search query
    $query = sanitize_text_field($_POST['query']);
    
    if (empty($query)) {
        wp_send_json_error('No search query provided');
    }

    // Search products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        's' => $query,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_sku',
                'value' => $query,
                'compare' => 'LIKE'
            )
        )
    );

    $products = new WP_Query($args);
    
    $html = '';
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product_url = get_permalink();
            $product_price = $product->get_price_html();
            $product_image = get_the_post_thumbnail_url($product_id, 'thumbnail') ?: wc_placeholder_img_src();
            
            $html .= '<div class="search-result-item" data-url="' . esc_url($product_url) . '">';
            $html .= '<div class="search-result-image">';
            $html .= '<img src="' . esc_url($product_image) . '" alt="' . esc_attr($product_title) . '">';
            $html .= '</div>';
            $html .= '<div class="search-result-content">';
            $html .= '<h4 class="search-result-title">' . esc_html($product_title) . '</h4>';
            $html .= '<div class="search-result-price">' . $product_price . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html = '<div class="search-no-results">No products found</div>';
    }
    
    wp_reset_postdata();
    
    wp_send_json_success(array('html' => $html));
}

add_action('wp_ajax_product_search', 'dimdul_gadget_product_search');
add_action('wp_ajax_nopriv_product_search', 'dimdul_gadget_product_search');
