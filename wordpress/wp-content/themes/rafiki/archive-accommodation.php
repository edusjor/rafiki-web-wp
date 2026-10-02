<?php
/**
 * archive-accommodation.php — booking-first /stay/ page (formerly "Stay - Copia").
 *
 * This is now the default /stay/ accommodation archive. The previous layout is
 * preserved untouched as the "Stay - Oficial" page template
 * (page-templates/template-stay-oficial.php).
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
 *
 * The tent / package detail pages (single-accommodation.php, single-package.php)
 * are confirm-only: their calendars were removed and they hand back here via
 * ?checkin=&checkout=&guests=&tent= (see rafiki_stay_selection* helpers).
 */
get_header();

$stay_url     = get_post_type_archive_link( 'accommodation' );
$packages_url = get_post_type_archive_link( 'package' );
$activities_url = get_post_type_archive_link( 'activity' );

/*
 * /stay/ lists every accommodation, in a fixed running order regardless of
 * each post's menu_order: Main Lodge, then Beach Camp, then Luxury Safari
 * Tents, with the Lekker Bar & Braai last. Anything added later that doesn't
 * match one of those keywords lands in the middle, ahead of Lekker.
 */
$stay_rank = function ( $title ) {
	$t = strtolower( (string) $title );
	if ( false !== strpos( $t, 'lekker' ) || false !== strpos( $t, 'braai' ) ) return 90;
	if ( false !== strpos( $t, 'main' ) || false !== strpos( $t, 'lodge' ) )   return 10;
	if ( false !== strpos( $t, 'beach' ) )                                      return 20;
	if ( false !== strpos( $t, 'luxury' ) || false !== strpos( $t, 'tent' ) )   return 30;
	return 50;
};

