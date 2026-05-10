(function ($) {
	'use strict';

	if (typeof window.themeLandingCheckout === 'undefined') {
		return;
	}

	var config = window.themeLandingCheckout;
	var $doc = $(document);
	var $body = $(document.body);
	var requestLocks = {
		addToCart: false,
		updateQty: false
	};

	function setStatus(type, text) {
		var $status = $('#landing-status-message');

		if (!$status.length) {
			return;
		}

		$status.removeClass('hidden bg-red-50 border-red-200 text-red-700 bg-green-50 border-green-200 text-green-700 bg-blue-50 border-blue-200 text-blue-700 border');

		if (type === 'error') {
			$status.addClass('bg-red-50 border border-red-200 text-red-700');
		} else if (type === 'success') {
			$status.addClass('bg-green-50 border border-green-200 text-green-700');
		} else {
			$status.addClass('bg-blue-50 border border-blue-200 text-blue-700');
		}

		$status.text(text);
	}

	function setButtonLoading($button, loadingText, isLoading) {
		if (!$button.length) {
			return;
		}

		if (isLoading) {
			if (!$button.data('original-text')) {
				$button.data('original-text', $button.text());
			}
			$button.prop('disabled', true).addClass('opacity-50 cursor-not-allowed').text(loadingText);
		} else {
			var originalText = $button.data('original-text') || $button.text();
			$button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').text(originalText);
		}
	}

	function triggerCheckoutRefresh() {
		$body.trigger('wc_fragment_refresh');
		$body.trigger('update_checkout');
	}

	function scrollToCheckout() {
		var $checkout = $('#landing-checkout-wrap');
		if (!$checkout.length || !$checkout.is(':visible')) {
			return;
		}

		$('html, body').animate(
			{
				scrollTop: $checkout.offset().top - 20
			},
			500
		);
	}

	function renderQtyControl(cartItemKey, quantity) {
		var html = '' +
			'<div class="landing-qty-control flex items-center gap-3" data-cart-item-key="' + String(cartItemKey) + '">' +
				'<button type="button" class="landing-qty-btn landing-qty-minus rounded-xl px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100">-</button>' +
				'<input type="number" min="0" step="1" class="landing-qty-input w-24 rounded-xl border border-gray-300 px-3 py-2 text-center" value="' + parseInt(quantity, 10) + '" />' +
				'<button type="button" class="landing-qty-btn landing-qty-plus rounded-xl px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100">+</button>' +
			'</div>';

		$('#landing-quantity-control-target').html(html);
		$('#landing-quantity-wrap').removeClass('hidden');
	}

	function updateQuantity(cartItemKey, quantity, $trigger) {
		if (!cartItemKey || requestLocks.updateQty) {
			return;
		}

		requestLocks.updateQty = true;
		var $buttons = $('.landing-qty-btn');
		$buttons.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

		$.ajax({
			url: config.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'landing_update_qty',
				nonce: config.nonce,
				product_id: config.product_id,
				cart_item_key: cartItemKey,
				quantity: quantity
			}
		})
			.done(function (response) {
				if (!response || !response.success) {
					setStatus('error', response && response.data && response.data.message ? response.data.message : config.strings.qty_error);
					return;
				}

				var data = response.data || {};
				var nextQty = typeof data.quantity !== 'undefined' ? parseInt(data.quantity, 10) : quantity;

				if (data.removed) {
					$('#landing-quantity-wrap').addClass('hidden');
					setStatus('info', data.message || config.strings.removed);
				} else {
					$('.landing-qty-input').val(nextQty);
					setStatus('success', data.message || config.strings.qty_updated);
					if (data.cart_item_key) {
						config.cart_item_key = data.cart_item_key;
					}
				}

				triggerCheckoutRefresh();
			})
			.fail(function () {
				setStatus('error', config.strings.qty_error);
			})
			.always(function () {
				requestLocks.updateQty = false;
				$buttons.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
				if ($trigger && $trigger.length) {
					$trigger.blur();
				}
			});
	}

	function handleVariableAddToCart() {
		$doc.on('submit', 'form.variations_form', function (e) {
			e.preventDefault();

			if (requestLocks.addToCart) {
				return;
			}

			var $form = $(this);
			var variationId = parseInt($form.find('input[name="variation_id"]').val(), 10) || 0;
			var quantity = parseInt($form.find('input.qty').val(), 10) || 1;
			var attributes = {};
			var isValid = true;

			$form.find('.variations select[name^="attribute_"], .variations input[type="radio"][name^="attribute_"]:checked').each(function () {
				var $field = $(this);
				var fieldName = $field.attr('name');
				var fieldValue = $field.val();
				attributes[fieldName] = fieldValue;
			});

			$form.find('.variations select[name^="attribute_"]').each(function () {
				var name = $(this).attr('name');
				if (!attributes[name]) {
					isValid = false;
				}
			});

			if (!variationId || !isValid) {
				setStatus('error', config.strings.select_variation);
				return;
			}

			requestLocks.addToCart = true;
			var $button = $form.find('.single_add_to_cart_button');
			setButtonLoading($button, config.strings.adding, true);

			$.ajax({
				url: config.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'landing_add_to_cart',
					nonce: config.nonce,
					product_id: config.product_id,
					variation_id: variationId,
					quantity: quantity,
					attributes: attributes
				}
			})
				.done(function (response) {
					if (!response || !response.success) {
						setStatus('error', response && response.data && response.data.message ? response.data.message : config.strings.add_error);
						return;
					}

					var data = response.data || {};
					setStatus('success', data.message || config.strings.added);

					if (data.cart_item_key) {
						config.cart_item_key = data.cart_item_key;
						renderQtyControl(data.cart_item_key, data.quantity || quantity);
					}

					$('#landing-checkout-wrap').removeClass('hidden');
					triggerCheckoutRefresh();
					scrollToCheckout();
				})
				.fail(function () {
					setStatus('error', config.strings.add_error);
				})
				.always(function () {
					requestLocks.addToCart = false;
					setButtonLoading($button, config.strings.adding, false);
				});
		});

		$doc.on('found_variation show_variation', 'form.variations_form', function (event, variation) {
			var $form = $(this);
			var $button = $form.find('.single_add_to_cart_button');
			$button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

			if (variation && variation.price_html) {
				setStatus('info', config.strings.variation_ready);
			}
		});

		$doc.on('reset_data hide_variation', 'form.variations_form', function () {
			var $form = $(this);
			var $button = $form.find('.single_add_to_cart_button');
			$button.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
		});
	}

	function handleQuantityControls() {
		$doc.on('click', '.landing-qty-plus', function () {
			var $control = $(this).closest('.landing-qty-control');
			var key = $control.data('cart-item-key') || config.cart_item_key;
			var $input = $control.find('.landing-qty-input');
			var qty = parseInt($input.val(), 10) || 0;
			qty += 1;
			$input.val(qty);
			updateQuantity(key, qty, $(this));
		});

		$doc.on('click', '.landing-qty-minus', function () {
			var $control = $(this).closest('.landing-qty-control');
			var key = $control.data('cart-item-key') || config.cart_item_key;
			var $input = $control.find('.landing-qty-input');
			var qty = parseInt($input.val(), 10) || 0;
			qty = Math.max(0, qty - 1);
			$input.val(qty);
			updateQuantity(key, qty, $(this));
		});

		$doc.on('change', '.landing-qty-input', function () {
			var $control = $(this).closest('.landing-qty-control');
			var key = $control.data('cart-item-key') || config.cart_item_key;
			var qty = parseInt($(this).val(), 10);
			if (isNaN(qty) || qty < 0) {
				qty = 0;
				$(this).val(qty);
			}
			updateQuantity(key, qty, $(this));
		});
	}

	function initSimpleQuantityState() {
		if (!config.is_simple || !config.cart_item_key) {
			return;
		}

		var qty = parseInt(config.initial_qty, 10) || 1;
		$('#landing-quantity-wrap').removeClass('hidden');
		renderQtyControl(config.cart_item_key, qty);
	}

	$(function () {
		handleVariableAddToCart();
		handleQuantityControls();
		initSimpleQuantityState();
	});
})(jQuery);
