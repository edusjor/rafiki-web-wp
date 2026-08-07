<?php
/**
 * Online booking: bridges accommodation/activity/package posts to hidden
 * WooCommerce products so the existing checkout/payment engine can be
 * reused without turning WooCommerce into a parallel content system.
 * Every hook in this file is a no-op unless WooCommerce is active AND
 * "Enable Online Booking" is checked in Settings > Rafiki — that single
 * guard is what keeps the site's current "Check Availability" / WhatsApp behavior
 * completely unchanged when booking is off (see rafiki_booking_active()).
 *
 * Card payments are handled entirely by the Onvopay WooCommerce plugin
 * (no code here). Bank transfer / SINPE Móvil reuses WooCommerce's
 * built-in "Direct bank transfer" (BACS) gateway, relabeled and driven
 * from the rafiki_bank_* options, with a proof-of-payment upload bolted
 * on top since BACS has no upload field of its own.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_booking_active() {
	return class_exists( 'WooCommerce' ) && rafiki_booking_enabled();
}

/* ------------------------------------------------------------------ */
/* CPT <-> WooCommerce product sync                                    */
/* ------------------------------------------------------------------ */

/**
 * Runs after rafiki_save_meta() (priority 10 in inc/meta-boxes.php) so it
 * reads the just-saved rafiki_price_amount postmeta rather than $_POST.
 * Reuses the same nonce/autosave/capability/post-type guards as
 * rafiki_save_meta() since it's the same save operation, not a separate
 * security boundary.
 */
