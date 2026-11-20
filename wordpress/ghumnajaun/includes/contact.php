<?php

/**
 * Render, validate and process the contact form.
 *
 * @package Ghumnajaun
 */

defined( 'ABSPATH' ) || exit;
function gj_contact_form() {
	get_template_part( 'template-parts/contact-form' );
}
add_action( 'wp_ajax_gj_contact', 'gj_send_contact' );
add_action( 'wp_ajax_nopriv_gj_contact', 'gj_send_contact' );
function gj_send_contact() {
	if ( ! check_ajax_referer( 'gj_contact', 'gj_nonce', false ) ) {
		wp_send_json_error( array( 'message' => 'Please reload the page and try again.' ), 403 );
	}

	foreach ( array( 'name', 'email', 'subject', 'body', 'website' ) as $key ) {
		if ( isset( $_POST[$key] ) && ! is_string( $_POST[$key] ) ) {
			wp_send_json_error( array( 'message' => 'Invalid form fields.' ), 422 );
		}
	}

	$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
	$body = sanitize_textarea_field( wp_unslash( $_POST['body'] ?? '' ) );

	if ( ! empty( $_POST['website'] ) || ! $name || mb_strlen( $name ) > 100 || ! is_email( $email ) || ! $body || mb_strlen( $body ) > 3000 || ! in_array( $subject, array( 'Suggest a place', 'Update a guide', 'Listings and collaborations', 'General enquiry' ), true ) ) {
		wp_send_json_error( array( 'message' => 'Please check your name, email, topic and message.' ), 422 );
	}

	$key = 'gj_contact_' . hash_hmac( 'sha256', $_SERVER['REMOTE_ADDR'] ?? '', wp_salt() );
	$count = (int) get_transient( $key );

	if ( $count >= 5 ) {
		wp_send_json_error( array( 'message' => 'Please wait 15 minutes before trying again.' ), 429 );
	}

	set_transient( $key, $count + 1, 15 * MINUTE_IN_SECONDS );
	$sent = wp_mail( gj_option( 'contact_recipient', get_option( 'admin_email' ) ), 'Ghumna Jaun: ' . $subject, $name . "\n" . $email . "\n\n" . $body, array( 'Reply-To: ' . $email ) );

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => 'Unable to send. Your message is still here; please try again.' ), 503 );
	}

	wp_send_json_success( array( 'message' => 'Your message was accepted for delivery. Thank you.' ) );
}
