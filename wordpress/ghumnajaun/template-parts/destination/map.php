<?php
/** Destination map. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide']; ?>
<a
	class="action trip-map-link"
	data-trip-map-link
	href="<?php echo esc_url( $args['map_url'] ); ?>"
	target="_blank"
	rel="noopener"
>
	<?php esc_html_e( 'Open location in Google Maps', 'ghumnajaun' ); ?> ↗
</a>
