jQuery(function ($) {
	const $tabsWrapper = $('.woocommerce-tabs');

	if (!$tabsWrapper.length) return;

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
});