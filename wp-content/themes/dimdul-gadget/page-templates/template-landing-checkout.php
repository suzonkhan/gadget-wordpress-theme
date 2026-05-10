<?php
/**
 * Template Name: Landing Checkout
 * Description: One-page WooCommerce landing checkout with AJAX cart updates.
 *
 * @package Dimdul_Gadget
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-10">
		<?php
		if ( ! class_exists( 'WooCommerce' ) ) :
			?>
			<div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3">
				<?php esc_html_e( 'WooCommerce is required for this landing page.', 'dimdul-gadget' ); ?>
			</div>
			<?php
		else :
			$product_id = function_exists( 'theme_get_landing_product_id' ) ? theme_get_landing_product_id( get_the_ID() ) : 0;
			$product    = function_exists( 'theme_get_landing_product' ) ? theme_get_landing_product( $product_id ) : false;

			if ( ! $product ) :
				?>
				<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl px-4 py-3">
					<?php esc_html_e( 'No valid WooCommerce product is configured for this page. Add a valid product ID to the custom field "landing_product_id".', 'dimdul-gadget' ); ?>
				</div>
				<?php
			else :
				if ( function_exists( 'theme_auto_add_simple_landing_product' ) ) {
					theme_auto_add_simple_landing_product( $product );
				}

				$is_variable       = $product->is_type( 'variable' );
				$landing_item_key  = function_exists( 'theme_get_landing_cart_item_key' ) ? theme_get_landing_cart_item_key( $product->get_id() ) : '';
				$checkout_is_ready = ( ! $is_variable ) || ! empty( $landing_item_key );
				$landing_quantity  = 1;

				if ( ! $is_variable && $landing_item_key && function_exists( 'WC' ) && WC()->cart ) {
					$cart_item = WC()->cart->get_cart_item( $landing_item_key );
					if ( ! empty( $cart_item['quantity'] ) ) {
						$landing_quantity = max( 1, absint( $cart_item['quantity'] ) );
					}
				}
				?>

				<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
					<section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 lg:p-8">
						<div id="landing-status-message" class="hidden mb-4 rounded-xl px-4 py-3 text-sm"></div>

						<?php if ( function_exists( 'theme_render_landing_product_summary' ) ) : ?>
							<?php theme_render_landing_product_summary( $product ); ?>
						<?php endif; ?>

						<?php if ( $is_variable ) : ?>
							<?php if ( function_exists( 'theme_render_landing_variable_form' ) ) : ?>
								<?php theme_render_landing_variable_form( $product ); ?>
							<?php endif; ?>
						<?php else : ?>
							<div class="mt-6">
								<h3 class="text-lg font-semibold text-gray-900 mb-3"><?php esc_html_e( 'Quantity', 'dimdul-gadget' ); ?></h3>
								<?php if ( function_exists( 'theme_render_landing_quantity_control' ) ) : ?>
									<?php theme_render_landing_quantity_control( $landing_item_key, $landing_quantity, true ); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<div id="landing-quantity-wrap" class="<?php echo $is_variable ? 'mt-6 hidden' : 'hidden'; ?>">
							<h3 class="text-lg font-semibold text-gray-900 mb-3"><?php esc_html_e( 'Quantity', 'dimdul-gadget' ); ?></h3>
							<div id="landing-quantity-control-target"></div>
						</div>
					</section>

					<section id="landing-checkout-wrap" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 lg:p-8 <?php echo $checkout_is_ready ? '' : 'hidden'; ?>">
						<?php if ( function_exists( 'theme_render_landing_checkout' ) ) : ?>
							<?php theme_render_landing_checkout(); ?>
						<?php endif; ?>
					</section>
				</div>
				<?php
			endif;
		endif;
		?>
	</div>
</main>

<?php
get_footer();