$all_accommodations = get_posts( array( 'post_type' => 'accommodation', 'posts_per_page' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );

usort( $all_accommodations, function ( $a, $b ) use ( $stay_rank ) {
	$ra = $stay_rank( $a->post_title );
	$rb = $stay_rank( $b->post_title );
	return $ra === $rb ? 0 : ( $ra < $rb ? -1 : 1 );
} );

// "What's in your safari tent" reads the real Luxury Safari Tents post,
// not just whatever now sorts first (Main Lodge).
$tent_post = null;
foreach ( $all_accommodations as $acc ) {
	$t = strtolower( get_the_title( $acc ) );
	if ( false !== strpos( $t, 'tent' ) || false !== strpos( $t, 'luxury' ) ) { $tent_post = $acc; break; }
}
if ( ! $tent_post && $all_accommodations ) $tent_post = $all_accommodations[0];
$tent_amenities = $tent_post ? rafiki_rows( $tent_post->ID, 'rafiki_amenities' ) : array();

/* ---- Build the tent-card list ---- */
$tents = array();

foreach ( $all_accommodations as $acc ) {
	$id = (int) $acc->ID;

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

	$tents[] = array(
		'post'       => $acc,
		'img'        => $img,
		'sub'        => $sub,
		'price'      => $price,
		'price_unit' => $price_unit,
		'facts'      => $facts,
		'capacity'   => $capacity,
	);
}
?>

<style>
  /* ===== /stay/ : accommodation cards + Stay Only vs Packages ===== */
  #availability { padding-top: 84px; }

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
  .stay-card-media { position: relative; aspect-ratio: 16 / 10; background: var(--cream-2); }
  .stay-card-media img { width: 100%; height: 100%; object-fit: cover; }
  .stay-card-body { display: flex; flex-direction: column; flex: 1; padding: 24px; }
  .stay-card-body h3 { font-size: 23px; text-transform: uppercase; margin: 0 0 8px; }
  .stay-card-specs {
    font-size: 13.5px; font-weight: 600; color: var(--text-dark);
    margin: 0 0 6px; letter-spacing: 0.2px;
  }
  .stay-card-diff { font-size: 13.5px; line-height: 1.55; color: var(--text-dark-muted); margin: 0 0 16px; }

  .stay-card-price {
    font-size: 14px; color: var(--text-dark-muted); margin: 0 0 20px;
    padding-top: 14px; border-top: 1px solid var(--cream-2);
  }
  .stay-card-price strong { font-family: var(--font-head); font-size: 30px; color: var(--text-dark); letter-spacing: 0.4px; }
  .stay-card-price span { display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 2px; }

  .stay-card-actions { margin-top: auto; display: flex; flex-direction: column; gap: 10px; }
  .stay-card-actions .btn { width: 100%; justify-content: center; white-space: nowrap; }

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
  .compare-card .compare-price { font-family: var(--font-head); font-size: 24px; color: var(--text-dark); margin: 0 0 16px; letter-spacing: 0.4px; }
  .compare-card .compare-price small { font-family: var(--font-body); font-size: 12.5px; color: var(--text-dark-muted); font-weight: 400; letter-spacing: 0; }
  .compare-card .btn { margin-top: auto; width: 100%; justify-content: center; }

  @media (max-width: 820px) {
    .stay-results { grid-template-columns: 1fr; }
  }
  @media (max-width: 560px) {
    .stay-compare { grid-template-columns: 1fr; }
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
      <p class="hero-sub">Fourteen safari tents, the Main Lodge and the Beach Camp. Have a look, then book your dates online.</p>
      <div class="hero-actions">
        <a href="#availability" class="btn btn-primary">See Where You'll Stay</a>
        <a href="#packages" class="btn btn-outline">See Packages</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. WHERE YOU'LL STAY ===== -->
<section class="section" id="availability">
  <div class="container">
    <span class="eyebrow center">Where You'll Stay</span>
    <h2 class="section-title center" style="margin-bottom:28px;">SAFARI TENTS, THE LODGE &amp; THE BEACH CAMP.</h2>

    <?php if ( $tents ) : ?>
      <div class="stay-results" id="stay-results">
        <?php foreach ( $tents as $i => $t ) :
          $p          = $t['post'];
          $permalink  = get_permalink( $p );
          $title      = get_the_title( $p );
          $card_info  = rafiki_is_info_only_stay( $p->ID ); // Lekker Bar & Braai — info card, not bookable

          $specs = array();
          if ( $t['capacity'] ) {
            // Normalise whatever the Quick Fact says ("4–5 people", "2-4", "Sleeps 4")
            // to one consistent "<n> guests" chip so every card reads the same way.
            $cap_num = preg_replace( '/\b(sleeps?|up to|people|persons?|guests?|pax|max\.?)\b/i', '', $t['capacity'] );
            $cap_num = trim( (string) $cap_num, " .,-" );
            if ( '' !== $cap_num ) $specs[] = ( '1' === $cap_num ? '1 guest' : $cap_num . ' guests' );
          }
          if ( ! $card_info ) $specs[] = 'Private bathroom';

          $unit_label = trim( ltrim( (string) $t['price_unit'], '/ ' ) );
          if ( '' === $unit_label ) $unit_label = 'per night';
        ?>
          <article class="stay-card">
            <div class="stay-card-media">
              <?php if ( $t['img'] ) : ?><img src="<?php echo esc_url( $t['img'] ); ?>" alt="<?php echo esc_attr( $title ); ?>"><?php endif; ?>
            </div>
            <div class="stay-card-body">
              <h3><?php echo esc_html( $title ); ?></h3>
              <p class="stay-card-specs"><?php echo esc_html( implode( ' · ', $specs ) ); ?></p>
              <?php if ( $t['sub'] ) : ?><p class="stay-card-diff"><?php echo esc_html( wp_trim_words( $t['sub'], 16 ) ); ?></p><?php endif; ?>

              <?php if ( $t['price'] ) : ?>
                <p class="stay-card-price">From <strong>$<?php echo esc_html( $t['price'] ); ?></strong><span><?php echo esc_html( $unit_label ); ?></span></p>
              <?php endif; ?>

              <div class="stay-card-actions">
                <a class="btn btn-primary" href="<?php echo esc_url( $permalink ); ?>"><?php echo $card_info ? 'See the Bar &amp; Restaurant' : 'View Tent'; ?></a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <p style="text-align:center; margin-top:36px;">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I have a question about staying at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener" style="border-color: var(--text-dark); color: var(--text-dark);">Have a question? Ask us on WhatsApp</a>
      </p>
    <?php else : ?>
      <p style="text-align:center;">No accommodations published yet.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ===== 03. WAYS TO STAY (Stay Only vs Packages) ===== -->
<section class="section" style="background:var(--cream-2);" id="packages">
  <div class="container">
    <span class="eyebrow center">Ways to Stay</span>
    <h2 class="section-title center" style="margin-bottom:16px;">STAY ONLY, OR MAKE IT A PACKAGE.</h2>
    <p style="text-align:center; max-width:640px; margin:0 auto 8px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">Book a tent on its own, or choose a package with activities already included. A package is an upgrade on the same stay &mdash; not a different product.</p>

    <?php
    $all_packages = get_posts( array( 'post_type' => 'package', 'posts_per_page' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
    ?>

    <div class="stay-compare">
      <div class="compare-card is-base">
        <span class="compare-kicker">Baseline</span>
        <h3>Stay Only</h3>
        <p class="compare-tagline">Your tent + breakfast</p>
        <ul class="compare-list">
          <li>Safari tent</li>
          <li>Breakfast</li>
          <li>Pool &amp; water slide</li>
          <li>Forest trails from your tent</li>
        </ul>
        <a class="btn btn-primary" href="#availability">View the Tents &uarr;</a>
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

        $p_tagline  = get_post_meta( $p_id, 'rafiki_compare_tagline', true );
        $price_text = $p_price ? ( 'From $' . $p_price . ( $p_unit ? ' ' . $p_unit : '' ) ) : '';
      ?>
        <div class="compare-card">
          <span class="compare-kicker"><?php echo esc_html( $kicker ); ?></span>
          <h3><?php echo esc_html( $p_title ); ?></h3>
          <p class="compare-tagline"><?php echo esc_html( $p_tagline ? $p_tagline : 'Your tent + breakfast + activities' ); ?></p>
          <ul class="compare-list">
            <?php foreach ( $extras as $ex ) : ?><li><?php echo esc_html( $ex ); ?></li><?php endforeach; ?>
            <?php if ( ! $extras && $p_sub ) : ?><li><?php echo esc_html( wp_trim_words( $p_sub, 16 ) ); ?></li><?php endif; ?>
          </ul>
          <p class="compare-price">
            <?php if ( $price_text ) : ?>
              <?php echo esc_html( $price_text ); ?>
            <?php else : ?>
              <small>See the package page for pricing</small>
            <?php endif; ?>
          </p>
          <a href="<?php echo esc_url( get_permalink( $pkg ) ); ?>" class="btn btn-outline" style="border-color:var(--text-dark); color:var(--text-dark);">View Package &rarr;</a>
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
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px;">All the tents are perched on raised hardwood platforms. Each has a unique view of the forest, allowing the sensation of sleeping in the forest without giving up the essentials you care about.</p>

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
    <p><a href="<?php echo esc_url( rafiki_lekker_url() ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Food at Rafiki &rarr;</a></p>
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
    <p>Choose your tent and make Rafiki the part of your Costa Rica trip where the river, forest and adventure all start from the same place.</p>
    <p style="display:flex; gap:14px; flex-wrap:wrap; margin:0;">
      <a href="#availability" class="btn btn-primary">See Where You'll Stay &rarr;</a>
      <a href="<?php echo esc_url( $packages_url ); ?>" class="btn btn-outline">Explore Packages</a>
    </p>
  </div>
</section>

<?php get_footer(); ?>
