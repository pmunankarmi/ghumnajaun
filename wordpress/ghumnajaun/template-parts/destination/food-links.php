<?php
/** Destination food links. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( array( 'Restaurants' => __( 'Find nearby restaurants', 'ghumnajaun' ), 'Hotels' => __( 'Find nearby stays', 'ghumnajaun' ) ) as $kind => $label ) : ?>
<a
	class="page-link"
	href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $kind . ' near ' . $args['name'] ) ); ?>"
	target="_blank"
	rel="noopener"
>
	<?php echo esc_html( $label ); ?> ↗
</a>
<?php endforeach; ?>
