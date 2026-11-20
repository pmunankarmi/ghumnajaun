<?php
/** Destination meals. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['meals'] ?? array() as $index => $meal ) : ?>
<article class="trip-card">
	<p class="eyebrow"><?php echo wp_kses_post( $meal[0] ); ?></p>
	<h3><?php echo wp_kses_post( $meal[1] ); ?></h3>
	<p><?php echo wp_kses_post( $meal[2] ); ?></p>
	<div class="trip-text-links">
		<?php $links = $details['mealLinks'][$index] ?? ( ! empty( $meal[3] ) ? array( array( $meal[4], $meal[3] ) ) : array() );

	foreach ( $links as $link ) : ?>
		<a href="<?php echo esc_url( $link[1] ); ?>" target="_blank" rel="noopener">
			<?php echo wp_kses_post( $link[0] ); ?> ↗
		</a>
		<?php endforeach; ?>
	</div>
</article>
<?php endforeach; ?>
