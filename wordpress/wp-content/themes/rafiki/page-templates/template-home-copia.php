<?php
/**
 * Template Name: Home - Copia
 *
 * Condensed homepage, wired up as a normal Page (not a front-page override)
 * so it shows in wp-admin as "Home - Copia" without touching the live
 * homepage. Goal: in one short scroll the visitor understands WHO Rafiki is,
 * WHAT Rafiki offers, and HOW to get it — with every primary CTA pointing to
 * /stay/ (the accommodation archive) as the next step: check availability.
 * Accommodation is the main product; packages (stay + activities) are the
 * second path. Funnel order: (1) hero — "want to live this", primary CTA
 * Check Availability, secondary View Safari Tents; (2) what Rafiki is + your
 * days; (3) where you'll sleep — the safari tents shown as the product
 * (photos + what every stay includes + CTAs); (4) experiences — what you can
 * do *while you stay*; (5) how to book — safari tent vs package; (6) guest
 * proof (lead quote from a returning guest); (7) who Rafiki is (family-owned
 * 25+ years, stats); (8) final CTA. CTA hierarchy is kept consistent
 * throughout: orange = Check Availability, outline = View Safari Tents /
 * Explore Packages. Does not touch front-page.php.
 */
get_header();

$stay_url     = get_post_type_archive_link( 'accommodation' ); // -> /stay/
$packages_url = get_post_type_archive_link( 'package' );        // -> /packages/

$hero_img_url = wp_get_attachment_image_url( 125, 'full' );
if ( ! $hero_img_url ) $hero_img_url = rafiki_photo( 'property-and-food-19' );

$rafting_post   = rafiki_find_post_by_keyword( 'activity', 'raft' );
$horseback_post = rafiki_find_post_by_keyword( 'activity', 'horseback' );
$hiking_post    = rafiki_find_post_by_keyword( 'activity', 'hik' );
$birding_post   = rafiki_find_post_by_keyword( 'activity', 'bird' );
?>

<!-- ===== 01. HERO — who Rafiki is, in one line ===== -->
<section class="hero" id="top">
  <div class="hero-media">
    <img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Safari tent and pool at Rafiki Safari Lodge, surrounded by rainforest">
    <div class="hero-overlay"></div>
  </div>

  <div class="container hero-content">
    <div class="hero-content-inner">
      <span class="eyebrow">All-Inclusive Nature in Costa Rica</span>
      <h1>GIVE YOUR COSTA RICA ROAD TRIP<br><span class="accent">A FEW DAYS IN THE WILD.</span></h1>
      <p class="hero-sub">A safari camp deep in the Savegre Valley, between Manuel Antonio and Dominical. Sleep in a tent in the rainforest, raft the river, ride horses through the valley, hike to waterfalls — then come back to the pool. Every adventure starts on the property.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( $stay_url ); ?>" class="btn btn-primary">Check Availability</a>
        <a href="#stay" class="btn btn-outline">View Safari Tents</a>
      </div>
    </div>
  </div>

  <?php
  $rafting_url    = $rafting_post ? get_permalink( $rafting_post ) : get_post_type_archive_link( 'activity' );
  $activities_url = get_post_type_archive_link( 'activity' );
  $why_url        = home_url( '/why-rafiki/' );
  ?>
  <div class="hero-features">
    <div class="container hero-features-inner">
      <a class="feature-item" href="<?php echo esc_url( $stay_url ); ?>">
        <?php echo rafiki_icon( 'tent' ); ?>
        <div>
          <strong>14 safari-style tents</strong>
          <span>Surrounded by nature, not buildings.</span>
        </div>
      </a>
      <a class="feature-item" href="<?php echo esc_url( $rafting_url ); ?>">
        <?php echo rafiki_icon( 'guide' ); ?>
        <div>
          <strong>Rafting on property</strong>
          <span>The only lodge on the river with direct rafting access.</span>
        </div>
      </a>
      <a class="feature-item" href="<?php echo esc_url( $activities_url ); ?>">
        <?php echo rafiki_icon( 'wave' ); ?>
        <div>
          <strong>Beach &amp; jungle</strong>
          <span>Two incredible worlds. One unforgettable trip.</span>
        </div>
      </a>
      <a class="feature-item" href="<?php echo esc_url( $why_url ); ?>">
        <?php echo rafiki_icon( 'heart' ); ?>
        <div>
          <strong>Family-owned for 25 years</strong>
          <span>Passion, purpose, and people.</span>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ===== 02. WHAT RAFIKI IS + WHAT YOUR DAYS LOOK LIKE ===== -->
