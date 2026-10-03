<?php
/**
 * Handles the general contact form (page-templates/template-contact.php).
 * Same approach as inc/group-inquiry.php: plain WordPress core, submits via
 * admin-post.php, emails through wp_mail() and redirects back with a
 * ?contact=success|error flag the template reads.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_handle_contact_form() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/contact/' );
	$redirect = remove_query_arg( 'contact', $redirect );

	if ( ! isset( $_POST['rafiki_contact_nonce'] ) || ! wp_verify_nonce( $_POST['rafiki_contact_nonce'], 'rafiki_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) . '#contact-form' );
		exit;
	}

	// Honeypot: real visitors never see or fill this field.
	if ( ! empty( $_POST['contact_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'success', $redirect ) . '#contact-form' );
		exit;
	}

	$name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
	$email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
	$phone   = isset( $_POST['contact_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) : '';
	$topic   = isset( $_POST['contact_topic'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_topic'] ) ) : '';
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) . '#contact-form' );
		exit;
	}

	$subject = sprintf( '[Rafiki] New message from %s', $name );
	$body    = "A new message came in through the Contact page.\n\n"
		. "Name: {$name}\n"
		. "Email: {$email}\n"
		. "Phone: {$phone}\n"
		. "Topic: {$topic}\n\n"
		. "Message:\n{$message}\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	// Same inbox as group inquiries (Settings → Rafiki), falling back to the admin email.
	$sent = wp_mail( rafiki_group_inquiry_email(), $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'success' : 'error', $redirect ) . '#contact-form' );
	exit;
}
add_action( 'admin_post_rafiki_contact', 'rafiki_handle_contact_form' );
add_action( 'admin_post_nopriv_rafiki_contact', 'rafiki_handle_contact_form' );
