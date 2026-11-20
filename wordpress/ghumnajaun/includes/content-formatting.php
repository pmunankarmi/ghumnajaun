<?php

/** Helpers for links in editor-authored content. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
function gj_content_dom( $html ) {
	$doc = new DOMDocument();
	$previous = libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="UTF-8"><div id="gj-fragment">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	return $doc;
}
function gj_content_html( $doc ) {
	$out = '';

	foreach ( $doc->getElementById( 'gj-fragment' )->childNodes as $child ) {
		$out .= $doc->saveHTML( $child );
	}

	return $out;
}
