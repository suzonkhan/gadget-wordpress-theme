<?php
/**
 * Custom WooCommerce Classic Checkout Fields
 * Simplified checkout with Email, Phone, Full Name, and Full Address only
 * 
 * Add this code to your theme's functions.php or use with Code Snippets plugin
 */

// Remove unwanted billing and shipping fields
add_filter( 'woocommerce_checkout_fields', 'custom_simplified_checkout_fields' );
function custom_simplified_checkout_fields( $fields ) {
    
    // Keep only these billing fields: email, phone, first_name (as full name), address_1 (as full address)
    $billing_fields_to_keep = array(
        'billing_email',
        'billing_phone', 
        'billing_first_name',
        'billing_address_1'
    );
    
    // Remove all other billing fields
    foreach ( $fields['billing'] as $key => $field ) {
        if ( ! in_array( $key, $billing_fields_to_keep ) ) {
            unset( $fields['billing'][$key] );
        }
    }
    
    // Remove all shipping fields completely
    unset( $fields['shipping'] );
    
    // Remove order comments
    unset( $fields['order']['order_comments'] );
    
    return $fields;
}

// Relabel the fields
add_filter( 'woocommerce_checkout_fields', 'custom_relabel_checkout_fields' );
function custom_relabel_checkout_fields( $fields ) {
    
    // Relabel billing_first_name to "Full Name"
    if ( isset( $fields['billing']['billing_first_name'] ) ) {
        $fields['billing']['billing_first_name']['label'] = 'Full Name';
        $fields['billing']['billing_first_name']['placeholder'] = 'Enter your full name';
        $fields['billing']['billing_first_name']['required'] = true;
    }
    
    // Relabel billing_address_1 to "Full Address"
    if ( isset( $fields['billing']['billing_address_1'] ) ) {
        $fields['billing']['billing_address_1']['label'] = 'Full Address';
        $fields['billing']['billing_address_1']['placeholder'] = 'Enter your complete address';
        $fields['billing']['billing_address_1']['required'] = true;
    }
    
    // Ensure email and phone are properly labeled
    if ( isset( $fields['billing']['billing_email'] ) ) {
        $fields['billing']['billing_email']['label'] = 'Email Address';
        $fields['billing']['billing_email']['placeholder'] = 'Enter your email address';
        $fields['billing']['billing_email']['required'] = true;
    }
    
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['label'] = 'Phone Number';
        $fields['billing']['billing_phone']['placeholder'] = 'Enter your phone number';
        $fields['billing']['billing_phone']['required'] = true;
    }
    
    return $fields;
}

// Force billing and shipping to be the same
add_action( 'woocommerce_checkout_update_order_review', 'force_same_address_shipping' );
function force_same_address_shipping( $post_data ) {
    // This ensures shipping is always set to be the same as billing
    WC()->customer->set_shipping_to_billing();
}

// Save order data correctly and set required hidden fields
add_action( 'woocommerce_checkout_create_order', 'custom_save_order_data', 10, 2 );
function custom_save_order_data( $order, $data ) {
    
    // Get posted data
    $posted_data = wc_clean( $_POST );
    
    // Save billing first name (full name)
    if ( isset( $posted_data['billing_first_name'] ) ) {
        $order->set_billing_first_name( sanitize_text_field( $posted_data['billing_first_name'] ) );
    }
    
    // Set billing last name as empty
    $order->set_billing_last_name( '' );
    
    // Save billing address 1 (full address)
    if ( isset( $posted_data['billing_address_1'] ) ) {
        $order->set_billing_address_1( sanitize_text_field( $posted_data['billing_address_1'] ) );
    }
    
    // Save billing email
    if ( isset( $posted_data['billing_email'] ) ) {
        $order->set_billing_email( sanitize_email( $posted_data['billing_email'] ) );
    }
    
    // Save billing phone
    if ( isset( $posted_data['billing_phone'] ) ) {
        $order->set_billing_phone( sanitize_text_field( $posted_data['billing_phone'] ) );
    }
    
    // Set required hidden billing fields with default values
    $order->set_billing_country( 'US' ); // Set your default country
    $order->set_billing_city( 'City' ); // Set a default or extract from full address
    $order->set_billing_state( 'CA' ); // Set your default state
    $order->set_billing_postcode( '00000' ); // Set default postal code
    $order->set_billing_company( '' );
    $order->set_billing_address_2( '' );
    
    // Copy billing data to shipping (force same address)
    $order->set_shipping_first_name( sanitize_text_field( $posted_data['billing_first_name'] ) );
    $order->set_shipping_last_name( '' );
    $order->set_shipping_address_1( sanitize_text_field( $posted_data['billing_address_1'] ) );
    $order->set_shipping_company( '' );
    $order->set_shipping_address_2( '' );
    $order->set_shipping_city( 'City' );
    $order->set_shipping_state( 'CA' );
    $order->set_shipping_postcode( '00000' );
    $order->set_shipping_country( 'US' );
    $order->set_shipping_phone( sanitize_text_field( $posted_data['billing_phone'] ) );
    
    // Force shipping to match billing
    $order->set_shipping_address_map_url( $order->get_billing_address_map_url() );
}

// Add validation for required fields
add_action( 'woocommerce_checkout_process', 'custom_validate_checkout_fields' );
function custom_validate_checkout_fields() {
    
    // Validate email
    if ( empty( $_POST['billing_email'] ) ) {
        wc_add_notice( 'Email address is required.', 'error' );
    } elseif ( ! is_email( $_POST['billing_email'] ) ) {
        wc_add_notice( 'Please enter a valid email address.', 'error' );
    }
    
    // Validate phone
    if ( empty( $_POST['billing_phone'] ) ) {
        wc_add_notice( 'Phone number is required.', 'error' );
    }
    
    // Validate full name
    if ( empty( $_POST['billing_first_name'] ) ) {
        wc_add_notice( 'Full name is required.', 'error' );
    }
    
    // Validate full address
    if ( empty( $_POST['billing_address_1'] ) ) {
        wc_add_notice( 'Full address is required.', 'error' );
    }
}

// Remove shipping section from checkout page entirely
add_action( 'template_redirect', 'remove_shipping_section_from_checkout' );
function remove_shipping_section_from_checkout() {
    if ( is_checkout() ) {
        // Remove shipping methods
        remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_shipping', 10 );
        remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review_shipping', 10 );
    }
}

// Hide shipping fields on checkout page
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

// Ensure shipping is always the same as billing in customer data
add_action( 'woocommerce_before_checkout_process', 'sync_shipping_with_billing' );
function sync_shipping_with_billing() {
    if ( WC()->customer ) {
        WC()->customer->set_shipping_to_billing();
    }
}
