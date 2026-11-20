<?php
/** Destination nearby links. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['nearby'] as $nearby ) : ?>
<a class="page-link" href="<?php echo esc_url( $nearby[2] ); ?>"><?php echo wp_kses_post( $nearby[1] ); ?> ↗</a>
<?php endforeach; ?>
