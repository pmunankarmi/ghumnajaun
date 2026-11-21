<?php
/**
 * Private inbox for contact form submissions.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	register_post_type( 'gj_submission', array(
		'labels' => array(
			'name' => 'Contact submissions',
			'singular_name' => 'Contact submission',
			'edit_item' => 'View contact submission',
			'search_items' => 'Search submissions',
			'not_found' => 'No contact submissions found.',
		),
		'public' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rewrite' => false,
		'query_var' => false,
		'menu_icon' => 'dashicons-email-alt',
		'supports' => array(),
		'capabilities' => array(
			'edit_post' => 'manage_options',
			'read_post' => 'manage_options',
			'delete_post' => 'manage_options',
			'edit_posts' => 'manage_options',
			'edit_others_posts' => 'manage_options',
			'publish_posts' => 'do_not_allow',
			'read_private_posts' => 'manage_options',
			'delete_posts' => 'manage_options',
			'delete_private_posts' => 'manage_options',
			'delete_published_posts' => 'manage_options',
			'delete_others_posts' => 'manage_options',
			'edit_private_posts' => 'manage_options',
			'edit_published_posts' => 'manage_options',
			'create_posts' => 'do_not_allow',
		),
		'map_meta_cap' => false,
	) );
} );

// These are private records, not translated editorial content.
add_filter( 'pll_get_post_types', function ( $types ) {
	unset( $types['gj_submission'] );
	return $types;
}, 100 );

function gj_save_contact_submission( $name, $email, $subject, $body ) {
	return wp_insert_post( wp_slash( array(
		'post_type' => 'gj_submission',
		'post_status' => 'private',
		'post_title' => $subject . ' — ' . $name,
		'post_content' => $body,
		'meta_input' => array(
			'_gj_sender_name' => $name,
			'_gj_sender_email' => $email,
			'_gj_subject' => $subject,
			'_gj_mail_status' => 'pending',
		),
	) ), true );
}

add_filter( 'manage_gj_submission_posts_columns', function ( $columns ) {
	return array(
		'cb' => $columns['cb'],
		'title' => 'Submission',
		'gj_sender' => 'Sender',
		'gj_topic' => 'Topic',
		'gj_mail' => 'Email notification',
		'date' => 'Received',
	);
} );
add_action( 'manage_gj_submission_posts_custom_column', function ( $column, $post_id ) {
	if ( 'gj_sender' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_gj_sender_name', true ) ) . '<br>';
		echo esc_html( get_post_meta( $post_id, '_gj_sender_email', true ) );
	} elseif ( 'gj_topic' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_gj_subject', true ) );
	} elseif ( 'gj_mail' === $column ) {
		echo esc_html( gj_contact_mail_status( $post_id ) );
	}
}, 10, 2 );

function gj_contact_mail_status( $post_id ) {
	$labels = array( 'sent' => 'Accepted for delivery', 'failed' => 'Failed — message saved', 'pending' => 'Pending / not confirmed' );
	return $labels[get_post_meta( $post_id, '_gj_mail_status', true )] ?? $labels['pending'];
}

add_filter( 'post_row_actions', function ( $actions, $post ) {
	if ( 'gj_submission' === $post->post_type ) {
		unset( $actions['inline hide-if-no-js'], $actions['view'] );
		if ( isset( $actions['edit'] ) ) {
			$actions['edit'] = '<a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '">View message</a>';
		}
	}
	return $actions;
}, 10, 2 );

add_action( 'add_meta_boxes_gj_submission', function ( $post ) {
	remove_meta_box( 'submitdiv', 'gj_submission', 'side' );
	add_meta_box( 'gj-submission-details', 'Contact submission', function ( $post ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$email = get_post_meta( $post->ID, '_gj_sender_email', true );
		echo '<p><strong>Received:</strong> ' . esc_html( get_the_date( 'F j, Y g:i a', $post ) ) . '</p>';
		echo '<p><strong>Name:</strong> ' . esc_html( get_post_meta( $post->ID, '_gj_sender_name', true ) ) . '</p>';
		echo '<p><strong>Email:</strong> <a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a></p>';
		echo '<p><strong>Topic:</strong> ' . esc_html( get_post_meta( $post->ID, '_gj_subject', true ) ) . '</p>';
		echo '<p><strong>Email notification:</strong> ' . esc_html( gj_contact_mail_status( $post->ID ) ) . '</p><hr>';
		echo '<div style="white-space:pre-wrap;overflow-wrap:anywhere">' . esc_html( $post->post_content ) . '</div>';
	}, 'gj_submission', 'normal', 'high' );
} );
