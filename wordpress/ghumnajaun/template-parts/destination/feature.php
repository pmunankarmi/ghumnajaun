<?php
/** Destination feature. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];
$key = $details['featurePhoto'] ?? $details['photos'][1] ?? '';

if ( isset( $data['photos'][$key] ) ) :
	gj_photo_image( $data['photos'][$key], array( 'loading' => 'lazy', 'sizes' => '(max-width:700px) calc(100vw - 32px), 50vw' ) ); ?>
<figcaption><?php echo esc_html( $details['featureCaption'] ?? $data['photos'][$key]['alt'] ); ?></figcaption>
<?php endif; ?>
