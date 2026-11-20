<?php
/** Destination transport. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

foreach ( $details['transport'] as $index => $transport ) : ?>
<div>
	<h3><?php echo esc_html( gj_display_label( $transport[0] ) ); ?></h3>
	<?php echo wp_kses_post( $details['transportHtml'][$index] ?? '<p>' . $transport[1] . '</p>' );

	foreach ( $details['transportLinks'][$index] ?? array() as $link ) : ?>
	<a href="<?php echo esc_url( $link[1] ); ?>" target="_blank" rel="noopener">
		<?php echo wp_kses_post( $link[0] ); ?> ↗
	</a>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
<div class="trip-text-links" data-trip-travel-links></div>
