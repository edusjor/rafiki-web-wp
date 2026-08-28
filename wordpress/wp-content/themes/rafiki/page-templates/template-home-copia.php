<?php
/**
 * Template Name: Home - Copia
 *
 * Shortened comparison copy of front-page.php, wired up as a normal Page
 * (not a front-page override) so it shows in wp-admin as "Home - Copia"
 * without touching the live homepage. Same wording, kept sections only:
 * hero, all-inclusive nature, why stay (trimmed from 6 to 4 cards),
 * experiences, guest reviews, our story. Dropped the "does this fit your
 * trip" checklist, the 2-3 nights itinerary walkthrough, the separate
 * "bring your people" banner, the "ways to stay" card grid (same card
 * layout repeated), packages, conservation and getting-here blocks — all
 * either duplicated elsewhere on the site or a second copy of the same
 * card-grid pattern already used above. Does not touch the original file.
 */
get_header();

$hero_img_url = wp_get_attachment_image_url( 125, 'full' );
if ( ! $hero_img_url ) $hero_img_url = 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_19-700x480.jpeg';

$rafting_post    = rafiki_find_post_by_keyword( 'activity', 'raft' );
$horseback_post  = rafiki_find_post_by_keyword( 'activity', 'horseback' );
$hiking_post     = rafiki_find_post_by_keyword( 'activity', 'hik' );
$birding_post    = rafiki_find_post_by_keyword( 'activity', 'bird' );
?>

<!-- ===== 01. HERO ===== -->
<section class="hero" id="top">
  <div class="hero-media">
    <img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Safari tent and pool at Rafiki Safari Lodge, surrounded by rainforest">
    <div class="hero-overlay"></div>
  </div>

  <div class="container hero-content">
    <div class="hero-content-inner">
      <span class="eyebrow">All-Inclusive Nature in Costa Rica</span>
      <h1>GIVE YOUR COSTA RICA ROAD TRIP<br><span class="accent">A FEW DAYS IN THE WILD.</span></h1>
      <p class="hero-sub">Raft the river. Horseback ride through the valley. Hike to a waterfall. Watch birds over breakfast. Relax by the pool. One safari tent in the rainforest, tucked in the forest between Manuel Antonio, Dominical and Uvita.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
        <a href="#plan" class="btn btn-outline">Plan Your Rafiki Stay</a>
      </div>
    </div>
  </div>

  <div class="hero-features">
    <div class="container hero-features-inner">
      <div class="feature-item">
        <?php echo rafiki_icon( 'tent' ); ?>
        <div>
          <strong>14 safari-style tents</strong>
          <span>Surrounded by nature, not buildings.</span>
        </div>
      </div>
      <div class="feature-item">
        <?php echo rafiki_icon( 'guide' ); ?>
        <div>
          <strong>Rafting on property</strong>
          <span>The only lodge on the river with its own put-in.</span>
        </div>
      </div>
      <div class="feature-item">
        <?php echo rafiki_icon( 'wave' ); ?>
        <div>
          <strong>Beach &amp; jungle</strong>
          <span>Two incredible worlds. One unforgettable trip.</span>
        </div>
      </div>
      <div class="feature-item">
        <?php echo rafiki_icon( 'heart' ); ?>
        <div>
          <strong>Family-owned for 25 years</strong>
          <span>Passion, purpose and people.</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== ALL-INCLUSIVE NATURE ===== -->
<section class="section" style="padding:64px 0; background:var(--cream);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">All-Inclusive Nature</span>
    <h2 class="section-title center" style="margin-bottom:20px;">NOT THE KIND THAT KEEPS YOU INSIDE A RESORT.</h2>
    <p>At Rafiki, nature is what fills the days. River. Forest. Horses. Trails. Birds. Waterfalls. Local guides. Long lunches. A pool waiting when you come back.</p>
    <p>Stay in one place and choose how much of it you want to experience.</p>
  </div>
</section>

<!-- ===== 02. WHY STAY HERE ===== -->
<section class="section" style="background:var(--cream-2);" id="why-stay">
  <div class="container">
    <div class="why-stay-intro">
      <div>
        <span class="eyebrow">Why Rafiki</span>
        <h2 class="section-title">ONE PLACE TO STAY.<br>A DIFFERENT KIND OF DAY EVERY MORNING.</h2>
      </div>
      <p>Costa Rica road trips can move fast. Pack the bags. Change hotels. Drive somewhere else. Find the next tour. Do it again tomorrow.<br><br>Rafiki gives you a few days where you don't have to. Experience curated Adventures, homestyle cuisine and Rafiki hospitality all from your front porch.</p>
    </div>

    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_24.jpeg" alt="Savegre River surrounded by tropical rainforest at Rafiki" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius); margin-bottom:56px;">

    <div class="plan-grid" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Sleep in the Rainforest</h3>
        <p>Rafiki has 14 South African-style safari tents surrounded by tropical forest. Proper beds. Private bathrooms. Your own porch. Close enough to hear what's happening outside without giving up the things that make a good night's sleep feel good.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Adventure Starts Here</h3>
        <p>Rafting, horseback riding, hiking and birding aren't things you have to search for once you arrive. They're part of life around Rafiki. Some days start on the river. Others start on horseback. And some never really need to leave the lodge.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>Travel Together Without Doing Everything Together</h3>
        <p>Especially good for families and groups. Some people want the river. Others want a trail. Someone wants the pool. Someone else wants a book and the porch. You don't all have to vacation the same way to still spend the trip together.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'meal' ); ?>
        <h3>Sit Down for Real Meals, Not Snacks Between Tours</h3>
        <p>Breakfast before the day starts, lunch when you're back from the river, dinner when everyone's stories catch up. Lekker Bar &amp; Braai keeps the lodge fed without anyone leaving the property.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 03. EXPERIENCES FROM ONE BASE ===== -->