function rafiki_sync_wc_product( $post_id ) {
	if ( ! isset( $_POST['rafiki_meta_nonce'] ) || ! wp_verify_nonce( $_POST['rafiki_meta_nonce'], 'rafiki_save_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	rafiki_sync_wc_product_for( $post_id );
}
add_action( 'save_post', 'rafiki_sync_wc_product', 20 );

/**
 * Core CPT -> WooCommerce product sync, split out from rafiki_sync_wc_product()
 * so it's callable directly (WP-CLI/import scripts) without the nonce/
 * capability guard that only makes sense for an actual browser form submit.
 */
function rafiki_sync_wc_product_for( $post_id ) {
	if ( ! rafiki_booking_active() ) return;
	if ( ! in_array( get_post_type( $post_id ), rafiki_post_types(), true ) ) return;

	$price      = get_post_meta( $post_id, 'rafiki_price_amount', true );
	$product_id = (int) get_post_meta( $post_id, '_rafiki_wc_product_id', true );
	$product    = $product_id ? wc_get_product( $product_id ) : false;

	if ( '' === $price || ! is_numeric( $price ) ) {
		// No valid online price: nothing to sell. Take an existing linked
		// product off sale rather than deleting it, so it doesn't orphan
		// any past order that references it.
		if ( $product ) {
			$product->set_status( 'draft' );
			$product->save();
		}
		return;
	}

	if ( ! $product ) {
		$product = new WC_Product_Simple();
	}

	$product->set_name( get_the_title( $post_id ) );
	$product->set_regular_price( (string) $price );
	$product->set_price( (string) $price );
	$product->set_virtual( true ); // tours/stays aren't shipped
	$product->set_sold_individually( false ); // quantity = number of people
	$product->set_catalog_visibility( 'hidden' ); // excluded from shop/search, still directly purchasable by ID
	$product->set_status( 'publish' );
	$product->update_meta_data( '_rafiki_source_post_id', $post_id );
	$product->save();

	update_post_meta( $post_id, '_rafiki_wc_product_id', $product->get_id() );
}
add_action( 'save_post', 'rafiki_sync_wc_product', 20 );

/**
 * Keeps a removed tour from staying purchasable via its linked product.
 * Runs whenever WooCommerce is active regardless of the booking toggle,
 * so a product created while booking was on doesn't outlive its source
 * post after the toggle is later switched off.
 */
function rafiki_trash_linked_product( $post_id ) {
	if ( ! class_exists( 'WooCommerce' ) ) return;
	if ( ! in_array( get_post_type( $post_id ), rafiki_post_types(), true ) ) return;
	$product_id = (int) get_post_meta( $post_id, '_rafiki_wc_product_id', true );
	if ( $product_id ) wp_trash_post( $product_id );
}
add_action( 'trashed_post', 'rafiki_trash_linked_product' );
add_action( 'before_delete_post', 'rafiki_trash_linked_product' );

/* ------------------------------------------------------------------ */
/* Availability: manual blocked-date list (all 3 post types) + for      */
/* accommodations, nights already covered by a live (non-cancelled)     */
/* order — this is what makes "admin cancels the order" == "date is     */
/* available again" true with no separate release step to remember.    */
/* ------------------------------------------------------------------ */

function rafiki_is_valid_booking_date( $date ) {
	$d = DateTime::createFromFormat( 'Y-m-d', (string) $date );
	if ( ! $d || $d->format( 'Y-m-d' ) !== $date ) return false;
	return $d > new DateTime( 'today' );
}

/** Dates the admin manually closed for this tour/stay (rafiki_blocked_dates repeater). */
function rafiki_blocked_dates_for( $post_id ) {
	$rows = rafiki_rows( $post_id, 'rafiki_blocked_dates' );
	$dates = array();
	foreach ( $rows as $row ) {
		if ( ! empty( $row['date'] ) ) $dates[] = $row['date'];
	}
	return $dates;
}

/** Whole nights between two Y-m-d dates (check-out day itself isn't an occupied night). */
function rafiki_nights_between( $checkin, $checkout ) {
	$in  = DateTime::createFromFormat( 'Y-m-d', (string) $checkin );
	$out = DateTime::createFromFormat( 'Y-m-d', (string) $checkout );
	if ( ! $in || ! $out ) return 0;
	return max( 0, (int) $in->diff( $out )->days );
}

/**
 * Nights already booked for an accommodation by orders that are still
 * "live" (not cancelled/refunded/failed) — pending/on-hold count as live
 * on purpose, so a reservation is provisionally blocked from the moment
 * it's placed, not only once confirmed. Cached briefly since it scans
 * recent orders; the short TTL means a same-minute cancel + rebook can
 * lag by up to a minute, which is an acceptable trade-off for a small
 * property instead of scanning all orders on every page view.
 */
function rafiki_booked_ranges_for( $post_id ) {
	$cache_key = 'rafiki_booked_ranges_' . $post_id;
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) return $cached;

	$ranges = array();
	$product_id = (int) get_post_meta( $post_id, '_rafiki_wc_product_id', true );

	if ( $product_id && function_exists( 'wc_get_orders' ) ) {
		$order_ids = wc_get_orders( array(
			'status' => array( 'wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed' ),
			'limit'  => -1,
			'return' => 'ids',
		) );

		foreach ( $order_ids as $order_id ) {
			$order = wc_get_order( $order_id );
			if ( ! $order ) continue;
			foreach ( $order->get_items() as $item ) {
				if ( (int) $item->get_product_id() !== $product_id ) continue;
				$checkin  = $item->get_meta( '_rafiki_checkin' );
				$checkout = $item->get_meta( '_rafiki_checkout' );
				if ( $checkin && $checkout ) {
					$ranges[] = array( $checkin, $checkout );
				}
			}
		}
	}

	set_transient( $cache_key, $ranges, MINUTE_IN_SECONDS );
	return $ranges;
}

function rafiki_ranges_overlap( $start_a, $end_a, $start_b, $end_b ) {
	return ( $start_a < $end_b ) && ( $start_b < $end_a );
}

/** True if every night in [$checkin, $checkout) is free of manual blocks and live-order bookings. */
function rafiki_is_range_available( $post_id, $checkin, $checkout ) {
	foreach ( rafiki_blocked_dates_for( $post_id ) as $blocked ) {
		if ( $blocked >= $checkin && $blocked < $checkout ) return false;
	}
	foreach ( rafiki_booked_ranges_for( $post_id ) as $range ) {
		if ( rafiki_ranges_overlap( $checkin, $checkout, $range[0], $range[1] ) ) return false;
	}
	return true;
}

/** True if a single day (activity/package booking) isn't manually blocked. */
function rafiki_is_date_available( $post_id, $date ) {
	return ! in_array( $date, rafiki_blocked_dates_for( $post_id ), true );
}

/* ------------------------------------------------------------------ */
/* Deposits: full payment, fixed (total or per person), or percentage   */
/* ------------------------------------------------------------------ */

function rafiki_deposit_type( $post_id ) {
	return get_post_meta( $post_id, 'rafiki_deposit_type', true ) ?: 'full';
}

/** How much of $full_total is due now at checkout, given the source post's deposit settings. */
function rafiki_deposit_due( $post_id, $full_total, $qty = 1 ) {
	$type = rafiki_deposit_type( $post_id );
	$due  = $full_total;

	if ( 'fixed_total' === $type ) {
		$amount = (float) get_post_meta( $post_id, 'rafiki_deposit_amount', true );
		if ( $amount > 0 ) $due = $amount;
	} elseif ( 'fixed_per_person' === $type ) {
		$amount = (float) get_post_meta( $post_id, 'rafiki_deposit_amount', true );
		if ( $amount > 0 ) $due = $amount * max( 1, (int) $qty );
	} elseif ( 'percent' === $type ) {
		$percent = (float) get_post_meta( $post_id, 'rafiki_deposit_percent', true );
		if ( $percent > 0 ) $due = $full_total * ( $percent / 100 );
	}

	return round( max( 0, min( $due, $full_total ) ), 2 );
}

/* ------------------------------------------------------------------ */
/* Add to cart: single day (activity/package) or check-in/check-out     */
/* (accommodation) + quantity, straight to checkout                    */
/* ------------------------------------------------------------------ */

function rafiki_validate_booking_add_to_cart( $passed, $product_id ) {
	if ( ! rafiki_booking_active() ) return $passed;
	$source_id = get_post_meta( $product_id, '_rafiki_source_post_id', true );
	if ( ! $source_id ) return $passed; // not one of ours

	$nonce = isset( $_POST['rafiki_booking_nonce'] ) ? wp_unslash( $_POST['rafiki_booking_nonce'] ) : '';
	if ( ! wp_verify_nonce( $nonce, 'rafiki_add_booking_' . $product_id ) ) {
		wc_add_notice( 'Invalid request, please try again.', 'error' );
		return false;
	}

	if ( 'accommodation' === get_post_type( $source_id ) ) {
		$checkin  = isset( $_POST['rafiki_checkin'] ) ? sanitize_text_field( wp_unslash( $_POST['rafiki_checkin'] ) ) : '';
		$checkout = isset( $_POST['rafiki_checkout'] ) ? sanitize_text_field( wp_unslash( $_POST['rafiki_checkout'] ) ) : '';

		if ( ! rafiki_is_valid_booking_date( $checkin ) || ! DateTime::createFromFormat( 'Y-m-d', $checkout ) || $checkout <= $checkin ) {
			wc_add_notice( 'Please select valid check-in and check-out dates.', 'error' );
			return false;
		}
		if ( ! rafiki_is_range_available( $source_id, $checkin, $checkout ) ) {
			wc_add_notice( 'Those dates are no longer available. Please choose different ones.', 'error' );
			return false;
		}
	} else {
		$date = isset( $_POST['rafiki_booking_date'] ) ? sanitize_text_field( wp_unslash( $_POST['rafiki_booking_date'] ) ) : '';
		if ( ! rafiki_is_valid_booking_date( $date ) ) {
			wc_add_notice( 'Please select a valid date (starting tomorrow).', 'error' );
			return false;
		}
		if ( ! rafiki_is_date_available( $source_id, $date ) ) {
			wc_add_notice( 'That date is no longer available. Please choose another.', 'error' );
			return false;
		}
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'rafiki_validate_booking_add_to_cart', 10, 2 );

function rafiki_add_cart_item_data( $cart_item_data, $product_id ) {
	if ( ! rafiki_booking_active() ) return $cart_item_data;
	$source_id = get_post_meta( $product_id, '_rafiki_source_post_id', true );
	if ( ! $source_id ) return $cart_item_data;

	if ( 'accommodation' === get_post_type( $source_id ) ) {
		$checkin  = isset( $_POST['rafiki_checkin'] ) ? sanitize_text_field( wp_unslash( $_POST['rafiki_checkin'] ) ) : '';
		$checkout = isset( $_POST['rafiki_checkout'] ) ? sanitize_text_field( wp_unslash( $_POST['rafiki_checkout'] ) ) : '';
		if ( rafiki_is_valid_booking_date( $checkin ) && $checkout > $checkin ) {
			$cart_item_data['rafiki_checkin']  = $checkin;
			$cart_item_data['rafiki_checkout'] = $checkout;
		}
	} else {
		$date = isset( $_POST['rafiki_booking_date'] ) ? sanitize_text_field( wp_unslash( $_POST['rafiki_booking_date'] ) ) : '';
		if ( rafiki_is_valid_booking_date( $date ) ) {
			$cart_item_data['rafiki_booking_date'] = $date;
		}
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'rafiki_add_cart_item_data', 10, 2 );

/**
 * Overrides each booking cart item's price to whatever is due now
 * (full price, unless a deposit is configured on the source post),
 * and — for accommodations — to nights × nightly rate instead of the
 * product's flat stored price. Runs late (priority 20) so it's the
 * final word on price for the cart calculation that follows.
 */
function rafiki_apply_dynamic_pricing( $cart ) {
	if ( ! rafiki_booking_active() ) return;
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

	foreach ( $cart->get_cart() as $cart_item ) {
		$product   = $cart_item['data'];
		$source_id = $product->get_meta( '_rafiki_source_post_id' );
		if ( ! $source_id ) continue;

		$rate = (float) get_post_meta( $source_id, 'rafiki_price_amount', true );
		$qty  = (int) $cart_item['quantity'];

		if ( ! empty( $cart_item['rafiki_checkin'] ) && ! empty( $cart_item['rafiki_checkout'] ) ) {
			$nights     = rafiki_nights_between( $cart_item['rafiki_checkin'], $cart_item['rafiki_checkout'] );
			$full_total = $rate * $nights;
			$due        = rafiki_deposit_due( $source_id, $full_total, 1 );
			$product->set_price( (string) $due ); // qty is always 1 for accommodation bookings
		} else {
			$full_total = $rate * $qty;
			$due        = rafiki_deposit_due( $source_id, $full_total, $qty );
			$product->set_price( (string) ( $qty > 0 ? $due / $qty : $due ) );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'rafiki_apply_dynamic_pricing', 20 );

/**
 * Recomputes full price / due-now / balance for a cart item, mirroring
 * rafiki_apply_dynamic_pricing's math, for display purposes (that hook
 * already mutated the product's live price, so this reads the *source*
 * post's rate fresh rather than trying to recover the original from the
 * now-overwritten product price).
 */
function rafiki_cart_item_pricing_summary( $cart_item ) {
	$source_id = $cart_item['data']->get_meta( '_rafiki_source_post_id' );
	if ( ! $source_id ) return null;

	$rate = (float) get_post_meta( $source_id, 'rafiki_price_amount', true );

	if ( ! empty( $cart_item['rafiki_checkin'] ) && ! empty( $cart_item['rafiki_checkout'] ) ) {
		$nights     = rafiki_nights_between( $cart_item['rafiki_checkin'], $cart_item['rafiki_checkout'] );
		$full_total = $rate * $nights;
		$due        = rafiki_deposit_due( $source_id, $full_total, 1 );
	} else {
		$qty        = (int) $cart_item['quantity'];
		$full_total = $rate * $qty;
		$due        = rafiki_deposit_due( $source_id, $full_total, $qty );
	}

	return array(
		'full_total' => round( $full_total, 2 ),
		'due'        => $due,
		'balance'    => round( $full_total - $due, 2 ),
	);
}

function rafiki_display_cart_item_date( $item_data, $cart_item ) {
	if ( ! empty( $cart_item['rafiki_checkin'] ) && ! empty( $cart_item['rafiki_checkout'] ) ) {
		$nights = rafiki_nights_between( $cart_item['rafiki_checkin'], $cart_item['rafiki_checkout'] );
		$item_data[] = array(
			'name'  => 'Check-in',
			'value' => date_i18n( get_option( 'date_format' ), strtotime( $cart_item['rafiki_checkin'] ) ),
		);
		$item_data[] = array(
			'name'  => 'Check-out',
			'value' => date_i18n( get_option( 'date_format' ), strtotime( $cart_item['rafiki_checkout'] ) ) . ' (' . $nights . ' night' . ( $nights > 1 ? 's' : '' ) . ')',
		);
	} elseif ( ! empty( $cart_item['rafiki_booking_date'] ) ) {
		$item_data[] = array(
			'name'  => 'Date',
			'value' => date_i18n( get_option( 'date_format' ), strtotime( $cart_item['rafiki_booking_date'] ) ),
		);
	}

	$summary = rafiki_cart_item_pricing_summary( $cart_item );
	if ( $summary && $summary['balance'] > 0 ) {
		$item_data[] = array(
			'name'  => 'Balance due',
			'value' => wc_price( $summary['balance'] ) . ' (paid on arrival)',
		);
	}

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'rafiki_display_cart_item_date', 10, 2 );

function rafiki_add_date_to_order_item( $item, $cart_item_key, $values ) {
	if ( ! empty( $values['rafiki_checkin'] ) && ! empty( $values['rafiki_checkout'] ) ) {
		$nights = rafiki_nights_between( $values['rafiki_checkin'], $values['rafiki_checkout'] );
		$item->add_meta_data( 'Check-in', date_i18n( get_option( 'date_format' ), strtotime( $values['rafiki_checkin'] ) ), true );
		$item->add_meta_data( 'Check-out', date_i18n( get_option( 'date_format' ), strtotime( $values['rafiki_checkout'] ) ) . ' (' . $nights . ' night' . ( $nights > 1 ? 's' : '' ) . ')', true );
		// Raw Y-m-d values, kept separately from the display-formatted meta
		// above, are what rafiki_booked_ranges_for() reads back to compute
		// availability for future bookings.
		$item->add_meta_data( '_rafiki_checkin', $values['rafiki_checkin'], true );
		$item->add_meta_data( '_rafiki_checkout', $values['rafiki_checkout'], true );
	} elseif ( ! empty( $values['rafiki_booking_date'] ) ) {
		$item->add_meta_data( 'Date', date_i18n( get_option( 'date_format' ), strtotime( $values['rafiki_booking_date'] ) ), true );
	}

	$summary = rafiki_cart_item_pricing_summary( $values );
	if ( $summary && $summary['balance'] > 0 ) {
		$item->add_meta_data( 'Balance due (paid on arrival)', wc_price( $summary['full_total'] ) . ' total — ' . wc_price( $summary['due'] ) . ' paid now — ' . wc_price( $summary['balance'] ) . ' due', true );
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'rafiki_add_date_to_order_item', 10, 3 );

function rafiki_redirect_to_checkout( $url ) {
	return rafiki_booking_active() ? wc_get_checkout_url() : $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'rafiki_redirect_to_checkout' );

/**
 * Runs just before WooCommerce's own add-to-cart handler (hooked on
 * wp_loaded at its default priority 20), so every "Reservar ahora" click
 * starts one clean booking instead of stacking onto whatever was left in
 * the session cart. There's no cart page or "keep shopping" concept in
 * this flow — booking a tour always replaces any other pending booking.
 */
function rafiki_empty_cart_before_booking() {
	if ( ! rafiki_booking_active() ) return;
	if ( empty( $_POST['add-to-cart'] ) || empty( $_POST['rafiki_booking_nonce'] ) ) return;
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) return;
	WC()->cart->empty_cart();
}
add_action( 'wp_loaded', 'rafiki_empty_cart_before_booking', 15 );

/**
 * WooCommerce's default "X has been added to your cart — View Cart" notice
 * is pure shop language and there's no cart page in this flow — suppress it
 * entirely so the customer just lands on checkout with nothing shown above
 * it. Note the filter is wc_add_to_cart_message_html (function-prefixed),
 * not woocommerce_add_to_cart_message_html — an earlier version of this
 * hook used the wrong name, which is why the default WooCommerce text was
 * still slipping through. Returning '' here is what actually suppresses
 * it: wc_add_notice() only queues a notice when the message is non-empty.
 */
function rafiki_add_to_cart_message( $message ) {
	return rafiki_booking_active() ? '' : $message;
}
add_filter( 'wc_add_to_cart_message_html', 'rafiki_add_to_cart_message' );

/* ------------------------------------------------------------------ */
/* 10-minute reservation hold: a countdown shown at checkout that       */
/* empties the cart if checkout isn't completed in time, so a picked    */
/* date doesn't sit claimed in someone's session indefinitely.          */
/* ------------------------------------------------------------------ */

function rafiki_set_reservation_expiry() {
	if ( ! rafiki_booking_active() ) return;
	if ( ! function_exists( 'WC' ) || ! WC()->session ) return;
	WC()->session->set( 'rafiki_reservation_expires', time() + 10 * MINUTE_IN_SECONDS );
}
add_action( 'woocommerce_add_to_cart', 'rafiki_set_reservation_expiry' );

/**
 * Runs before WooCommerce's own template-level "redirect to cart if empty"
 * check (which happens later, inside the checkout shortcode template) so we
 * can both enforce the 10-minute hold and send an empty cart somewhere that
 * actually has a styled page instead of WooCommerce's bare, un-enqueued-CSS
 * cart page (its styles are dequeued site-wide while booking is active —
 * see rafiki_dequeue_wc_styles).
 */
function rafiki_handle_checkout_cart_state() {
	if ( ! rafiki_booking_active() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) return;
	// The order-received/thank-you page is also is_checkout() === true, and
	// by design has an empty cart (WooCommerce just emptied it after placing
	// the order) — without this it would bounce the customer straight back
	// to the homepage instead of showing their own confirmation page.
	if ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) return;
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) return;

	if ( WC()->session && ! WC()->cart->is_empty() ) {
		$expires = (int) WC()->session->get( 'rafiki_reservation_expires' );
		if ( $expires && time() > $expires ) {
			WC()->cart->empty_cart();
			WC()->session->set( 'rafiki_reservation_expires', null );
			wc_add_notice( 'Your reservation hold expired after 10 minutes. Please pick your date again.', 'notice' );
		}
	}

	if ( WC()->cart->is_empty() ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}
add_action( 'template_redirect', 'rafiki_handle_checkout_cart_state' );

/** Countdown badge under the Step 1 recap card, shown only while a hold is active. */
function rafiki_checkout_reservation_timer() {
	if ( ! rafiki_booking_active() ) return;
	if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) return;
	?>
	<p class="rafiki-reservation-timer" id="rafiki-reservation-timer">Your spot is held for <strong id="rafiki-timer-value">10:00</strong></p>
	<?php
}
add_action( 'woocommerce_checkout_before_customer_details', 'rafiki_checkout_reservation_timer', 6 );

/** Secondary path for a customer who'd rather finish over WhatsApp than pay online right now. */
function rafiki_checkout_whatsapp_fallback() {
	if ( ! rafiki_booking_active() ) return;
	if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) return;

	$product = null;
	$meta    = array();
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		$product = $cart_item['data'];
		if ( ! empty( $cart_item['rafiki_checkin'] ) && ! empty( $cart_item['rafiki_checkout'] ) ) {
			$meta[] = $cart_item['rafiki_checkin'] . ' to ' . $cart_item['rafiki_checkout'];
		} elseif ( ! empty( $cart_item['rafiki_booking_date'] ) ) {
			$meta[] = $cart_item['rafiki_booking_date'];
		}
		break; // one booking at a time
	}
	if ( ! $product ) return;

	$message = 'Hi! I would like to book: ' . $product->get_name() . ( $meta ? ' (' . implode( ', ', $meta ) . ')' : '' );
	$url     = rafiki_whatsapp_link( $message );
	?>
	<p class="rafiki-checkout-whatsapp-alt">
		Prefer to finish over WhatsApp instead?
		<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo rafiki_whatsapp_icon_svg(); ?> Book via WhatsApp</a>
	</p>
	<?php
}
add_action( 'woocommerce_review_order_after_submit', 'rafiki_checkout_whatsapp_fallback' );

/* ------------------------------------------------------------------ */
/* Checkout: simplified fields, no shipping, no shop chrome            */
/* ------------------------------------------------------------------ */

function rafiki_simplify_checkout_fields( $fields ) {
	if ( ! rafiki_booking_active() ) return $fields;

	unset( $fields['shipping'] );
	foreach ( array( 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ) as $key ) {
		unset( $fields['billing'][ "billing_{$key}" ] );
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['required'] = true;
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'rafiki_simplify_checkout_fields' );

function rafiki_cart_needs_shipping( $needs_shipping ) {
	return rafiki_booking_active() ? false : $needs_shipping;
}
add_filter( 'woocommerce_cart_needs_shipping', 'rafiki_cart_needs_shipping' );

/* ------------------------------------------------------------------ */
/* Checkout: numbered "Paso 1/2/3" headings so this reads as a guided   */
/* booking flow instead of a generic WooCommerce form.                  */
/* ------------------------------------------------------------------ */

function rafiki_checkout_step_head( $number, $title ) {
	echo '<div class="rafiki-checkout-step-head"><span class="rafiki-step-badge">' . esc_html( $number ) . '</span><h2>' . esc_html( $title ) . '</h2></div>';
}

/**
 * Real wrapper <div>s around the two checkout "columns", opened/closed
 * via hooks rather than a CSS grouping trick. Needed because WooCommerce
 * doesn't put everything in one column in a single sibling div: the
 * "Additional information" block (order notes + our proof upload) is
 * rendered from checkout/form-shipping.php into #customer_details' own
 * "col-2" slot — i.e. nested *inside* #customer_details, not a sibling
 * after it — so #customer_details already contains the full left column
 * on its own. Wrapping it (and the step headings before it) in one real
 * container, and #order_review (and its heading) in another, lets each
 * side use plain independent-height flexbox instead of CSS Grid rows
 * shared between columns of very different heights.
 */
function rafiki_checkout_open_main() {
	if ( ! rafiki_booking_active() ) return;
	echo '<div class="rafiki-checkout-main">';
}
add_action( 'woocommerce_checkout_before_customer_details', 'rafiki_checkout_open_main', 5 );

function rafiki_checkout_close_main() {
	if ( ! rafiki_booking_active() ) return;
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_customer_details', 'rafiki_checkout_close_main' );

function rafiki_checkout_open_sidebar() {
	if ( ! rafiki_booking_active() ) return;
	echo '<div class="rafiki-checkout-sidebar">';
}
add_action( 'woocommerce_checkout_before_order_review', 'rafiki_checkout_open_sidebar', 5 );

function rafiki_checkout_close_sidebar() {
	if ( ! rafiki_booking_active() ) return;
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_order_review', 'rafiki_checkout_close_sidebar' );

/**
 * Paso 1 (what's being booked) + Paso 2 (contact) headings. Both are
 * injected outside #order_review, the only part of checkout WooCommerce
 * regenerates via AJAX on totals updates (see class-wc-ajax.php
 * update_order_review — it swaps the review table and #payment
 * specifically, nothing else), so unlike a hook inside payment.php these
 * render once and reliably stay put.
 */
function rafiki_checkout_steps_1_and_2() {
	if ( ! rafiki_booking_active() ) return;
	if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) return;

	$product = null;
	$meta    = array();
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		$product = $cart_item['data'];
		if ( ! empty( $cart_item['rafiki_checkin'] ) && ! empty( $cart_item['rafiki_checkout'] ) ) {
			$nights = rafiki_nights_between( $cart_item['rafiki_checkin'], $cart_item['rafiki_checkout'] );
			$meta[] = date_i18n( get_option( 'date_format' ), strtotime( $cart_item['rafiki_checkin'] ) ) . ' → ' . date_i18n( get_option( 'date_format' ), strtotime( $cart_item['rafiki_checkout'] ) );
			$meta[] = $nights . ' night' . ( $nights > 1 ? 's' : '' );
		} else {
			if ( ! empty( $cart_item['rafiki_booking_date'] ) ) {
				$meta[] = date_i18n( get_option( 'date_format' ), strtotime( $cart_item['rafiki_booking_date'] ) );
			}
			$qty = (int) $cart_item['quantity'];
			if ( $qty ) $meta[] = $qty . ' guest' . ( $qty > 1 ? 's' : '' );
		}
		break; // one booking at a time
	}
	if ( ! $product ) return;

	rafiki_checkout_step_head( 1, 'Your Booking' );
	?>
	<div class="rafiki-checkout-recap-card">
		<strong><?php echo esc_html( $product->get_name() ); ?></strong>
		<span><?php echo esc_html( implode( ' · ', $meta ) ); ?></span>
	</div>
	<?php
	rafiki_checkout_step_head( 2, 'Contact Details' );
}
add_action( 'woocommerce_checkout_before_customer_details', 'rafiki_checkout_steps_1_and_2' );

/** Replaces the native "Your order" heading (hidden via CSS), positioned in the sidebar column. */
function rafiki_checkout_summary_heading() {
	if ( ! rafiki_booking_active() ) return;
	echo '<h2 class="rafiki-checkout-summary-heading">Booking Summary</h2>';
}
add_action( 'woocommerce_checkout_before_order_review', 'rafiki_checkout_summary_heading' );

/** No "have a coupon?" shop chrome — this is a booking, not a purchase from a catalog. */
function rafiki_disable_coupons( $enabled ) {
	return rafiki_booking_active() ? false : $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'rafiki_disable_coupons' );

/** "Place order" reads like checking out of a cart; this is confirming a booking. */
function rafiki_order_button_text( $text ) {
	return rafiki_booking_active() ? 'Confirm & Pay' : $text;
}
add_filter( 'woocommerce_order_button_text', 'rafiki_order_button_text' );

/* ------------------------------------------------------------------ */
/* Bank transfer / SINPE Móvil: relabel BACS from our own settings     */
/* ------------------------------------------------------------------ */

function rafiki_filter_bacs_settings( $settings ) {
	if ( ! rafiki_booking_active() ) return $settings;
	if ( ! is_array( $settings ) ) $settings = array();

	$settings['enabled']     = 'yes';
	$settings['title']       = 'Bank Transfer / SINPE Móvil';
	$settings['description'] = 'Pay by bank transfer or SINPE Móvil and attach your proof of payment below. We\'ll confirm your booking once it\'s verified.';

	$lines  = array();
	$holder = get_option( 'rafiki_bank_account_holder' );
	$bank   = get_option( 'rafiki_bank_name' );
	$acct   = get_option( 'rafiki_bank_account_number' );
	$sinpe  = get_option( 'rafiki_sinpe_phone' );
	$extra  = get_option( 'rafiki_bank_instructions' );
	if ( $holder ) $lines[] = 'Account holder: ' . $holder;
	if ( $bank )   $lines[] = 'Bank: ' . $bank;
	if ( $acct )   $lines[] = 'Account / IBAN: ' . $acct;
	if ( $sinpe )  $lines[] = 'SINPE Móvil: ' . $sinpe;
	if ( $extra )  $lines[] = $extra;

	$settings['instructions'] = implode( "\n", $lines );
	$settings['account']      = array(); // we render our own instructions text above, not WC's native account table

	return $settings;
}
add_filter( 'option_woocommerce_bacs_settings', 'rafiki_filter_bacs_settings' );
// WordPress only fires option_{name} when the option row already exists;
// on a fresh install nobody has ever opened the BACS settings screen, so
// the row doesn't exist yet and only default_option_{name} fires instead.
// Hooking both means BACS comes forced-on with no manual WC setup step.
add_filter( 'default_option_woocommerce_bacs_settings', 'rafiki_filter_bacs_settings' );

/* ------------------------------------------------------------------ */
/* Proof of payment: async upload (works with WooCommerce's AJAX       */
/* checkout, which drops <input type=file> via plain form.serialize()) */
/* ------------------------------------------------------------------ */

/** Redirects uploads for this feature into a dedicated, non-public-by-default subdirectory. */
function rafiki_proof_upload_dir( $dirs ) {
	$dirs['subdir'] = '/rafiki-proofs';
	$dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
	$dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];
	return $dirs;
}

/** Best-effort: block directory listing / direct access where the webserver honors .htaccess. */
function rafiki_protect_proof_dir() {
	$dir  = wp_upload_dir();
	$path = $dir['basedir'] . '/rafiki-proofs';
	if ( ! file_exists( $path ) ) {
		wp_mkdir_p( $path );
	}
	if ( ! file_exists( $path . '/index.php' ) ) {
		@file_put_contents( $path . '/index.php', "<?php\n// Silence is golden.\n" );
	}
	if ( ! file_exists( $path . '/.htaccess' ) ) {
		@file_put_contents( $path . '/.htaccess', "Require all denied\n" );
	}
}

/**
 * Validates and stores one uploaded proof-of-payment file. Returns the
 * absolute server path on success, or a WP_Error. Never trusts the
 * client-supplied MIME type — sniffs the real one with finfo.
 */
function rafiki_store_proof_upload( $file ) {
	if ( empty( $file['tmp_name'] ) || ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== $file['error'] ) ) {
		return new WP_Error( 'upload_error', 'Error uploading the file.' );
	}
	if ( $file['size'] > 5 * MB_IN_BYTES ) {
		return new WP_Error( 'too_large', 'The file exceeds 5MB.' );
	}

	$allowed_mimes = array( 'image/jpeg', 'image/png', 'application/pdf' );
	$finfo         = finfo_open( FILEINFO_MIME_TYPE );
	$real_mime     = $finfo ? finfo_file( $finfo, $file['tmp_name'] ) : false;
	if ( $finfo ) finfo_close( $finfo );
	if ( ! $real_mime || ! in_array( $real_mime, $allowed_mimes, true ) ) {
		return new WP_Error( 'invalid_type', 'File type not allowed. Use JPG, PNG or PDF.' );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	rafiki_protect_proof_dir();

	add_filter( 'upload_dir', 'rafiki_proof_upload_dir' );
	$uploaded = wp_handle_upload( $file, array(
		'test_form'                => false,
		'mimes'                    => array(
			'jpg|jpeg' => 'image/jpeg',
			'png'      => 'image/png',
			'pdf'      => 'application/pdf',
		),
		'unique_filename_callback' => function ( $dir, $name, $ext ) {
			return wp_generate_password( 40, false, false ) . $ext;
		},
	) );
	remove_filter( 'upload_dir', 'rafiki_proof_upload_dir' );

	if ( isset( $uploaded['error'] ) ) {
		return new WP_Error( 'upload_failed', $uploaded['error'] );
	}

	return $uploaded['file']; // absolute server path — the public 'url' is intentionally never used/exposed
}

/** AJAX endpoint the checkout page calls immediately when a file is chosen. */
function rafiki_ajax_upload_proof() {
	if ( ! rafiki_booking_active() ) wp_send_json_error( array( 'message' => 'Booking is not enabled.' ), 400 );
	check_ajax_referer( 'rafiki_upload_proof', 'nonce' );
	if ( empty( $_FILES['file'] ) ) wp_send_json_error( array( 'message' => 'No file was received.' ), 400 );

	$result = rafiki_store_proof_upload( $_FILES['file'] );
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
	}

	$token = wp_generate_password( 32, false, false );
	set_transient( 'rafiki_proof_' . $token, $result, 6 * HOUR_IN_SECONDS );
	wp_send_json_success( array( 'token' => $token ) );
}
add_action( 'wp_ajax_rafiki_upload_proof', 'rafiki_ajax_upload_proof' );
add_action( 'wp_ajax_nopriv_rafiki_upload_proof', 'rafiki_ajax_upload_proof' );

