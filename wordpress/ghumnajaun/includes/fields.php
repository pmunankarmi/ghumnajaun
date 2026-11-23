<?php

/**
 * ACF field groups for guides, translations and site settings.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;
add_action( 'acf/init', function () {
	acf_add_options_page( array(

		'page_title' => 'Ghumna Jaun settings',

		'menu_title' => 'Site settings',

		'menu_slug' => 'gj-settings',

		'capability' => 'edit_theme_options',

		'redirect' => false

	) );
	$field = function ( $name, $label, $type = 'text', $extra = array() ) {
		return array_merge( array( 'key' => 'field_gj_' . $name, 'name' => $name, 'label' => $label, 'type' => $type ), $extra );
	};
	$row = function ( $name, $label, $subs ) use ( $field ) {
		return $field( $name, $label, 'repeater', array( 'layout' => 'block', 'button_label' => 'Add item', 'sub_fields' => $subs ) );
	};
	$fields = array(

		$field( 'card_label', 'Card subtitle' ),

		$field( 'address', 'Address' ),

		$field( 'latitude', 'Latitude', 'number', array( 'step' => 'any' ) ),

		$field( 'longitude', 'Longitude', 'number', array( 'step' => 'any' ) ),

		$field( 'guide_location', 'Guide location label' ),

		$field( 'history', 'History', 'wysiwyg' ),

		$field( 'history_source_title', 'History source name' ),

		$field( 'history_source_url', 'History source URL', 'url' ),

		$field( 'feature_image', 'Guide feature photo', 'image', array( 'return_format' => 'id' ) ),

		$field( 'feature_caption', 'Feature photo caption' ),

		$row( 'guide_links', 'Guide references', array( $field( 'guide_link_label', 'Link label' ), $field( 'guide_link_url', 'URL', 'url' ) ) ),

		$field( 'gallery', 'Photo gallery', 'gallery', array( 'return_format' => 'id' ) ),

		$row( 'facts', 'Visit facts', array(

			$field( 'fact_label', 'Label' ),

			$field( 'fact_value', 'Value (include units)' ),

			$field( 'fact_note', 'Note' ),

			$field( 'fact_note_url', 'Note link', 'url' )

		) ),

		$row( 'stops', 'Things to do', array(

			$field( 'stop_title', 'Title' ),

			$field( 'stop_description', 'Description', 'textarea' ),

			$field( 'stop_image', 'Photo', 'image', array( 'return_format' => 'id' ) ),

			$field( 'stop_map', 'Map query' ),

			$field( 'stop_destination', 'Linked destination', 'post_object', array( 'post_type' => array( 'destination' ), 'return_format' => 'id' ) )

		) ),

		$field( 'visit_notes', 'Plan your visit', 'wysiwyg' ),

		$field( 'bus', 'By bus', 'textarea' ),

		$field( 'car', 'By car or taxi', 'textarea' ),

		$field( 'facilities', 'Facilities and accessibility', 'textarea' ),

		$field( 'bus_source_label', 'Bus reference label' ),

		$field( 'bus_source_url', 'Bus reference URL', 'url' ),

		$field( 'transport_pin_label', 'Transport stop label' ),

		$field( 'transport_pin_query', 'Transport stop map query' ),

		$field( 'meal_note', 'Eat and stay introduction', 'textarea' ),

		$row( 'day_plan', 'Day plan', array(

			$field( 'stage_title', 'Stage' ),

			$field( 'stage_description', 'Description', 'textarea' ),

			$field( 'stage_link_label', 'Linked text' ),

			$field( 'stage_link_url', 'Link destination' )

		) ),

		$row( 'dining_stays', 'Eat and stay', array(

			$field( 'venue_kind', 'Kind', 'select', array( 'choices' => array( 'meal' => 'Food stop', 'stay' => 'Accommodation' ), 'default_value' => 'meal' ) ),

			$field( 'venue_stage', 'When to visit' ),

			$field( 'venue_title', 'Name' ),

			$field( 'venue_description', 'Details', 'textarea' ),

			$field( 'venue_url', 'Website', 'url' ),

			$row( 'venue_links', 'Links', array( $field( 'venue_link_label', 'Link label' ), $field( 'venue_link_url', 'URL', 'url' ) ) )

		) ),

		$field( 'nearby_note', 'Nearby stops note', 'textarea' ),

		$row( 'nearby_stops', 'Nearby stops', array( $field( 'nearby_label', 'Name' ), $field( 'nearby_url', 'Map or guide URL', 'url' ) ) ),

		$row( 'sources', 'Sources', array( $field( 'source_title', 'Source name' ), $field( 'source_url', 'URL', 'url' ) ) ),

		$field( 'verification_date', 'Last verified', 'date_picker', array( 'return_format' => 'Y-m-d' ) ),

		$field( 'video_url', 'Video URL', 'url' ),

		$field( 'related', 'Related destinations', 'relationship', array( 'post_type' => array( 'destination' ), 'return_format' => 'id' ) )

	);
	gj_add_field_group( array(

		'key' => 'group_gj_guide',

		'title' => 'Destination guide',

		'fields' => $fields,

		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'destination' ) ) ),

		'show_in_rest' => true

	) );
	gj_add_field_group( array(

		'key' => 'group_gj_city',

		'title' => 'City card destinations',

		'fields' => array(

			$field( 'city_sights', 'Destinations shown in this city', 'relationship', array( 'post_type' => array( 'destination' ), 'return_format' => 'id' ) )

		),

		'location' => array( array( array( 'param' => 'taxonomy', 'operator' => '==', 'value' => 'place_city' ) ) )

	) );
	gj_add_field_group( array(

		'key' => 'group_gj_options',

		'title' => 'Branding and homepage',

		'fields' => array(

			$field( 'site_logo', 'Light mode logo', 'image', array( 'return_format' => 'id', 'instructions' => 'Also used as the WordPress Customizer Site Logo.' ) ),

			$field( 'site_logo_dark', 'Dark mode logo', 'image', array( 'return_format' => 'id', 'instructions' => 'Used in the header and footer when dark mode is active.' ) ),

			$field( 'home_eyebrow', 'Homepage eyebrow' ),

			$field( 'home_title', 'Homepage headline' ),

			$field( 'home_intro', 'Homepage introduction', 'textarea' ),

			$field( 'home_cities', 'Homepage cities', 'taxonomy', array( 'taxonomy' => 'place_city', 'field_type' => 'multi_select', 'return_format' => 'id', 'multiple' => 1 ) ),

			$field( 'home_banners', 'Homepage banner destinations', 'relationship', array( 'post_type' => array( 'destination' ), 'return_format' => 'id' ) ),

			$field( 'home_cover', 'Homepage cover', 'image', array( 'return_format' => 'id' ) ),

			$field( 'seo_default_image', 'Default social sharing image', 'image', array( 'return_format' => 'id', 'instructions' => 'Used when a page has no sharing or featured image. Prefer a 1200 × 630 image.' ) ),

			$field( 'featured_destinations', 'Featured destinations', 'relationship', array( 'post_type' => array( 'destination' ), 'return_format' => 'id' ) ),

			$field( 'contact_recipient', 'Contact recipient', 'email' ),

			$field( 'social_youtube', 'YouTube URL', 'url' ),

			$field( 'social_tiktok', 'TikTok URL', 'url' ),

			$field( 'social_instagram', 'Instagram URL', 'url' ),

			$field( 'social_facebook', 'Facebook URL', 'url' ),

			$field( 'explore_seo_title', 'Explore SEO title' ),

			$field( 'explore_seo_description', 'Explore meta description' ),

		),

		'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'gj-settings' ) ) )

	) );
	gj_add_field_group( array(

		'key' => 'group_gj_article_links',

		'title' => 'Internal links',

		'fields' => array(

			$field( 'linked_destinations', 'Destinations in this article', 'relationship', array(

				'post_type' => array( 'destination' ),

				'post_status' => array( 'publish' ),

				'return_format' => 'id',

				'instructions' => 'Choose additional relevant destination guides. Links already in the article are detected automatically. Linked translations are used on each language version.'

			) )

		),

		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ) ) )

	) );
	gj_add_field_group( array(

		'key' => 'group_gj_credits',

		'title' => 'Photo attribution',

		'fields' => array(

			$field( 'photo_author', 'Photographer / author' ),

			$field( 'photo_source', 'Source URL', 'url' ),

			$field( 'photo_license', 'License' )

		),

		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'attachment' ) ) )

	) );
	gj_add_field_group( array(

		'key' => 'group_gj_seo',

		'title' => 'Search appearance',

		'fields' => array(

			$field( 'seo_title', 'SEO title' ),

			$field( 'seo_description', 'Meta description', 'textarea', array( 'rows' => 2 ) ),

			$field( 'seo_image', 'Social sharing image', 'image', array( 'return_format' => 'id', 'instructions' => 'Optional. Falls back to the featured image, guide photo, then site default.' ) ),

			$field( 'seo_noindex', 'Hide from search engines', 'true_false' )

		),

		'location' => array(

			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'destination' ) ),

			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ) ),

			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ) )

		)

	) );
} );

add_action( 'admin_head', function () {
	if ( ( $_GET['page'] ?? '' ) !== 'gj-settings' ) {
		return;
	}

	echo '<style>.acf-field[data-name="site_logo_dark"] .acf-image-uploader .image-wrap{background:#172720;padding:12px;border-radius:6px}.acf-field[data-name="site_logo_dark"] .acf-image-uploader img{display:block;background:transparent}</style>';
} );
