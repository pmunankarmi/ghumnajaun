<?php
/** Publication safeguards and an explicitly run editorial repair. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

// No account is created or submitted. Site Kit must not inject verification/ad tags.
add_filter( 'googlesitekit_adsense_tag_blocked', '__return_true', PHP_INT_MAX );
// Preserve Site Kit's configuration, but release its tag only after analytics consent.
add_filter( 'googlesitekit_analytics-4_tag_block_on_consent', '__return_true', PHP_INT_MAX );
add_action( 'wp_head', function () {
	echo "<script>window.gjAnalyticsConsent=false;window['ga-disable-G-6L88WJ5X6E']=true;window.dataLayer=window.dataLayer||[];window.gtag=window.gtag||function(){window.dataLayer.push(arguments);};window.gtag('consent','default',{analytics_storage:'denied',ad_storage:'denied',ad_user_data:'denied',ad_personalization:'denied'});</script>\n";
}, -100 );

/** A future ad implementation must also pass this language check. Ads remain off. */
function gj_ad_language_supported() {
	return function_exists( 'pll_current_language' ) && pll_current_language( 'slug' ) === 'en';
}

/** Only documented commercial-use rights permit third-party photos to be published. */
function gj_photo_publishable( $id ) {
	$source = (string) get_post_meta( $id, 'photo_source', true );
	$license = trim( (string) get_post_meta( $id, 'photo_license', true ) );
	$rights = (string) get_post_meta( $id, 'photo_rights_status', true );
	$evidence = (string) get_post_meta( $id, 'photo_rights_evidence', true );
	// Imported brand artwork has no external source. Do not withhold the site logos.
	if ( ! $source ) {
		return in_array( (int) $id, array_filter( array( (int) get_theme_mod( 'custom_logo' ), (int) gj_option( 'site_logo_dark' ), (int) get_option( 'site_icon' ) ) ), true ) || $rights === 'original';
	}
	if ( in_array( $license, array( 'PD', 'CC0', 'BY', '3.0', '4.0' ), true ) ) {
		return true;
	}
	// A previously checked WordPress Photo Directory image has explicit CC0 rights.
	if ( $source === 'https://wordpress.org/photos/photo/3716aacc1b/' ) {
		return true;
	}
	return in_array( $rights, array( 'original', 'permission' ), true ) && $evidence !== '';
}

// Withhold from rendered images, social previews and the public image API, retain Media.
add_filter( 'wp_get_attachment_image_src', function ( $image, $id ) {
	return is_admin() || gj_photo_publishable( $id ) ? $image : false;
}, 20, 2 );
add_filter( 'wp_get_attachment_image', function ( $html, $id ) {
	return is_admin() || gj_photo_publishable( $id ) ? $html : '';
}, 20, 2 );

/** Empty sections stay out of discovery until there are real published listings. */
function gj_has_scenic_dining() {
	foreach ( gj_site_data()['places'] as $place ) {
		if ( in_array( 'Scenic dining', explode( '|', $place[2] ), true ) ) {
			return true;
		}
	}
	return false;
}
add_filter( 'wp_nav_menu_objects', function ( $items ) {
	if ( is_admin() || gj_has_scenic_dining() ) {
		return $items;
	}
	return array_filter( $items, function ( $item ) {
		parse_str( wp_parse_url( $item->url, PHP_URL_QUERY ) ?: '', $query );
		return ( $query['category'] ?? '' ) !== 'Scenic dining';
	} );
} );

function gj_publisher_byline() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	echo '<p class="post-byline"><span class="post-author">' . esc_html( get_the_author() ) . '</span> · <time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date( 'j F Y' ) ) . '</time></p>';
}

// These fields remain ordinary ACF Pro media fields; no external permission is inferred.
add_action( 'acf/init', function () {
	acf_add_local_field_group( array(
		'key' => 'group_gj_media_rights', 'title' => 'Photo publication rights',
		'fields' => array(
			array( 'key' => 'field_gj_photo_rights_status', 'name' => 'photo_rights_status', 'label' => 'Rights basis', 'type' => 'select', 'choices' => array( '' => 'Use documented license, otherwise withhold', 'original' => 'Our original work', 'permission' => 'Commercial-use permission obtained' ), 'instructions' => 'Do not select permission without evidence from the rights holder.' ),
			array( 'key' => 'field_gj_photo_rights_evidence', 'name' => 'photo_rights_evidence', 'label' => 'Permission evidence / record reference', 'type' => 'text', 'instructions' => 'Private reference to the permission email, license or original-work record. Never displayed publicly.' ),
		),
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'attachment' ) ) ),
	) );
} );

