<?php
/**
 * Handles the "Bring Your Group" inquiry form (page-templates/template-bring-your-group.php).
 * Plain WordPress core only — no form-plugin dependency. Submits via
 * admin-post.php, sends an email through wp_mail(), and redirects back to
 * the page with a #group-form-status flag the template reads to show a
 * confirmation or error message.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_group_inquiry_email() {
	$email = get_option( 'rafiki_group_inquiry_email', '' );
	return $email ? $email : get_option( 'admin_email' );
}

function rafiki_handle_group_inquiry() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! isset( $_POST['rafiki_group_nonce'] ) || ! wp_verify_nonce( $_POST['rafiki_group_nonce'], 'rafiki_group_inquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'group_inquiry', 'error', $redirect ) . '#group-form' );
		exit;
	}

	$name        = isset( $_POST['group_name'] ) ? sanitize_text_field( wp_unslash( $_POST['group_name'] ) ) : '';
	$email       = isset( $_POST['group_email'] ) ? sanitize_email( wp_unslash( $_POST['group_email'] ) ) : '';
	$phone       = isset( $_POST['group_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['group_phone'] ) ) : '';
	$group_type  = isset( $_POST['group_type'] ) ? sanitize_text_field( wp_unslash( $_POST['group_type'] ) ) : '';
	$group_size  = isset( $_POST['group_size'] ) ? sanitize_text_field( wp_unslash( $_POST['group_size'] ) ) : '';
	$dates       = isset( $_POST['group_dates'] ) ? sanitize_text_field( wp_unslash( $_POST['group_dates'] ) ) : '';
	$message     = isset( $_POST['group_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['group_message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $group_size ) {
		wp_safe_redirect( add_query_arg( 'group_inquiry', 'error', $redirect ) . '#group-form' );
		exit;
	}

	$subject = sprintf( '[Rafiki] New group inquiry from %s', $name );
	$body    = "A new group/retreat inquiry came in through the Bring Your Group page.\n\n"
		. "Name: {$name}\n"
		. "Email: {$email}\n"
		. "Phone: {$phone}\n"
		. "Type of group: {$group_type}\n"
		. "Group size: {$group_size}\n"
		. "Preferred dates: {$dates}\n\n"
		. "Message:\n{$message}\n";

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	wp_mail( rafiki_group_inquiry_email(), $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'group_inquiry', 'success', $redirect ) . '#group-form' );
	exit;
}
add_action( 'admin_post_rafiki_group_inquiry', 'rafiki_handle_group_inquiry' );
add_action( 'admin_post_nopriv_rafiki_group_inquiry', 'rafiki_handle_group_inquiry' );
