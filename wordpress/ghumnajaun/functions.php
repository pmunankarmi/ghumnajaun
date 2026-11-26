<?php

/**
 * Load the theme's features in dependency order.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;

$gj_modules = array(
	'link-repairs', // Compatibility for retired external references.
	'setup',          // Theme supports, menus, assets and logo synchronization.
	'languages',      // English and Nepali routing and translations.
	'readiness',      // Consent, image rights, disabled ads and explicit editorial repair.
	'fields',         // ACF guide fields and theme options.
	'content',        // Public guide data and REST endpoints.
	'content-formatting', // Helpers for links in editor-authored content.
	'site-data', // Build interactive discovery data from WordPress.
	'templates',         // Server-rendered pages and browser enhancement data.
	'internal-links', // Related guides and contextual links.
	'blog',           // Native categories and related article navigation.
	'performance',    // Responsive images and asset delivery.
	'seo',            // Metadata and structured data.
	'contact',        // Contact form processing.
	'destination-update', // Explicit, authenticated import of the reviewed Karya Binayak update.
	'updates',        // Existing theme update integration.
);

foreach ( $gj_modules as $gj_module ) {
	require_once __DIR__ . '/includes/' . $gj_module . '.php';
}
unset( $gj_modules, $gj_module );