<section class="section" style="background:var(--cream-2);" id="what-rafiki-offers">
  <div class="container">
    <div class="why-stay-intro">
      <div>
        <span class="eyebrow">What Rafiki Is</span>
        <h2 class="section-title">ALL-INCLUSIVE NATURE — NOT THE KIND THAT KEEPS YOU INSIDE A RESORT.</h2>
      </div>
      <p>At Rafiki, there's no pressure to do everything. Start early or sleep in. Head out for an adventure or spend the afternoon by the pool. The day is yours.<br><br>You stay in one place, unpack once, and choose how much you want to do. Curated adventures, home-style meals and Rafiki hospitality are all steps from your front porch.</p>
    </div>

    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-24' ) ); ?>" alt="Savegre River surrounded by tropical rainforest at Rafiki" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius); margin-bottom:56px;">

    <div class="plan-grid cols-4" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Sleep in the Rainforest</h3>
        <p>14 South African-style safari tents surrounded by forest. Proper beds, private bathrooms, your own porch — close enough to hear what's outside without giving up a good night's sleep.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Adventure Starts Here</h3>
        <p>With over 600 acres to play with, every day is a new journey.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>Travel Together, Your Own Way</h3>
        <p>Especially good for families and groups. Some want the river, some want a trail, some want to sit by the pool and read. You don't all have to vacation the same way to still travel together.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'meal' ); ?>
        <h3>Real Meals, Not Snacks Between Tours</h3>
        <p>Breakfast is included every morning before the day starts. Lunch and dinner are available at the Lekker Bar &amp; Braai, so you can eat well without ever leaving the property.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 03. WHERE YOU'LL SLEEP — the safari tents are the product ===== -->
