/**
 * Product Landing Page JavaScript
 * Handles gallery, variations, and checkout functionality
 */

jQuery(document).ready(function($) {
    
    // Gallery thumbnail functionality
    function initGallery() {
        $('.gallery-thumb').on('click', function() {
            const imageUrl = $(this).data('image');
            const mainImage = $('#main-product-image');
            
            if (imageUrl && mainImage.length) {
                mainImage.attr('src', imageUrl);
                
                // Update active thumbnail
                $('.gallery-thumb').removeClass('active border-blue-500').addClass('border-transparent');
                $(this).addClass('active border-blue-500').removeClass('border-transparent');
            }
        });
    }
    
    // Quantity selector functionality
    function initQuantitySelector() {
        $('#qty-decrease').on('click', function() {
            const input = $('#product-quantity');
            const currentVal = parseInt(input.val()) || 1;
            const minVal = parseInt(input.attr('min')) || 1;
            
            if (currentVal > minVal) {
                input.val(currentVal - 1);
            }
        });
        
        $('#qty-increase').on('click', function() {
            const input = $('#product-quantity');
            const currentVal = parseInt(input.val()) || 1;
            const maxVal = parseInt(input.attr('max')) || 999;
            
            if (currentVal < maxVal) {
                input.val(currentVal + 1);
            }
        });
        
        // Validate quantity input
        $('#product-quantity').on('change', function() {
            const input = $(this);
            const val = parseInt(input.val()) || 1;
            const minVal = parseInt(input.attr('min')) || 1;
            const maxVal = parseInt(input.attr('max')) || 999;
            
            if (val < minVal) {
                input.val(minVal);
            } else if (val > maxVal) {
                input.val(maxVal);
            } else {
                input.val(val);
            }
        });
    }
    
    // Variable product functionality
    function initVariableProduct() {
        $('.variation-select').on('change', function() {
            updateVariation();
        });
    }
    
    function updateVariation() {
        const $form = $('#landing-add-to-cart-form');
        const productId = $form.find('input[name="landing_product_id"]').val();
        
        // Collect all variation attributes
        const variations = {};
        $('.variation-select').each(function() {
            const $select = $(this);
            const attributeName = $select.data('attribute');
            const selectedValue = $select.val();
            
            if (selectedValue) {
                variations[attributeName] = selectedValue;
            }
        });
        
        // Check if all required variations are selected
        const totalVariations = $('.variation-select').length;
        const selectedVariations = Object.keys(variations).length;
        
        if (selectedVariations < totalVariations) {
            // Not all variations selected
            $('.variation-price').html('');
            $('.variation-stock').html('');
            $('#add-to-cart-btn').prop('disabled', true).text('<?php esc_html_e("Select options", "woocommerce"); ?>');
            return;
        }
        
        // Find matching variation
        $.ajax({
            url: landing_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_variation_data',
                product_id: productId,
                variations: variations,
                nonce: landing_ajax.nonce_add_to_cart
            },
            beforeSend: function() {
                $('#add-to-cart-btn').prop('disabled', true).text('<?php esc_html_e("Loading...", "woocommerce"); ?>');
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Update price
                    $('.variation-price').html(data.price);
                    
                    // Update stock status
                    const stockClass = data.is_in_stock ? 'text-green-600' : 'text-red-600';
                    $('.variation-stock').html('<span class="' + stockClass + '">' + data.stock_status + '</span>');
                    
                    // Update add to cart button
                    if (data.is_purchasable && data.is_in_stock) {
                        $('#add-to-cart-btn').prop('disabled', false).text('<?php esc_html_e("Add to Cart", "woocommerce"); ?>');
                        
                        // Update quantity max
                        if (data.max_quantity && data.max_quantity > 0) {
                            $('#product-quantity').attr('max', data.max_quantity);
                        }
                    } else {
                        $('#add-to-cart-btn').prop('disabled', true).text(data.is_in_stock ? '<?php esc_html_e("Not Available", "woocommerce"); ?>' : '<?php esc_html_e("Out of Stock", "woocommerce"); ?>');
                    }
                } else {
                    console.error('Variation data error:', response.data.message);
                    $('.variation-price').html('');
                    $('.variation-stock').html('<span class="text-red-600"><?php esc_html_e("Invalid variation", "woocommerce"); ?></span>');
                    $('#add-to-cart-btn').prop('disabled', true).text('<?php esc_html_e("Not Available", "woocommerce"); ?>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#add-to-cart-btn').prop('disabled', true).text('<?php esc_html_e("Error", "woocommerce"); ?>');
            },
            complete: function() {
                // Reset button text if still disabled after error
                if ($('#add-to-cart-btn').prop('disabled')) {
                    $('#add-to-cart-btn').text('<?php esc_html_e("Select options", "woocommerce"); ?>');
                }
            }
        });
    }
    
        
        
    // Utility functions
    function isValidEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }
    
    function showMessage(message, type) {
        // Remove existing messages
        $('.landing-message').remove();
        
        const messageClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        const messageHtml = '<div class="landing-message ' + messageClass + ' px-4 py-3 rounded border mb-4">' + 
                           '<span>' + message + '</span>' + 
                           '</div>';
        
        // Insert message at the top of the main content
        $('.product-landing-page').prepend(messageHtml);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            $('.landing-message').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
        
        // Scroll to top to show message
        $('html, body').animate({
            scrollTop: $('.product-landing-page').offset().top - 100
        }, 300);
    }
    
    // Form field enhancements
    function initFormEnhancements() {
        // Add floating label effect
        $('input, textarea, select').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            if (!$(this).val()) {
                $(this).parent().removeClass('focused');
            }
        });
        
        // Auto-format phone number
        $('#billing_phone').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 0) {
                // Simple phone formatting - you can customize this
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + '-' + value.slice(3);
                } else {
                    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            $(this).val(value);
        });
        
        // Country/state handling
        $('#billing_country').on('change', function() {
            const country = $(this).val();
            const stateField = $('#billing_state');
            
            // You could implement state/province loading based on country here
            // For now, just show/hide the state field based on country
            if (country === 'US') {
                stateField.closest('div').show();
                stateField.prop('required', true);
            } else {
                stateField.closest('div').hide();
                stateField.prop('required', false);
            }
        });
    }
    
    // Smooth scroll for anchor links
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }
        });
    }
    
    // Initialize all functionality
    function init() {
        initGallery();
        initFormEnhancements();
        initSmoothScroll();
    }
    
    // Run initialization
    init();
    
});
