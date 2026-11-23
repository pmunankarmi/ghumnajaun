<?php
/** Destination gallery. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

if ( empty( $details['photos'] ) ) {
	echo '<div class="swiper-slide"><div class="trip-photo-placeholder" role="img" aria-label="' . esc_attr( gj_is_nepali() ? 'गन्तव्यको तस्बिर उपलब्ध छैन' : 'Destination photo unavailable' ) . '" style="width:100%;height:100%;min-height:22rem;background:linear-gradient(135deg,#203e34,#677c67)"></div></div>';
}
foreach ( $details['photos'] as $index => $key ) : ?>
<div class="swiper-slide"><?php gj_photo_image( $data['photos'][$key] ?? null, array( 'data-trip-photo' => $key, 'loading' => $index ? 'lazy' : 'eager', 'fetchpriority' => $index ? 'auto' : 'high' ) ); ?></div>
<?php endforeach; ?>
