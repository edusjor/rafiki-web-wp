<?php
/**
 * Template Name: Stay - Copia
 *
 * Booking-first version of the /stay/ page, wired as a normal Page so it
 * shows in wp-admin as "Stay - Copia" without touching the live
 * archive-accommodation.php.
 *
 * The Home already did the work of selling the desire, so this page is
 * mostly a choice-and-booking screen, not another long marketing page:
 *
 *   1. Short hero
 *   2. Choose your dates  ->  which safari tents are available  [#availability]
 *   3. Book the tent on its own OR make it a package            [#packages]
 *   4. Supporting content, condensed (why stay, 2/3 nights, what's
 *      in the tent, food, group stays)
 *   5. Add individual activities                                 [#activities]
 *   6. Final CTA
 *
 * The date picker filters the tent cards client-side using each
 * accommodation's real blocked dates + nights already held by a live
 * order (rafiki_blocked_dates_for / rafiki_booked_ranges_for). When
 * online booking is active and a tent has a purchasable linked product,
 * "Select This Tent" posts the chosen check-in/check-out straight to the
 * WooCommerce cart (same contract as template-parts/booking-cta.php);
 * otherwise it falls back to WhatsApp with the dates in the message.
 * Does not touch archive-accommodation.php or single-accommodation.php.
 */
get_header();

$stay_url     = get_post_type_archive_link( 'accommodation' );
$packages_url = get_post_type_archive_link( 'package' );
$activities_url = get_post_type_archive_link( 'activity' );

$booking_on = function_exists( 'rafiki_booking_active' ) && rafiki_booking_active();

$beach_camp    = function_exists( 'rafiki_beach_camp_post' ) ? rafiki_beach_camp_post() : null;
$beach_camp_id = $beach_camp ? (int) $beach_camp->ID : 0;

