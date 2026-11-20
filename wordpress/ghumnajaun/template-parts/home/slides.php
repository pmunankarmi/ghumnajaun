<?php
/** Home slides. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

foreach ( $args['data']['bannerPlaces'] as $index => $place ) :
	$photo = $args['data']['bannerPhotos'][$place] ?? $args['data']['photos'][$place] ?? null; ?>
<div class="swiper-slide"><?php gj_photo_image( $photo, array( 'loading' => $index ? 'lazy' : 'eager', 'fetchpriority' => $index ? 'auto' : 'high' ) ); ?></div>
<?php endforeach; ?>
