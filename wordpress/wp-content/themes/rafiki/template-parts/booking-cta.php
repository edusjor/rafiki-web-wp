<?php
/**
 * "Check Availability" + WhatsApp buttons for an activity / tour detail page.
 *
 * The primary button points to rafiki_booking_link( $post_id ) — the post's
 * own "Booking Link" field if set, otherwise the site-wide Beds24 URL from
 * Rafiki Settings. The secondary button opens WhatsApp.
 *
 * Expects (via get_template_part's $args): $post_id, and optionally
 * $book_now_label (defaults to "Check Availability").
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! isset( $post_id ) ) $post_id = get_the_ID();
if ( ! isset( $book_now_label ) ) $book_now_label = 'Check Availability';
?>
<div class="btn-group">
	<a href="<?php echo esc_url( rafiki_booking_link( $post_id ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener"><?php echo esc_html( $book_now_label ); ?></a>
	<a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to book: ' . get_the_title( $post_id ) ) ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener"><?php echo rafiki_whatsapp_icon_svg(); ?> Book via WhatsApp</a>
</div>
