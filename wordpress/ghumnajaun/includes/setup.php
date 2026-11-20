<?php

/**
 * Theme supports, menus, assets, shared slugs and logo synchronization.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;
add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'ghumnajaun', get_template_directory() . '/languages' );

	foreach ( array( 'title-tag', 'post-thumbnails', 'custom-logo', 'automatic-feed-links', 'responsive-embeds' ) as $feature ) {
		add_theme_support( $feature );
	}

	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array( 'primary' => 'Main navigation', 'footer' => 'Footer navigation' ) );
} );
add_filter( 'use_block_editor_for_post', '__return_false', 100 );
add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
add_filter( 'use_widgets_block_editor', '__return_false' );
add_action( 'init', function () {
	register_post_type( 'destination', array(

		'labels' => array( 'name' => 'Destinations', 'singular_name' => 'Destination' ),

		'public' => true,

		'has_archive' => 'explore',

		'rewrite' => array( 'slug' => 'destination' ),

		'show_in_rest' => true,

		'menu_icon' => 'dashicons-location-alt',

		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' )

	) );

	foreach ( array( 'place_city' => 'Cities', 'place_type' => 'Destination types', 'outing' => 'Outing categories' ) as $key => $label ) {
		register_taxonomy( $key, array( 'destination' ), array( 'label' => $label, 'public' => true, 'hierarchical' => true, 'show_in_rest' => true ) );
	}

	if ( defined( 'POLYLANG_VERSION' ) && ! function_exists( 'polylang_slug_unique_slug_in_language' ) ) {
		require_once __DIR__ . '/polylang-slug.php';
	}
} );
add_filter( 'pll_get_post_types', function ( $types ) {
	$types['destination'] = 'destination';

	return $types;
} );
add_filter( 'pll_get_taxonomies', function ( $types ) {
	foreach ( array( 'place_city', 'place_type', 'outing' ) as $t ) {
		$types[$t] = $t;
	}

	return $types;
} );
add_action( 'after_switch_theme', function () {
	do_action( 'gj_register_ready' );
	flush_rewrite_rules();
} );
add_action( 'wp_enqueue_scripts', function () {
	$base = get_template_directory_uri();
	wp_enqueue_style( 'gj-wordpress', $base . '/assets/site.css', array(), hash_file( 'sha256', get_template_directory() . '/assets/site.css' ) );
	wp_enqueue_script( 'gj-swiper', $base . '/assets/vendor/swiper-bundle.min.js', array(), hash_file( 'sha256', get_template_directory() . '/assets/vendor/swiper-bundle.min.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gj-aos', $base . '/assets/vendor/aos.js', array(), hash_file( 'sha256', get_template_directory() . '/assets/vendor/aos.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$runtime_dependencies = array( 'gj-swiper', 'gj-aos' );

	if ( gj_is_nepali() ) {
		$dictionary = get_template_directory() . '/assets/ne-dictionary.js';
		wp_enqueue_script( 'gj-ne-dictionary', $base . '/assets/ne-dictionary.js', array(), hash_file( 'sha256', $dictionary ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		$runtime_dependencies[] = 'gj-ne-dictionary';
	}

	wp_enqueue_script( 'gj-runtime', $base . '/assets/theme.js', $runtime_dependencies, hash_file( 'sha256', get_template_directory() . '/assets/theme.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_add_inline_script( 'gj-runtime', 'window.GJ=' . wp_json_encode( gj_runtime_data(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE ) . ';', 'before' );
} );
function gj_value( $name, $id = null, $default = '' ) {
	$id = $id ?: get_the_ID();
	$value = function_exists( 'get_field' ) ? get_field( $name, $id ) : get_post_meta( $id, $name, true );

	return ( $value === false || $value === null || $value === '' ) ? $default : $value;
}
function gj_option( $name, $default = '' ) {
	return function_exists( 'get_field' ) ? ( get_field( $name, 'option' ) ?: $default ) : $default;
}
function gj_logo() {
	return wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) ?: get_template_directory_uri() . '/assets/logo-option-16.svg';
}
// ACF stores an attachment ID; Customizer uses the same attachment, never a second copy.
add_filter( 'acf/update_value/name=site_logo', function ( $value ) {
	set_theme_mod( 'custom_logo', absint( $value ) );

	return absint( $value );
} );
add_action( 'updated_option', function ( $name, $old, $new ) {
	if ( $name === 'theme_mods_' . get_stylesheet() && ( $old['custom_logo'] ?? 0 ) !== ( $new['custom_logo'] ?? 0 ) && function_exists( 'update_field' ) ) {
		update_field( 'field_gj_site_logo', absint( $new['custom_logo'] ?? 0 ), 'option' );
	}
}, 10, 3 );
add_filter( 'acf/load_value/name=site_logo', function ( $value ) {
	return absint( get_theme_mod( 'custom_logo' ) );
} );
function gj_legacy_links( $html ) {
	$html = gj_public_link_repairs( $html );

	return preg_replace_callback( '/(href|action)="([a-z-]+)\.html([^"\n]*)"/', function ( $m ) {
		$tail = html_entity_decode( $m[3], ENT_QUOTES | ENT_HTML5, 'UTF-8' );

		if ( $m[1] === 'href' && $m[2] === 'destination' ) {
			parse_str( wp_parse_url( $tail, PHP_URL_QUERY ) ?: '', $query );
			$name = $query['place'] ?? '';

			if ( is_string( $name ) && $name !== '' ) {
				static $destinations = array();
				$language = gj_is_nepali() ? 'ne' : 'en';

				if ( ! isset( $destinations[$language] ) ) {
					$destinations[$language] = array();

					foreach ( get_posts( array( 'post_type' => 'destination', 'post_status' => 'publish', 'posts_per_page' => -1, 'lang' => $language ) ) as $post ) {
						$destinations[$language][gj_canonical_title( $post->ID )] = get_permalink( $post->ID );
					}
				}
				$url = $destinations[$language][$name] ?? '';
				unset( $query['place'] );

				if ( ! $url ) {
					$url = gj_home_url( '/explore/' );
					$query['q'] = $name;
				}

				if ( $query ) {
					$url = add_query_arg( $query, $url );
				}
				$fragment = wp_parse_url( $tail, PHP_URL_FRAGMENT );

				if ( $fragment ) {
					$url .= '#' . $fragment;
				}

				return 'href="' . esc_url( $url ) . '"';
			}
		}

		return $m[1] . '="' . esc_url( gj_home_url( '/' . ( $m[2] === 'index' ? '' : $m[2] . '/' ) . $tail ) ) . '"';
	}, $html );
}
add_filter( 'the_content', 'gj_legacy_links' );
add_action( 'admin_notices', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		echo '<div class="notice notice-warning"><p>Ghumna Jaun requires ACF Pro for guide fields and theme options.</p></div>';
	}
} );
class GJ_Flat_Nav extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
	}
	public function end_lvl( &$output, $depth = 0, $args = null ) {
	}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$data = '';
		$parts = wp_parse_url( $item->url );

		if ( ( $parts['host'] ?? '' ) === wp_parse_url( home_url(), PHP_URL_HOST ) ) {
			if ( untrailingslashit( $parts['path'] ?? '' ) === untrailingslashit( wp_parse_url( home_url( '/explore/' ), PHP_URL_PATH ) ) ) {
				parse_str( $parts['query'] ?? '', $query );
				$data = ' data-filter="' . esc_attr( $query['category'] ?? 'All' ) . '"';
			} elseif ( untrailingslashit( $parts['path'] ?? '' ) === untrailingslashit( wp_parse_url( home_url( '/blog/' ), PHP_URL_PATH ) ) ) {
				$data = ' data-page="blog"';
			}
		}

		$output .= '<a class="page-link" href="' . esc_url( $item->url ) . '"' . $data . ( in_array( 'current-menu-item', $item->classes ?? array(), true ) ? ' aria-current="page"' : '' ) . '>' . esc_html( $item->title ) . '</a>';
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
	}
}

// Local's macOS PHP-FPM can abort in Apple's asynchronous IPv6 resolver.
// IPv4 transport retains HTTPS verification and allows WordPress/GitHub updates.
if ( PHP_OS_FAMILY === 'Darwin' && wp_get_environment_type() === 'local' ) {
	add_action( 'http_api_curl', function ( $handle ) {
		curl_setopt( $handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	} );
}

function gj_uses_reference_logo() {
	$id = (int) get_theme_mod( 'custom_logo' );

	return ! $id || get_post_meta( $id, '_gj_svg_logo_sha256', true ) === hash_file( 'sha256', get_template_directory() . '/assets/site-logo.svg' );
}

function gj_dark_logo() {
	$id = (int) gj_option( 'site_logo_dark' );

	return ( $id ? wp_get_attachment_image_url( $id, 'full' ) : false ) ?: ( gj_uses_reference_logo() ? get_template_directory_uri() . '/assets/logo-option-16-dark.svg' : gj_logo() );
}
function gj_brand_logo() {
	echo '<img class="brand-logo brand-logo--light" src="' . esc_url( gj_logo() ) . '" width="321" height="64" alt=""><img class="brand-logo brand-logo--dark" src="' . esc_url( gj_dark_logo() ) . '" width="321" height="64" alt="">';
}