<section class="section" id="stay">
  <div class="container">
    <span class="eyebrow center">Where You'll Sleep</span>
    <h2 class="section-title center" style="margin-bottom:16px;">YOUR HOME IN THE RAINFOREST.</h2>
    <p style="text-align:center; max-width:660px; margin:0 auto 44px; color:var(--text-dark-muted); font-size:16.5px;">14 safari-style tents. Perched on a scenic plateau. Real beds, private bathrooms, hot water and your own porch &mdash; with the river valley, birds and forest right outside your door.</p>

    <style>
      #stay .tent-shots { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:40px; }
      #stay .tent-shots img { width:100%; height:300px; object-fit:cover; border-radius:var(--radius); }
      @media (max-width:700px){ #stay .tent-shots { grid-template-columns:1fr; } #stay .tent-shots img { height:240px; } }
      #stay-includes .amenity-item strong { font-size:16px; }
      #stay-includes .amenity-item span { font-size:15px; }
    </style>

    <div class="tent-shots">
      <img src="<?php echo esc_url( rafiki_photo( 'tents-19' ) ); ?>" alt="Safari tent exterior surrounded by rainforest at Rafiki">
      <img src="<?php echo esc_url( rafiki_photo( 'tents-1' ) ); ?>" alt="Inside a safari tent at Rafiki — real bed and canvas walls">
      <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-3' ) ); ?>" alt="Forest view from a safari tent porch at Rafiki">
    </div>

    <p style="display:flex; gap:14px; justify-content:center; flex-wrap:wrap; margin:0 0 60px;">
      <a href="<?php echo esc_url( $stay_url ); ?>" class="btn btn-primary">Check Availability</a>
      <a href="<?php echo esc_url( $stay_url . '#tents' ); ?>" class="btn btn-outline" style="border-color:var(--text-dark); color:var(--text-dark);">View Safari Tents</a>
    </p>

    <!-- What every stay includes -->
    <div id="stay-includes">
      <span class="eyebrow center">What Every Stay Includes</span>
      <h2 class="section-title center" style="margin-bottom:10px;">KNOW EXACTLY WHAT YOU'RE BOOKING.</h2>
      <p style="text-align:center; max-width:660px; margin:0 auto 40px; color:var(--text-dark-muted); font-size:16.5px;">Every safari-tent booking includes the essentials below. Guided adventures are added to your stay, or already bundled into a package.</p>

      <div class="amenities-grid">
        <div class="amenity-item">
          <?php echo rafiki_icon( 'check' ); ?>
          <div><strong>Safari tent</strong><span>Bed, private bathroom, hot water, porch.</span></div>
        </div>
        <div class="amenity-item">
          <?php echo rafiki_icon( 'check' ); ?>
          <div><strong>Meals</strong><span>Breakfast, lunch &amp; dinner, every day.</span></div>
        </div>
        <div class="amenity-item">
          <?php echo rafiki_icon( 'check' ); ?>
          <div><strong>Pool &amp; water slide</strong><span>Open all day.</span></div>
        </div>
        <div class="amenity-item">
          <?php echo rafiki_icon( 'check' ); ?>
          <div><strong>Forest trails</strong><span>Rivers, waterfalls and lookouts from your tent.</span></div>
        </div>
        <div class="amenity-item">
          <?php echo rafiki_icon( 'check' ); ?>
          <div><strong>Birdwatching &amp; wildlife</strong><span>Toucans, monkeys and hundreds of species.</span></div>
        </div>
        <div class="amenity-item">
          <?php echo rafiki_icon( 'check' ); ?>
          <div><strong>Local team on site</strong><span>Guides and hosts from the valley.</span></div>
        </div>
      </div>

      <p style="text-align:center; margin-top:36px; font-size:15.5px; color:var(--text-dark);"><strong>Add or bundle:</strong> whitewater rafting, horseback riding and guided hikes &mdash; booked alongside your stay, or included for you in a package.</p>
    </div>
  </div>
</section>

<!-- ===== 04. EXPERIENCES — everything you can do while you stay ===== -->
<section class="section experience" id="experiences">
  <div class="container">
    <span class="eyebrow center">Your Days at Rafiki</span>
    <h2 class="section-title center">PICK THE KIND OF DAY YOU WANT.</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16.5px;">Every stay can be as active or as relaxed as you want. Pick one adventure, choose several, or spend the afternoon doing nothing.</p>

    <div class="experience-grid">
      <a href="<?php echo $rafting_post ? esc_url( get_permalink( $rafting_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $rafting_post ? esc_url( rafiki_lead_image_url( $rafting_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'activities-25' ); ?>" alt="Whitewater rafting on the Savegre River">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>RUN THE RIVER</h3>
          <p>Rapids, forest, swimming holes, waterfalls and guides who know these waters through more than one season.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $horseback_post ? esc_url( get_permalink( $horseback_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $horseback_post ? esc_url( rafiki_lead_image_url( $horseback_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'activities-50' ); ?>" alt="Horseback riding through the valley">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>SEE THE VALLEY FROM THE SADDLE</h3>
          <p>Ride with local guides and horses that are part of life here, not simply brought in for the activity.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $hiking_post ? esc_url( get_permalink( $hiking_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $hiking_post ? esc_url( rafiki_lead_image_url( $hiking_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'place-wildlife-1' ); ?>" alt="Hiking into the rainforest at Rafiki">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>WALK INTO THE FOREST</h3>
          <p>Trails toward rivers, waterfalls, suspension bridges and places you wouldn't see from the road.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $birding_post ? esc_url( get_permalink( $birding_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $birding_post ? esc_url( rafiki_lead_image_url( $birding_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'place-wildlife-24' ); ?>" alt="Birding at Rafiki Safari Lodge">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>WAKE UP EARLY FOR A GOOD REASON</h3>
          <p>Sometimes the day starts with something landing outside the rancho before you've finished your first coffee.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
    </div>

    <p style="text-align:center; margin-top:36px;">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Explore Rafiki Experiences</a>
    </p>
  </div>
</section>

<!-- ===== 05. HOW TO BOOK — safari tent vs package, both lead to Stay ===== -->
<section class="section" id="how-to-book">
  <div class="container">
    <span class="eyebrow center">How to Book Your Stay</span>
    <h2 class="section-title center">THE NEXT STEP IS TO CHECK AVAILABILITY.</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 44px; color:var(--text-dark-muted); font-size:16.5px;">Everything at Rafiki starts with a safari tent. Book the stay on its own, or book it as a package with your adventures already included.</p>

    <div class="plan-grid cols-2" style="margin-bottom:36px;">
      <a href="<?php echo esc_url( $stay_url ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Book a Safari Tent</h3>
        <p><strong>Tent.</strong> You add rafting, horseback riding and guided hikes separately, choosing only the days you want.</p>
        <p style="font-size:14.5px; color:var(--text-dark-muted); margin-top:10px;"><strong>Best for:</strong> travelers who want to build their own stay.</p>
        <span class="btn btn-primary" style="margin-top:18px;">See Options &rarr;</span>
      </a>
      <a href="<?php echo esc_url( $packages_url ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Book a Package</h3>
        <p><strong>Tent + selected adventures already included.</strong> Rafting, horseback riding and more are included in your stay, so nothing is left to organize.</p>
        <p style="font-size:14.5px; color:var(--text-dark-muted); margin-top:10px;"><strong>Best for:</strong> travelers who want everything planned.</p>
        <span class="btn btn-outline" style="margin-top:18px; border-color:var(--text-dark); color:var(--text-dark);">Explore Packages &rarr;</span>
      </a>
    </div>

    <p style="text-align:center;">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( "Hi! I'd like help planning a stay at Rafiki Safari Lodge." ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener" style="border-color: var(--text-dark); color: var(--text-dark);">Not sure? Ask us on WhatsApp</a>
    </p>
  </div>
</section>

<!-- ===== 06. PROOF ===== -->
<section class="section testimonials" style="background:var(--cream-2);" id="guest-stories">
  <div class="container">
    <span class="eyebrow center">What People Remember</span>
    <h2 class="section-title center">THE REVIEWS START WITH THE ADVENTURE — THEN THEY TALK ABOUT THE PEOPLE.</h2>

    <blockquote style="max-width:760px; margin:8px auto 48px; text-align:center; border:0;">
      <p style="font-family:var(--font-head); font-size:clamp(24px,3vw,32px); line-height:1.35; color:var(--text-dark); margin:0 0 14px;">&ldquo;We've been back 5 times in 15 years.&rdquo;</p>
      <p style="font-size:15.5px; color:var(--text-dark-muted); margin:0;">All the guides grew up in the area and that's what makes the trip truly special. Pura vida. &mdash; <strong>Lance R.</strong>, Facebook</p>
    </blockquote>

    <div class="testimonial-grid">
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'google' ); ?><span>Google Review</span></div>
        <div class="stars">★★★★★</div>
        <p>"A beautiful, African-inspired lodge for the wildlife lover. Incredible guides and luxurious tents that exceeded our expectations."</p>
        <span class="testimonial-author">Yooperchick</span>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'tripadvisor' ); ?><span>TripAdvisor Review</span></div>
        <div class="stars">★★★★★</div>
        <p>"Lovely cabins, excellent food, and the rafting was a life-changing experience. The whole family loved every minute."</p>
        <span class="testimonial-author">TaikoM</span>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'facebook' ); ?><span>Facebook Review</span></div>
        <div class="stars">★★★★★</div>
        <p>"The staff exceeded our expectations. The kids didn't want to leave the water slide and we were already planning the next trip on the drive out."</p>
        <span class="testimonial-author">Marisol P.</span>
      </div>
    </div>
    <!-- TODO(Eduardo): swap these three for real Google/TripAdvisor/Facebook reviews once we have a set to point to. -->
  </div>
</section>

<!-- ===== 07. WHO RAFIKI IS — text only ===== -->
<section class="section" style="background:var(--cream-2);" id="about">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Family-Owned in Costa Rica for 25+ Years</span>
    <h2 class="section-title center" style="margin-bottom:20px;">RAFIKI DIDN'T START AS A HOTEL CONCEPT.</h2>
    <p>Constant Boshoff founded Rafiki after bringing an idea inspired by Africa to Costa Rica and finding a home for it in this river valley. Now Loki and Mauren continue that story alongside a local team closely connected to Santo Domingo and the communities around Rafiki.</p>
    <p>Twenty-five years on, it's still family-run, still 100% Costa Rican, still built around the same 600-acre river valley between the jungle and the ocean &mdash; with guests who keep coming back year after year.</p>
    <p style="margin-top:8px;"><a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>" class="btn btn-outline" style="border-color:var(--text-dark); color:var(--text-dark);">Read the Rafiki Story &rarr;</a></p>
  </div>
</section>

<!-- ===== 08. FINAL CTA — full-width background, contained content, short ===== -->
<section class="cta-banner" style="padding:76px 0;" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Group rafting in front of the lodge">
    <div class="hero-overlay" style="background:rgba(8,7,5,0.78);"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Make Room for Rafiki</span>
    <h2>GOT A FEW NIGHTS?</h2>
    <p>Give your Costa Rica trip a few days by the river, in the forest, and away from the usual route.</p>
    <p style="display:flex; gap:14px; flex-wrap:wrap; margin:0;">
      <a href="<?php echo esc_url( $stay_url ); ?>" class="btn btn-primary">Check Availability &rarr;</a>
      <a href="<?php echo esc_url( $packages_url ); ?>" class="btn btn-outline">Explore Packages</a>
    </p>
  </div>
</section>

<?php get_footer(); ?>
