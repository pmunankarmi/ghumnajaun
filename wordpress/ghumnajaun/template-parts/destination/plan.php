<?php
/** Destination plan. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['plan'] as $stage ) : ?>
<li>
	<strong><?php echo wp_kses_post( $stage[0] ); ?></strong>
	<span><?php echo wp_kses_post( $stage[1] ); ?></span>
</li>
<?php endforeach; ?>
