<?php
/**
 * Single branch point between the two booking UIs:
 * - online booking ON + this tour has a purchasable linked product -> a
 *   colored availability calendar + "Reserve Now" (accommodations pick a
 *   check-in/check-out range; activities & packages pick a single day plus
 *   a guest count, since pricing there is price x guests, not nights x rate)
 * - anything else (off, WooCommerce inactive, no price set) -> the original
 *   external "Check Availability" + WhatsApp buttons, byte-for-byte unchanged.
 *
 * Expects (via get_template_part's $args, extracted below): $post_id,
 * and optionally $book_now_label (defaults to "Check Availability").
 *
 * This partial is included twice per single-*.php template (sidebar +
 * closing banner), so every id is suffixed with a per-render unique
 * token rather than just $post_id, to avoid duplicate-id form fields.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! isset( $post_id ) ) $post_id = get_the_ID();
if ( ! isset( $book_now_label ) ) $book_now_label = 'Check Availability';

$booking_product = null;
if ( rafiki_booking_active() ) {
	$linked_id = get_post_meta( $post_id, '_rafiki_wc_product_id', true );
	if ( $linked_id ) {
		$candidate = wc_get_product( $linked_id );
		if ( $candidate && $candidate->is_purchasable() ) {
			$booking_product = $candidate;
		}
	}
}
?>
<?php if ( $booking_product ) :
	$uid             = 'rb_' . substr( md5( uniqid( '', true ) ), 0, 8 );
	$is_accommodation = ( 'accommodation' === get_post_type( $post_id ) );
	$min_date         = wp_date( 'Y-m-d', strtotime( '+1 day' ) );
	$blocked_dates    = rafiki_blocked_dates_for( $post_id );
	$booked_ranges    = $is_accommodation ? rafiki_booked_ranges_for( $post_id ) : array();

	$deposit_type = rafiki_deposit_type( $post_id );
	$deposit_note = '';
	if ( 'fixed_total' === $deposit_type ) {
		$amount = get_post_meta( $post_id, 'rafiki_deposit_amount', true );
		$deposit_note = $amount ? 'Book with a $' . esc_html( $amount ) . ' deposit — the rest is paid on arrival.' : '';
	} elseif ( 'fixed_per_person' === $deposit_type ) {
		$amount = get_post_meta( $post_id, 'rafiki_deposit_amount', true );
		$deposit_note = $amount ? 'Book with a $' . esc_html( $amount ) . ' deposit per person — the rest is paid on arrival.' : '';
	} elseif ( 'percent' === $deposit_type ) {
		$percent = get_post_meta( $post_id, 'rafiki_deposit_percent', true );
		$deposit_note = $percent ? 'Book with a ' . esc_html( $percent ) . '% deposit — the rest is paid on arrival.' : '';
	}
	?>
	<form method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>" class="booking-form" id="<?php echo esc_attr( $uid ); ?>_form">
		<?php wp_nonce_field( 'rafiki_add_booking_' . $booking_product->get_id(), 'rafiki_booking_nonce' ); ?>
		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $booking_product->get_id() ); ?>">

		<div class="rafiki-calendar-wrap">
			<div class="rafiki-calendar-summary">
				<?php if ( $is_accommodation ) : ?>
					<div class="rafiki-calendar-field">
						<span class="rafiki-calendar-label">Check-in</span>
						<span class="rafiki-calendar-value" id="<?php echo esc_attr( $uid ); ?>_in_display">Select a date</span>
					</div>
					<div class="rafiki-calendar-field">
						<span class="rafiki-calendar-label">Check-out</span>
						<span class="rafiki-calendar-value" id="<?php echo esc_attr( $uid ); ?>_out_display">Select a date</span>
					</div>
				<?php else : ?>
					<div class="rafiki-calendar-field">
						<span class="rafiki-calendar-label">Date</span>
						<span class="rafiki-calendar-value" id="<?php echo esc_attr( $uid ); ?>_date_display">Select a date</span>
					</div>
				<?php endif; ?>
			</div>
			<div class="rafiki-calendar" id="<?php echo esc_attr( $uid ); ?>_cal"></div>
			<p class="rafiki-calendar-legend"><span class="rafiki-calendar-dot rafiki-calendar-dot--available"></span> Available <span class="rafiki-calendar-dot rafiki-calendar-dot--unavailable"></span> Unavailable</p>
		</div>

		<?php if ( $is_accommodation ) : ?>
			<input type="hidden" name="rafiki_checkin" id="<?php echo esc_attr( $uid ); ?>_in">
			<input type="hidden" name="rafiki_checkout" id="<?php echo esc_attr( $uid ); ?>_out">
		<?php else : ?>
			<input type="hidden" name="rafiki_booking_date" id="<?php echo esc_attr( $uid ); ?>_date">
			<div class="booking-form-row">
				<label for="<?php echo esc_attr( $uid ); ?>_qty">Guests</label>
				<input type="number" id="<?php echo esc_attr( $uid ); ?>_qty" name="quantity" value="1" min="1" max="20">
			</div>
		<?php endif; ?>

		<p class="booking-form-error" id="<?php echo esc_attr( $uid ); ?>_error" style="display:none;"></p>
		<?php if ( $deposit_note ) : ?>
			<p class="booking-form-deposit-note"><?php echo esc_html( $deposit_note ); ?></p>
		<?php endif; ?>

		<button type="submit" class="btn btn-primary" id="<?php echo esc_attr( $uid ); ?>_submit" disabled>Reserve Now</button>
	</form>
	<script>
	(function () {
		var uid = <?php echo wp_json_encode( $uid ); ?>;
		var mode = <?php echo wp_json_encode( $is_accommodation ? 'range' : 'single' ); ?>;
		var blocked = <?php echo wp_json_encode( array_values( $blocked_dates ) ); ?>;
		var ranges = <?php echo wp_json_encode( array_values( $booked_ranges ) ); ?>;
		var minDateStr = <?php echo wp_json_encode( $min_date ); ?>;

		var form = document.getElementById( uid + '_form' );
		if ( ! form ) return;
		var errorEl    = document.getElementById( uid + '_error' );
		var submitBtn  = document.getElementById( uid + '_submit' );
		var calBody    = document.getElementById( uid + '_cal' );
		var dateDisplay, inDisplay, outDisplay, dateField, inField, outField;

		if ( 'range' === mode ) {
			inDisplay  = document.getElementById( uid + '_in_display' );
			outDisplay = document.getElementById( uid + '_out_display' );
			inField    = document.getElementById( uid + '_in' );
			outField   = document.getElementById( uid + '_out' );
		} else {
			dateDisplay = document.getElementById( uid + '_date_display' );
			dateField   = document.getElementById( uid + '_date' );
		}

		var checkin  = null;
		var checkout = null;

		var minParts = minDateStr.split( '-' );
		var today    = new Date( parseInt( minParts[ 0 ], 10 ), parseInt( minParts[ 1 ], 10 ) - 1, parseInt( minParts[ 2 ], 10 ) );
		var viewYear  = today.getFullYear();
		var viewMonth = today.getMonth();
		var todayYear  = viewYear;
		var todayMonth = viewMonth;

		function pad2( n ) { return ( n < 10 ? '0' : '' ) + n; }
		function fmt( y, m, d ) { return y + '-' + pad2( m + 1 ) + '-' + pad2( d ); }
		function parseDateStr( s ) {
			var p = s.split( '-' );
			return new Date( parseInt( p[ 0 ], 10 ), parseInt( p[ 1 ], 10 ) - 1, parseInt( p[ 2 ], 10 ) );
		}
		function formatDisplay( s ) {
			return parseDateStr( s ).toLocaleDateString( 'en-US', { weekday: 'short', month: 'short', day: 'numeric' } );
		}
		function isBlockedDate( dateStr ) {
			if ( blocked.indexOf( dateStr ) !== -1 ) return true;
			for ( var i = 0; i < ranges.length; i++ ) {
				if ( dateStr >= ranges[ i ][ 0 ] && dateStr < ranges[ i ][ 1 ] ) return true;
			}
			return false;
		}
		function isPastDate( dateStr ) { return dateStr < minDateStr; }

		function showError( msg ) {
			errorEl.textContent = msg;
			errorEl.style.display = msg ? '' : 'none';
		}

		function updateSubmit() {
			submitBtn.disabled = ( 'range' === mode ) ? ! ( checkin && checkout ) : ! checkin;
		}

		function renderCalendar() {
			var first        = new Date( viewYear, viewMonth, 1 );
			var startWeekday = first.getDay();
			var daysInMonth  = new Date( viewYear, viewMonth + 1, 0 ).getDate();
			var monthLabel   = first.toLocaleString( 'en-US', { month: 'long', year: 'numeric' } );
			var weekdays     = [ 'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' ];

			var html = '<div class="rafiki-calendar-header">'
				+ '<button type="button" class="rafiki-calendar-nav" data-nav="prev" aria-label="Previous month">‹</button>'
				+ '<span class="rafiki-calendar-month">' + monthLabel + '</span>'
				+ '<button type="button" class="rafiki-calendar-nav" data-nav="next" aria-label="Next month">›</button>'
				+ '</div><div class="rafiki-calendar-weekdays">';
			for ( var w = 0; w < weekdays.length; w++ ) html += '<span>' + weekdays[ w ] + '</span>';
			html += '</div><div class="rafiki-calendar-grid">';

			for ( var i = 0; i < startWeekday; i++ ) html += '<span class="rafiki-calendar-day rafiki-calendar-day--empty"></span>';

			for ( var d = 1; d <= daysInMonth; d++ ) {
				var dateStr  = fmt( viewYear, viewMonth, d );
				var disabled = isPastDate( dateStr ) || isBlockedDate( dateStr );
				var classes  = [ 'rafiki-calendar-day', disabled ? 'rafiki-calendar-day--unavailable' : 'rafiki-calendar-day--available' ];

				if ( 'range' === mode ) {
					if ( checkin && checkout && dateStr > checkin && dateStr < checkout ) classes.push( 'rafiki-calendar-day--inrange' );
					if ( dateStr === checkin ) classes.push( 'rafiki-calendar-day--checkin' );
					if ( dateStr === checkout ) classes.push( 'rafiki-calendar-day--checkout' );
				} else if ( dateStr === checkin ) {
					classes.push( 'rafiki-calendar-day--selected' );
				}

				html += '<button type="button" class="' + classes.join( ' ' ) + '" data-date="' + dateStr + '"' + ( disabled ? ' disabled' : '' ) + '>' + d + '</button>';
			}
			html += '</div>';
			calBody.innerHTML = html;

			var prevBtn = calBody.querySelector( '[data-nav="prev"]' );
			if ( prevBtn ) prevBtn.disabled = ( viewYear === todayYear && viewMonth === todayMonth );
		}

		function onDaySelect( dateStr ) {
			if ( 'single' === mode ) {
				checkin = dateStr;
				dateField.value = dateStr;
				dateDisplay.textContent = formatDisplay( dateStr );
				showError( '' );
			} else {
				if ( ! checkin || checkout ) {
					checkin = dateStr;
					checkout = null;
				} else if ( dateStr <= checkin ) {
					checkin = dateStr;
					checkout = null;
				} else {
					var conflict = false;
					var cur = parseDateStr( checkin );
					var end = parseDateStr( dateStr );
					while ( cur < end ) {
						if ( isBlockedDate( fmt( cur.getFullYear(), cur.getMonth(), cur.getDate() ) ) ) { conflict = true; break; }
						cur.setDate( cur.getDate() + 1 );
					}
					if ( conflict ) {
						showError( 'Those dates include an unavailable day. Please choose a different range.' );
						checkin = dateStr;
						checkout = null;
					} else {
						checkout = dateStr;
						showError( '' );
					}
				}
				inField.value  = checkin || '';
				outField.value = checkout || '';
				inDisplay.textContent  = checkin ? formatDisplay( checkin ) : 'Select a date';
				outDisplay.textContent = checkout ? formatDisplay( checkout ) : 'Select a date';
			}
			renderCalendar();
			updateSubmit();
		}

		calBody.addEventListener( 'click', function ( e ) {
			var target = e.target;
			if ( ! target ) return;
			var nav = target.getAttribute( 'data-nav' );
			if ( nav ) {
				viewMonth += ( 'next' === nav ) ? 1 : -1;
				if ( viewMonth < 0 ) { viewMonth = 11; viewYear--; }
				if ( viewMonth > 11 ) { viewMonth = 0; viewYear++; }
				renderCalendar();
				return;
			}
			if ( target.classList.contains( 'rafiki-calendar-day' ) && ! target.disabled ) {
				onDaySelect( target.getAttribute( 'data-date' ) );
			}
		} );

		renderCalendar();
		updateSubmit();
	})();
	</script>
<?php else : ?>
	<div class="btn-group">
		<a href="<?php echo esc_url( rafiki_booking_link( $post_id ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener"><?php echo esc_html( $book_now_label ); ?></a>
		<a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to book: ' . get_the_title( $post_id ) ) ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener"><?php echo rafiki_whatsapp_icon_svg(); ?> Book via WhatsApp</a>
	</div>
<?php endif; ?>
