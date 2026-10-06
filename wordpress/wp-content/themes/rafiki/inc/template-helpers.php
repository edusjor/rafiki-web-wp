<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_rows( $post_id, $meta_key ) {
	$rows = get_post_meta( $post_id, $meta_key, true );
	return is_array( $rows ) ? $rows : array();
}

/** One paragraph per non-empty line. */
function rafiki_paragraphs( $text ) {
	$lines = array_filter( array_map( 'trim', explode( "\n", (string) $text ) ) );
	$html  = '';
	foreach ( $lines as $line ) {
		$html .= '<p>' . esc_html( $line ) . '</p>';
	}
	return $html;
}

function rafiki_testimonial_source_svg( $source ) {
	$icons = array(
		'google' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>',
		'tripadvisor' => '<svg viewBox="0 0 24 24" width="24" height="24"><circle cx="12" cy="12" r="11" fill="#34E0A1"/><circle cx="8" cy="12" r="3.2" fill="#fff"/><circle cx="16" cy="12" r="3.2" fill="#fff"/><circle cx="8" cy="12" r="1.3" fill="#1a1a1a"/><circle cx="16" cy="12" r="1.3" fill="#1a1a1a"/></svg>',
		'facebook' => '<svg viewBox="0 0 24 24" width="24" height="24"><circle cx="12" cy="12" r="11" fill="#1877F2"/><path fill="#fff" d="M13.5 21v-7.2h2.4l.36-2.8h-2.76V9.1c0-.81.22-1.36 1.39-1.36h1.48V5.2c-.26-.03-1.14-.11-2.16-.11-2.14 0-3.6 1.31-3.6 3.7v2.21H8.2v2.8h2.41V21h2.89z"/></svg>',
		'yelp' => '<svg viewBox="0 0 24 24" width="24" height="24"><circle cx="12" cy="12" r="11" fill="#D32323"/><path fill="#fff" d="M11.2 12.9 6.8 14.4c-.6.2-1.2-.3-1.1-.9.2-1.6.5-3.9.9-4.9.2-.5.9-.6 1.3-.2l3.6 3.3c.5.4.2 1.2-.3 1.2zm.8 1.7 2.9 3.6c.4.5 0 1.2-.6 1.2-1.1 0-2.9-.1-3.9-.5-.5-.2-.6-.9-.2-1.3l1-1c.8-.9 1.6-1.5 2.8-2zm1.6-2.2 4.1-1.9c.6-.3 1.3.2 1.1.8-.4 1.5-1.1 3.6-1.7 4.5-.3.4-1 .5-1.3.1l-2.7-3c-.4-.4-.1-1.1.5-1.5zm-1.1-1.9-.9-4.5c-.1-.6.5-1.1 1-.9 1.4.6 3.4 1.6 4.1 2.4.4.4.2 1.1-.3 1.3l-3.1 1.8c-.4.3-.7-.1-.8-.1z"/></svg>',
	);
	return isset( $icons[ $source ] ) ? $icons[ $source ] : $icons['google'];
}

function rafiki_testimonial_source_label( $source ) {
	$labels = array( 'google' => 'Google Review', 'tripadvisor' => 'TripAdvisor Review', 'facebook' => 'Facebook Review', 'yelp' => 'Yelp Review' );
	return isset( $labels[ $source ] ) ? $labels[ $source ] : 'Review';
}

/** WhatsApp number from General Settings, normalized with country code. */
function rafiki_whatsapp_number() {
	$digits = get_option( 'rafiki_whatsapp_number', '50683689944' );
	$digits = preg_replace( '/[^0-9]/', '', (string) $digits );
	if ( 8 === strlen( $digits ) ) {
		$digits = '506' . $digits;
	}
	return $digits;
}

