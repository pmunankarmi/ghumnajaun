<?php
/** Editable destination photo grid using the existing Media Library gallery. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
$photos = $args['data']['photos'];
$keys = array_values( array_filter( array_unique( $args['details']['photos'] ?? array() ), fn ( $key ) => isset( $photos[$key] ) ) );

if ( ! $keys ) {
	return;
}
?>
<section aria-labelledby="gj-gallery-title" id="gj-gallery">
	<h2 id="gj-gallery-title"><?php esc_html_e( 'Photo gallery', 'ghumnajaun' ); ?></h2>
	<div class="trip-gallery-grid">
		<?php foreach ( $keys as $key ) :
			$photo = $photos[$key];
			$caption = $photo['caption'] ?? '';
			$title = $caption ?: ( $photo['alt'] ?: get_the_title() ); ?>
		<figure class="trip-gallery-item">
			<a data-gallery-link href="<?php echo esc_url( $photo['original_src'] ?? $photo['src'] ); ?>">
				<?php gj_photo_image( $photo, array( 'class' => 'trip-gallery-photo', 'data-trip-photo' => $key, 'data-photo-title' => $title, 'loading' => 'lazy', 'sizes' => '(max-width:767px) calc(50vw - 26px), (max-width:1199px) calc(33vw - 32px), 460px' ) ); ?>
			</a>
			<?php if ( $caption ) : ?><figcaption><?php echo esc_html( $caption ); ?></figcaption><?php endif; ?>
		</figure>
		<?php endforeach; ?>
	</div>
</section>
