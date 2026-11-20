<?php
/** Cards articles. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

foreach ( $args['posts'] as $post ) :

	$post_id = $post['id'];

	if ( is_category() && ! has_category( get_queried_object_id(), $post_id ) ) {
		continue;
	}
	$photo = $args['data']['photos'][$post['photo']] ?? null;
	?>
<div class="col" data-reveal="fade-up">
	<a class="place page-link blog-card" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
		<span class="art"><?php gj_photo_image( $photo, array( 'loading' => 'lazy', 'sizes' => gj_card_image_sizes() ) ); ?></span>
		<span class="info">
			<span class="meta"><?php echo esc_html( wp_strip_all_tags( $post['category'] ) ); ?></span>
			<strong><?php echo esc_html( get_the_title( $post_id ) ); ?></strong>
			<span><?php echo esc_html( get_the_excerpt( $post_id ) ); ?></span>
			<span class="meta"><?php esc_html_e( 'Read guide', 'ghumnajaun' ); ?> ↗</span>
		</span>
	</a>
</div>
<?php endforeach; ?>
