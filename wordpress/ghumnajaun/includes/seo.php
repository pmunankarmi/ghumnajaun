<?php

/**
 * Page metadata, canonical URLs and structured data.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;
function gj_external_seo() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' );
}
/** Normalize editor copy without exposing shortcodes or HTML in metadata. */
function gj_seo_text( $text ) {
	return trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( (string) $text ) ) ) );
}

/** Match the URL used by WordPress, including multipage posts and archive pagination. */
function gj_seo_url() {
	$url = is_singular() ? wp_get_canonical_url( get_queried_object_id() ) : get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ), false );
	return remove_query_arg( array( 'from', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'fbclid' ), $url ?: get_permalink() );
}

function gj_seo_description( $id ) {
	if ( $id && post_password_required( $id ) ) {
		return gj_seo_text( get_bloginfo( 'description' ) );
	}
	$description = gj_seo_text( $id ? gj_value( 'seo_description', $id ) : ( gj_archive_seo()['description'] ?? '' ) );
	if ( ! $description && $id ) {
		$description = wp_trim_words( gj_seo_text( get_post_field( 'post_excerpt', $id ) ?: get_post_field( 'post_content', $id ) ), 30, '' );
	}
	return gj_seo_text( $description ?: get_bloginfo( 'description' ) );
}

// The configured Posts page is an archive, so WordPress does not mark it singular.
function gj_seo_page_id() {
	if ( is_singular() ) {
		return (int) get_queried_object_id();
	}

	if ( ! is_home() ) {
		return 0;
	}

	$id = (int) get_option( 'page_for_posts' );

	if ( $id && function_exists( 'pll_get_post' ) && function_exists( 'pll_current_language' ) ) {
		$id = (int) ( pll_get_post( $id, pll_current_language( 'slug' ) ) ?: $id );
	}

	return $id;
}
function gj_archive_seo() {
	$ne = function_exists( 'gj_is_nepali' ) && gj_is_nepali();
	$brand = get_bloginfo( 'name' );

	if ( is_post_type_archive( 'destination' ) ) {
		return array(

			'title' => gj_option( 'explore_seo_title', $ne ? 'काठमाडौं उपत्यकामा घुम्ने ठाउँ खोज्नुहोस् | घुम्न जाऔँ' : 'Explore Kathmandu Valley Places to Visit | Ghumna Jaun' ),

			'description' => gj_option( 'explore_seo_description', $ne ? 'काठमाडौं उपत्यकाका मन्दिर, सम्पदा, बगैंचा र पिकनिक स्थल खोज्नुहोस्। सहर र घुमघामको प्रकारअनुसार गन्तव्य छानेर आफ्नो यात्राको योजना बनाउनुहोस्।' : 'Explore Kathmandu Valley temples, heritage sites, gardens and picnic spots. Browse destinations by city and outing type to plan your next day out.' )

		);
	}

	if ( is_tax( array( 'outing', 'place_city', 'place_type' ) ) || is_category() || is_tag() ) {
		$term = get_queried_object();

		if ( ! ( $term instanceof WP_Term ) ) {
			return array();
		}

		$name = wp_strip_all_tags( $term->name );

		if ( $term->taxonomy === 'place_city' ) {
			return array(

				'title' => $ne ? $name . 'मा घुम्ने ठाउँ र भ्रमण जानकारी | ' . $brand : 'Places to Visit in ' . $name . ' | ' . $brand,

				'description' => $ne ? $name . 'का गन्तव्य, मुख्य आकर्षण र जाने बाटोबारे जानकारी लिनुहोस्। परिवार वा साथीसँग घुम्न मिल्ने ठाउँ छानेर आफ्नो यात्राको योजना बनाउनुहोस्।' : 'Discover places to visit in ' . $name . '. Explore destination highlights, visiting information and transport guidance to plan an outing with family or friends.'

			);
		}

		$blog = in_array( $term->taxonomy, array( 'category', 'post_tag' ), true );

		return array(

			'title' => $ne ? $name . ( $blog ? ': यात्रा लेख र घुमघामका विचार | ' : ': घुम्ने ठाउँ र यात्रा जानकारी | ' ) . $brand : $name . ( $blog ? ' Travel Articles & Trip Ideas | ' : ' Outings & Destination Guides | ' ) . $brand,

			'description' => $ne ? $name . ( $blog ? 'सम्बन्धी यात्रा लेख पढ्नुहोस्। घुम्ने ठाउँ र दिनको योजना बनाउने विचार लिएर काठमाडौं उपत्यका आसपासको यात्राको तयारी गर्नुहोस्।' : 'का लागि घुम्ने ठाउँ खोज्नुहोस्। गन्तव्यका आकर्षण, भ्रमणसम्बन्धी जानकारी र जाने बाटो हेरेर काठमाडौं उपत्यका आसपासको यात्रा मिलाउनुहोस्।' ) : 'Explore ' . $name . ( $blog ? ' travel articles and outing ideas. Find inspiration for heritage walks, day trips and visits around Kathmandu Valley.' : ' outings around Kathmandu Valley. Browse destination highlights, visiting information and directions to help plan your day.' )

		);
	}

	return array();
}

