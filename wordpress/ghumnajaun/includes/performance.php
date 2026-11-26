<?php

/**
 * Responsive image variants and local asset delivery.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;
/** Responsive information stays tied to the selected WordPress attachment. */
function gj_photo_sizes( $id ) {
	$webp = gj_webp_variants( $id );

	if ( $webp && ! empty( $webp['images'] ) ) {
		$images = $webp['images'];
		$largest = end( $images );
		$srcset = implode( ', ', array_map( function ( $image ) {
			return $image['src'] . ' ' . $image['width'] . 'w';
		}, $images ) );

		return array(

			'src' => $largest['src'],

			'thumbnail' => $images[0]['src'],

			'width' => $largest['width'],

			'height' => $largest['height'],

			'srcset' => $srcset

		);
	}

	$image = wp_get_attachment_image_src( $id, 'large' );

	return $image ? array(

		'thumbnail' => wp_get_attachment_image_url( $id, 'medium' ),

		'width' => $image[1],

		'height' => $image[2],

		'srcset' => wp_get_attachment_image_srcset( $id, 'large' ) ?: ''

	) : array();
}
function gj_responsive_attributes( $photo, $sizes = '100vw' ) {
	$html = '';

	foreach ( array( 'width', 'height' ) as $key ) {
		if ( ! empty( $photo[$key] ) ) {
			$html .= ' ' . $key . '="' . absint( $photo[$key] ) . '"';
		}
	}

	if ( ! empty( $photo['srcset'] ) ) {
		$html .= ' srcset="' . esc_attr( $photo['srcset'] ) . '" sizes="' . esc_attr( $sizes ) . '"';
	}

	return $html;
}
// Fetch the real first hero early, with the same responsive candidates as its markup.
add_action( 'wp_head', function () {
	$data = gj_site_data();
	$photo = null;

	if ( is_front_page() ) {
		$name = $data['bannerPlaces'][0] ?? '';
		$photo = $data['bannerPhotos'][$name] ?? $data['photos'][$name] ?? null;
	} elseif ( is_singular( 'destination' ) ) {
		$name = $data['currentPlace'] ?? '';
		$key = $data['tripDetails'][$name]['heroPhoto'] ?? $data['tripDetails'][$name]['photos'][0] ?? '';
		$photo = $data['photos'][$key] ?? null;
	}

	if ( $photo ) {
		echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url( $photo['src'] ) . '"';

		if ( ! empty( $photo['srcset'] ) ) {
			echo ' imagesrcset="' . esc_attr( $photo['srcset'] ) . '" imagesizes="100vw"';
		}

		echo ">\n";
	}

	// Let the large Devanagari font follow CSS discovery so it does not compete with the LCP image.
	if ( gj_is_nepali() ) {
		return;
	}

	$font = 'instrument-serif-latin.woff2';
	echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="' . esc_url( get_template_directory_uri() . '/assets/fonts/' . $font . '?v=' . substr( hash_file( 'sha256', get_template_directory() . '/assets/fonts/' . $font ), 0, 12 ) ) . '">' . "\n";
}, 2 );