/**
 * Resolves the proof file for the current checkout request: normally
 * the pre-uploaded token (see above — required because WooCommerce's
 * AJAX checkout submits form.serialize(), which drops file inputs), with
 * a direct $_FILES fallback for non-JS checkout submissions. Memoized so
 * the (potentially expensive) fallback upload only runs once per request.
 */
function rafiki_resolve_pending_proof() {
	static $resolved = false;
	static $done      = false;
	if ( $done ) return $resolved;
	$done = true;

	if ( ! empty( $_POST['rafiki_payment_proof_token'] ) ) {
		$token = sanitize_text_field( wp_unslash( $_POST['rafiki_payment_proof_token'] ) );
		$path  = get_transient( 'rafiki_proof_' . $token );
		$resolved = ( $path && file_exists( $path ) ) ? $path : false;
		return $resolved;
	}

	if ( ! empty( $_FILES['rafiki_payment_proof']['tmp_name'] ) ) {
		$result   = rafiki_store_proof_upload( $_FILES['rafiki_payment_proof'] );
		$resolved = is_wp_error( $result ) ? false : $result;
		return $resolved;
	}

	return $resolved;
}

function rafiki_render_proof_upload_field() {
	if ( ! rafiki_booking_active() ) return;
	?>
	<div id="rafiki-proof-upload" style="display:none;">
		<h3>Proof of Payment</h3>
		<p>If paying by bank transfer or SINPE Móvil, attach a photo or screenshot of your proof of payment (JPG, PNG or PDF, max 5MB).</p>
		<input type="file" id="rafiki_payment_proof_input" name="rafiki_payment_proof" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">
		<input type="hidden" name="rafiki_payment_proof_token" id="rafiki_payment_proof_token" value="">
		<p id="rafiki-proof-status"></p>
	</div>
	<?php
}
// Rendered outside #order_review so it survives WooCommerce's AJAX refresh
// of the order-review/payment section on every checkout update.
add_action( 'woocommerce_after_order_notes', 'rafiki_render_proof_upload_field' );