add_filter( 'pre_get_document_title', function ( $title ) {
	if ( gj_external_seo() ) {
		return $title;
	}

	$id = gj_seo_page_id();
	$custom = $id ? gj_value( 'seo_title', $id ) : ( gj_archive_seo()['title'] ?? '' );

	if ( ! $custom ) {
		return $title;
	}

	$page = max( (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

	return gj_seo_text( $custom ) . ( $page > 1 ? ( ( function_exists( 'gj_is_nepali' ) && gj_is_nepali() ) ? ' — पृष्ठ ' : ' — Page ' ) . $page : '' );
} );
function gj_seo_filtered_discovery() {
	return ( is_post_type_archive( 'destination' ) || is_tax( array( 'outing', 'place_city', 'place_type' ) ) ) && ( isset( $_GET['q'] ) || isset( $_GET['city'] ) || isset( $_GET['category'] ) || isset( $_GET['from'] ) );
}

add_filter( 'wp_robots', function ( $robots ) {
	$id = gj_seo_page_id();

	if ( ( $id && gj_value( 'seo_noindex', $id ) ) || is_search() || is_404() || is_author() || ( is_singular() && post_password_required( $id ) ) || gj_seo_filtered_discovery() ) {
		$robots['noindex'] = true;
		unset( $robots['index'] );
	}

	return $robots;
} );
// Keep intentionally hidden and password-protected content out of the core sitemap.
add_filter( 'wp_sitemaps_posts_query_args', function ( $args ) {
	if ( gj_external_seo() ) {
		return $args;
	}
	$visible = array(
		'relation' => 'OR',
		array( 'key' => 'seo_noindex', 'compare' => 'NOT EXISTS' ),
		array( 'key' => 'seo_noindex', 'value' => '1', 'compare' => '!=' ),
	);
	$args['meta_query'] = empty( $args['meta_query'] ) ? $visible : array( 'relation' => 'AND', $args['meta_query'], $visible );
	$args['has_password'] = false;
	return $args;
} );

// Empty account archives are not editorial landing pages.
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return $name === 'users' ? false : $provider;
}, 10, 2 );
add_action( 'wp_head', function () {
	if ( gj_external_seo() || is_404() || is_search() || is_feed() || is_preview() ) {
		return;
	}

	$id = gj_seo_page_id();
	$singular = is_singular();
	$url = gj_seo_url();
	$description = gj_seo_description( $id );
	$title = wp_get_document_title();
	$protected = $id && post_password_required( $id );
	// Try each source in order; a deleted attachment must not prevent the fallback.
	$image_ids = array(
		! $protected && $id ? (int) gj_value( 'seo_image', $id ) : 0,
		! $protected && $id ? (int) get_post_thumbnail_id( $id ) : 0,
		! $protected && $id ? (int) gj_value( 'feature_image', $id ) : 0,
		(int) gj_option( 'seo_default_image' ),
		(int) gj_option( 'home_cover' ),
	);
	$image_data = false;
	$image_id = 0;
	foreach ( array_unique( array_filter( $image_ids ) ) as $candidate ) {
		$image_data = wp_get_attachment_image_src( $candidate, 'full' );
		if ( $image_data ) {
			$image_id = $candidate;
			break;
		}
	}
	$image = $image_data ? $image_data[0] : '';

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";

	// WordPress emits the singular canonical itself.
	if ( ! $singular && ! is_404() && ! is_search() ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	foreach ( array(

		'og:title' => $title,

		'og:description' => $description,

		'og:url' => $url,

		'og:site_name' => get_bloginfo( 'name' ),

		'og:locale' => str_replace( '-', '_', get_bloginfo( 'language' ) ),

		'og:type' => is_singular( 'post' ) ? 'article' : 'website'

	) as $key => $value ) {
		echo '<meta property="' . esc_attr( $key ) . '" content="' . esc_attr( $value ) . '">' . "\n";
	}

	// Publish only locales with an actual linked translation.
	if ( $id && function_exists( 'pll_get_post_translations' ) ) {
		foreach ( pll_get_post_translations( $id ) as $translation_id ) {
			if ( (int) $translation_id === (int) $id ) {
				continue;
			}
			$locale = pll_get_post_language( $translation_id, 'locale' );
			if ( $locale ) {
				echo '<meta property="og:locale:alternate" content="' . esc_attr( str_replace( '-', '_', $locale ) ) . '">' . "\n";
			}
		}
	}
	foreach ( array( 'twitter:card' => $image ? 'summary_large_image' : 'summary', 'twitter:title' => $title, 'twitter:description' => $description ) as $key => $value ) {
		echo '<meta name="' . esc_attr( $key ) . '" content="' . esc_attr( $value ) . '">' . "\n";
	}

	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		echo '<meta property="og:image:width" content="' . (int) $image_data[1] . '">' . "\n";
		echo '<meta property="og:image:height" content="' . (int) $image_data[2] . '">' . "\n";
		$mime = get_post_mime_type( $image_id );
		if ( $mime ) {
			echo '<meta property="og:image:type" content="' . esc_attr( $mime ) . '">' . "\n";
		}
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
		$alt = gj_seo_text( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) );
		if ( $alt ) {
			echo '<meta property="og:image:alt" content="' . esc_attr( $alt ) . '">' . "\n";
			echo '<meta name="twitter:image:alt" content="' . esc_attr( $alt ) . '">' . "\n";
		}
	}

	if ( $protected ) {
		return;
	}

	$schema = array(

		'@context' => 'https://schema.org',

		'@type' => 'WebSite',

		'@id' => trailingslashit( get_option( 'home' ) ) . '#website',

		'name' => 'Ghumna Jaun',

		'alternateName' => 'घुम्न जाऔँ',

		'url' => trailingslashit( get_option( 'home' ) )

	);

	if ( is_singular( 'destination' ) ) {
		$schema = array(

			'@context' => 'https://schema.org',

			'@type' => 'TouristAttraction',

			'name' => html_entity_decode( wp_strip_all_tags( get_the_title( $id ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),

			'url' => $url,

			'description' => $description

		);

		if ( $image ) {
			$schema['image'] = $image;
		}

		$lat = gj_value( 'latitude', $id );
		$lng = gj_value( 'longitude', $id );

		if ( is_numeric( $lat ) && is_numeric( $lng ) && abs( (float) $lat ) <= 90 && abs( (float) $lng ) <= 180 ) {
			$schema['geo'] = array( '@type' => 'GeoCoordinates', 'latitude' => (float) $lat, 'longitude' => (float) $lng );
		}
	} elseif ( is_singular( 'post' ) ) {
		$author_id = (int) get_post_field( 'post_author', $id );

		if ( ! $author_id && function_exists( 'pll_get_post' ) ) {
			$author_id = (int) get_post_field( 'post_author', pll_get_post( $id, 'en' ) ?: $id );
		}

		$author_name = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
		$author = $author_name ? array( '@type' => 'Person', 'name' => $author_name ) : array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ) );
		$schema = array(

			'@context' => 'https://schema.org',

			'@type' => 'BlogPosting',

			'headline' => html_entity_decode( wp_strip_all_tags( get_the_title( $id ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),

			'description' => $description,

			'url' => $url,

			'mainEntityOfPage' => $url,

			'datePublished' => get_post_time( 'c', true, $id ),

			'dateModified' => get_post_modified_time( 'U', true, $id ) >= get_post_time( 'U', true, $id ) ? get_post_modified_time( 'c', true, $id ) : get_post_time( 'c', true, $id ),

			'author' => $author,

			'publisher' => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ) )

		);

		if ( $image ) {
			$schema['image'] = $image;
		}
	}

	if ( is_singular( 'destination' ) ) {
		// Language describes the guide, rather than the physical attraction.
		unset( $schema['@context'] );
		$schema['@id'] = $url . '#place';
		$schema = array(
			'@context' => 'https://schema.org',
			'@graph' => array(
				array(
					'@type' => 'WebPage',
					'@id' => $url . '#webpage',
					'url' => $url,
					'name' => html_entity_decode( wp_strip_all_tags( get_the_title( $id ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
					'description' => $description,
					'inLanguage' => get_bloginfo( 'language' ),
					'mainEntity' => array( '@id' => $url . '#place' )
				),
				$schema
			)
		);
	} else {
		$schema['inLanguage'] = get_bloginfo( 'language' );
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . '</script>';
}, 5 );
// Redirect legacy prototype URLs to the permanent server-rendered destination.
add_action( 'template_redirect', function () {
	$path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );

	if ( preg_match( '~/destination(?:\.html)?/?$~', $path ) && isset( $_GET['place'] ) && is_string( $_GET['place'] ) ) {
		$name = sanitize_text_field( wp_unslash( $_GET['place'] ) );
		$posts = get_posts( array( 'post_type' => 'destination', 'post_status' => 'publish', 'title' => $name, 'posts_per_page' => 1 ) );

		if ( $posts ) {
			wp_safe_redirect( get_permalink( $posts[0] ), 301 );
			exit;
		}
	}
} );
