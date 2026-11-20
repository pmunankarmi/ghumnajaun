<?php
/** Destination gallery. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['photos'] as $index => $key ) : ?>
<div class="swiper-slide"><?php gj_photo_image( $data['photos'][$key] ?? null, array( 'data-trip-photo' => $key, 'loading' => $index ? 'lazy' : 'eager', 'fetchpriority' => $index ? 'auto' : 'high' ) ); ?></div>
<?php endforeach; ?>
