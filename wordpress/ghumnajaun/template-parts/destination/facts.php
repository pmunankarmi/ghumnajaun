<?php
/** Destination facts. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['facts'] as $fact ) : ?>
<div>
	<dt><?php echo wp_kses_post( gj_display_label( $fact[0] ) ); ?></dt>
	<dd><?php echo wp_kses_post( gj_display_label( $fact[1] ) ); ?></dd>
	<dd><?php echo wp_kses_post( gj_is_nepali() ? gj_translate_html( $fact[2] ) : $fact[2] ); ?></dd>
</div>
<?php endforeach; ?>
<div>
	<dt data-trip-origin><?php esc_html_e( 'From central Kathmandu', 'ghumnajaun' ); ?></dt>
	<dd data-trip-distance></dd>
	<dd><?php esc_html_e( 'Straight-line distance', 'ghumnajaun' ); ?></dd>
</div>
