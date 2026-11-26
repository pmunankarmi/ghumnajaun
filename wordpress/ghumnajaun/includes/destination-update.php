<?php

/** Explicit import of the reviewed Karya Binayak copy and owner photos. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_management_page( 'Karya Binayak update', 'Karya Binayak update', 'manage_options', 'gj-destination-update', 'gj_karya_update_screen' );
} );
function gj_karya_update_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$report = get_option( 'gj_karya_update_20261126' );
	echo '<div class="wrap"><h1>Karya Binayak destination update</h1>';

	if ( $report ) {
		echo '<div class="notice notice-success inline"><p>Applied the reviewed English and Nepali guide, eight original gallery photos, selected hero image, activity pictures and Shikali destination links. The companion vlog articles remain drafts.</p></div>';
		echo '<p><a href="' . esc_url( get_permalink( $report['destinations']['en'] ) ) . '">View English destination</a> · <a href="' . esc_url( get_permalink( $report['destinations']['ne'] ) ) . '">View Nepali destination</a></p>';
	} else {
		echo '<p>Apply the reviewed bilingual Karya Binayak update and import its selected photos into Media. Existing content and image selections are backed up privately on the affected records. Other destinations, site settings and the current video URL are preserved.</p>';
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="gj_apply_karya_update">';
		wp_nonce_field( 'gj_apply_karya_update' );
		submit_button( 'Publish Karya Binayak update' );
		echo '</form>';
	}
	echo '</div>';
}
function gj_karya_update_run() {
	$existing = get_option( 'gj_karya_update_20261126' );

	if ( $existing ) {
		return $existing;
	}

	if ( ! function_exists( 'update_field' ) || ! function_exists( 'pll_get_post' ) ) {
		throw new RuntimeException( 'ACF Pro and Polylang must be active.' );
	}
	$directory = __DIR__ . '/destination-update';
	$content = json_decode( file_get_contents( $directory . '/content.json' ), true, 512, JSON_THROW_ON_ERROR );
	$manifest = json_decode( file_get_contents( $directory . '/media-manifest.json' ), true, 512, JSON_THROW_ON_ERROR );
	$destinations = array();

	foreach ( get_posts( array( 'post_type' => 'destination', 'post_status' => 'publish', 'posts_per_page' => -1, 'lang' => '' ) ) as $post ) {
		$destinations[$post->post_name][pll_get_post_language( $post->ID )] = $post->ID;
	}

	foreach ( array( 'karya-binayak-temple', 'shikali-temple', 'rato-machhindranath-temple', 'rudrayani-temple', 'jal-binayak-temple' ) as $slug ) {
		if ( empty( $destinations[$slug]['en'] ) || empty( $destinations[$slug]['ne'] ) || (int) pll_get_post( $destinations[$slug]['en'], 'ne' ) !== (int) $destinations[$slug]['ne'] ) {
			throw new RuntimeException( 'Linked destination missing: ' . $slug );
		}
	}
	$ids = $destinations['karya-binayak-temple'];
	$photos = array();

	foreach ( $content['fields']['stops'] as $stop ) {
		$key = $stop['stop_photo_key'];
		$found = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_gj_photo_key', 'meta_value' => $key, 'lang' => '' ) );

		if ( $found ) {
			$photos[$key] = (int) $found[0];
		}

		if ( ! $found && ! in_array( $key, array_column( $manifest, 'key' ), true ) ) {
			throw new RuntimeException( 'Existing activity image missing: ' . $key );
		}
	}

	// Validate every bundled file before changing any destination content.
	foreach ( $manifest as $photo ) {
		$file = $directory . '/media/' . basename( $photo['file'] );

		if ( ! is_file( $file ) || ! hash_equals( $photo['sha256'], hash_file( 'sha256', $file ) ) ) {
			throw new RuntimeException( 'Bundled photo failed verification: ' . $photo['file'] );
		}
	}
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	foreach ( $manifest as $photo ) {
		$found = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_gj_photo_key', 'meta_value' => $photo['key'], 'lang' => '' ) );

		if ( $found ) {
			$id = (int) $found[0];
		} else {
			$tmp = wp_tempnam( $photo['file'] );

			if ( ! copy( $directory . '/media/' . basename( $photo['file'] ), $tmp ) ) {
				throw new RuntimeException( 'Could not prepare photo: ' . $photo['file'] );
			}
			$id = media_handle_sideload( array( 'name' => $photo['file'], 'tmp_name' => $tmp ), $ids['en'], $photo['title'] );

			if ( is_wp_error( $id ) ) {
				throw new RuntimeException( $id->get_error_message() );
			}
		}
		update_post_meta( $id, '_gj_photo_key', $photo['key'] );
		update_post_meta( $id, '_wp_attachment_image_alt', $photo['alt'] );
		update_post_meta( $id, 'photo_rights_status', $photo['rights'] );
		update_post_meta( $id, 'photo_rights_evidence', $photo['evidence'] );
		$result = wp_update_post( array( 'ID' => $id, 'post_excerpt' => $photo['caption'] ), true );

		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( $result->get_error_message() );
		}

		foreach ( array( 'author', 'source', 'license' ) as $key ) {
			update_field( 'field_gj_photo_' . $key, $photo[$key], $id );
		}

		if ( ! gj_photo_publishable( $id ) ) {
			throw new RuntimeException( 'Photo is not available for publication: ' . $photo['key'] );
		}
		// JPEG/srcset sizes exist already; WebP derivatives can finish via Image performance.
		$photos[$photo['key']] = (int) $id;
	}
	$replace = array(
		'{{karya}}' => get_permalink( $ids['en'] ), '{{karya_ne}}' => get_permalink( $ids['ne'] ),
		'{{shikali}}' => get_permalink( $destinations['shikali-temple']['en'] ), '{{shikali_ne}}' => get_permalink( $destinations['shikali-temple']['ne'] )
	);
	$rewrite = function ( $value ) use ( &$rewrite, $replace ) {
		return is_array( $value ) ? array_map( $rewrite, $value ) : ( is_string( $value ) ? strtr( $value, $replace ) : $value );
	};
	$fields = $rewrite( $content['fields'] );

	foreach ( $fields['stops'] as &$stop ) {
		$stop['stop_image'] = $photos[$stop['stop_photo_key']];
		$stop['stop_destination'] = $stop['stop_slug'] ? $destinations[$stop['stop_slug']]['en'] : 0;
		unset( $stop['stop_photo_key'], $stop['stop_slug'] );
	}
	unset( $stop );

	foreach ( $fields['nearby_stops'] as &$stop ) {
		$stop['nearby_url'] = get_permalink( $destinations[$stop['nearby_slug']]['en'] );
		unset( $stop['nearby_slug'] );
	}
	unset( $stop );
	$fields['hero_image'] = $photos[$content['hero_key']];
	$fields['feature_image'] = $photos[$content['feature_key']];
	$fields['feature_caption'] = $content['feature_caption'];
	$fields['feature_caption_ne'] = $content['feature_caption_ne'];
	$fields['gallery'] = array_map( fn ( $key ) => $photos[$key], $content['owner_gallery'] );

	foreach ( $ids as $language => $id ) {
		$before = array( 'post' => get_post( $id ), 'fields' => get_fields( $id, false ), 'thumbnail' => get_post_thumbnail_id( $id ) );
		add_post_meta( $id, '_gj_karya_before_20261126', $before, true );
		$summary = $content[$language === 'ne' ? 'summary_ne' : 'summary'];
		$result = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $summary, 'post_excerpt' => $summary ) ), true );

		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( $result->get_error_message() );
		}

		foreach ( $fields as $key => $value ) {
			update_field( 'field_gj_' . $key, $value, $id );
		}
		set_post_thumbnail( $id, $fields['hero_image'] );
		clean_post_cache( $id );

		if ( count( get_field( 'gallery', $id, false ) ?: array() ) !== 8 || (int) get_post_meta( $id, 'hero_image', true ) !== $fields['hero_image'] || (int) get_post_meta( $id, 'stops_5_stop_destination', true ) !== (int) $destinations['shikali-temple']['en'] ) {
			throw new RuntimeException( 'Destination fields did not save completely: ' . $id );
		}
	}
	$drafts = array();

	foreach ( array( 'en', 'ne' ) as $language ) {
		$found = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 1, 'lang' => '', 'meta_key' => '_gj_karya_vlog_draft', 'meta_value' => $language ) );

		if ( $found ) {
			$drafts[$language] = $found[0]->ID;
			continue;
		}
		$id = wp_insert_post( wp_slash( array( 'post_type' => 'post', 'post_status' => 'draft', 'post_title' => $content['vlog'][$language === 'ne' ? 'title_ne' : 'title'], 'post_content' => $rewrite( $content['vlog'][$language === 'ne' ? 'content_ne' : 'content'] ), 'meta_input' => array( '_gj_karya_vlog_draft' => $language ) ) ), true );

		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}
		pll_set_post_language( $id, $language );
		$drafts[$language] = $id;
	}
	pll_save_post_translations( $drafts );
	$report = array( 'destinations' => $ids, 'gallery_count' => 8, 'hero_image' => $fields['hero_image'], 'drafts' => $drafts, 'completed_at' => current_time( 'mysql', true ) );
	update_option( 'gj_karya_update_20261126', $report, false );
	do_action( 'litespeed_purge_all' );

	return $report;
}
add_action( 'admin_post_gj_apply_karya_update', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not authorized.', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'gj_apply_karya_update' );

	if ( get_transient( 'gj_karya_update_lock' ) ) {
		wp_die( 'The import is already running. Please wait before retrying.' );
	}
	set_transient( 'gj_karya_update_lock', 1, 15 * MINUTE_IN_SECONDS );
	try {
		gj_karya_update_run();
	} catch ( Throwable $error ) {
		delete_transient( 'gj_karya_update_lock' );
		wp_die( esc_html( $error->getMessage() ) );
	} finally {
		delete_transient( 'gj_karya_update_lock' );
	}
	wp_safe_redirect( admin_url( 'tools.php?page=gj-destination-update' ) );
	exit;
} );