function rafiki_validate_proof_at_checkout() {
	if ( ! rafiki_booking_active() ) return;
	$method = isset( $_POST['payment_method'] ) ? sanitize_text_field( wp_unslash( $_POST['payment_method'] ) ) : '';
	if ( 'bacs' !== $method ) return;

	if ( ! rafiki_resolve_pending_proof() ) {
		wc_add_notice( 'Please attach proof of your bank transfer or SINPE Móvil payment before continuing.', 'error' );
	}
}
add_action( 'woocommerce_checkout_process', 'rafiki_validate_proof_at_checkout' );

function rafiki_attach_proof_to_order( $order_id ) {
	if ( ! rafiki_booking_active() ) return;
	$order = wc_get_order( $order_id );
	if ( ! $order || 'bacs' !== $order->get_payment_method() ) return;

	$path = rafiki_resolve_pending_proof();
	if ( ! $path ) return; // already blocked at checkout_process if this was required

	$order->update_meta_data( '_rafiki_payment_proof_path', $path );
	$order->add_order_note( 'Proof of payment attached by the customer.' );
	$order->save();

	if ( ! empty( $_POST['rafiki_payment_proof_token'] ) ) {
		delete_transient( 'rafiki_proof_' . sanitize_text_field( wp_unslash( $_POST['rafiki_payment_proof_token'] ) ) );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'rafiki_attach_proof_to_order' );

/* ------------------------------------------------------------------ */
/* Admin: surface the proof on the order screen via a private,         */
/* capability-checked download — never a public attachment URL         */
/* ------------------------------------------------------------------ */

function rafiki_add_proof_meta_box() {
	if ( ! rafiki_booking_active() ) return;
	$screens = array( 'shop_order' );
	if ( function_exists( 'wc_get_page_screen_id' ) ) {
		$screens[] = wc_get_page_screen_id( 'shop-order' );
	}
	foreach ( array_unique( $screens ) as $screen ) {
		add_meta_box( 'rafiki_payment_proof', 'Proof of Payment', 'rafiki_render_proof_meta_box', $screen, 'side', 'high' );
	}
}
add_action( 'add_meta_boxes', 'rafiki_add_proof_meta_box' );

function rafiki_render_proof_meta_box( $post_or_order ) {
	$order = ( $post_or_order instanceof WP_Post ) ? wc_get_order( $post_or_order->ID ) : $post_or_order;
	if ( ! $order ) { echo '<p>—</p>'; return; }

	$path = $order->get_meta( '_rafiki_payment_proof_path' );
	if ( ! $path || ! file_exists( $path ) ) {
		echo '<p>No proof of payment attached.</p>';
		return;
	}

	$order_id = $order->get_id();
	$url      = wp_nonce_url( admin_url( 'admin-post.php?action=rafiki_download_proof&order_id=' . $order_id ), 'rafiki_download_proof_' . $order_id );
	echo '<p><a href="' . esc_url( $url ) . '" class="button" target="_blank" rel="noopener">Download proof</a></p>';
}

function rafiki_download_proof() {
	$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
	$nonce    = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
	if ( ! $order_id || ! wp_verify_nonce( $nonce, 'rafiki_download_proof_' . $order_id ) ) {
		wp_die( 'Invalid request.', 400 );
	}
	if ( ! current_user_can( 'edit_shop_order', $order_id ) ) {
		wp_die( 'Not authorized.', 403 );
	}

	$order = wc_get_order( $order_id );
	$path  = $order ? $order->get_meta( '_rafiki_payment_proof_path' ) : '';
	if ( ! $path || ! file_exists( $path ) ) {
		wp_die( 'File not found.', 404 );
	}

	nocache_headers();
	header( 'Content-Type: ' . mime_content_type( $path ) );
	header( 'Content-Disposition: attachment; filename="payment-proof-order-' . $order_id . '.' . pathinfo( $path, PATHINFO_EXTENSION ) . '"' );
	header( 'Content-Length: ' . filesize( $path ) );
	readfile( $path );
	exit;
}
add_action( 'admin_post_rafiki_download_proof', 'rafiki_download_proof' );

/* ------------------------------------------------------------------ */
/* Order-received (thank-you) page: reservation-toned messaging,       */
/* wrapped in a styled card instead of WooCommerce's bare defaults —   */
/* the actual booking recap (tour, dates, amounts) already renders     */
/* automatically via WooCommerce's own woocommerce_thankyou ->         */
/* woocommerce_order_details_table hook, we're not duplicating it.     */
/* ------------------------------------------------------------------ */

function rafiki_thankyou_open_card() {
	if ( ! rafiki_booking_active() ) return;
	echo '<div class="rafiki-thankyou-card">';
}
add_action( 'woocommerce_before_thankyou', 'rafiki_thankyou_open_card' );

/** Priority 999 so it closes after everything thankyou.php renders, including the order details table added at priority 10. */
function rafiki_thankyou_close_card() {
	if ( ! rafiki_booking_active() ) return;
	echo '</div>';
}
add_action( 'woocommerce_thankyou', 'rafiki_thankyou_close_card', 999 );

/** Swaps the generic "Thank you. Your order has been received." for reservation wording, explicit about the pending-approval state while a bank transfer / SINPE proof is awaiting manual review. */
function rafiki_thankyou_message( $message, $order ) {
	if ( ! rafiki_booking_active() || ! $order ) return $message;

	if ( $order->has_status( 'on-hold' ) ) {
		return "Thank you for your reservation! We're now verifying your payment — you'll receive a confirmation email as soon as it's approved.";
	}
	if ( $order->has_status( array( 'processing', 'completed' ) ) ) {
		return 'Thank you for your reservation! Your payment was received — see you soon at Rafiki.';
	}
	return 'Thank you for your reservation!';
}
add_filter( 'woocommerce_thankyou_order_received_text', 'rafiki_thankyou_message', 10, 2 );

/* ------------------------------------------------------------------ */
/* Customer emails: WooCommerce sends these automatically on the       */
/* matching order-status transition (on-hold for a pending bank/SINPE  */
/* proof, processing/completed once payment clears) — no extra code    */
/* needed for that part, only reservation-toned wording here so it     */
/* reads the same as the rest of the flow. Delivery itself depends on  */
/* SMTP being configured (WP's default mail() often gets flagged as    */
/* spam or blocked outright by hosts).                                 */
/* ------------------------------------------------------------------ */

function rafiki_email_heading_on_hold( $heading ) {
	return rafiki_booking_active() ? 'Your reservation is pending approval' : $heading;
}
add_filter( 'woocommerce_email_heading_customer_on_hold_order', 'rafiki_email_heading_on_hold' );

function rafiki_email_subject_on_hold( $subject ) {
	return rafiki_booking_active() ? '[Rafiki Safari Lodge] Your reservation is pending approval' : $subject;
}
add_filter( 'woocommerce_email_subject_customer_on_hold_order', 'rafiki_email_subject_on_hold' );

function rafiki_email_heading_confirmed( $heading ) {
	return rafiki_booking_active() ? 'Your reservation is confirmed' : $heading;
}
add_filter( 'woocommerce_email_heading_customer_processing_order', 'rafiki_email_heading_confirmed' );
add_filter( 'woocommerce_email_heading_customer_completed_order', 'rafiki_email_heading_confirmed' );

function rafiki_email_subject_confirmed( $subject ) {
	return rafiki_booking_active() ? '[Rafiki Safari Lodge] Your reservation is confirmed' : $subject;
}
add_filter( 'woocommerce_email_subject_customer_processing_order', 'rafiki_email_subject_confirmed' );
add_filter( 'woocommerce_email_subject_customer_completed_order', 'rafiki_email_subject_confirmed' );

/* ------------------------------------------------------------------ */
/* Checkout page assets                                                */
/* ------------------------------------------------------------------ */

/**
 * WooCommerce's own frontend.css (float/flex rules for .col-1/.col-2,
 * light-grey payment boxes, etc.) fights our theming instead of just
 * being overridden by it — cascade order and selector specificity in
 * WooCommerce's stylesheet aren't guaranteed to lose to ours. Rather
 * than chase that with !important, hand the checkout page's appearance
 * entirely to assets/css/style.css, which now covers every element WC
 * would otherwise style itself.
 */
function rafiki_dequeue_wc_styles( $enqueue_styles ) {
	return rafiki_booking_active() ? array() : $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'rafiki_dequeue_wc_styles' );

function rafiki_checkout_assets() {
	if ( ! rafiki_booking_active() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) return;
	$js_path = get_template_directory() . '/assets/js/checkout.js';
	wp_enqueue_script( 'rafiki-checkout', get_template_directory_uri() . '/assets/js/checkout.js', array(), file_exists( $js_path ) ? filemtime( $js_path ) : '1.0', true );

	$expires_at = 0;
	if ( function_exists( 'WC' ) && WC()->session ) {
		$expires_at = (int) WC()->session->get( 'rafiki_reservation_expires' );
	}
	wp_localize_script( 'rafiki-checkout', 'rafikiCheckout', array(
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( 'rafiki_upload_proof' ),
		'expiresAt' => $expires_at ? $expires_at * 1000 : 0, // ms, for Date.now() comparisons
	) );
}
add_action( 'wp_enqueue_scripts', 'rafiki_checkout_assets' );
