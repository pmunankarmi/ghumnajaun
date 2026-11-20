<?php

/**
 * Small presentation helpers for native WordPress template parts.
 *
 * @package Ghumnajaun
 */
defined( 'ABSPATH' ) || exit;

/** Capture a reusable template when a caller needs its markup as a string. */
function gj_template_html( $slug, $args = array() ) {
	ob_start();
	get_template_part( 'template-parts/' . $slug, null, $args );

	return ob_get_clean();
}

function gj_card_image_sizes() {
	return '(max-width:575px) calc(100vw - 32px), (max-width:991px) calc(50vw - 32px), 360px';
}

/** Output the selected Media Library image, retaining its optimized variants. */
function gj_photo_image( $photo, $attributes = array() ) {
	if ( empty( $photo['src'] ) ) {
		return;
	}
	$attributes = array_merge( array(

		'src' => $photo['src'],

		'alt' => $photo['alt'] ?? '',

		'loading' => 'lazy',

		'decoding' => 'async',

		'width' => $photo['width'] ?? null,

		'height' => $photo['height'] ?? null,

		'srcset' => $photo['srcset'] ?? null,

		'sizes' => '100vw',

	), $attributes );
	echo '<img';

	foreach ( $attributes as $name => $value ) {
		if ( $value === null || $value === false || ! preg_match( '/^[a-z][a-z0-9-]*$/', $name ) ) {
			continue;
		}
		echo ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
	echo '>';
}

function gj_destination_cards_html( $names, $data = null, $distances = null ) {
	$data = $data ?? gj_site_data();
	$places = array();

	foreach ( $data['places'] as $place ) {
		$places[$place[0]] = $place;
	}

	return gj_template_html( 'cards/destinations', array( 'names' => $names, 'places' => $places, 'data' => $data, 'distances' => $distances ) );
}

function gj_article_cards_html( $limit = 0 ) {
	$data = gj_site_data();
	$posts = $limit ? array_slice( $data['blogPosts'], 0, $limit ) : $data['blogPosts'];

	return gj_template_html( 'cards/articles', array( 'posts' => $posts, 'data' => $data ) );
}

function gj_family_destinations() {
	$query = array(
		'post_type' => 'destination',
		'post_status' => 'publish',
		'posts_per_page' => 3,
		'tax_query' => array( array( 'taxonomy' => 'outing', 'field' => 'slug', 'terms' => 'kids' ) )
	);

	if ( function_exists( 'pll_get_term' ) ) {
		$term = get_term_by( 'slug', 'kids', 'outing' );
		$translated = $term ? pll_get_term( $term->term_id ) : 0;

		if ( $translated ) {
			$query['tax_query'][0] = array( 'taxonomy' => 'outing', 'terms' => $translated );
		}
	}

	return array_map( fn ( $post ) => gj_canonical_title( $post->ID ), get_posts( $query ) );
}

function gj_home_heading() {
	$title = gj_option( 'home_title', 'Where shall we go today?' );

	if ( $title === 'Where shall we go today?' && ! gj_is_nepali() ) {
		return 'Where shall we<br>go today?';
	}

	return esc_html( gj_display_label( $title ) );
}

function gj_query_text( $key ) {
	return isset( $_GET[$key] ) && is_string( $_GET[$key] ) ? sanitize_text_field( wp_unslash( $_GET[$key] ) ) : '';
}

/** Build the initial archive using the same content as the interactive filters. */
function gj_destination_listing( $data ) {
	$names = $data['archivePlaces'] ?? array_column( $data['places'], 0 );
	$city = $data['city'] ?? gj_query_text( 'city' );
	$category = $data['category'] ?? gj_query_text( 'category' );
	$search = gj_query_text( 'q' );

	if ( isset( $data['citySightseeing'][$city] ) ) {
		$names = $data['citySightseeing'][$city];
	}

	if ( $category && $category !== 'All' ) {
		$allowed = array();

		foreach ( $data['places'] as $place ) {
			if ( in_array( $place[0], $names, true ) && ( $place[1] === $category || in_array( $category, explode( '|', $place[2] ), true ) ) ) {
				$allowed[] = $place[0];
			}
		}
		$names = $allowed;
	}

	if ( $search ) {
		$terms = preg_split( '/\s+/u', gj_normalize_search( $search ), -1, PREG_SPLIT_NO_EMPTY );
		$matches = array();

		foreach ( $data['places'] as $place ) {
			if ( ! in_array( $place[0], $names, true ) ) {
				continue;
			}
			$labels = array_merge( array( $place[0], $place[1] ), explode( '|', $place[2] ), $data['searchAliases'][$place[0]] ?? array() );

			foreach ( $data['citySightseeing'] as $city_name => $sights ) {
				if ( in_array( $place[0], $sights, true ) ) {
					$labels[] = $city_name;
				}
			}
			$index = gj_normalize_search( implode( ' ', array_merge( $labels, gj_is_nepali() ? array_map( 'gj_translate_label', $labels ) : array() ) ) );
			$found = true;

			foreach ( $terms as $term ) {
				if ( mb_strpos( $index, $term ) === false ) {
					$found = false;
					break;
				}
			}

			if ( $found ) {
				$matches[] = $place[0];
			}
		}
		$names = $matches;
	}
	$origins = gj_starting_points();
	$starting_from = gj_query_text( 'from' );

	if ( ! isset( $origins[$starting_from] ) ) {
		$starting_from = 'Kathmandu Valley';
	}
	$distances = array();

	foreach ( $names as $name ) {
		$point = $data['placePoints'][$name] ?? null;

		if ( $category === 'Picnic Spot' ) {
			$point = $data['destinationGuides'][$name]['picnicPoint'] ?? $point;
		}
		$distances[$name] = gj_distance_km( $origins[$starting_from], $point );
	}

	if ( ! $city ) {
		usort( $names, fn ( $first, $second ) => $distances[$first] <=> $distances[$second] );
	}

	$title = $data['archiveTitle'] ?? $data['resultsTitle'] ?? __( 'Explore nearby', 'ghumnajaun' );

	if ( $search ) {
		$title = sprintf( __( 'Search results for “%s”', 'ghumnajaun' ), $search );
	}

	return array(
		'names' => $names,
		'distances' => $city ? null : $distances,
		'title' => $title,
		'origin' => $city ? __( 'Browse by city', 'ghumnajaun' ) : sprintf( __( 'From %s', 'ghumnajaun' ), gj_display_label( $starting_from ) )
	);
}

function gj_destination_context( $id ) {
	$data = gj_site_data();
	$name = gj_canonical_title( $id );

	return array(

		'id' => $id,

		'data' => $data,

		'name' => $name,

		'details' => $data['tripDetails'][$name] ?? array(),

		'guide' => $data['destinationGuides'][$name] ?? array(),

		'map_url' => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( gj_value( 'address', $id, $name ) ),

	);
}

function gj_related_destinations( $id ) {
	$ids = gj_value( 'related', $id, array() );

	if ( ! $ids ) {
		$ids = get_posts( array(
			'post_type' => 'destination',
			'post_status' => 'publish',
			'posts_per_page' => 3,
			'post__not_in' => array( $id ),
			'fields' => 'ids'
		) );
	}

	return array_map( 'gj_canonical_title', array_slice( $ids, 0, 3 ) );
}

/** Fixed starting-point coordinates are shared by server and browser filters. */
function gj_starting_points() {
	return array(
		'Kathmandu Valley' => array( 27.717, 85.324 ),
		'Kathmandu' => array( 27.717, 85.324 ),
		'Lalitpur' => array( 27.673, 85.325 ),
		'Bhaktapur' => array( 27.672, 85.429 )
	);
}
function gj_normalize_search( $text ) {
	if ( class_exists( 'Normalizer' ) ) {
		$text = Normalizer::normalize( $text, Normalizer::FORM_KC );
	}

	return trim( preg_replace( '/[^\p{L}\p{M}\p{N}]+/u', ' ', mb_strtolower( $text ) ) );
}
function gj_distance_km( $origin, $point ) {
	if ( ! $point ) {
		return INF;
	}
	$latitude = deg2rad( $point[0] - $origin[0] );
	$longitude = deg2rad( $point[1] - $origin[1] );
	$chord = sin( $latitude / 2 ) ** 2 + cos( deg2rad( $origin[0] ) ) * cos( deg2rad( $point[0] ) ) * sin( $longitude / 2 ) ** 2;

	return 6371 * 2 * asin( sqrt( min( 1, $chord ) ) );
}