function rafiki_whatsapp_link( $message = '' ) {
	$url = 'https://wa.me/' . rafiki_whatsapp_number();
	if ( $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

function rafiki_whatsapp_icon_svg() {
	return '<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.36A10 10 0 1 0 12 2zm5.9 14.2c-.25.7-1.45 1.34-2 1.42-.5.08-1.15.11-1.86-.12-.43-.14-.98-.32-1.68-.63-2.96-1.28-4.9-4.24-5.04-4.44-.15-.2-1.2-1.6-1.2-3.05 0-1.45.76-2.16 1.03-2.46.27-.3.6-.37.8-.37h.57c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.07.15.12.33.02.53-.1.2-.15.32-.3.5-.15.18-.32.4-.45.53-.15.15-.31.32-.13.62.18.3.8 1.32 1.72 2.14 1.18 1.05 2.18 1.38 2.48 1.53.3.15.48.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.28.1 1.75.83 2.05 .98.3.15.5.22.57.35.08.13.08.75-.17 1.45z"/></svg>';
}

/**
 * "Book Now" link for an accommodation / package / activity: its own
 * "Booking Link" field if the admin set one, otherwise the site-wide
 * Beds24 URL from Rafiki Settings (rafiki_beds24_url()).
 */
function rafiki_booking_link( $post_id ) {
	$link = get_post_meta( $post_id, 'rafiki_booking_link', true );
	return $link ? $link : rafiki_beds24_url();
}

/**
 * URL for the Lekker Bar & Braai: its "accommodation" post (the current
 * info page) if it exists, otherwise the dedicated /lekker-bar-braai/ page.
 * One source of truth for the menu, footer and the /stay/ "Food at Rafiki" link.
 */
function rafiki_lekker_url() {
	$post = rafiki_find_post_by_keyword( 'accommodation', 'Lekker' );
	return $post ? get_permalink( $post ) : home_url( '/lekker-bar-braai/' );
}

/**
 * True for "accommodation" posts that aren't actually bookable and are
 * shown in the Stay listing for information only — the Lekker Bar & Braai
 * (the lodge bar/restaurant, included with every stay). Their detail page
 * drops the booking CTA and keeps just a WhatsApp "ask a question" button.
 */
function rafiki_is_info_only_stay( $post_id ) {
	$t = strtolower( (string) get_the_title( $post_id ) );
	return ( false !== strpos( $t, 'lekker' ) || false !== strpos( $t, 'braai' ) );
}

/**
 * The "Beach Camp" accommodation post, used by the header/footer/home to link
 * straight to it. Looks up the `rafiki_is_beach_camp` flag first (set on the
 * accommodation's Page Content meta box); falls back to matching the post
 * title "Beach Camp" for sites that haven't set the flag yet.
 */
function rafiki_beach_camp_post() {
	static $post = false;
	if ( false !== $post ) return $post;

	$flagged = get_posts( array(
		'post_type'      => 'accommodation',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'meta_key'       => 'rafiki_is_beach_camp',
		'meta_value'     => '1',
	) );
	if ( $flagged ) {
		$post = $flagged[0];
		return $post;
	}

	$by_title = get_posts( array( 'post_type' => 'accommodation', 'title' => 'Beach Camp', 'posts_per_page' => 1, 'post_status' => 'publish' ) );
	$post = $by_title ? $by_title[0] : null;
	return $post;
}

/** First published post of $post_type whose title contains $keyword, or null. */
function rafiki_find_post_by_keyword( $post_type, $keyword ) {
	$found = get_posts( array(
		'post_type'      => $post_type,
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		's'              => $keyword,
	) );
	return $found ? $found[0] : null;
}

/** Returns the first gallery image URL, or the featured image, as a fallback for heros/CTAs. */
function rafiki_lead_image_url( $post_id, $size = 'large' ) {
	if ( 'accommodation' === get_post_type( $post_id ) && ( $set = rafiki_photo_set( rafiki_stay_photo_key( $post_id ) ) ) ) {
		return $set[0]['url'];
	}
	$gallery = rafiki_rows( $post_id, 'rafiki_gallery' );
	if ( ! empty( $gallery[0]['image'] ) ) {
		$url = wp_get_attachment_image_url( $gallery[0]['image'], $size );
		if ( $url ) return $url;
	}
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, $size );
	}
	return '';
}

/* ------------------------------------------------------------------ */
/* Stay selection hand-off between the Stay selection page            */
/* (page-templates/template-stay-copia.php), single-accommodation.php  */
/* and single-package.php, carried in the query string:               */
/*   ?checkin=Y-m-d&checkout=Y-m-d&guests=N&tent=<accommodation id>    */
/* ------------------------------------------------------------------ */

/**
 * URL of the stay selection page. That is the /stay/ accommodation archive
 * (archive-accommodation.php, the booking-first page). Kept as a helper so
 * every detail-page "back to your stay" link has one source of truth.
 */
function rafiki_stay_selection_page_url() {
	return get_post_type_archive_link( 'accommodation' );
}

/**
 * Reads and validates the current request's stay selection from $_GET.
 * Returns an array with: checkin, checkout (Y-m-d or ''), guests (int),
 * tent (accommodation post id or 0), tent_post (WP_Post|null), nights (int).
 */
