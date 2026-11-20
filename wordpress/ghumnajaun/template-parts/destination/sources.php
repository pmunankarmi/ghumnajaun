<?php
/** Destination sources. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['sources'] as $source ) : ?>
<a
	class="trip-inline-link"
	href="<?php echo esc_url( $source[1] ); ?>"
	target="_blank"
	rel="noopener"
>
	<?php echo wp_kses_post( $source[0] ); ?> ↗
</a>
<?php endforeach; ?>
