<?php
/** Destination activities. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['activities'] as $activity ) : ?>
<li>
	<?php gj_photo_image( $data['photos'][$activity[3] ?? ''] ?? null, array( 'class' => 'trip-stop-photo', 'data-trip-photo' => $activity[3] ?? '', 'loading' => 'lazy' ) ); ?>
	<div>
		<h3><?php echo wp_kses_post( $activity[0] ); ?></h3>
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
	</div>
</li>
<?php endforeach; ?>