/** Keep original uploads intact; serve smaller WebP derivatives when available. */
function gj_webp_variants( $id ) {
	$file = get_attached_file( $id );

	if ( ! $file || ! is_file( $file ) ) {
		return false;
	}

	$cached = get_post_meta( $id, '_gj_webp_variants', true );

	if ( is_array( $cached ) && ( $cached['source_mtime'] ?? 0 ) === filemtime( $file ) ) {
		return $cached;
	}

	return false;
}
function gj_build_webp_variants( $id ) {
	if ( gj_webp_variants( $id ) ) {
		return true;
	}

	if ( ! in_array( get_post_mime_type( $id ), array( 'image/jpeg', 'image/png' ), true ) ) {
		return false;
	}

	$file = get_attached_file( $id );

	if ( ! $file || ! is_file( $file ) ) {
		return false;
	}

	$meta = wp_get_attachment_metadata( $id );

	if ( empty( $meta['width'] ) || empty( $meta['height'] ) ) {
		return false;
	}

	$uploads = wp_get_upload_dir();
	$variants = array();

	foreach ( array_unique( array(

		min( 480, $meta['width'] ),

		min( 768, $meta['width'] ),

		min( 1280, $meta['width'] ),

		min( 1920, $meta['width'] )

	) ) as $width ) {
		$editor = wp_get_image_editor( $file );

		if ( is_wp_error( $editor ) ) {
			return false;
		}

		$editor->set_quality( 78 );

		if ( $width < $meta['width'] ) {
			$resized = $editor->resize( $width, null, false );

			if ( is_wp_error( $resized ) ) {
				return false;
			}
		}

		$target = dirname( $file ) . '/' . pathinfo( $file, PATHINFO_FILENAME ) . '-gj-' . $width . '.webp';
		$saved = $editor->save( $target, 'image/webp' );

		if ( is_wp_error( $saved ) ) {
			return false;
		}

		$variants[] = array(

			'src' => str_replace( $uploads['basedir'], $uploads['baseurl'], $saved['path'] ),

			'width' => $saved['width'],

			'height' => $saved['height']

		);
	}

	update_post_meta( $id, '_gj_webp_variants', array( 'source_mtime' => filemtime( $file ), 'images' => $variants ) );

	return true;
}

add_action( 'admin_menu', function () {
	add_management_page( 'Image performance', 'Image performance', 'manage_options', 'gj-performance', function () {
		echo '<div class="wrap"><h1>Image performance</h1><p>Create responsive WebP images while retaining every original upload. Existing copies are reused.</p><button class="button button-primary" id="gj-optimize-images">Optimize image delivery</button><p id="gj-image-progress" role="status"></p></div>';
		$endpoint = admin_url( 'admin-ajax.php' );
		$nonce = wp_create_nonce( 'gj-optimize-images' );
		echo '<script>document.getElementById("gj-optimize-images").onclick=async function(){this.disabled=true;const status=document.getElementById("gj-image-progress");let offset=0,done=false,failed=0;try{while(!done){status.textContent="Processing images: "+offset;const body=new URLSearchParams({action:"gj_optimize_images",nonce:' . wp_json_encode( $nonce ) . ',offset});const response=await fetch(' . wp_json_encode( $endpoint ) . ',{method:"POST",body});const result=await response.json();if(!result.success)throw new Error("Image processing failed");failed+=result.data.failed;offset=result.data.offset;done=result.data.done;}status.textContent="Complete. Checked "+offset+" attachments; optimized copies are ready. Failed: "+failed+".";}catch(error){status.textContent=error.message+". You can retry safely.";}finally{this.disabled=false;}};</script>';
	} );
} );
add_action( 'wp_ajax_gj_optimize_images', function () {
	check_ajax_referer( 'gj-optimize-images', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( null, 403 );
	}

	$offset = max( 0, (int) ( $_POST['offset'] ?? 0 ) );
	$ids = get_posts( array(

		'post_type' => 'attachment',

		'post_status' => 'inherit',

		'post_mime_type' => 'image',

		'posts_per_page' => 3,

		'offset' => $offset,

		'orderby' => 'ID',

		'order' => 'ASC',

		'fields' => 'ids',

		'suppress_filters' => true

	) );
	$failed = 0;

	foreach ( $ids as $id ) {
		if ( in_array( get_post_mime_type( $id ), array( 'image/jpeg', 'image/png' ), true ) && ! gj_build_webp_variants( $id ) ) {
			$failed++;
		}
	}

	wp_send_json_success( array( 'offset' => $offset + count( $ids ), 'done' => count( $ids ) < 3, 'failed' => $failed ) );
} );
add_action( 'gj_optimize_attachment', 'gj_build_webp_variants' );
add_filter( 'wp_generate_attachment_metadata', function ( $metadata, $id ) {
	if ( ! wp_next_scheduled( 'gj_optimize_attachment', array( $id ) ) ) {
		wp_schedule_single_event( time() + 10, 'gj_optimize_attachment', array( $id ) );
	}

	return $metadata;
}, 20, 2 );
