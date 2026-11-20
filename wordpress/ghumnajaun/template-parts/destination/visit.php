<?php
/** Destination visit. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

$data = $args['data'];
$details = $args['details'];
$guide = $args['guide'];

if ( ! empty( $details['visitHtml'] ) ) :
	echo wp_kses_post( $details['visitHtml'] );
else :
	foreach ( $details['visit'] as $section ) : ?>
<div class="trip-card">
	<h3><?php echo esc_html( gj_display_label( $section[0] ) ); ?></h3>
	<?php foreach ( $section[1] as $note ) : ?>
	<div><?php echo wp_kses_post( wpautop( $note ) ); ?></div>
	<?php endforeach; ?>
</div>
<?php endforeach;
endif; ?>
