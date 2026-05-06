jQuery(function ($) {
	// AJAX Product Search - This should always run regardless of tabs
	const $searchInput = $('.search-bar .search-field');
	let searchTimeout;

	if ($searchInput.length) {
		console.log('Search input found:', $searchInput.length);
		
		// Create search results container
		$searchInput.after('<div id="search-results" class="search-results-dropdown"></div>');
		const $searchResults = $('#search-results');

		// Handle search input
		$searchInput.on('input', function () {
			const query = $(this).val().trim();
			console.log('Search input triggered:', query);
			
			// Clear previous timeout
			clearTimeout(searchTimeout);
			
			// Hide results if query is too short
			if (query.length < 3) {
				$searchResults.hide().empty();
				return;
			}
			
			// Debounce search
			searchTimeout = setTimeout(function () {
				performAjaxSearch(query);
			}, 300);
		});

		// Hide results when clicking outside
		$(document).on('click', function (e) {
			if (!$(e.target).closest('.search-bar').length) {
				$searchResults.hide();
			}
		});

		// Handle search result item click
		$(document).on('click', '.search-result-item', function (e) {
			e.preventDefault();
			window.location.href = $(this).data('url');
		});
	}

	function performAjaxSearch(query) {
		const $searchResults = $('#search-results');
		
		console.log('Performing AJAX search for:', query);
		console.log('AJAX object available:', typeof ajax_object !== 'undefined');
		
		if (typeof ajax_object === 'undefined') {
			console.error('AJAX object not available');
			$searchResults.html('<div class="search-error">AJAX not configured properly</div>').show();
			return;
		}
		
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: {
				action: 'product_search',
				query: query,
				nonce: ajax_object.nonce
			},
			beforeSend: function () {
				$searchResults.html('<div class="search-loading">Searching...</div>').show();
			},
			success: function (response) {
				console.log('AJAX response:', response);
				if (response.success && response.data.html) {
					$searchResults.html(response.data.html).show();
				} else {
					$searchResults.html('<div class="search-no-results">No products found</div>').show();
				}
			},
			error: function (xhr, status, error) {
				console.error('AJAX error:', error);
				$searchResults.html('<div class="search-error">Search failed. Please try again.</div>').show();
			}
		});
	}

	// WooCommerce Tabs functionality - Only run if tabs exist
	const $tabsWrapper = $('.woocommerce-tabs');

	if ($tabsWrapper.length) {

	// Show all panels
	$tabsWrapper.find('.woocommerce-Tabs-panel').show();

	// Disable WooCommerce tab switching behavior
	$tabsWrapper.find('ul.tabs a').off('click');

	// Smooth scroll on tab click
	$tabsWrapper.find('ul.tabs a').on('click', function (e) {
		e.preventDefault();

		const target = $(this).attr('href');
		const $target = $(target);

		if (!$target.length) return;

		// Update active nav item
		$tabsWrapper.find('ul.tabs li').removeClass('active');
		$(this).closest('li').addClass('active');

		$('html, body').animate(
			{
				scrollTop: $target.offset().top - 80
			},
			600
		);
	});

	// Optional: update active tab while scrolling
	$(window).on('scroll', function () {
		let currentSection = '';

		$tabsWrapper.find('.woocommerce-Tabs-panel').each(function () {
			const sectionTop = $(this).offset().top - 120;

			if ($(window).scrollTop() >= sectionTop) {
				currentSection = '#' + $(this).attr('id');
			}
		});

		if (currentSection) {
			$tabsWrapper.find('ul.tabs li').removeClass('active');
			$tabsWrapper
				.find('ul.tabs a[href="' + currentSection + '"]')
				.closest('li')
				.addClass('active');
		}
	});
	} // Close WooCommerce tabs if statement
});