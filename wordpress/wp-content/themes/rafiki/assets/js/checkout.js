(function () {
	'use strict';

	function getUploadBox() {
		return document.getElementById( 'rafiki-proof-upload' );
	}

	function toggleForMethod( method ) {
		var box = getUploadBox();
		if ( ! box ) return;
		box.style.display = ( 'bacs' === method ) ? '' : 'none';
	}

	// Delegated: the payment method radios live inside WooCommerce's
	// #order_review panel, which gets replaced wholesale on every AJAX
	// checkout totals update, so a directly-bound listener would stop
	// working after the first refresh.
	document.addEventListener( 'change', function ( e ) {
		if ( e.target && 'payment_method' === e.target.name ) {
			toggleForMethod( e.target.value );
		}
	} );

	document.addEventListener( 'DOMContentLoaded', function () {
		var checked = document.querySelector( 'input[name="payment_method"]:checked' );
		if ( checked ) toggleForMethod( checked.value );
	} );

	// Reservation hold countdown. On expiry we just reload the page — the
	// server-side check (rafiki_handle_checkout_cart_state) is the actual
	// source of truth, this is purely the visible ticking clock.
	function initReservationTimer() {
		var el = document.getElementById( 'rafiki-timer-value' );
		if ( ! el || typeof window.rafikiCheckout === 'undefined' || ! window.rafikiCheckout.expiresAt ) return;

		var expiresAt = window.rafikiCheckout.expiresAt;
		var interval;

		function tick() {
			var remaining = Math.round( ( expiresAt - Date.now() ) / 1000 );
			if ( remaining <= 0 ) {
				clearInterval( interval );
				window.location.reload();
				return;
			}
			var m = Math.floor( remaining / 60 );
			var s = remaining % 60;
			el.textContent = m + ':' + ( s < 10 ? '0' : '' ) + s;
		}

		tick();
		interval = setInterval( tick, 1000 );
	}
	document.addEventListener( 'DOMContentLoaded', initReservationTimer );

	// Uploaded immediately (rather than at form submit) because
	// WooCommerce's AJAX checkout posts form.serialize(), which silently
	// drops file inputs; a pre-uploaded token travels as a plain hidden
	// text field instead.
	document.addEventListener( 'change', function ( e ) {
		if ( e.target && 'rafiki_payment_proof_input' === e.target.id ) {
			uploadProof( e.target.files && e.target.files[ 0 ] );
		}
	} );

	function uploadProof( file ) {
		var status = document.getElementById( 'rafiki-proof-status' );
		var tokenField = document.getElementById( 'rafiki_payment_proof_token' );
		if ( ! file || ! tokenField || typeof window.rafikiCheckout === 'undefined' ) return;

		tokenField.value = '';
		if ( status ) status.textContent = 'Uploading proof...';

		var data = new FormData();
		data.append( 'action', 'rafiki_upload_proof' );
		data.append( 'nonce', window.rafikiCheckout.nonce );
		data.append( 'file', file );

		fetch( window.rafikiCheckout.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: data,
		} )
			.then( function ( res ) { return res.json(); } )
			.then( function ( json ) {
				if ( json && json.success && json.data && json.data.token ) {
					tokenField.value = json.data.token;
					if ( status ) status.textContent = 'Proof ready: ' + file.name;
				} else {
					if ( status ) status.textContent = ( json && json.data && json.data.message ) || 'Could not upload the proof.';
				}
			} )
			.catch( function () {
				if ( status ) status.textContent = 'Could not upload the proof. Please try again.';
			} );
	}
})();
