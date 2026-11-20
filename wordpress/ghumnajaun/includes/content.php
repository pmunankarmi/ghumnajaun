<?php
/**
 * Public destination data and REST API endpoints.
 *
 * @package Ghumnajaun
 */
defined( 'ABSPATH' ) || exit;
function gj_destinations( $args = array() ) {
	return new WP_Query( array_merge( array(

		'post_type' => 'destination',

		'post_status' => 'publish',

		'posts_per_page' => 12,

		'ignore_sticky_posts' => true

	), $args ) );
}
function gj_cards( $args = array() ) {
	$query = gj_destinations( $args );
	echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row wp-cards">';
	while ( $query->have_posts() ) {
		$query->the_post();?><div class="col"><a class="place" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?><div class="info"><h3><?php the_title(); ?></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p><span>Explore guide ↗</span></div></a></div><?php }

	echo '</div>';
	wp_reset_postdata();

	return $query;
}
function gj_photo_credit( $id ) {
	$author = gj_value( 'photo_author', $id );
	$source = gj_value( 'photo_source', $id );
	$license = gj_value( 'photo_license', $id );
	echo '<figcaption>';

	if ( $source ) {
		echo '<a href="' . esc_url( $source ) . '" rel="noopener">';
	}

	echo esc_html( trim( $author . ' · ' . $license, ' ·' ) );

	if ( $source ) {
		echo '</a>';
	}

	echo '</figcaption>';
}
function gj_record( $post ) {
	$id = $post->ID;
	$image = get_post_thumbnail_id( $id );

	return array(

		'id' => $id,

		'slug' => $post->post_name,

		'title' => get_the_title( $id ),

		'url' => get_permalink( $id ),

		'summary' => get_the_excerpt( $id ),

		'content' => apply_filters( 'the_content', $post->post_content ),

		'modified' => get_post_modified_time( 'c', true, $id ),

		'language' => function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $id ) : get_locale(),

		'translations' => function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $id ) : array(),

		'image' => array(

			'id' => $image,

			'url' => wp_get_attachment_image_url( $image, 'large' ) ?: null,

			'alt' => get_post_meta( $image, '_wp_attachment_image_alt', true ),

			'author' => gj_value( 'photo_author', $image ),

			'source' => gj_value( 'photo_source', $image ),

			'license' => gj_value( 'photo_license', $image )

		),

		'guide' => function_exists( 'get_fields' ) ? get_fields( $id ) : array()

	);
}

add_action( 'rest_api_init', function () {
	$list = function ( WP_REST_Request $r ) {
		$args = array(

			'posts_per_page' => min( 50, max( 1, absint( $r['per_page'] ?: 12 ) ) ),

			'paged' => max( 1, absint( $r['page'] ?: 1 ) ),

			's' => sanitize_text_field( $r['q'] ?: '' )

		);
		$tax = array();

		foreach ( array( 'city' => 'place_city', 'type' => 'place_type', 'category' => 'outing' ) as $key => $taxonomy ) {
			if ( $r[$key] ) {
				$tax[] = array( 'taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => sanitize_title( $r[$key] ) );
			}
		}

		if ( $tax ) {
			$args['tax_query'] = $tax;
		}

		if ( $r['lang'] && function_exists( 'pll_languages_list' ) ) {
			if ( ! in_array( $r['lang'], pll_languages_list( array( 'fields' => 'slug' ) ), true ) ) {
				return new WP_Error( 'invalid_language', 'Unknown language', array( 'status' => 400 ) );
			}

			$args['lang'] = sanitize_key( $r['lang'] );
		}

		$q = gj_destinations( $args );
		$response = rest_ensure_response( array_map( 'gj_record', $q->posts ) );
		$response->header( 'X-WP-Total', $q->found_posts );
		$response->header( 'X-WP-TotalPages', $q->max_num_pages );

		return $response;
	};

	foreach ( array( '/destinations', '/search' ) as $route ) {
		register_rest_route( 'ghumnajaun/v1', $route, array( 'methods' => 'GET', 'permission_callback' => '__return_true', 'callback' => $list ) );
	}

	register_rest_route( 'ghumnajaun/v1', '/destinations/(?P<id>\d+)', array(

		'methods' => 'GET',

		'permission_callback' => '__return_true',

		'callback' => function ( $r ) {
			$p = get_post( absint( $r['id'] ) );

			return $p && $p->post_type === 'destination' && $p->post_status === 'publish' ? gj_record( $p ) : new WP_Error( 'not_found', 'Destination not found', array( 'status' => 404 ) );
		}

	) );
	register_rest_route( 'ghumnajaun/v1', '/filters', array(

		'methods' => 'GET',

		'permission_callback' => '__return_true',

		'callback' => function () {
			$out = array();

			foreach ( array( 'place_city', 'place_type', 'outing' ) as $t ) {
				$out[$t] = get_terms( array( 'taxonomy' => $t, 'hide_empty' => true ) );
			}

			return $out;
		}

	) );
} );