<section class="section experience" id="experiences">
  <div class="container">
    <span class="eyebrow center">Your Days at Rafiki</span>
    <h2 class="section-title center">PICK THE KIND OF DAY YOU WANT.</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16px;">You don't stay at Rafiki just to have somewhere to sleep between tours. The lodge is the base the experiences grow from.</p>

    <div class="experience-grid home-grid">
      <a href="<?php echo $rafting_post ? esc_url( get_permalink( $rafting_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $rafting_post ? esc_url( rafiki_lead_image_url( $rafting_post->ID, 'rafiki-card' ) ) : 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg'; ?>" alt="Whitewater rafting on the Savegre River">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>RUN THE RIVER</h3>
          <p>Rapids, forest, swimming holes, waterfalls and guides who know these waters through more than one season.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $horseback_post ? esc_url( get_permalink( $horseback_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $horseback_post ? esc_url( rafiki_lead_image_url( $horseback_post->ID, 'rafiki-card' ) ) : 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_50.jpeg'; ?>" alt="Horseback riding through the valley">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>SEE THE VALLEY FROM THE SADDLE</h3>
          <p>Ride with local guides and horses that are part of life here, not simply brought in for the activity.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $hiking_post ? esc_url( get_permalink( $hiking_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $hiking_post ? esc_url( rafiki_lead_image_url( $hiking_post->ID, 'rafiki-card' ) ) : 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_1.jpeg'; ?>" alt="Hiking into the rainforest at Rafiki">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>WALK INTO THE FOREST</h3>
          <p>Trails toward rivers, waterfalls, suspension bridges and places you wouldn't see from the road.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $birding_post ? esc_url( get_permalink( $birding_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $birding_post ? esc_url( rafiki_lead_image_url( $birding_post->ID, 'rafiki-card' ) ) : 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_24.jpeg'; ?>" alt="Birding at Rafiki Safari Lodge">
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

<!-- ===== 04. GUEST PROOF ===== -->
<section class="section testimonials" id="guest-stories">
  <div class="container">
    <span class="eyebrow center">What People Remember</span>
    <h2 class="section-title center">THE REVIEWS USUALLY START WITH THE ADVENTURE.</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16px;">Then they start talking about everything around it — the safari tents, the birds at breakfast, the food, the river, the kids not wanting to leave the water slide. And, again and again, the people.</p>

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
        <p>"We've been back 5 times in 15 years. All the guides grew up in the area and that's what makes the trip truly special. Pura vida."</p>
        <span class="testimonial-author">Lance R.</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== 05. A FAMILY PLACE, BUILT OVER TIME ===== -->
<section class="section why-rafiki" id="about">
  <div class="why-grid">
    <div class="why-media">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_16.jpeg" alt="Family sharing a meal at Rafiki Safari Lodge">
      <div class="why-media-text">
        <span class="eyebrow">Our Story</span>
        <h2>RAFIKI DIDN'T START<br><span class="accent">AS A HOTEL CONCEPT.</span></h2>
        <p>Constant Boshoff founded Rafiki after bringing an idea inspired by Africa to Costa Rica and finding a home for it in this river valley. Now Loki and Mauren continue that story alongside a local team closely connected to Santo Domingo and the communities around Rafiki.</p>
        <a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>" class="btn btn-primary">Read the Rafiki Story →</a>
      </div>
    </div>

    <div class="why-stats">
      <div class="stat">
        <?php echo rafiki_icon( 'family' ); ?>
        <strong>25+ YEARS</strong>
        <span>Family-owned and operated.</span>
      </div>
      <div class="stat">
        <?php echo rafiki_icon( 'leaf' ); ?>
        <strong>100% COSTA RICAN</strong>
        <span>Local team. Local impact.</span>
      </div>
      <div class="stat">
        <?php echo rafiki_icon( 'shield' ); ?>
        <strong>1 INCREDIBLE LOCATION</strong>
        <span>Between the jungle and the ocean — 600 acres.</span>
      </div>
      <div class="stat">
        <?php echo rafiki_icon( 'heart' ); ?>
        <strong>A LIVING PLACE</strong>
        <span>Not a museum about the family who built it.</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== 06. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_34.jpeg" alt="Group rafting in front of the lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Make Room for Rafiki</span>
    <h2>GOT TWO OR THREE NIGHTS?</h2>
    <p>Give your Costa Rica trip a few days by the river, in the forest and away from the usual route.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
      <a href="#top" class="btn btn-outline">Back to Top</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
