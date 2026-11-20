<?php
/** Cards destinations. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

foreach ( $args['names'] as $name ) :

	$place = $args['places'][$name] ?? null;

	if ( ! $place || empty( $args['data']['urls'][$name] ) ) {
		continue;
	}
	$photo = $args['data']['photos'][$name] ?? null;
	?>
<div class="col" data-reveal="fade-up">
	<a
		class="place page-link"
		href="<?php echo esc_url( $args['data']['urls'][$name] ); ?>"
		data-place="<?php echo esc_attr( $name ); ?>"
	>
		<span class="art"><?php gj_photo_image( $photo, array( 'loading' => 'lazy', 'sizes' => gj_card_image_sizes() ) ); ?></span>
		<span class="info">
			<span class="meta"><?php echo esc_html( gj_display_label( $place[1] ) ); ?></span>
			<strong><?php echo esc_html( gj_display_label( $name ) ); ?></strong>
			<?php if ( is_array( $args['distances'] ) ) : ?>
			<span class="place-distance"><?php
			$distance = $args['distances'][$name];
				echo esc_html( is_finite( $distance ) ? ( $distance < 1 ? __( 'Under 1 km away', 'ghumnajaun' ) : sprintf( __( '≈ %d km away', 'ghumnajaun' ), round( $distance ) ) ) : __( 'Distance unavailable', 'ghumnajaun' ) );
				?></span>
			<?php endif; ?>
			<span class="card-foot">
				<?php echo esc_html( gj_display_label( implode( ' · ', array_slice( explode( '|', $place[2] ), 0, 2 ) ) ) ); ?>
				<span aria-hidden="true">↗</span>
			</span>
		</span>
	</a>
</div>
<?php endforeach; ?>