$all_accommodations = get_posts( array( 'post_type' => 'accommodation', 'posts_per_page' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );

$tent_post      = $all_accommodations ? $all_accommodations[0] : null;
$tent_amenities = $tent_post ? rafiki_rows( $tent_post->ID, 'rafiki_amenities' ) : array();

/* ---- Build the bookable-tent list + the JSON the date filter runs on ---- */
$tents    = array();
$js_tents = array();

foreach ( $all_accommodations as $acc ) {
	$id = (int) $acc->ID;
	if ( $id === $beach_camp_id ) continue; // Beach Camp is a separate property, not a stay option here

	$img        = rafiki_lead_image_url( $id, 'rafiki-card' );
	if ( ! $img ) $img = rafiki_lead_image_url( $id, 'large' );
	$sub        = get_post_meta( $id, 'rafiki_subtitle', true );
	$price      = get_post_meta( $id, 'rafiki_price', true );
	$price_unit = get_post_meta( $id, 'rafiki_price_unit', true );
	if ( ! $price_unit ) $price_unit = '/ night';
	$facts      = rafiki_rows( $id, 'rafiki_quick_facts' );

	$capacity = '';
	foreach ( $facts as $f ) {
		$label = strtolower( isset( $f['label'] ) ? $f['label'] : '' );
		if ( preg_match( '/sleep|guest|capacit|occupan|people|persons?/', $label ) ) {
			$capacity = $f['value'];
			break;
		}
	}

	// Nightly rate used for the "Stay Only" total after a tent is picked:
	// the numeric online-booking rate if set, otherwise the first number in
	// the display price ("$189 / night" -> 189).
	$rate = (float) get_post_meta( $id, 'rafiki_price_amount', true );
	if ( $rate <= 0 && $price && preg_match( '/\d+(?:\.\d+)?/', str_replace( ',', '', $price ), $m ) ) {
		$rate = (float) $m[0];
	}

	$product_id = (int) get_post_meta( $id, '_rafiki_wc_product_id', true );
	$product    = ( $booking_on && $product_id && function_exists( 'wc_get_product' ) ) ? wc_get_product( $product_id ) : null;
	$bookable   = $product && $product->is_purchasable();

	$tents[] = array(
		'post'       => $acc,
		'img'        => $img,
		'sub'        => $sub,
		'price'      => $price,
		'price_unit' => $price_unit,
		'facts'      => $facts,
		'capacity'   => $capacity,
		'rate'       => $rate,
		'bookable'   => $bookable,
		'product_id' => $bookable ? $product->get_id() : 0,
	);

	$js_tents[] = array(
		'id'        => $id,
		'name'      => get_the_title( $acc ),
		'permalink' => get_permalink( $acc ),
		'rate'      => $rate,
		'priceText' => $price ? ( '$' . $price ) : '',
		'bookable'  => $bookable,
		'blocked'   => array_values( rafiki_blocked_dates_for( $id ) ),
		'ranges'    => array_values( rafiki_booked_ranges_for( $id ) ),
	);
}

$min_checkin  = wp_date( 'Y-m-d', strtotime( '+1 day' ) );
$wa_base_url  = 'https://wa.me/' . rafiki_whatsapp_number();
?>

<style>
  /* ===== Stay - Copia: date search + tent results (light context) ===== */
  #availability { padding-top: 84px; }

  /* --- Date block: a real inline calendar (light) --- */
  .stay-search {
    background: #fff;
    border: 1px solid var(--cream-2);
    border-radius: var(--radius);
    padding: 26px;
    max-width: 900px;
    margin: 0 auto 22px;
    box-shadow: 0 14px 40px rgba(32,28,22,0.10);
  }
  .stay-search-grid { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 28px; align-items: start; }

  .stay-cal-summary { display: flex; gap: 12px; margin-bottom: 14px; }
  .stay-cal-summary > div {
    flex: 1; display: flex; flex-direction: column; gap: 3px;
    padding: 10px 13px; border: 1px solid var(--cream-2); border-radius: var(--radius); background: var(--cream);
  }
  .stay-cal-summary span { font-size: 10.5px; font-weight: 700; letter-spacing: 0.7px; text-transform: uppercase; color: var(--text-dark-muted); }
  .stay-cal-summary strong { font-size: 13.5px; color: var(--text-dark); }
  .stay-cal-summary > .cal-sum { transition: box-shadow 0.15s ease, border-color 0.15s ease; }
  .stay-cal-summary > .cal-sum.is-active {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(232,121,26,0.18);
  }
  .stay-cal-summary > .cal-sum.is-active span { color: var(--orange); }

  .stay-cal { border: 1px solid var(--cream-2); border-radius: var(--radius); padding: 14px; }
  .stay-cal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
  .stay-cal-month { font-family: var(--font-head); font-size: 17px; letter-spacing: 0.5px; color: var(--text-dark); }
  .stay-cal-nav {
    width: 28px; height: 28px; border-radius: 50%; border: 1px solid #d7d0c1; background: #fff;
    color: var(--text-dark); font-size: 16px; line-height: 1; cursor: pointer; padding: 0;
    display: flex; align-items: center; justify-content: center;
  }
  .stay-cal-nav:hover:not(:disabled) { border-color: var(--orange); color: var(--orange); }
  .stay-cal-nav:disabled { opacity: 0.3; cursor: default; }
  .stay-cal-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 10px; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-dark-muted); margin-bottom: 6px; }
  .stay-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; }
  .stay-cal-day {
    aspect-ratio: 1; border: none; background: transparent; color: var(--text-dark);
    font-family: var(--font-body); font-size: 12.5px; border-radius: 50%; cursor: pointer;
    display: flex; align-items: center; justify-content: center; padding: 0;
  }
  .stay-cal-day--empty { visibility: hidden; cursor: default; }
  .stay-cal-day:not(.stay-cal-day--past):not(.stay-cal-day--empty):hover { background: var(--cream-2); }
  .stay-cal-day--past { color: #c8c0b0; cursor: not-allowed; }
  .stay-cal-day--checkin,
  .stay-cal-day--checkout { background: var(--orange); color: #fff; font-weight: 700; }
  .stay-cal-day--inrange { background: rgba(232,121,26,0.18); border-radius: 0; }

  .stay-search-side { display: flex; flex-direction: column; gap: 16px; }
  .stay-field { display: flex; flex-direction: column; gap: 7px; }
  .stay-field label { font-size: 11px; font-weight: 700; letter-spacing: 0.9px; text-transform: uppercase; color: var(--text-dark-muted); }
  .stay-field select {
    font-family: var(--font-body); font-size: 15px; padding: 13px 14px; border-radius: var(--radius);
    border: 1px solid #d7d0c1; background: var(--cream); color: var(--text-dark); width: 100%;
  }
  .stay-field select:focus { outline: none; border-color: var(--orange); }
  .stay-search-side .btn { width: 100%; justify-content: center; height: 50px; }
  .stay-search-side .btn:disabled { opacity: 0.4; cursor: not-allowed; }
  .stay-search-hint { font-size: 12.5px; color: var(--text-dark-muted); line-height: 1.5; margin: 0; }
  .stay-field select:invalid { color: var(--text-dark-muted); }

  #stay-search-status {
    text-align: center; max-width: 900px; margin: 0 auto 48px;
    font-size: 17px; color: var(--text-dark-muted);
  }
  #stay-search-status strong { color: var(--text-dark); }

  .stay-results {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 28px;
  }
  .stay-card {
    display: flex; flex-direction: column;
    background: #fff; border: 1px solid var(--cream-2);
    border-radius: var(--radius); overflow: hidden;
  }
  .stay-results.has-search .stay-card[data-available="0"] { opacity: 0.55; }
  .stay-card-media { position: relative; aspect-ratio: 16 / 10; background: var(--cream-2); }
  .stay-card-media img { width: 100%; height: 100%; object-fit: cover; }
  .stay-card-body { display: flex; flex-direction: column; flex: 1; padding: 24px; }
  .stay-card-body h3 { font-size: 23px; text-transform: uppercase; margin: 0 0 8px; }
  .stay-card-specs {
    font-size: 13.5px; font-weight: 600; color: var(--text-dark);
    margin: 0 0 6px; letter-spacing: 0.2px;
  }
  .stay-card-diff { font-size: 13.5px; line-height: 1.55; color: var(--text-dark-muted); margin: 0 0 16px; }

  /* availability pill — reads in a glance */
  .stay-card-avail {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 12.5px; font-weight: 700; letter-spacing: 0.4px;
    text-transform: uppercase; padding: 7px 12px; border-radius: 999px;
    margin-bottom: 14px;
  }
  .stay-card-avail::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
  .stay-card-avail.is-idle { background: var(--cream-2); color: var(--text-dark-muted); }
  .stay-card-avail.is-available { background: #e4f2e8; color: #1f7a3d; }
  .stay-card-avail.is-unavailable { background: #efe9e2; color: #8a6f4a; }

  .stay-card-price {
    font-size: 14px; color: var(--text-dark-muted); margin: 0 0 20px;
    padding-top: 14px; border-top: 1px solid var(--cream-2);
  }
  .stay-card-price strong { font-family: var(--font-head); font-size: 30px; color: var(--text-dark); letter-spacing: 0.4px; }
  .stay-card-price span { display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 2px; }

  .stay-card-actions { margin-top: auto; display: flex; flex-direction: column; gap: 10px; }
  .stay-card-actions .btn { width: 100%; justify-content: center; white-space: nowrap; }
  .stay-card-actions form { display: flex; }
  .stay-card-actions form .btn { width: 100%; }
  .stay-card-actions .btn[disabled] { opacity: 0.4; pointer-events: none; }
  #stay-select-hint { text-align: center; margin: 24px auto 0; font-size: 14px; color: var(--text-dark-muted); }

  /* --- Stay Only vs Packages comparison --- */
  .stay-compare {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 22px;
    align-items: stretch; margin-top: 44px;
  }
  .compare-card {
    display: flex; flex-direction: column; background: #fff;
    border: 1px solid var(--cream-2); border-radius: var(--radius); padding: 28px;
  }
  .compare-card.is-base { border-color: var(--text-dark); }
  .compare-card .compare-kicker {
    font-size: 11px; font-weight: 700; letter-spacing: 0.9px; text-transform: uppercase;
    color: var(--orange); margin-bottom: 8px;
  }
  .compare-card h3 { font-size: 21px; text-transform: uppercase; margin: 0 0 4px; }
  .compare-card .compare-tagline { font-size: 13.5px; color: var(--text-dark-muted); margin: 0 0 18px; }
  .compare-list { list-style: none; margin: 0 0 20px; padding: 0; display: flex; flex-direction: column; gap: 9px; }
  .compare-list li { position: relative; padding-left: 24px; font-size: 14px; line-height: 1.5; color: var(--text-dark); }
  .compare-list li::before {
    content: ""; position: absolute; left: 0; top: 3px; width: 14px; height: 14px;
    border-radius: 50%; background: var(--orange);
    -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z'/%3E%3C/svg%3E") center/12px no-repeat;
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z'/%3E%3C/svg%3E") center/12px no-repeat;
  }
  .compare-plus { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-dark-muted); margin: 0 0 10px; }
  .compare-card .compare-price { font-family: var(--font-head); font-size: 24px; color: var(--text-dark); margin: 0 0 16px; letter-spacing: 0.4px; }
  .compare-card .compare-price small { font-family: var(--font-body); font-size: 12.5px; color: var(--text-dark-muted); font-weight: 400; letter-spacing: 0; }
  .compare-card .btn { margin-top: auto; width: 100%; justify-content: center; }
  .compare-card .stay-only-form { margin-top: auto; width: 100%; }
  .compare-card .stay-only-form .btn { margin-top: 0; }
  .compare-card .stay-only-form[hidden] { display: none; }
  .compare-card .btn[hidden] { display: none; }
  .stay-picked[hidden] { display: none; }
  .compare-list li.is-context { color: var(--text-dark-muted); }
  .compare-list li.is-context::before { background: var(--text-dark-muted); }

  /* --- selected tent state --- */
  .stay-card.is-selected { border-color: var(--orange); box-shadow: 0 0 0 2px var(--orange); }
  .stay-card-tag {
    position: absolute; left: 12px; top: 12px; z-index: 2;
    background: var(--orange); color: #fff; font-size: 11.5px; font-weight: 700;
    letter-spacing: 0.5px; text-transform: uppercase; padding: 6px 11px; border-radius: 999px;
  }
  .stay-card-actions .btn.is-selected-btn { background: #fff; color: var(--orange); border: 1px solid var(--orange); }

  /* --- "Your stay" recap inside #packages --- */
  .stay-picked {
    display: flex; align-items: center; flex-wrap: wrap; gap: 6px 14px;
    max-width: 720px; margin: 28px auto 0; padding: 14px 20px;
    background: #fff; border: 1px solid var(--orange); border-radius: var(--radius);
  }
  .stay-picked-label { font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: var(--orange); }
  .stay-picked strong { font-size: 15px; color: var(--text-dark); }
  .stay-picked #stay-picked-meta { font-size: 14px; color: var(--text-dark-muted); }
  .stay-picked-change {
    margin-left: auto; background: none; border: none; cursor: pointer;
    font-size: 12.5px; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase;
    color: var(--text-dark-muted); text-decoration: underline;
  }
  .stay-picked-change:hover { color: var(--orange); }

  /* --- sticky booking bar --- */
  .stay-sticky {
    position: fixed; left: 0; right: 0; bottom: 0; z-index: 60;
    background: var(--dark); color: #fff;
    transform: translateY(110%); transition: transform 0.25s ease;
    box-shadow: 0 -8px 30px rgba(0,0,0,0.25);
    pointer-events: none;
  }
  .stay-sticky.is-visible { transform: none; pointer-events: auto; }
  .stay-sticky-inner { display: flex; align-items: center; gap: 16px; padding: 14px 24px; }
  .stay-sticky-info { display: flex; flex-direction: column; line-height: 1.35; min-width: 0; }
  .stay-sticky-info span { font-size: 10.5px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: rgba(255,255,255,0.55); }
  .stay-sticky-info strong { font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .stay-sticky .btn { margin-left: auto; white-space: nowrap; flex-shrink: 0; }

  @media (max-width: 820px) {
    .stay-search-grid { grid-template-columns: 1fr; }
    .stay-results { grid-template-columns: 1fr; }
  }
  @media (max-width: 560px) {
    .stay-compare { grid-template-columns: 1fr; }
    .stay-sticky-inner { padding: 10px 16px; gap: 10px; }
    .stay-sticky .btn { padding: 10px 16px; font-size: 13px; }
  }
</style>

<!-- ===== 01. HERO (short) ===== -->
<section class="page-hero">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'tents-19' ) ); ?>" alt="Safari tent at Rafiki Safari Lodge, surrounded by rainforest">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Stay</span></p>
      <span class="eyebrow">Book Your Stay</span>
      <h1>STAY IN THE FOREST.</h1>
      <p class="hero-sub">If you don&rsquo;t find availability for your travel dates, contact us &mdash; we may be able to create a custom option for you.</p>
      <div class="hero-actions">
        <a href="#availability" class="btn btn-primary">Check Dates</a>
        <a href="#packages" class="btn btn-outline">See Packages</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. CHOOSE YOUR DATES + AVAILABLE TENTS ===== -->
<section class="section" id="availability">
  <div class="container">
    <span class="eyebrow center">Your Dates</span>
    <h2 class="section-title center" style="margin-bottom:28px;">WHEN DO YOU WANT TO COME?</h2>

    <div class="stay-search">
      <form id="stay-search-form">
        <div class="stay-search-grid">
          <div>
            <div class="stay-cal-summary">
              <div class="cal-sum" id="cal-sum-in"><span>Check-in</span><strong id="stay-in-display">Select a date</strong></div>
              <div class="cal-sum" id="cal-sum-out"><span>Check-out</span><strong id="stay-out-display">Select a date</strong></div>
            </div>
            <div class="stay-cal" id="stay-cal"></div>
          </div>
          <div class="stay-search-side">
            <div class="stay-field">
              <label for="stay-guests">Guests</label>
              <select id="stay-guests" required>
                <option value="" selected disabled>How many guests?</option>
                <?php for ( $g = 1; $g <= 8; $g++ ) : ?>
                  <option value="<?php echo $g; ?>"><?php echo $g; ?><?php echo $g === 8 ? '+' : ''; ?> guest<?php echo $g === 1 ? '' : 's'; ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary" id="stay-search-btn" disabled>Search Availability</button>
            <p class="stay-search-hint" id="stay-search-hint">Pick your check-in and check-out days, then your guests.</p>
          </div>
        </div>
        <input type="hidden" id="stay-checkin">
        <input type="hidden" id="stay-checkout">
      </form>
    </div>

    <p id="stay-search-status">Pick your dates to see which safari tents are open.</p>

    <?php if ( $tents ) : ?>
      <div class="stay-results" id="stay-results">
        <?php foreach ( $tents as $i => $t ) :
          $p          = $t['post'];
          $permalink  = get_permalink( $p );
          $title      = get_the_title( $p );

          $specs = array();
          if ( $t['capacity'] ) {
            // Normalise whatever the Quick Fact says ("4–5 people", "2-4", "Sleeps 4")
            // to one consistent "<n> guests" chip so every card reads the same way.
            $cap_num = preg_replace( '/\b(sleeps?|up to|people|persons?|guests?|pax|max\.?)\b/i', '', $t['capacity'] );
            $cap_num = trim( (string) $cap_num, " .,-" );
            if ( '' !== $cap_num ) $specs[] = ( '1' === $cap_num ? '1 guest' : $cap_num . ' guests' );
          }
          $specs[] = 'Private bathroom';

          $unit_label = trim( ltrim( (string) $t['price_unit'], '/ ' ) );
          if ( '' === $unit_label ) $unit_label = 'per night';
        ?>
          <article class="stay-card" data-tent-idx="<?php echo (int) $i; ?>" data-available="1">
            <div class="stay-card-media">
              <?php if ( $t['img'] ) : ?><img src="<?php echo esc_url( $t['img'] ); ?>" alt="<?php echo esc_attr( $title ); ?>"><?php endif; ?>
              <span class="stay-card-tag" data-selected-tag hidden>&#10003; Selected stay</span>
            </div>
            <div class="stay-card-body">
              <h3><?php echo esc_html( $title ); ?></h3>
              <p class="stay-card-specs"><?php echo esc_html( implode( ' · ', $specs ) ); ?></p>
              <?php if ( $t['sub'] ) : ?><p class="stay-card-diff"><?php echo esc_html( wp_trim_words( $t['sub'], 16 ) ); ?></p><?php endif; ?>

              <span class="stay-card-avail is-idle" data-avail>Select dates to check availability</span>

              <?php if ( $t['price'] ) : ?>
                <p class="stay-card-price">From <strong>$<?php echo esc_html( $t['price'] ); ?></strong><span><?php echo esc_html( $unit_label ); ?></span></p>
              <?php endif; ?>

              <div class="stay-card-actions">
                <button type="button" class="btn btn-primary" data-select-stay disabled>Select This Stay</button>
                <a class="btn btn-outline" data-tent-cta data-href="<?php echo esc_url( $permalink ); ?>" href="<?php echo esc_url( $permalink ); ?>" style="border-color:var(--text-dark); color:var(--text-dark);">View Tent</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <p id="stay-select-hint">Search your dates, then <strong>Select This Stay</strong> &mdash; you'll choose Stay Only or a package next.</p>

      <p style="text-align:center; margin-top:36px;">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like help finding availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener" style="border-color: var(--text-dark); color: var(--text-dark);">Can't find your dates? Ask us on WhatsApp</a>
      </p>
    <?php else : ?>
      <p style="text-align:center;">No accommodations published yet.</p>
    <?php endif; ?>
  </div>
</section>

<script>
document.addEventListener( 'DOMContentLoaded', function () {
  var TENTS = <?php echo wp_json_encode( $js_tents ); ?>;
  var MIN_DATE = <?php echo wp_json_encode( $min_checkin ); ?>;
  var WA_URL = <?php echo wp_json_encode( $wa_base_url ); ?>;
  var form = document.getElementById( 'stay-search-form' );
  if ( ! form ) return;

  var inField   = document.getElementById( 'stay-checkin' );
  var outField  = document.getElementById( 'stay-checkout' );
  var inDisp    = document.getElementById( 'stay-in-display' );
  var outDisp   = document.getElementById( 'stay-out-display' );
  var guestsEl  = document.getElementById( 'stay-guests' );
  var statusEl  = document.getElementById( 'stay-search-status' );
  var calEl     = document.getElementById( 'stay-cal' );
  var grid      = document.getElementById( 'stay-results' );
  if ( ! grid || ! calEl ) return;
  var cards = Array.prototype.slice.call( grid.querySelectorAll( '.stay-card' ) );

  var pkgSection   = document.getElementById( 'packages' );
  var pickedEl     = document.getElementById( 'stay-picked' );
  var pickedName   = document.getElementById( 'stay-picked-name' );
  var pickedMeta   = document.getElementById( 'stay-picked-meta' );
  var pickedChange = document.getElementById( 'stay-picked-change' );
  var stayTagline  = document.getElementById( 'stay-only-tagline' );
  var stayPriceEl  = document.getElementById( 'stay-only-price' );
  var stayForms    = Array.prototype.slice.call( document.querySelectorAll( '.stay-only-form' ) );
  var stayFallback = document.getElementById( 'stay-only-fallback' );
  var stickyEl     = document.getElementById( 'stay-sticky' );
  var stickyText   = document.getElementById( 'stay-sticky-text' );
  var searchBtn    = document.getElementById( 'stay-search-btn' );
  var searchHint   = document.getElementById( 'stay-search-hint' );
  var sumIn        = document.getElementById( 'cal-sum-in' );
  var sumOut       = document.getElementById( 'cal-sum-out' );

  var selectedIdx = null;
  var pendingSelectTentIdx = null;
  var lastCi = '', lastCo = '', lastGuests = '';

  function pad2( n ) { return ( n < 10 ? '0' : '' ) + n; }
  function ymd( y, m, d ) { return y + '-' + pad2( m + 1 ) + '-' + pad2( d ); }
  function parseYmd( s ) { var p = s.split( '-' ); return new Date( +p[ 0 ], +p[ 1 ] - 1, +p[ 2 ] ); }
  function nightsBetween( a, b ) { return Math.round( ( parseYmd( b ) - parseYmd( a ) ) / 86400000 ); }
  function longDisp( s ) { return parseYmd( s ).toLocaleDateString( 'en-US', { weekday: 'short', month: 'short', day: 'numeric' } ); }
  function fmt( s ) { return parseYmd( s ).toLocaleDateString( 'en-US', { month: 'short', day: 'numeric' } ); }

  function rangeAvailable( tent, checkin, checkout ) {
    for ( var i = 0; i < tent.blocked.length; i++ ) {
      if ( tent.blocked[ i ] >= checkin && tent.blocked[ i ] < checkout ) return false;
    }
    for ( var j = 0; j < tent.ranges.length; j++ ) {
      if ( checkin < tent.ranges[ j ][ 1 ] && tent.ranges[ j ][ 0 ] < checkout ) return false;
    }
    return true;
  }

  /* ----- inline range calendar ----- */
  var mp = MIN_DATE.split( '-' );
  var viewY = +mp[ 0 ], viewM = +mp[ 1 ] - 1;
  var minY = viewY, minM = viewM;
  var checkin = null, checkout = null;
  var WEEKDAYS = [ 'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' ];

  function renderCal() {
    var first = new Date( viewY, viewM, 1 );
    var startWeekday = first.getDay();
    var daysInMonth = new Date( viewY, viewM + 1, 0 ).getDate();
    var monthLabel = first.toLocaleString( 'en-US', { month: 'long', year: 'numeric' } );

    var html = '<div class="stay-cal-header">'
      + '<button type="button" class="stay-cal-nav" data-nav="prev" aria-label="Previous month">&#8249;</button>'
      + '<span class="stay-cal-month">' + monthLabel + '</span>'
      + '<button type="button" class="stay-cal-nav" data-nav="next" aria-label="Next month">&#8250;</button>'
      + '</div><div class="stay-cal-weekdays">';
    for ( var w = 0; w < 7; w++ ) html += '<span>' + WEEKDAYS[ w ] + '</span>';
    html += '</div><div class="stay-cal-grid">';
    for ( var e = 0; e < startWeekday; e++ ) html += '<span class="stay-cal-day stay-cal-day--empty"></span>';

    for ( var d = 1; d <= daysInMonth; d++ ) {
      var ds = ymd( viewY, viewM, d );
      var past = ds < MIN_DATE;
      var cls = [ 'stay-cal-day' ];
      if ( past ) cls.push( 'stay-cal-day--past' );
      if ( checkin && checkout && ds > checkin && ds < checkout ) cls.push( 'stay-cal-day--inrange' );
      if ( ds === checkin ) cls.push( 'stay-cal-day--checkin' );
      if ( ds === checkout ) cls.push( 'stay-cal-day--checkout' );
      html += '<button type="button" class="' + cls.join( ' ' ) + '" data-date="' + ds + '"' + ( past ? ' disabled' : '' ) + '>' + d + '</button>';
    }
    html += '</div>';
    calEl.innerHTML = html;

    var prev = calEl.querySelector( '[data-nav="prev"]' );
    if ( prev ) prev.disabled = ( viewY === minY && viewM === minM );
  }

  function pickDay( ds ) {
    if ( ! checkin || checkout || ds <= checkin ) {
      checkin = ds;
      checkout = null;
    } else {
      checkout = ds;
    }
    inField.value  = checkin || '';
    outField.value = checkout || '';
    inDisp.textContent  = checkin ? longDisp( checkin ) : 'Select a date';
    outDisp.textContent = checkout ? longDisp( checkout ) : 'Select a date';
    renderCal();
    updateStep();

    // Range complete but no guest count yet -> push them to that next.
    if ( checkin && checkout && guestsEl && ! guestsEl.value ) guestsEl.focus();
  }

  // Highlights the check-in / check-out box for the tap that comes next,
  // and only enables the search button once dates + guests are set.
  function updateStep() {
    var pickingOut = !!( checkin && ! checkout );
    if ( sumIn )  sumIn.classList.toggle( 'is-active', ! pickingOut );
    if ( sumOut ) sumOut.classList.toggle( 'is-active', pickingOut );

    var ready = !!( checkin && checkout && guestsEl && guestsEl.value );
    if ( searchBtn ) searchBtn.disabled = ! ready;
    if ( searchHint ) {
      searchHint.textContent = ( ! checkin || ! checkout )
        ? 'Pick your check-in and check-out days, then your guests.'
        : ( guestsEl && ! guestsEl.value )
          ? 'Now choose how many guests are coming.'
          : 'Ready — search to see which tents are open.';
    }
  }

  calEl.addEventListener( 'click', function ( ev ) {
    var t = ev.target;
    if ( ! t ) return;
    var nav = t.getAttribute && t.getAttribute( 'data-nav' );
    if ( nav ) {
      viewM += ( nav === 'next' ) ? 1 : -1;
      if ( viewM < 0 ) { viewM = 11; viewY--; }
      if ( viewM > 11 ) { viewM = 0; viewY++; }
      renderCal();
      return;
    }
    if ( t.classList && t.classList.contains( 'stay-cal-day' ) && ! t.disabled ) {
      var ds = t.getAttribute( 'data-date' );
      if ( ds ) pickDay( ds );
    }
  } );

  renderCal();
  updateStep();

  /* ----- run availability against the tent cards ----- */
  form.addEventListener( 'submit', function ( e ) {
    e.preventDefault();
    var ci = inField.value, co = outField.value;
    if ( ! ci ) { statusEl.textContent = 'Tap a check-in day on the calendar.'; return; }
    if ( ! co ) { statusEl.textContent = 'Now tap a check-out day on the calendar.'; return; }
    if ( guestsEl && ! guestsEl.value ) { statusEl.textContent = 'Choose how many guests are coming.'; guestsEl.focus(); return; }

    lastCi = ci; lastCo = co;
    lastGuests = guestsEl ? guestsEl.value : '';
    var n = nightsBetween( ci, co );
    var available = 0;

    cards.forEach( function ( card ) {
      var idx  = card.getAttribute( 'data-tent-idx' );
      var tent = TENTS[ idx ];
      var ok   = rangeAvailable( tent, ci, co );

      card.setAttribute( 'data-available', ok ? '1' : '0' );
      card.style.order = ok ? '0' : '1';

      var avail = card.querySelector( '[data-avail]' );
      avail.textContent = ok ? 'Available for your dates' : 'Not available for these dates';
      avail.className = 'stay-card-avail ' + ( ok ? 'is-available' : 'is-unavailable' );

      var selBtn = card.querySelector( '[data-select-stay]' );
      if ( selBtn ) selBtn.disabled = ! ok;

      // If the currently selected tent just went unavailable, clear the selection.
      if ( ! ok && selectedIdx !== null && String( selectedIdx ) === String( idx ) ) clearSelection();

      if ( ok ) available++;
    } );

    grid.classList.add( 'has-search' );
    statusEl.innerHTML = available
      ? '<strong>' + available + ' safari tent' + ( available > 1 ? 's' : '' ) + ' available</strong> for ' + fmt( ci ) + ' &rarr; ' + fmt( co ) + ' &middot; ' + n + ' night' + ( n > 1 ? 's' : '' )
      : 'No tents are open for those exact dates. Try shifting a night, or ask us on WhatsApp.';

    // Package prices + detail links follow the guest count / dates.
    setDetailCtaQueries();
    updatePackagePrices();

    // Keep an already-picked tent's dates/price in sync with a fresh search.
    if ( selectedIdx !== null ) selectStay( selectedIdx, true );

    // A tent requested via the URL (returning from its detail page): select it
    // now that we know it's available, and jump straight to the packages.
    var didAutoSelect = false;
    if ( pendingSelectTentIdx !== null ) {
      var want = pendingSelectTentIdx;
      pendingSelectTentIdx = null;
      var cardEl = grid.querySelector( '.stay-card[data-tent-idx="' + want + '"]' );
      if ( cardEl && cardEl.getAttribute( 'data-available' ) === '1' ) {
        selectStay( String( want ), false );
        didAutoSelect = true;
      }
    }

    if ( ! didAutoSelect ) ( statusEl || grid ).scrollIntoView( { behavior: 'smooth', block: 'start' } );
  } );

  /* ----- select a stay -> mark it, fill #packages, scroll down ----- */
  cards.forEach( function ( card ) {
    var btn = card.querySelector( '[data-select-stay]' );
    if ( ! btn ) return;
    btn.addEventListener( 'click', function () {
      if ( btn.disabled ) return;
      selectStay( card.getAttribute( 'data-tent-idx' ), false );
    } );
  } );

  // Guest count: gate the search button, and (after a search) re-price packages.
  if ( guestsEl ) {
    guestsEl.addEventListener( 'change', function () {
      updateStep();
      if ( ! lastCi ) return;
      lastGuests = guestsEl.value;
      setDetailCtaQueries();
      updatePackagePrices();
      if ( selectedIdx !== null ) selectStay( selectedIdx, true );
    } );
  }

  function clearSelection() {
    selectedIdx = null;
    cards.forEach( function ( c ) {
      c.classList.remove( 'is-selected' );
      var tag = c.querySelector( '[data-selected-tag]' );
      if ( tag ) tag.hidden = true;
      var b = c.querySelector( '[data-select-stay]' );
      if ( b ) { b.textContent = 'Select This Stay'; b.classList.remove( 'is-selected-btn' ); }
    } );
    if ( pickedEl ) pickedEl.hidden = true;
    if ( pkgSection ) pkgSection.classList.remove( 'has-pick' );
    resetPackageContext();
    updateSticky();
  }

  function selectStay( idx, keepScroll ) {
    selectedIdx = idx;
    var tent = TENTS[ idx ];
    var n = nightsBetween( lastCi, lastCo );
    var datesLabel = fmt( lastCi ) + ' – ' + fmt( lastCo ) + ' · ' + n + ' night' + ( n > 1 ? 's' : '' );

    cards.forEach( function ( c ) {
      var on = String( c.getAttribute( 'data-tent-idx' ) ) === String( idx );
      c.classList.toggle( 'is-selected', on );
      var tag = c.querySelector( '[data-selected-tag]' );
      if ( tag ) tag.hidden = ! on;
      var b = c.querySelector( '[data-select-stay]' );
      if ( b ) {
        b.textContent = on ? '✓ Selected' : 'Select This Stay';
        b.classList.toggle( 'is-selected-btn', on );
      }
    } );

    if ( pickedEl ) {
      pickedEl.hidden = false;
      pickedName.textContent = tent.name;
      pickedMeta.textContent = datesLabel;
    }
    if ( stickyText ) stickyText.textContent = tent.name + ' · ' + datesLabel;

    if ( stayTagline ) stayTagline.textContent = tent.name + ' · ' + n + ' night' + ( n > 1 ? 's' : '' ) + ' · breakfast';
    if ( stayPriceEl ) {
      var total = tent.rate > 0 ? Math.round( tent.rate * n ) : 0;
      if ( total ) {
        stayPriceEl.innerHTML = '$' + total.toLocaleString() + ' <small>total &middot; ' + n + ' night' + ( n > 1 ? 's' : '' ) + ( tent.priceText ? ' &middot; ' + tent.priceText + '/night' : '' ) + '</small>';
      } else if ( tent.priceText ) {
        stayPriceEl.innerHTML = 'From ' + tent.priceText + ' <small>per night</small>';
      } else {
        stayPriceEl.innerHTML = '<small>We\'ll confirm the total with you</small>';
      }
    }

    var matched = false;
    stayForms.forEach( function ( f ) {
      var on = String( f.getAttribute( 'data-tent-idx' ) ) === String( idx );
      f.hidden = ! on;
      if ( on ) {
        matched = true;
        f.querySelector( 'input[name="rafiki_checkin"]' ).value  = lastCi;
        f.querySelector( 'input[name="rafiki_checkout"]' ).value = lastCo;
      }
    } );
    if ( stayFallback ) {
      if ( matched ) {
        stayFallback.hidden = true;
      } else {
        stayFallback.hidden = false;
        stayFallback.textContent = 'Continue with Stay Only →';
        stayFallback.target = '_blank';
        stayFallback.rel = 'noopener';
        stayFallback.href = WA_URL + '?text=' + encodeURIComponent(
          'Hi! I would like to book Stay Only: ' + tent.name + ', ' + lastCi + ' to ' + lastCo +
          ' (' + n + ' night' + ( n > 1 ? 's' : '' ) + '), ' + lastGuests + ' guest' + ( lastGuests === '1' ? '' : 's' ) + '.'
        );
      }
    }

    // Make each package read as "the same stay, upgraded".
    document.querySelectorAll( '#packages .compare-card [data-pkg-list]' ).forEach( function ( ul ) {
      Array.prototype.slice.call( ul.querySelectorAll( 'li.is-context' ) ).forEach( function ( li ) { li.remove(); } );
      var a = document.createElement( 'li' ); a.className = 'is-context'; a.textContent = n + ' night' + ( n > 1 ? 's' : '' ) + ' in the ' + tent.name;
      var b = document.createElement( 'li' ); b.className = 'is-context'; b.textContent = 'Breakfast, lunch & dinner';
      ul.insertBefore( b, ul.firstChild );
      ul.insertBefore( a, ul.firstChild );
    } );
    document.querySelectorAll( '#packages .compare-card [data-generic-tagline]' ).forEach( function ( p ) { p.hidden = true; } );
    setDetailCtaQueries();
    updatePackagePrices();

    if ( pkgSection ) pkgSection.classList.add( 'has-pick' );
    if ( ! keepScroll && pkgSection ) pkgSection.scrollIntoView( { behavior: 'smooth', block: 'start' } );
    updateSticky();
  }

  function qs( base, params ) {
    var parts = [];
    for ( var k in params ) { if ( params[ k ] ) parts.push( k + '=' + encodeURIComponent( params[ k ] ) ); }
    if ( ! parts.length ) return base;
    return base + ( base.indexOf( '?' ) > -1 ? '&' : '?' ) + parts.join( '&' );
  }

  // "View Tent" / "View Package" carry the current dates + guests (+ the picked
  // tent, for packages) so the detail page can offer "Change" / straight checkout.
  function setDetailCtaQueries() {
    var selTent = selectedIdx !== null ? TENTS[ selectedIdx ] : null;
    cards.forEach( function ( card ) {
      var link = card.querySelector( '[data-tent-cta]' );
      if ( ! link ) return;
      var cardIdx = card.getAttribute( 'data-tent-idx' );
      var isSel = selTent && String( selectedIdx ) === String( cardIdx );
      link.href = qs( link.getAttribute( 'data-href' ), {
        checkin: lastCi, checkout: lastCo, guests: lastGuests,
        tent: isSel ? TENTS[ cardIdx ].id : ''
      } );
    } );
    document.querySelectorAll( '#packages .compare-pkg-cta' ).forEach( function ( link ) {
      link.href = qs( link.getAttribute( 'data-href' ), {
        checkin: lastCi, checkout: lastCo, guests: lastGuests,
        tent: selTent ? selTent.id : ''
      } );
    } );
  }

  // Package price = "Pricing by Group Size" row matching the guest count,
  // else per-person price x guests, else the flat display price.
  function updatePackagePrices() {
    var guests = parseInt( lastGuests, 10 );
    document.querySelectorAll( '#packages [data-pkg-card]' ).forEach( function ( card ) {
      var el = card.querySelector( '[data-pkg-price]' );
      if ( ! el ) return;
      var data;
      try { data = JSON.parse( card.getAttribute( 'data-pricing' ) || '{}' ); } catch ( err ) { data = {}; }

      if ( ! guests ) {
        el.innerHTML = data.flat ? esc( data.flat ) : '<small>Choose dates &amp; guests to see the price</small>';
        return;
      }

      var rows = data.rows || [];
      if ( rows.length ) {
        var best = null;
        rows.forEach( function ( r ) {
          if ( r.min <= guests && ( ! best || r.min > best.min ) ) best = r;
        } );
        if ( ! best ) best = rows[ 0 ];
        el.innerHTML = '$' + Math.round( best.total ).toLocaleString() +
          ' <small>total &middot; for ' + esc( String( best.label ) ) + '</small>';
      } else if ( data.perPerson > 0 ) {
        var tot = Math.round( data.perPerson * guests );
        el.innerHTML = '$' + tot.toLocaleString() +
          ' <small>total &middot; ' + guests + ' guest' + ( guests === 1 ? '' : 's' ) +
          ' &middot; $' + Math.round( data.perPerson ).toLocaleString() + ' pp</small>';
      } else if ( data.flat ) {
        el.innerHTML = esc( data.flat );
      } else {
        el.innerHTML = '<small>See the package page for pricing</small>';
      }
    } );
  }

  function esc( s ) { var d = document.createElement( 'div' ); d.textContent = s; return d.innerHTML; }

  function resetPackageContext() {
    document.querySelectorAll( '#packages .compare-card [data-pkg-list] li.is-context' ).forEach( function ( li ) { li.remove(); } );
    document.querySelectorAll( '#packages .compare-card [data-generic-tagline]' ).forEach( function ( p ) { p.hidden = false; } );
    setDetailCtaQueries();
    updatePackagePrices();
    if ( stayTagline ) stayTagline.textContent = 'Your tent + breakfast';
    if ( stayPriceEl ) stayPriceEl.innerHTML = '<small>Pick your dates and a tent above to see the total</small>';
    stayForms.forEach( function ( f ) { f.hidden = true; } );
    if ( stayFallback ) { stayFallback.hidden = false; stayFallback.textContent = 'Select a Tent First'; stayFallback.removeAttribute( 'target' ); stayFallback.href = '#availability'; }
  }

  if ( pickedChange ) {
    pickedChange.addEventListener( 'click', function () {
      var a = document.getElementById( 'availability' );
      if ( a ) a.scrollIntoView( { behavior: 'smooth', block: 'start' } );
    } );
  }

  /* ----- restore a selection passed in the URL (returning from a detail page) ----- */
  ( function initFromUrl() {
    var p;
    try { p = new URLSearchParams( location.search ); } catch ( err ) { return; }
    var ci = p.get( 'checkin' ), co = p.get( 'checkout' ), g = p.get( 'guests' ), tentId = p.get( 'tent' );
    var reDate = /^\d{4}-\d{2}-\d{2}$/;

    var wantIdx = null;
    if ( tentId ) {
      for ( var i = 0; i < TENTS.length; i++ ) {
        if ( String( TENTS[ i ].id ) === String( tentId ) ) { wantIdx = i; break; }
      }
    }

    var hasDates = ci && co && reDate.test( ci ) && reDate.test( co ) && co > ci && ci >= MIN_DATE;

    // Only restore the guest count as part of a real returning selection
    // (dates present). A stray ?guests= on its own must NOT pre-fill it.
    var hasGuests = false;
    if ( hasDates && g && guestsEl && /^\d+$/.test( g ) ) {
      var gi = Math.min( 8, Math.max( 1, parseInt( g, 10 ) || 1 ) );
      guestsEl.value = String( gi );
      hasGuests = !! guestsEl.value;
    }

    if ( hasDates ) {
      checkin = ci; checkout = co;
      inField.value = ci; outField.value = co;
      inDisp.textContent = longDisp( ci );
      outDisp.textContent = longDisp( co );
      var mpU = ci.split( '-' );
      viewY = +mpU[ 0 ]; viewM = +mpU[ 1 ] - 1;
      renderCal();
    }
    pendingSelectTentIdx = wantIdx;
    updateStep();

    if ( hasDates && hasGuests ) {
      if ( form.requestSubmit ) form.requestSubmit();
      else form.dispatchEvent( new Event( 'submit', { cancelable: true } ) );
    } else if ( hasDates || wantIdx !== null ) {
      // Missing a piece (usually the guest count) — land them on the picker.
      if ( hasDates && ! hasGuests && guestsEl ) guestsEl.focus();
      else if ( wantIdx !== null ) {
        statusEl.innerHTML = 'Pick your dates for the <strong>' + esc( TENTS[ wantIdx ].name ) + '</strong> and we\'ll show you the options.';
      }
      var av = document.getElementById( 'availability' );
      if ( av ) av.scrollIntoView( { behavior: 'smooth', block: 'start' } );
    }
  } )();

  /* ----- sticky bar: show once a stay is picked and #packages is off-screen ----- */
  var offscreenZones = 0;
  function updateSticky() {
    var show = selectedIdx !== null && offscreenZones >= 2;
    if ( stickyEl ) stickyEl.classList.toggle( 'is-visible', show );
  }
  if ( 'IntersectionObserver' in window ) {
    var io = new IntersectionObserver( function ( entries ) {
      entries.forEach( function ( en ) {
        en.target.__vis = en.isIntersecting;
      } );
      offscreenZones = 0;
      [ document.getElementById( 'availability' ), pkgSection ].forEach( function ( el ) {
        if ( el && ! el.__vis ) offscreenZones++;
      } );
      updateSticky();
    }, { threshold: 0.01 } );
    io.observe( document.getElementById( 'availability' ) );
    if ( pkgSection ) io.observe( pkgSection );
  }
} );
</script>

<!-- ===== 03. CHOOSE HOW YOU WANT TO STAY (Stay Only vs Packages) ===== -->
<section class="section" style="background:var(--cream-2);" id="packages">
  <div class="container">
    <span class="eyebrow center">After You Pick a Tent</span>
    <h2 class="section-title center" style="margin-bottom:16px;">CHOOSE HOW YOU WANT TO STAY.</h2>
    <p style="text-align:center; max-width:640px; margin:0 auto 8px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">Book your tent on its own, or add a package with activities already included. A package is an upgrade on the same stay &mdash; not a different product.</p>

    <div class="stay-picked" id="stay-picked" hidden>
      <span class="stay-picked-label">Your stay</span>
      <strong id="stay-picked-name">&mdash;</strong>
      <span id="stay-picked-meta">&mdash;</span>
      <button type="button" class="stay-picked-change" id="stay-picked-change">Change</button>
    </div>

    <?php
    $all_packages = get_posts( array( 'post_type' => 'package', 'posts_per_page' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
    ?>

    <div class="stay-compare">
      <div class="compare-card is-base">
        <span class="compare-kicker">Baseline</span>
        <h3>Stay Only</h3>
        <p class="compare-tagline" id="stay-only-tagline">Your tent + breakfast</p>
        <ul class="compare-list">
          <li>Safari tent</li>
          <li>Breakfast</li>
          <li>Pool &amp; water slide</li>
          <li>Forest trails from your tent</li>
        </ul>
        <p class="compare-price" id="stay-only-price"><small>Pick your dates and a tent above to see the total</small></p>

        <?php foreach ( $tents as $i => $t ) : if ( ! $t['bookable'] ) continue; ?>
          <form method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>" class="stay-only-form" data-tent-idx="<?php echo (int) $i; ?>" hidden>
            <?php wp_nonce_field( 'rafiki_add_booking_' . $t['product_id'], 'rafiki_booking_nonce' ); ?>
            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $t['product_id'] ); ?>">
            <input type="hidden" name="rafiki_checkin" value="">
            <input type="hidden" name="rafiki_checkout" value="">
            <button type="submit" class="btn btn-primary">Continue with Stay Only &rarr;</button>
          </form>
        <?php endforeach; ?>
        <a class="btn btn-primary" id="stay-only-fallback" href="#availability">Select a Tent First</a>
      </div>

      <?php foreach ( $all_packages as $pkg ) :
        $p_id     = $pkg->ID;
        $p_title  = get_the_title( $pkg );
        $badges   = rafiki_rows( $p_id, 'rafiki_badges' );
        $p_sub    = get_post_meta( $p_id, 'rafiki_subtitle', true );
        $p_price  = get_post_meta( $p_id, 'rafiki_price', true );
        $p_unit   = trim( ltrim( (string) get_post_meta( $p_id, 'rafiki_price_unit', true ), '/ ' ) );
        $p_amen   = rafiki_rows( $p_id, 'rafiki_amenities' );

        // The "plus" list: package inclusions that aren't already in Stay Only.
        $extras = array();
        foreach ( $p_amen as $a ) {
          $ti = isset( $a['title'] ) ? trim( $a['title'] ) : '';
          if ( '' === $ti ) continue;
          if ( preg_match( '/tent|meal|breakfast|lunch|dinner|pool|water slide|trail|accommodat|lodging|\broom\b/i', $ti ) ) continue;
          $extras[] = $ti;
        }
        if ( ! $extras ) {
          foreach ( $p_amen as $a ) { if ( ! empty( $a['title'] ) ) $extras[] = trim( $a['title'] ); }
        }
        $extras = array_slice( $extras, 0, 6 );
        $kicker = ( $badges && ! empty( $badges[0]['text'] ) ) ? $badges[0]['text'] : 'Package';

        // Price by group size: match the guest count to a "Pricing by Group Size"
        // row (Green season, else High). Falls back to the per-person online price
        // x guests, then to the flat display price.
        $group      = rafiki_rows( $p_id, 'rafiki_group_pricing' );
        $price_rows = array();
        foreach ( $group as $gr ) {
          if ( empty( $gr['guests'] ) || ! preg_match( '/\d+/', $gr['guests'], $gm ) ) continue;
          $amt_src = ( isset( $gr['green'] ) && '' !== $gr['green'] ) ? $gr['green'] : ( isset( $gr['high'] ) ? $gr['high'] : '' );
          if ( ! preg_match( '/\d[\d,.]*/', (string) $amt_src, $am ) ) continue;
          $price_rows[] = array(
            'min'   => (int) $gm[0],
            'label' => $gr['guests'],
            'total' => (float) str_replace( ',', '', $am[0] ),
          );
        }
        $per_person  = (float) get_post_meta( $p_id, 'rafiki_price_amount', true );
        $pkg_pricing = array(
          'rows'      => $price_rows,
          'perPerson' => $per_person > 0 ? $per_person : 0,
          'flat'      => $p_price ? ( 'From $' . $p_price . ( $p_unit ? ' ' . $p_unit : '' ) ) : '',
        );
      ?>
        <div class="compare-card" data-pkg-card data-pricing="<?php echo esc_attr( wp_json_encode( $pkg_pricing ) ); ?>">
          <span class="compare-kicker"><?php echo esc_html( $kicker ); ?></span>
          <h3><?php echo esc_html( $p_title ); ?></h3>
          <?php $p_tagline = get_post_meta( $p_id, 'rafiki_compare_tagline', true ); ?>
          <p class="compare-tagline" data-generic-tagline><?php echo esc_html( $p_tagline ? $p_tagline : 'Your tent + breakfast + activities' ); ?></p>
          <ul class="compare-list" data-pkg-list>
            <?php foreach ( $extras as $ex ) : ?><li><?php echo esc_html( $ex ); ?></li><?php endforeach; ?>
            <?php if ( ! $extras && $p_sub ) : ?><li><?php echo esc_html( wp_trim_words( $p_sub, 16 ) ); ?></li><?php endif; ?>
          </ul>
          <p class="compare-price" data-pkg-price>
            <?php if ( $pkg_pricing['flat'] ) : ?>
              <?php echo esc_html( $pkg_pricing['flat'] ); ?>
            <?php else : ?>
              <small>Choose dates &amp; guests to see the price</small>
            <?php endif; ?>
          </p>
          <a href="<?php echo esc_url( get_permalink( $pkg ) ); ?>" class="btn btn-outline compare-pkg-cta" data-href="<?php echo esc_url( get_permalink( $pkg ) ); ?>" target="_blank" rel="noopener" style="border-color:var(--text-dark); color:var(--text-dark);">View Package &rarr;</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== 04. THIS ISN'T CAMPING ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Safari Tent Living</span>
    <h2 class="section-title center" style="margin-bottom:20px;">CLOSE TO NATURE DOESN'T HAVE TO MEAN SLEEPING ON THE GROUND.</h2>
    <p>The idea came from the safari camps Constant Boshoff knew in Africa. A canvas tent lets you hear more of what's happening outside. But inside, it still needs to feel good.</p>
    <p>Proper beds. Private bathrooms. Hot showers. Space for your things. A porch to sit on when you've had enough adventure for the day.</p>
  </div>
  <div class="container">
    <img src="<?php echo esc_url( rafiki_photo( 'tents-1' ) ); ?>" alt="Inside a safari tent at Rafiki Safari Lodge" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius);">
  </div>
</section>

<!-- ===== 05. HOW MANY NIGHTS ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <span class="eyebrow center">How Many Nights?</span>
    <h2 class="section-title center">TWO NIGHTS WORK.<br>THREE NIGHTS FEEL BETTER.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">One night tells you what Rafiki looks like. Two nights give you time for a real adventure. Three nights let you have another one without feeling like you're already leaving.</p>

    <div class="plan-grid cols-2" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Two Nights</h3>
        <p>Arrive from the coast. Settle in. Spend one full day on the river, horseback or exploring the forest. Wake up one more morning before continuing your trip.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>Three Nights</h3>
        <p>Arrive without rushing. Choose two different kinds of days. Leave room for the pool, birds, food, conversation and doing nothing for an afternoon.</p>
      </div>
    </div>
  </div>
</section>

<?php if ( $tent_amenities ) : ?>
<!-- ===== 06. WHAT'S IN YOUR SAFARI TENT ===== -->
<section class="section">
  <div class="container">
    <span class="eyebrow center">The Practical Part</span>
    <h2 class="section-title center">WILD OUTSIDE. COMFORTABLE INSIDE.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px;">Each Rafiki safari tent gives you the feeling of sleeping in the forest without giving up the essentials you care about.</p>

    <div class="amenities-grid">
      <?php foreach ( $tent_amenities as $a ) : if ( empty( $a['title'] ) ) continue; ?>
        <div class="amenity-item">
          <?php echo rafiki_icon( $a['icon'] ); ?>
          <div><strong><?php echo esc_html( $a['title'] ); ?></strong><span><?php echo esc_html( $a['text'] ); ?></span></div>
        </div>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center; margin-top:36px;">
      <a href="<?php echo esc_url( $tent_post ? get_permalink( $tent_post ) : $stay_url ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">See Full Tent Details &rarr;</a>
    </p>
  </div>
</section>
<?php endif; ?>

<!-- ===== 07. FOOD ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Around the Table</span>
    <h2 class="section-title center">ADVENTURE MAKES PEOPLE HUNGRY.</h2>
    <p>Meals at Rafiki are simple, generous and made for the kind of days people have here. Breakfast before heading out. Lunch when you return. Dinner when everyone finally slows down again.</p>
    <p><a href="<?php echo esc_url( home_url( '/lekker-bar-braai/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Food at Rafiki &rarr;</a></p>
  </div>
</section>

<!-- ===== 08. GROUP STAYS ===== -->
<section class="cta-banner">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-14a' ) ); ?>" alt="Group gathered together at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Group Stays</span>
    <h2>TRAVEL TOGETHER WITHOUT SPENDING EVERY MINUTE TOGETHER.</h2>
    <p>With 14 safari tents, Rafiki has room for families and groups to come. Some people raft. Some ride. Some stay with the pool. Then everyone comes back to the same table at the end of the day.</p>
    <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="btn btn-primary">Plan a Group Stay &rarr;</a>
  </div>
</section>

<?php
$activities = get_posts( array( 'post_type' => 'activity', 'posts_per_page' => 4, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
if ( $activities ) : ?>
<!-- ===== 09. ADD SOMETHING EXTRA ===== -->
<section class="section experience" id="activities">
  <div class="container">
    <span class="eyebrow center">While You're Here</span>
    <h2 class="section-title center">WANT TO ADD SOMETHING EXTRA?</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16px;">Book the stay first. You can add individual adventures to any booking &mdash; or leave the days open and decide once you're here.</p>

    <div class="experience-grid archive-grid">
      <?php foreach ( $activities as $act ) :
        $img = rafiki_lead_image_url( $act->ID, 'rafiki-card' );
      ?>
        <a href="<?php echo esc_url( get_permalink( $act ) ); ?>" class="experience-card">
          <?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title( $act ) ); ?>"><?php endif; ?>
          <div class="experience-card-overlay"></div>
          <div class="experience-card-content">
            <h3><?php echo esc_html( get_the_title( $act ) ); ?></h3>
            <span class="arrow-link">&rarr;</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <p style="text-align:center; margin-top:36px;">
      <a href="<?php echo esc_url( $activities_url ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">View All Activities</a>
    </p>
  </div>
</section>
<?php endif; ?>

<!-- ===== 10. FINAL CTA ===== -->
<section class="cta-banner" style="padding:76px 0;" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Group rafting in front of the lodge">
    <div class="hero-overlay" style="background:rgba(8,7,5,0.78);"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Your Base for a Few Wild Days</span>
    <h2>UNPACK ONCE. SEE WHAT HAPPENS.</h2>
    <p>Pick your dates, choose your tent, and make Rafiki the part of your Costa Rica trip where the river, forest and adventure all start from the same place.</p>
    <p style="display:flex; gap:14px; flex-wrap:wrap; margin:0;">
      <a href="#availability" class="btn btn-primary">Check Dates &rarr;</a>
      <a href="<?php echo esc_url( $packages_url ); ?>" class="btn btn-outline">Explore Packages</a>
    </p>
  </div>
</section>

<div class="stay-sticky" id="stay-sticky">
  <div class="stay-sticky-inner">
    <div class="stay-sticky-info">
      <span>Your stay</span>
      <strong id="stay-sticky-text">&mdash;</strong>
    </div>
    <a href="#packages" class="btn btn-primary">Choose How to Book</a>
  </div>
</div>

<?php get_footer(); ?>
