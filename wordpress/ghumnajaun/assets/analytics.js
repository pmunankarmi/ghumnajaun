/* Business actions only; Site Kit owns tag loading and consent handling. */
(() => {
	const root = document.getElementById('gj');
	if (!root || root.dataset.analyticsBound === 'true') return;
	root.dataset.analyticsBound = 'true';
	const measurementId = 'G-6L88WJ5X6E';
	const topics = {
		'Suggest a place': 'suggest_place',
		'Update a guide': 'guide_update',
		'Listings and collaborations': 'listing_collaboration',
		'General enquiry': 'general_enquiry',
	};
	function track(name, parameters) {
		// Do not create a tag or bypass Site Kit's tracking exclusions.
		if (typeof window.gtag !== 'function') return;
		try {
			window.gtag('event', name, {
				send_to: measurementId,
				page_language: document.documentElement.lang.split('-')[0],
				...parameters,
			});
		} catch {
			// Analytics must never interrupt navigation or a successful enquiry.
		}
	}
	root.addEventListener('click', (event) => {
		const link = event.target.closest?.('a[data-trip-directions]');
		if (!link || !root.contains(link)) return;
		track('get_directions', {
			destination_name: window.GJ?.currentPlace || '',
			button_location: link.closest('.trip-mobile-bar') ? 'mobile_bar' : 'guide_header',
		});
	});
	root.addEventListener('gj:contact-success', (event) => {
		if (event.target.id !== 'gj-contact-form') return;
		const topic = topics[event.detail?.topic];
		if (!topic) return;
		track('contact_submit', { form_id: 'gj-contact-form', contact_topic: topic });
	});
})();
