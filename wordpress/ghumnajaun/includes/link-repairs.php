<?php

/** Exact compatibility replacements for retired publisher URLs found in the link audit. */
defined( 'ABSPATH' ) || exit;
function gj_public_link_repairs( $value ) {
	return strtr( $value, array(
		'https://banepamun.gov.np/ne/node/3' => 'https://banepamun.gov.np/local-level-profile/',
		'https://banepamun.gov.np/sites/banepamun.gov.np/files/RoadMap1.pdf' => 'https://www.google.com/maps/search/?api=1&query=Banepa%2C%20Nepal',
		'https://www.bhaktapurmun.gov.np/en/node/4' => 'https://ntb.gov.np/en/bhaktapur',
		'https://www.mccy.gov.sg/-/media/Mccy-Ca/Feature/Resources/Conference-Papers/5th-Asian-Museums-National-Museums-Association-Conference-Publication/ANMA-Publication.pdf' => 'https://www.nara.accu.or.jp/img/thematictraining/2015.pdf',
		'Banepa Municipality map' => 'Banepa map (Google Maps)',
		'बनेपा नगरपालिकाको नक्सा' => 'बनेपाको नक्सा (Google Maps)',
		'Bhaktapur Municipality guide to the squares' => 'Nepal Tourism Board guide to Bhaktapur',
		'भक्तपुर नगरपालिकाले दिएको दरबार क्षेत्रसम्बन्धी जानकारी' => 'नेपाल पर्यटन बोर्डको भक्तपुरसम्बन्धी जानकारी',
		'National Museum conference contribution' => 'National Museum country report (2015)',
		'राष्ट्रिय सङ्ग्रहालयसम्बन्धी सम्मेलनमा प्रस्तुत सामग्री' => 'राष्ट्रिय सङ्ग्रहालयको देशगत प्रतिवेदन (२०१५)',
	) );
}