function rafiki_stay_selection() {
	$sel = array(
		'checkin'   => '',
		'checkout'  => '',
		'guests'    => 0,
		'tent'      => 0,
		'tent_post' => null,
		'nights'    => 0,
	);

	$ci = isset( $_GET['checkin'] ) ? sanitize_text_field( wp_unslash( $_GET['checkin'] ) ) : '';
	$co = isset( $_GET['checkout'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout'] ) ) : '';
	$d1 = DateTime::createFromFormat( 'Y-m-d', $ci );
	$d2 = DateTime::createFromFormat( 'Y-m-d', $co );
	if ( $d1 && $d1->format( 'Y-m-d' ) === $ci && $d2 && $d2->format( 'Y-m-d' ) === $co && $co > $ci ) {
		$sel['checkin']  = $ci;
		$sel['checkout'] = $co;
		$sel['nights']   = (int) $d1->diff( $d2 )->days;
	}

	if ( isset( $_GET['guests'] ) ) {
		$g = (int) $_GET['guests'];
		if ( $g > 0 && $g <= 40 ) {
			$sel['guests'] = $g;
		}
	}

	if ( isset( $_GET['tent'] ) ) {
		$tid = (int) $_GET['tent'];
		if ( $tid > 0 && 'accommodation' === get_post_type( $tid ) && 'publish' === get_post_status( $tid ) ) {
			$sel['tent']      = $tid;
			$sel['tent_post'] = get_post( $tid );
		}
	}

	return $sel;
}

/** Builds a link back to the stay selection page carrying the given selection + optional #hash. */
function rafiki_stay_selection_url( $args = array(), $hash = '' ) {
	$clean = array();
	foreach ( array( 'checkin', 'checkout', 'guests', 'tent' ) as $k ) {
		if ( ! empty( $args[ $k ] ) ) {
			$clean[ $k ] = $args[ $k ];
		}
	}
	$url = rafiki_stay_selection_page_url();
	if ( $clean ) {
		$url = add_query_arg( $clean, $url );
	}
	if ( $hash ) {
		$url .= '#' . ltrim( $hash, '#' );
	}
	return $url;
}

/** "Sat, Sep 12 – Mon, Sep 14 · 2 nights" style label for a selection. */
function rafiki_stay_selection_label( $sel ) {
	if ( empty( $sel['checkin'] ) || empty( $sel['checkout'] ) ) {
		return '';
	}
	$fmt = 'M j';
	$in  = date_i18n( $fmt, strtotime( $sel['checkin'] ) );
	$out = date_i18n( $fmt, strtotime( $sel['checkout'] ) );
	$n   = (int) $sel['nights'];
	$label = $in . ' – ' . $out . ' · ' . $n . ' night' . ( 1 === $n ? '' : 's' );
	if ( ! empty( $sel['guests'] ) ) {
		$label .= ' · ' . $sel['guests'] . ' guest' . ( 1 === (int) $sel['guests'] ? '' : 's' );
	}
	return $label;
}

/**
 * Whether the page at $path is published. Hard-coded menu links to a page
 * check this so a page switched to draft drops out of the nav instead of
 * leaving a link to a 404.
 */
function rafiki_page_is_published( $path ) {
	$page = get_page_by_path( $path );
	return $page && 'publish' === $page->post_status;
}

/* ------------------------------------------------------------------ */
/* Client photo sets (assets/img/photos/)                              */
/* Bundled with the theme so they ship with the repo. Where a set      */
/* exists for an accommodation it wins over the post's gallery meta.   */
/* ------------------------------------------------------------------ */

/** URL of a bundled photo, e.g. rafiki_photo( 'tents-1' ). */
function rafiki_photo( $name ) {
	return get_template_directory_uri() . '/assets/img/photos/' . $name . '.webp';
}

/** Named photo sets: key => list of array( file, alt ). */
function rafiki_photo_set( $key ) {
	$sets = array(
		'home'   => array(
			array( 'property-and-food-19', 'Aerial view of the Main Lodge and pool in the rainforest' ),
			array( 'property-and-food-22', 'Luxury safari tent deck surrounded by rainforest' ),
			array( 'activities-35', 'Twin waterfall in the rainforest of the reserve' ),
			array( 'lodge-heliconia', 'The Main Lodge seen through heliconia flowers' ),
			array( 'tent-sunset', 'Sunset over the valley from a safari tent' ),
			array( 'property-and-food-5', 'Guests sharing dinner and drinks at the Lekker Bar' ),
		),
		'lodge'  => array(
			array( 'property-and-food-19', 'Aerial view of the Main Lodge, pool and gardens' ),
			array( 'property-and-food-14', 'The Main Lodge thatched roof against the forest' ),
			array( 'lodge-heliconia', 'The Main Lodge seen through heliconia flowers' ),
			array( 'property-and-food-18', 'Aerial view of the ponds and gardens around the lodge' ),
			array( 'property-and-food-12', 'Wooden bridge into the rainforest gardens' ),
		),
		'tents'  => array(
			array( 'property-and-food-22', 'Safari tent deck raised above the rainforest' ),
			array( 'tents-1', 'Inside a safari tent: real bed and canvas walls' ),
			array( 'tents-10', 'Safari tent bedroom open to the forest' ),
			array( 'tents-5', 'Private bathroom with walk-in shower' ),
			array( 'tents-15', 'Tent deck with table and rocking chairs' ),
			array( 'tents-4', 'Family tent with a view out to the valley' ),
			array( 'tents-9', 'Deck seating looking into the tent' ),
			array( 'tents-3', 'Family tent with double and single beds' ),
			array( 'tents-17', 'King bed under the canvas roof' ),
			array( 'tent-sunset', 'Sunset over the valley from a safari tent' ),
			array( 'tent-garden', 'Safari tents among the gardens' ),
			array( 'tent-bridge', 'Footbridge up to a safari tent' ),
		),
		'lekker' => array(
			array( 'lekker-dinner', 'Dinner under the thatched roof of the Lekker Bar & Braai' ),
			array( 'property-and-food-3', 'Cocktails being made at the bar' ),
			array( 'property-and-food-6', 'Grilled steak with mashed potatoes and vegetables' ),
			array( 'property-and-food-5', 'Guests sharing dinner and drinks' ),
			array( 'property-and-food-7', 'Candlelit table for two' ),
			array( 'property-and-food-9', 'Breakfast with a view over the valley' ),
			array( 'lekker-deck-sunset', 'Guests watching the sunset from the deck' ),
		),
		'story'  => array(
			array( 'property-and-food-22', 'Safari tent raised above the rainforest' ),
			array( 'tent-bridge', 'Footbridge up to a safari tent' ),
			array( 'tents-9', 'Inside an African-style safari tent' ),
			array( 'tent-garden', 'Safari tents among the gardens' ),
			array( 'cabin-horizontal', 'A safari tent hidden in the forest' ),
			array( 'tent-sunset', 'Sunset over the valley from a safari tent' ),
		),
		'eco'    => array(
			array( 'place-wildlife-9', 'Hummingbird at a heliconia flower' ),
			array( 'activities-49', 'Tree frog on a leaf at night' ),
			array( 'activities-35', 'Twin waterfall in the rainforest' ),
			array( 'property-and-food-18', 'Aerial view of the forest and ponds' ),
			array( 'activities-42', 'Hiking up a rainforest river' ),
			array( 'activities-44', 'Waterfall pool in the reserve' ),
			array( 'property-and-food-12', 'Wooden bridge into the rainforest' ),
		),
	);
	if ( empty( $sets[ $key ] ) ) return array();
	return array_map( function ( $p ) {
		return array( 'url' => rafiki_photo( $p[0] ), 'alt' => $p[1] );
	}, $sets[ $key ] );
}

/** Which bundled set belongs to an accommodation post ('' if none). */
function rafiki_stay_photo_key( $post_id ) {
	$t = strtolower( get_the_title( $post_id ) );
	if ( false !== strpos( $t, 'lekker' ) || false !== strpos( $t, 'braai' ) ) return 'lekker';
	if ( false !== strpos( $t, 'tent' ) ) return 'tents';
	if ( false !== strpos( $t, 'lodge' ) ) return 'lodge';
	return '';
}

/**
 * Hero photo for a package page. Each package gets its own bundled photo
 * (their galleries all lead with the same rafting shot); falls back to the
 * lead image for any package not listed here.
 */
function rafiki_package_hero_url( $post_id ) {
	$heroes = array(
		'rafiki-safari'       => 'img-3784',          // family floating down the river
		'safarito'            => 'img-1190',          // lodge deck at sunset
		'savegre-adventure'   => 'place-wildlife-24', // Savegre valley at sunset
		'super-lekker-safari' => 'img-1448',          // pool with misty mountains
	);
	$slug = get_post_field( 'post_name', $post_id );
	return isset( $heroes[ $slug ] ) ? rafiki_photo( $heroes[ $slug ] ) : rafiki_lead_image_url( $post_id, 'full' );
}

/**
 * .gallery-grid tile classes for photo $i of $n: the lead photo takes a 2x2
 * block, and the last few are widened so every 4-column row ends flush
 * (no holes, whatever the photo count).
 */
function rafiki_gallery_span( $i, $n ) {
	if ( 0 === $i ) return 'span-2 span-2-row';
	$extra = ( 4 - ( ( $n + 3 ) % 4 ) ) % 4;
	return $i >= $n - $extra ? 'span-2' : '';
}

/** Renders a photo set with the .gallery-grid layout used on stay pages. */
function rafiki_photo_grid( $photos ) {
	$n = count( $photos );
	foreach ( $photos as $i => $p ) {
		$span = rafiki_gallery_span( $i, $n );
		printf( '<img class="%s" src="%s" alt="%s" loading="lazy">', esc_attr( $span ), esc_url( $p['url'] ), esc_attr( $p['alt'] ) );
	}
}