add_action( 'admin_menu', function () {
	add_management_page( 'Publication readiness', 'Publication readiness', 'manage_options', 'gj-publication-readiness', 'gj_readiness_screen' );
} );
function gj_readiness_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>Publication readiness</h1><p>Ads are disabled by the theme. No AdSense application, publisher ID or ad units are configured by this repair.</p>';
	echo '<p>Before a future ad launch: obtain approval, restrict tags to supported English content, configure and test a Google-certified advertising CMP where required, update disclosures to match actual settings, and publish the account’s exact ads.txt entry.</p>';
	if ( get_option( 'gj_readiness_repair_20261123' ) ) {
		echo '<div class="notice notice-success inline"><p>Reviewed bilingual privacy, publisher and destination updates applied. Previous content is retained in private post metadata and WordPress revisions.</p></div>';
	} else {
		echo '<p>Apply the reviewed English/Nepali privacy and publisher additions, replace the five reviewed destination visit sections, and correct the verified CC0 photo license. Existing content and fields are backed up first. This action does not enable advertising.</p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="gj_apply_readiness_repair">';
		wp_nonce_field( 'gj_apply_readiness_repair' );
		submit_button( 'Apply reviewed readiness updates' );
		echo '</form>';
	}
	echo '<h2>Photos withheld pending rights evidence</h2><p>Files and existing field selections remain in Media. A source credit alone does not establish commercial reuse rights.</p><ul>';
	foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1, 'lang' => '' ) ) as $photo ) {
		if ( get_post_meta( $photo->ID, 'photo_source', true ) && ! gj_photo_publishable( $photo->ID ) ) {
			echo '<li><a href="' . esc_url( get_edit_post_link( $photo->ID ) ) . '">' . esc_html( $photo->post_title ?: '#' . $photo->ID ) . '</a></li>';
		}
	}
	echo '</ul></div>';
}

add_action( 'admin_post_gj_apply_readiness_repair', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not permitted.', 403 );
	}
	check_admin_referer( 'gj_apply_readiness_repair' );
	if ( get_option( 'gj_readiness_repair_20261123' ) ) {
		wp_safe_redirect( admin_url( 'tools.php?page=gj-publication-readiness' ) );
		exit;
	}
	$payload = json_decode( file_get_contents( __DIR__ . '/readiness-content.json' ), true );
	if ( ! is_array( $payload ) ) {
		wp_die( 'Reviewed content could not be loaded.' );
	}
	$targets = array();
	foreach ( $payload as $row ) {
		// Shared Polylang slugs can cause a name lookup to select the other translation.
		$posts = get_posts( array( 'post_type' => $row['type'], 'post_status' => 'publish', 'lang' => '', 'suppress_filters' => true, 'posts_per_page' => -1 ) );
		$posts = array_values( array_filter( $posts, function ( $post ) use ( $row ) {
			return $post->post_name === $row['slug'] && ( ! function_exists( 'pll_get_post_language' ) || pll_get_post_language( $post->ID ) === 'en' );
		} ) );
		if ( count( $posts ) !== 1 ) {
			wp_die( 'Content changed; review target: ' . esc_html( $row['slug'] ) );
		}
		$id = $posts[0]->ID;
		$translations = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $id ) : array( 'en' => $id );
		foreach ( array( 'en', 'ne' ) as $lang ) {
			if ( empty( $translations[$lang] ) ) {
				wp_die( 'Linked translation missing: ' . esc_html( $row['slug'] ) );
			}
			$targets[] = array( $translations[$lang], $lang, $row );
		}
	}
	foreach ( $targets as [$id, $lang, $row] ) {
		$post = get_post( $id );
		add_post_meta( $id, '_gj_readiness_before_20261123', array( 'content' => $post->post_content, 'fields' => get_fields( $id ), 'modified' => $post->post_modified ), true );
		if ( $row['type'] === 'page' ) {
			$content = $post->post_content;
			// Retry safely after an interrupted request; never append the same section twice.
			if ( ! str_contains( $content, 'gj-readiness-20261123' ) ) {
				$addition = '<section class="body wp-content gj-readiness-20261123">' . $row[$lang] . '</section>';
				$updated = ! empty( $row['replace'] ) ? $row[$lang] : $content . "\n" . $addition;
				$result = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $updated ) ), true );
				if ( is_wp_error( $result ) ) {
					wp_die( esc_html( $result->get_error_message() ) );
				}
			}
		} else {
			update_field( 'field_gj_visit_notes', $row['en'], $id );
			update_field( 'field_gj_visit_notes_ne', $row['ne'], $id );
		}
	}
	// Verified at the original WordPress Photo Directory record; no guessed license.
	foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1, 'lang' => '', 'meta_key' => 'photo_source', 'meta_value' => 'https://wordpress.org/photos/photo/3716aacc1b/' ) ) as $photo ) {
		add_post_meta( $photo->ID, '_gj_license_before_20261123', get_post_meta( $photo->ID, 'photo_license', true ), true );
		update_field( 'field_gj_photo_license', 'CC0', $photo->ID );
	}
	update_option( 'gj_readiness_repair_20261123', gmdate( 'c' ), false );
	do_action( 'litespeed_purge_all' );
	wp_safe_redirect( admin_url( 'tools.php?page=gj-publication-readiness' ) );
	exit;
} );
