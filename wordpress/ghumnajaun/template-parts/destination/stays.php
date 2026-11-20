<?php
/** Destination stays. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['stays'] ?? array() as $stay ) : ?>
<li>
	<strong><?php echo wp_kses_post( $stay[0] ); ?></strong>
	<span><?php echo wp_kses_post( $stay[1] ); ?></span>
	<a href="<?php echo esc_url( $stay[2] ); ?>" target="_blank" rel="noopener">
		<?php echo wp_kses_post( $stay[3] ?? __( 'Hotel website', 'ghumnajaun' ) ); ?> ↗
	</a>
</li>
<?php endforeach; ?>
