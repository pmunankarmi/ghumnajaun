<?php
/** Home cities. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

foreach ( $args['data']['citySightseeing'] as $city => $destinations ) :

	$photo = $args['data']['photos'][$destinations[0] ?? ''] ?? null;

	if ( ! $photo ) {
		continue;
	}
	$url = $args['data']['collectionUrls']['city'][$city] ?? add_query_arg( 'city', $city, gj_home_url( '/explore/' ) );
	?>
<div class="col" data-reveal="fade-up">
	<a
		class="place city-card page-link"
		data-city="<?php echo esc_attr( $city ); ?>"
		href="<?php echo esc_url( $url ); ?>"
	>
		<span class="art"><?php gj_photo_image( $photo, array( 'alt' => '', 'loading' => 'lazy', 'sizes' => gj_card_image_sizes() ) ); ?></span>
		<span class="info">
			<strong><?php echo esc_html( gj_display_label( $city ) ); ?></strong>
			<span class="card-foot">
				<span><?php echo esc_html( gj_display_label( count( $destinations ) . ' places to see' ) ); ?></span>
				<span aria-hidden="true">↗</span>
			</span>
		</span>
	</a>
</div>
<?php endforeach; ?>
