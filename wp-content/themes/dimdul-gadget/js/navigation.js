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


    // Select the tab links
    $('.wc-tabs li a').on('click', function (e) {

        // Only proceed if it is a genuine user click (has an originalEvent)
        // This prevents the "auto-click" on page load from triggering the scroll
        if (e.originalEvent !== undefined) {

            var target = $(this).attr('href');

            // Verify the target is an ID on the current page
            if (target && target.indexOf('#') === 0 && $(target).length) {
                e.preventDefault();

                $('html, body').stop().animate({
                    scrollTop: $(target).offset().top - 120 // Offset for your header
                }, 600);
            }
        }
    });
    // Fixed Header
    var lastScrollTop = 0;
    var delta = 5; // Minimum scroll amount to trigger
    var navbarHeight = $('.site-header').outerHeight();

    $(window).scroll(function (event) {
        var st = $(this).scrollTop();

        // Make sure they've scrolled more than delta
        if (Math.abs(lastScrollTop - st) <= delta) return;

        // If scrolling down and past the navbar, add 'nav-up'
        if (st > lastScrollTop && st > navbarHeight) {
            $('.site-header').removeClass('scroll-down').addClass('scroll-up');
        } else {
            // If scrolling up
            if (st + $(window).height() < $(document).height()) {
                $('.site-header').removeClass('scroll-up').addClass('scroll-down');
            }
        }

        lastScrollTop = st;
    });

    // Fixed CTA
    $(window).on('scroll', function() {
        var stickyPoint = $('.product-cta-wrapper').parent().offset().top + $('.product-cta-wrapper').outerHeight();

        if ($(window).scrollTop() > stickyPoint) {
            $('.product-cta-wrapper').addClass('is-sticky');
        } else {
            $('.product-cta-wrapper').removeClass('is-sticky');
        }
    });



});