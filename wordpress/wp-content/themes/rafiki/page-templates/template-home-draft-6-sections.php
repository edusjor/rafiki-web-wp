<?php
/**
 * Template Name: Home — Draft (6 Sections)
 *
 * Comparison draft only. This is the consolidated homepage (hero + 5
 * sections) built to test a shorter structure against the official
 * 15-section front-page.php. Not linked from any nav/menu — reachable
 * only by direct URL while it's being reviewed.
 */
get_header();

$rafting_post    = rafiki_find_post_by_keyword( 'activity', 'raft' );
$horseback_post  = rafiki_find_post_by_keyword( 'activity', 'horseback' );
$hiking_post     = rafiki_find_post_by_keyword( 'activity', 'hik' );
$birding_post    = rafiki_find_post_by_keyword( 'activity', 'bird' );
$beach_camp_post = rafiki_beach_camp_post();

$hero_img_url = wp_get_attachment_image_url( 125, 'full' );
if ( ! $hero_img_url ) $hero_img_url = rafiki_photo( 'property-and-food-19' );
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

<!-- ===== 02. WHY RAFIKI (consolidated — intro + reasons in one section) ===== -->
<section class="section" style="background:var(--cream-2);" id="why-stay">
  <div class="container">
    <div class="why-stay-intro">
      <div>
        <span class="eyebrow">All-Inclusive Nature</span>
        <h2 class="section-title">ONE PLACE TO STAY.<br>AS MUCH ADVENTURE AS YOU WANT.</h2>
      </div>
      <p>Nature is what fills the days here — river, forest, horses, trails, birds, waterfalls. Stay two or three nights, unpack once, and decide each morning how much of it becomes your day.</p>
    </div>

    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-24' ) ); ?>" alt="Savegre River surrounded by tropical rainforest at Rafiki" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius); margin-bottom:56px;">

    <div class="plan-grid cols-4" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Sleep in the Rainforest</h3>
        <p>14 South African-style safari tents surrounded by tropical forest. Proper beds. Private bathrooms. Your own porch.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>Travel Together Without Doing Everything Together</h3>
        <p>Someone wants the river. Someone wants the pool. You don't all have to vacation the same way to still spend the trip together.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>Meet the People Who Know This Place</h3>
        <p>Many guides live just down the road in Santo Domingo and grew up around these rivers, horses and forests. That changes the experience.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'meal' ); ?>
        <h3>Sit Down for Real Meals</h3>
        <p>Breakfast before the day starts, lunch when you're back, dinner when everyone's stories catch up — all without leaving the property.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 03. EXPERIENCES FROM ONE BASE ===== -->
<section class="section experience" id="experiences">
  <div class="container">
    <span class="eyebrow center">Your Days at Rafiki</span>
    <h2 class="section-title center">PICK THE KIND OF DAY YOU WANT.</h2>

    <div class="experience-grid home-grid">
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
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="experience-card">
        <img src="<?php echo esc_url( rafiki_photo( 'beach-pool' ) ); ?>" alt="Pool at Rafiki Safari Lodge">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>OR DON'T PLAN THE AFTERNOON</h3>
          <p>Pool. Water slide. A long lunch. A massage. Your porch. A book. Doing less counts too.</p>
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
    <!-- TODO(Eduardo): link to the real Google/TripAdvisor review page once we have one to point to. -->

    <div class="trust-strip">
      <div class="trust-strip-item">
        <?php echo rafiki_icon( 'family' ); ?>
        <strong>25+ YEARS</strong>
        <span>Family-owned and operated</span>
      </div>
      <div class="trust-strip-item">
        <?php echo rafiki_icon( 'leaf' ); ?>
        <strong>100% COSTA RICAN</strong>
        <span>Local team, local impact</span>
      </div>
      <div class="trust-strip-item">
        <?php echo rafiki_icon( 'shield' ); ?>
        <strong>1 INCREDIBLE LOCATION</strong>
        <span>Between the jungle and the ocean</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== 05. CHOOSE YOUR PATH ===== -->
<section class="section" id="ways-to-stay">
  <div class="container">
    <span class="eyebrow center">Ways to Stay</span>
    <h2 class="section-title center">START WITH THE KIND OF TRIP YOU'RE PLANNING.</h2>

    <div class="plan-grid cols-4" style="margin-top:48px;">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Stay Two or Three Nights</h3>
        <p>The easiest way to add Rafiki to a Costa Rica road trip.</p>
      </a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Let Us Build the Experience</h3>
        <p>Want the stay and activities to already make sense together? Start with a package.</p>
      </a>
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>Bring Your Group</h3>
        <p>Families, friends, reunions, retreats — a few days together somewhere different.</p>
      </a>
      <a href="<?php echo $beach_camp_post ? esc_url( get_permalink( $beach_camp_post ) ) : esc_url( home_url( '/#beach-camp' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'wave' ); ?>
        <h3>Forest + Beach</h3>
        <p>Combine Rafiki Safari Lodge with Rafiki Beach Camp near Playa Matapalo.</p>
      </a>
    </div>
  </div>
</section>

<!-- ===== 06. FINAL CTA ===== -->
<section class="cta-banner" id="plan">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Group rafting in front of the lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Make Room for Rafiki</span>
    <h2>GOT TWO OR THREE NIGHTS?</h2>
    <p>Give your Costa Rica trip a few days by the river, in the forest and away from the usual route.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( "Hi! I'd like help planning my Costa Rica trip around a stay at Rafiki." ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Not Sure? Let Us Help You Plan</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
