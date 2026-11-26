<?php
/** Destination activities. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['activities'] as $activity ) : ?>
<li>
	<?php gj_photo_image( $data['photos'][$activity[3] ?? ''] ?? null, array( 'class' => 'trip-stop-photo', 'data-trip-photo' => $activity[3] ?? '', 'loading' => 'lazy', 'sizes' => '80px' ) ); ?>
	<div>
		<h3><?php if ( ! empty( $activity[4] ) ) : ?><a href="<?php echo esc_url( $activity[4] ); ?>"><?php echo wp_kses_post( $activity[0] ); ?></a><?php else : ?><?php echo wp_kses_post( $activity[0] ); ?><?php endif; ?></h3>
		<p><?php echo wp_kses_post( $activity[1] ); ?></p>
		<?php if ( ! empty( $activity[2] ) ) : ?>
		<a
			class="trip-inline-link"
			href="<?php echo esc_url( $activity[2] ); ?>"
			target="_blank"
			rel="noopener"
		>
			<?php esc_html_e( 'View on map', 'ghumnajaun' ); ?> ↗
		</a>
		<?php endif; ?>
		<?php if ( ! empty( $activity[4] ) ) : ?>
		<a class="trip-inline-link" href="<?php echo esc_url( $activity[4] ); ?>"><?php esc_html_e( 'Read destination guide', 'ghumnajaun' ); ?> ↗</a>
		<?php endif; ?>
	</div>
</li>
<?php endforeach; ?>
