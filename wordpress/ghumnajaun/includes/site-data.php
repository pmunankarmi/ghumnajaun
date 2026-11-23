<?php

/** Build discovery data from WordPress posts, terms, Media Library and ACF. @package Ghumnajaun */

defined( 'ABSPATH' ) || exit;
/** Format editable transport copy without allowing HTML from plain text fields. */
function gj_transport_html( $text ) {
	$paragraphs = preg_split( '/\n\s*\n/u', trim( (string) $text ), -1, PREG_SPLIT_NO_EMPTY );
	$time = '';
	$fare = '';
	$body = array();

	foreach ( $paragraphs as $i => $paragraph ) {
		if ( $i === 0 && preg_match( '/^(Approximate[^:]+|[^:]*अनुमानित[^:]*):\s*(.+)$/us', $paragraph, $match ) ) {
			$time = '<p class="trip-transport-time"><strong>' . esc_html( $match[1] ) . ':</strong> ' . esc_html( $match[2] ) . '</p>';
			continue;
		}

		if ( preg_match( '/(Approximate (?:taxi )?budget from [^:]+|(?:मध्य काठमाडौं[^:]*|रत्नपार्क वा एनएसीबाट) अनुमानित (?:ट्याक्सी )?बजेट):\s*([^।]+।|[^.]+\.)/u', $paragraph, $match ) ) {
			$fare = '<p class="trip-transport-fare"><strong>' . esc_html( str_contains( $match[1], 'अनुमानित' ) ? 'अनुमानित भाडा:' : 'Approx. fare:' ) . '</strong> ' . esc_html( $match[2] ) . '</p>';
			$paragraph = trim( str_replace( $match[0], '', $paragraph ) );
		}

		if ( preg_match( '/^(Bus route from central Kathmandu|By car\/taxi|Suggested circuit|मध्य काठमाडौंबाट बसको रुट|कार वा ट्याक्सीको बाटो|सुझाव गरिएको परिक्रमा):\s*([^।.]+[।.])\s*(.*)$/us', $paragraph, $match ) ) {
			$body[] = '<p class="trip-transport-route"><strong>' . esc_html( $match[1] ) . ':</strong> ' . esc_html( $match[2] ) . '</p>';
			$paragraph = trim( $match[3] );
		}

		if ( $paragraph !== '' ) {
			$body[] = '<p>' . esc_html( $paragraph ) . '</p>';
		}
	}

	return $time . $fare . implode( '', $body );
}
/** Resolve an imported prototype self-link to this guide's language-specific URL. */
function gj_destination_fact_url( $url, $id ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	$path = wp_parse_url( $url, PHP_URL_PATH );

	if ( ! in_array( $host, array( '127.0.0.1', 'localhost' ), true ) || $path !== '/destination.html' ) {
		return $url;
	}

	parse_str( wp_parse_url( $url, PHP_URL_QUERY ) ?: '', $query );

	if ( ( $query['place'] ?? '' ) !== gj_canonical_title( $id ) ) {
		return $url;
	}

	$fragment = wp_parse_url( $url, PHP_URL_FRAGMENT );

	return get_permalink( $id ) . ( $fragment ? '#' . $fragment : '' );
}
function gj_current_view() {
	if ( is_front_page() ) {
		return 'home';
	}

	if ( is_singular( 'destination' ) ) {
		return 'detail';
	}

	if ( is_post_type_archive( 'destination' ) || is_tax( array( 'outing', 'place_city', 'place_type' ) ) ) {
		return 'results';
	}

	if ( is_home() ) {
		return 'blog';
	}

	if ( is_singular( 'post' ) ) {
		return 'post';
	}

	if ( is_page( 'about' ) ) {
		return 'about';
	}

	if ( is_page( 'contact' ) ) {
		return 'contact';
	}

	return 'page';
}
function gj_site_data() {
	static $out;

	if ( $out ) {
		return $out;
	}

	$out = array(
		'origins' => gj_starting_points(),
		'bannerPhotos' => array(),
		'photos' => array(),
		'places' => array(),
		'destinationGuides' => array(),
		'destinationGalleries' => array(),
		'tripDetails' => array(),
		'placePoints' => array(),
		'dayPlans' => array(),
		'citySightseeing' => array(),
		'blogPosts' => array(),
		'urls' => array(),
		'postUrls' => array(),
		'pages' => array(),
		'featured' => array(),
		'bannerPlaces' => array()
	);

	foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1 ) ) as $image ) {
		if ( ! gj_photo_publishable( $image->ID ) ) {
			continue;
		}
		$photo = array(
			'attachment_id' => $image->ID,
			'src' => wp_get_attachment_image_url( $image->ID, 'large' ),
			'alt' => get_post_meta( $image->ID, '_wp_attachment_image_alt', true ),
			'author' => gj_value( 'photo_author', $image->ID ),
			'source' => gj_value( 'photo_source', $image->ID ),
			'license' => gj_value( 'photo_license', $image->ID )
		);
		$photo['original_src'] = $photo['src'];
		$photo = array_merge( $photo, gj_photo_sizes( $image->ID ) );
		$out['photos']['media-' . $image->ID] = $photo;
		$key = get_post_meta( $image->ID, '_gj_photo_key', true );

		if ( $key ) {
			$out['photos'][$key] = $photo;
		}

		if ( str_starts_with( (string) $key, 'Banner: ' ) ) {
			$out['bannerPhotos'][substr( $key, 8 )] = $photo;
		}
	}

	$names = array();

	foreach ( get_posts( array(
		'post_type' => 'destination',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'menu_order title',
		'order' => 'ASC'
	) ) as $p ) {
		$id = $p->ID;
		$name = gj_canonical_title( $id );
		$names[$id] = $name;

		if ( function_exists( 'pll_get_post' ) ) {
			$english = pll_get_post( $id, 'en' );

			if ( $english ) {
				$names[$english] = $name;
			}
		}
		$f = function_exists( 'get_fields' ) ? ( get_fields( $id ) ?: array() ) : array();

		foreach ( array( 'gallery', 'stops', 'facts', 'sources', 'day_plan', 'dining_stays', 'related', 'guide_links', 'nearby_stops' ) as $key ) {
			if ( ! is_array( $f[$key] ?? null ) ) {
				$f[$key] = array();
			}
		}
		$tags = gj_canonical_terms( $id, 'outing' );
		$types = gj_canonical_terms( $id, 'place_type' );
		$address = $f['address'] ?? '';
		$type = gj_value( 'card_label', $id, implode( ' · ', $types ) );

		$out['places'][] = array( $name, $type, implode( '|', $tags ) );
		$out['urls'][$name] = get_permalink( $id );
		$cities = gj_canonical_terms( $id, 'place_city' );
		// Match both linked titles so a search survives switching languages.
		$search_labels = array_merge( array( $name, get_the_title( $id ), $type ), $tags, $cities );

		if ( function_exists( 'pll_get_post' ) ) {
			foreach ( array( 'en', 'ne' ) as $search_language ) {
				$translated_id = pll_get_post( $id, $search_language );

				if ( $translated_id ) {
					$search_labels[] = get_the_title( $translated_id );
				}
			}
		}
		$out['searchAliases'][$name] = array_values( array_unique( array_merge( $search_labels, array_map( 'gj_translate_label', $search_labels ) ) ) );

		foreach ( $cities as $city ) {
			$out['citySightseeing'][$city][] = $name;
		}

		if ( is_numeric( $f['latitude'] ?? null ) && is_numeric( $f['longitude'] ?? null ) ) {
			$out['placePoints'][$name] = array( (float) $f['latitude'], (float) $f['longitude'] );
		}

		$cover = get_post_thumbnail_id( $id );

		if ( isset( $out['photos']['media-' . $cover] ) ) {
			$out['photos'][$name] = $out['photos']['media-' . $cover];
		}

		$gallery = array_values( array_filter( array_map( fn ( $image ) => 'media-' . $image, $f['gallery'] ?? array() ), fn ( $key ) => isset( $out['photos'][$key] ) ) );

		if ( ! $gallery && $cover && isset( $out['photos']['media-' . $cover] ) ) {
			$gallery = array( 'media-' . $cover );
		}
		$out['destinationGalleries'][$name] = $gallery;
		$summary = wp_strip_all_tags( apply_filters( 'the_content', $p->post_content ) );
		$stops = array();
		$activities = array();

		foreach ( $f['stops'] ?? array() as $s ) {
			$photo = ! empty( $s['stop_image'] ) ? 'media-' . $s['stop_image'] : null;
			$stops[] = array( $s['stop_title'], $s['stop_description'], $photo );
			$activities[] = array(
				esc_html( $s['stop_title'] ),
				esc_html( $s['stop_description'] ),
				! empty( $s['stop_map'] ) ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $s['stop_map'] ) : null,
				$photo
			);
		}

		$guide = array( 'summary' => $summary, 'stops' => $stops, 'bus' => $f['bus'] ?? '', 'transport' => $f['car'] ?? '' );

		if ( ! empty( $f['history'] ) ) {
			$history = wp_strip_all_tags( $f['history'] );

			if ( preg_match( '~<p[^>]*>(.*?)</p>~s', $f['history'], $historyMatch ) ) {
				$history = wp_strip_all_tags( $historyMatch[1] );
			}
			$sources = $f['sources'] ?? array();
			$guide['history'] = array(
				$history,
				( $f['history_source_title'] ?? '' ) ?: ( $sources[0]['source_title'] ?? 'Source' ),
				( $f['history_source_url'] ?? '' ) ?: ( $sources[0]['source_url'] ?? gj_home_url( '/photo-credits/' ) )
			);
		}
		$out['destinationGuides'][$name] = $guide;
		$facts = array();

		foreach ( $f['facts'] ?? array() as $fact ) {
			$noteUrl = gj_destination_fact_url( $fact['fact_note_url'] ?? '', $id );
			$noteTarget = str_starts_with( $noteUrl, get_permalink( $id ) . '#' ) ? '' : ' target="_blank" rel="noopener"';
			$facts[] = array(
				esc_html( $fact['fact_label'] ),
				esc_html( $fact['fact_value'] ),
				$noteUrl ? '<a href="' . esc_url( $noteUrl ) . '"' . $noteTarget . '>' . esc_html( $fact['fact_note'] ) . '</a>' : esc_html( $fact['fact_note'] )
			);
		}

		if ( ! $facts ) {
			$facts = array(
				array( 'Outing', esc_html( $type ), '' ),
				array( 'Visiting', 'Check local access', '' ),
				array( 'Visit plan', 'See places to visit', '' )
			);
		}

		$sources = array();

		foreach ( $f['sources'] ?? array() as $s ) {
			$sources[] = array( esc_html( $s['source_title'] ), esc_url_raw( $s['source_url'] ) );
		}

		$plan = array();

		foreach ( $f['day_plan'] ?? array() as $s ) {
			$copy = esc_html( $s['stage_description'] );

			if ( ! empty( $s['stage_link_label'] ) && ! empty( $s['stage_link_url'] ) ) {
				$copy = str_replace( esc_html( $s['stage_link_label'] ), '<a href="' . esc_url( $s['stage_link_url'] ) . '">' . esc_html( $s['stage_link_label'] ) . '</a>', $copy );
			}
			$plan[] = array( esc_html( $s['stage_title'] ), $copy );
		}

		$meals = array();
		$mealLinks = array();
		$stays = array();

		foreach ( $f['dining_stays'] ?? array() as $v ) {
			if ( ( $v['venue_kind'] ?? 'meal' ) === 'stay' ) {
				$stays[] = array(
					esc_html( $v['venue_title'] ),
					esc_html( $v['venue_description'] ),
					esc_url_raw( $v['venue_url'] ),
					esc_html( $v['venue_links'][0]['venue_link_label'] ?? 'Hotel website' ),
					get_post_meta( $id, '_gj_import_key', true ) === 'Godawari Botanical Garden'
				);
				continue;
			}
			$links = array();

			foreach ( ( ( $v['venue_links'] ?? array() ) ?: array() ) as $link ) {
				$links[] = array( esc_html( $link['venue_link_label'] ), esc_url_raw( $link['venue_link_url'] ) );
			}
			$meals[] = array(
				( $v['venue_stage'] ?? '' ) ?: 'Nearby',
				esc_html( $v['venue_title'] ),
				esc_html( $v['venue_description'] ),
				esc_url_raw( $v['venue_url'] ),
				$links[0][0] ?? 'Visit website'
			);
			$mealLinks[] = $links;
		}

		if ( ! empty( $f['guide_links'] ) ) {
			$sources = array();

			foreach ( $f['guide_links'] as $link ) {
				$sources[] = array( esc_html( $link['guide_link_label'] ), esc_url_raw( $link['guide_link_url'] ) );
			}
		}

		$nearby = array();

		foreach ( $f['related'] ?? array() as $related ) {
			if ( get_post_status( $related ) === 'publish' ) {
				$nearby[] = array( null, esc_html( get_the_title( $related ) ), get_permalink( $related ) );
			}
		}

		$out['tripDetails'][$name] = array(
			'location' => ( ( $f['guide_location'] ?? '' ) ?: $address ) ?: $type,
			'photos' => $gallery,
			'facts' => $facts,
			'activities' => $activities,
			'sources' => $sources,
			'visit' => array( array( 'Before you go', array( wp_kses_post( $f['visit_notes'] ?? '' ) ) ) ),
			'transport' => array( array( 'By bus', esc_html( $f['bus'] ?? '' ) ), array( 'By car or taxi', esc_html( $f['car'] ?? '' ) ) ),
			'plan' => $plan,
			'nearby' => $nearby
		);

		if ( $meals ) {
			$out['tripDetails'][$name]['meals'] = $meals;
			$out['tripDetails'][$name]['mealLinks'] = $mealLinks;
		}

		if ( $stays ) {
			$out['tripDetails'][$name]['stays'] = $stays;
		}

		$detail = &$out['tripDetails'][$name];
		$detail['transportHtml'] = array( gj_transport_html( $f['bus'] ?? '' ), gj_transport_html( $f['car'] ?? '' ) );
		$detail['mealNote'] = $f['meal_note'] ?? '';
		$detail['nearbyNote'] = $f['nearby_note'] ?? '';

		if ( ! empty( $f['nearby_stops'] ) ) {
			$detail['nearby'] = array();

			foreach ( $f['nearby_stops'] as $stop ) {
				$detail['nearby'][] = array( null, esc_html( $stop['nearby_label'] ), esc_url_raw( $stop['nearby_url'] ) );
			}
		}

		if ( ! empty( $f['feature_image'] ) ) {
			$detail['featurePhoto'] = 'media-' . $f['feature_image'];
			$detail['featureCaption'] = $f['feature_caption'] ?? '';
		}

		if ( ! empty( $f['video_url'] ) ) {
			$detail['videoUrl'] = esc_url_raw( $f['video_url'] );
		}

		$rawVisit = gj_is_nepali() ? ( get_field( 'visit_notes_ne', $id, false ) ?: get_field( 'visit_notes', $id, false ) ) : get_field( 'visit_notes', $id, false );

		if ( str_contains( $rawVisit ?: '', 'trip-card' ) ) {
			$detail['visitHtml'] = gj_rich_layout( $rawVisit );
		}
		$detail['originalGardenLayout'] = get_post_meta( $id, '_gj_import_key', true ) === 'Godawari Botanical Garden';

		if ( ! empty( $f['bus_source_url'] ) ) {
			$detail['transportLinks'] = array( array( array( esc_html( $f['bus_source_label'] ), esc_url_raw( $f['bus_source_url'] ) ) ), array() );
		}

		if ( ! empty( $f['transport_pin_query'] ) ) {
			$detail['transportPin'] = array( $f['transport_pin_query'], $f['transport_pin_label'] );
		}

		unset( $detail );

		if ( ! empty( $f['facilities'] ) ) {
			$out['tripDetails'][$name]['visit'][] = array( 'Facilities and accessibility', array( esc_html( $f['facilities'] ) ) );
		}
	}

	$cityOrder = gj_option( 'home_cities', array() );
	$cityNames = array();

	foreach ( $cityOrder as $termId ) {
		$term = get_term( $termId, 'place_city' );

		if ( $term && ! is_wp_error( $term ) ) {
			$cityNames[] = $term->name;
		}
	}

	if ( ! $cityNames ) {
		$cityNames = array( 'Kathmandu', 'Lalitpur', 'Bhaktapur', 'Banepa', 'Panauti', 'Dhulikhel' );
	}

	foreach ( $cityNames as $cityName ) {
		$cityTerm = get_term_by( 'name', $cityName, 'place_city' );
		$sights = $cityTerm ? get_field( 'city_sights', 'place_city_' . $cityTerm->term_id ) : array();

		if ( $sights ) {
			$out['citySightseeing'][$cityName] = array();

			foreach ( $sights as $sightId ) {
				if ( isset( $names[$sightId] ) ) {
					$out['citySightseeing'][$cityName][] = $names[$sightId];
				}
			}
		}
	}
	$out['citySightseeing'] = array_intersect_key( array_replace( array_fill_keys( $cityNames, array() ), $out['citySightseeing'] ), array_fill_keys( $cityNames, true ) );

	foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1 ) ) as $p ) {
		$image = get_post_thumbnail_id( $p->ID );

		if ( ! $image ) {
			continue;
		}
		$out['postUrls'][$p->post_name] = get_permalink( $p->ID );
		$out['blogPosts'][] = array(
			'id' => $p->ID,
			'slug' => $p->post_name,
			'title' => esc_html( $p->post_title ),
			'category' => esc_html( implode( ', ', wp_get_post_categories( $p->ID, array( 'fields' => 'names' ) ) ) ),
			'categoryHtml' => gj_blog_category_links( $p->ID ),
			'photo' => 'media-' . $image,
			'intro' => esc_html( $p->post_excerpt ),
			'sections' => array(),
			'places' => array(),
			'sources' => array(),
			'html' => ''
		);
	}

	foreach ( array(
		'index' => '',
		'explore' => 'explore',
		'blog' => 'blog',
		'post' => 'blog',
		'about' => 'about',
		'contact' => 'contact',
		'photo-credits' => 'photo-credits',
		'privacy-policy' => 'privacy-policy',
		'terms-of-use' => 'terms-of-use',
		'disclaimer' => 'disclaimer',
		'destination' => 'explore'
	) as $file => $slug ) {
		$out['pages'][$file . '.html'] = gj_home_url( '/' . ( $slug ? $slug . '/' : '' ) );
	}

	$featured = gj_option( 'featured_destinations', array() );

	foreach ( $featured as $id ) {
		if ( isset( $names[$id] ) ) {
			$out['featured'][] = $names[$id];
		}
	}

	if ( ! $out['featured'] ) {
		$out['featured'] = array_slice( array_values( array_unique( $names ) ), 0, 6 );
	}

	$banners = gj_option( 'home_banners', array() );

	foreach ( $banners as $id ) {
		if ( isset( $names[$id] ) ) {
			$out['bannerPlaces'][] = $names[$id];
		}
	}

	if ( ! $out['bannerPlaces'] ) {
		$out['bannerPlaces'] = array_values( array_filter( array( 'Nagarkot', 'Chandragiri Hills', 'Panauti', 'Kulekhani', 'Bheda Farm' ), fn ( $n ) => isset( $out['urls'][$n] ) ) );
	}

	if ( ! $out['bannerPlaces'] ) {
		$out['bannerPlaces'] = array_slice( array_values( $names ), 0, 1 );
	}

	$out['bannerPlaces'] = array_values( array_filter( $out['bannerPlaces'], fn ( $name ) => isset( $out['photos'][$name] ) ) );
	if ( is_singular( 'destination' ) ) {
		$out['currentPlace'] = gj_canonical_title( get_queried_object_id() );
		$out['currentAddress'] = gj_value( 'address', get_queried_object_id(), $out['currentPlace'] );
	}

	if ( is_singular( 'post' ) ) {
		$out['currentPost'] = get_post_field( 'post_name', get_queried_object_id() );
	}

	if ( is_tax( array( 'outing', 'place_city', 'place_type' ) ) ) {
		$term = get_queried_object();
		$out['archivePlaces'] = array_map( fn ( $p ) => gj_canonical_title( $p->ID ), get_posts( array(
			'post_type' => 'destination',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'tax_query' => array( array( 'taxonomy' => $term->taxonomy, 'terms' => $term->term_id ) )
		) ) );
		$ne = gj_is_nepali();
		$out['archiveTitle'] = $term->taxonomy === 'place_city' ? ( $ne ? $term->name . 'मा घुम्ने ठाउँ' : 'Places to visit in ' . $term->name ) : ( $ne ? $term->name . ' सम्बन्धी घुम्ने ठाउँ' : $term->name . ' outings' );
	}

	if ( is_tax( array( 'outing', 'place_city' ) ) ) {
		$term = get_queried_object();
		$english = function_exists( 'pll_get_term' ) ? pll_get_term( $term->term_id, 'en' ) : 0;
		$canonical = $english ? get_term( $english, $term->taxonomy ) : $term;
		$out[$term->taxonomy === 'outing' ? 'category' : 'city'] = $canonical->name;
	}

	$out['collectionUrls'] = array( 'city' => array(), 'category' => array() );

	foreach ( array( 'place_city' => 'city', 'outing' => 'category' ) as $taxonomy => $kind ) {
		foreach ( get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) ) as $term ) {
			$link = get_term_link( $term );

			if ( ! is_wp_error( $link ) ) {
				$out['collectionUrls'][$kind][gj_canonical_term_name( $term )] = $link;
			}
		}
	}

	array_walk_recursive( $out, function ( &$value ) {
		if ( is_string( $value ) ) {
			$value = gj_public_link_repairs( $value );
		}
	} );

	if ( is_post_type_archive( 'destination' ) ) {
		$out['resultsTitle'] = gj_is_nepali() ? 'काठमाडौं उपत्यकामा घुम्ने ठाउँ' : 'Places to visit around Kathmandu Valley';
	}

	return $out;
}
// Keep discovery data for search, but send full editorial detail only for this page.
function gj_runtime_data() {
	$data = gj_site_data();
	$current = $data['currentPlace'] ?? '';
	$post = $data['currentPost'] ?? '';

	foreach ( array( 'tripDetails', 'destinationGalleries', 'dayPlans' ) as $key ) {
		$data[$key] = $current && isset( $data[$key][$current] ) ? array( $current => $data[$key][$current] ) : (object) array();
	}

	foreach ( $data['destinationGuides'] as $name => $guide ) {
		if ( $name !== $current ) {
			$data['destinationGuides'][$name] = isset( $guide['picnicPoint'] ) ? array( 'picnicPoint' => $guide['picnicPoint'] ) : (object) array();
		}
	}

	foreach ( $data['blogPosts'] as &$entry ) {
		if ( ( $entry['slug'] ?? '' ) !== $post ) {
			unset( $entry['html'] );
		}
	}
	unset( $entry );

	if ( ! is_page( 'photo-credits' ) ) {
		$keys = array_merge( array_column( $data['places'], 0 ), array_column( $data['blogPosts'], 'photo' ), array( 'Dining', 'Kulekhani' ) );

		if ( $current ) {
			$detail = $data['tripDetails'][$current] ?? array();
			$keys = array_merge( $keys, $detail['photos'] ?? array(), array( $detail['featurePhoto'] ?? '' ) );

			foreach ( $detail['activities'] ?? array() as $activity ) {
				$keys[] = $activity[3] ?? '';
			}
		}

		$data['photos'] = array_intersect_key( $data['photos'], array_fill_keys( $keys, true ) );
	}

	if ( ! is_front_page() && ! is_page( 'photo-credits' ) ) {
		$data['bannerPhotos'] = (object) array();
	}

	return $data;
}
function gj_home_categories_html() {
	$terms = array();

	foreach ( get_terms( array( 'taxonomy' => 'outing', 'hide_empty' => true ) ) as $term ) {
		$terms[gj_canonical_term_name( $term )] = $term;
	}

	$out = '';

	foreach ( array( 'Family', 'Couple', 'Friends', 'Group', 'Honeysun', 'Honeymoon', 'Dharmik', 'Picnic Spot', 'Kids' ) as $label ) {
		$term = $terms[$label] ?? null;
		$url = $term ? get_term_link( $term ) : add_query_arg( 'category', $label, gj_home_url( '/explore/' ) );
		$out .= '<a class="page-link" href="' . esc_url( $url ) . '" data-filter="' . esc_attr( $label ) . '" data-reveal="fade-up">' . esc_html( gj_is_nepali() ? gj_translate_label( $label ) : $label ) . '</a>';
	}

	return $out;
}
function gj_outing_filters_html() {
	$out = '';

	foreach ( get_terms( array( 'taxonomy' => 'outing', 'hide_empty' => true ) ) as $t ) {
		$out .= '<a class="page-link" href="' . esc_url( get_term_link( $t ) ) . '" data-filter="' . esc_attr( gj_canonical_term_name( $t ) ) . '">' . esc_html( $t->name ) . '</a>';
	}

	return $out;
}
function gj_footer_navigation() {
	$ne = gj_is_nepali();
	$label = function ( $en, $np ) use ( $ne ) {
		return $ne ? $np : $en;
	};
	$link = function ( $path, $en, $np ) use ( $label ) {
		echo '<a href="' . esc_url( gj_home_url( $path ) ) . '">' . esc_html( $label( $en, $np ) ) . '</a>';
	};
	echo '<nav class="footer-columns" aria-label="' . esc_attr( $label( 'Footer navigation', 'फुटर नेभिगेसन' ) ) . '"><div><h2>' . esc_html( $label( 'Explore', 'घुमघाम' ) ) . '</h2>';
	$link( '/explore/', 'Places to visit', 'घुम्ने ठाउँ' );
	if ( gj_has_scenic_dining() ) {
		$link( '/explore/?category=Scenic%20dining', 'Scenic dining', 'दृश्यसँगै खानपान' );
	}
	$link( '/explore/?category=Dharmik', 'Dharmik places', 'धार्मिक स्थल' );
	$link( '/blog/', 'Travel articles', 'यात्रा लेखहरू' );
	echo '</div><div><h2>' . esc_html( $label( 'About & help', 'हाम्रो बारेमा र सहयोग' ) ) . '</h2>';
	$link( '/about/', 'About us', 'हाम्रो बारेमा' );
	$link( '/faq/', 'Frequently asked questions', 'बारम्बार सोधिने प्रश्नहरू' );
	$link( '/contact/', 'Contact / report a correction', 'सम्पर्क / विवरण सच्याउन' );
	$link( '/photo-credits/', 'Photo credits', 'तस्बिरको श्रेय' );
	echo '</div></nav><div class="footer-bottom"><p class="footer-copyright">© ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( $label( 'Ghumna Jaun. All rights reserved.', 'घुम्न जाऔँ। सर्वाधिकार सुरक्षित।' ) ) . '</p><div class="footer-links footer-legal" aria-label="' . esc_attr( $label( 'Policies', 'नीतिहरू' ) ) . '">';
	$link( '/privacy-policy/', 'Privacy Policy', 'गोपनीयता नीति' );
	$link( '/terms-of-use/', 'Terms of Use', 'प्रयोगका सर्तहरू' );
	$link( '/disclaimer/', 'Disclaimer', 'अस्वीकरण' );
	echo '<button class="footer-cookie-preferences" type="button" data-cookie-preferences hidden>' . esc_html( $label( 'Cookie preferences', 'कुकी प्राथमिकताहरू' ) ) . '</button></div></div>';
}
function gj_credit_items() {
	$data = gj_site_data();
	$unique = array();

	foreach ( array_merge( array_values( $data['photos'] ), array_values( $data['bannerPhotos'] ?? array() ) ) as $photo ) {
		if ( empty( $photo['author'] ) || empty( $photo['source'] ) && empty( $photo['commons'] ) ) {
			continue;
		}
		$unique[$photo['src']] = $photo;
	}

	return array_values( $unique );
}
function gj_credits_html() {
	$html = '';

	foreach ( gj_credit_items() as $p ) {
		$source = $p['source'] ?? $p['commons'] ?? '';
		$license = $p['license'] ?? '';
		$credit = '';

		if ( $license === 'CC0' || ( $p['source'] ?? '' ) === 'https://wordpress.org/photos/photo/3716aacc1b/' ) {
			$credit = 'by ' . esc_html( $p['author'] ) . ' · <a href="https://creativecommons.org/publicdomain/zero/1.0/">CC0</a>';
		} elseif ( $license === 'PD' ) {
			$credit = 'by ' . esc_html( $p['author'] ) . ' · Public domain';
		} elseif ( $license ) {
			$by = $license === 'BY';
			$label = $by ? 'CC BY 4.0' : 'CC BY-SA ' . $license;
			$url = 'https://creativecommons.org/licenses/' . ( $by ? 'by/4.0/' : 'by-sa/' . rawurlencode( $license ) . '/' );
			$credit = 'by ' . esc_html( $p['author'] ) . ' · <a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
		} else {
			$credit = '· Photo source: ' . esc_html( $p['author'] );
		}
		$html .= '<li><span class="credit-thumbnail"><img src="' . esc_url( $p['thumbnail'] ?? $p['src'] ) . '" width="64" height="48" alt="" loading="lazy" decoding="async"></span><div><a href="' . esc_url( $source ) . '">' . esc_html( $p['alt'] ) . '</a> ' . $credit . '</div></li>';
	}

	return $html;
}
function gj_social_icons() {
	get_template_part( 'template-parts/social-icons' );
}

function gj_rich_layout( $html ) {
	// Editor padding outside the cards becomes an anonymous CSS grid item.
	$html = preg_replace( '~\A(?:\s|\x{00A0}|&nbsp;|&#0*160;|&#x0*a0;)+|(?:\s|\x{00A0}|&nbsp;|&#0*160;|&#x0*a0;)+\z~iu', '', $html );
	$html = preg_replace( '~<span\b[^>]*class=[\"\']link-arrow[\"\'][^>]*>.*?</span>~s', '<span class="link-arrow" aria-hidden="true">↗</span>', $html );

	return wp_kses_post( $html );
}
