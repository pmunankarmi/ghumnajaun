<?php
/** Destination history source. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

if ( ! empty( $guide['history'] ) ) : ?>
<a href="<?php echo esc_url( $guide['history'][2] ); ?>" target="_blank" rel="noopener">
	<?php echo esc_html( $guide['history'][1] ); ?>
</a>
<?php endif; ?>
