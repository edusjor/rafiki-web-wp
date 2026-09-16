<?php
/**
 * Template Name: Home - Oficial
 *
 * Preserved copy of the original homepage layout. Kept as an assignable Page
 * template after the front page (front-page.php) switched to the condensed
 * "Home - Copia" layout. Nothing was deleted — assign this template to a Page
 * to view the original homepage.
 */
get_header(); ?>

<?php
$packages = get_posts( array( 'post_type' => 'package', 'posts_per_page' => 4, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );

$hero_img_url = wp_get_attachment_image_url( 125, 'full' );
if ( ! $hero_img_url ) $hero_img_url = 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_19-700x480.jpeg';

$rafting_post    = rafiki_find_post_by_keyword( 'activity', 'raft' );
$horseback_post  = rafiki_find_post_by_keyword( 'activity', 'horseback' );
$hiking_post     = rafiki_find_post_by_keyword( 'activity', 'hik' );
$birding_post    = rafiki_find_post_by_keyword( 'activity', 'bird' );
$beach_camp_post = rafiki_beach_camp_post();
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
      <p class="hero-sub">Imagine your own safari tent&hellip; Raft down the river, ride horses through the valley, hike to waterfalls. Relax by the pool. All adventures start on site, deep in the heart of the Savegre Valley in between Manuel Antonio and Dominical.</p>
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

<!-- ===== ALL-INCLUSIVE NATURE (explain the hero line in seconds) ===== -->
<section class="section" style="padding:64px 0; background:var(--cream);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">All-Inclusive Nature</span>
    <h2 class="section-title center" style="margin-bottom:20px;">NOT THE KIND THAT KEEPS YOU INSIDE A RESORT.</h2>
    <p>At Rafiki, nature is what fills the days. River. Forest. Horses. Trails. Birds. Waterfalls. Local guides. Long lunches. A pool waiting when you come back.</p>
    <p>Stay in one place and choose how much of it you want to experience.</p>
  </div>
</section>

<!-- ===== 02. WHY STAY HERE (central section) ===== -->
<section class="section" style="background:var(--cream-2);" id="why-stay">
  <div class="container">
    <div class="why-stay-intro">
      <div>
        <span class="eyebrow">Why Rafiki</span>
        <h2 class="section-title">ONE PLACE TO STAY.<br>A DIFFERENT WAY TO SPEND EVERY DAY.</h2>
      </div>
      <p>Costa Rica road trips can move fast. Pack the bags. Change hotels. Drive somewhere else. Find the next tour. Do it again tomorrow.<br><br>Rafiki gives you a few days where you don't have to pack, drive or chase the next tour. Curated adventures, homestyle cuisine and Rafiki hospitality are all just steps from your front porch.</p>
    </div>

    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_24.jpeg" alt="Savegre River surrounded by tropical rainforest at Rafiki" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius); margin-bottom:56px;">

    <div class="plan-grid" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Sleep in the Rainforest</h3>
        <p>Rafiki has 14 South African-style safari tents surrounded by tropical forest. Proper beds. Private bathrooms. Your own porch. Close enough to hear what's happening outside without giving up what makes for a good night's sleep.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Adventure Starts Here</h3>
        <p>Rafting, horseback riding, hiking and birding aren't things you have to search for once you arrive. They're part of life around Rafiki. Some days start on the river. Others start on horseback. And some never really need to leave the lodge.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'wave' ); ?>
        <h3>Do More Without Moving Again</h3>
        <p>Wake up. Have breakfast. Go rafting. Come back for lunch. Spend the afternoon at the pool. Tomorrow, ride horses or hike into the forest. You can fit a lot into a few days when you aren't packing the car every morning.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>Travel Together Without Doing Everything Together</h3>
        <p>Especially good for families and groups. Some people want the river. Others want a trail. Someone wants the pool. Someone else wants a book and the porch. You don't all have to vacation the same way to still spend the trip together.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>Meet the People Who Know This Place</h3>
        <p>The people guiding you, cooking for you and looking after Rafiki aren't learning this valley from a script. Many live just down the road in Santo Domingo and grew up around these rivers, horses and forests. That changes the experience.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'meal' ); ?>
        <h3>Sit Down for Real Meals, Not Snacks Between Tours</h3>
        <p>Breakfast before the day starts, lunch when you're back from the river, dinner when everyone's stories catch up. Lekker Bar &amp; Braai keeps the lodge fed without anyone leaving the property.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 03. DOES RAFIKI FIT YOUR COSTA RICA TRIP? ===== -->
<section class="section for-you" id="fit-your-trip">
  <div class="container">
    <span class="eyebrow center">See if This Sounds Like Your Trip</span>
    <h2 class="section-title center">RAFIKI MAY NOT HAVE BEEN ON YOUR LIST YET.<br>BUT IT PROBABLY BELONGS THERE IF…</h2>

    <div class="fit-checklist">
      <div class="fit-checklist-item">
        <?php echo rafiki_icon( 'check' ); ?>
        <div>
          <h3>You're Road-Tripping Costa Rica</h3>
          <p>…and want to add something different between the beaches, national parks and coastal towns already on your route.</p>
        </div>
      </div>
      <div class="fit-checklist-item">
        <?php echo rafiki_icon( 'check' ); ?>
        <div>
          <h3>Manuel Antonio, Dominical or Uvita Are Already in Your Plans</h3>
          <p>Rafiki takes you inland for a few days without sending your trip in a completely different direction.</p>
        </div>
      </div>
      <div class="fit-checklist-item">
        <?php echo rafiki_icon( 'check' ); ?>
        <div>
          <h3>Your Family Wants to Do More Than See Costa Rica From a Hotel</h3>
          <p>You want to raft, ride, walk through the forest, swim, see wildlife — then sit down for dinner together without organizing another transfer.</p>
        </div>
      </div>
      <div class="fit-checklist-item">
        <?php echo rafiki_icon( 'check' ); ?>
        <div>
          <h3>You Want Several Days of Adventure Without Chasing Tours Every Morning</h3>
          <p>Stay in one place. Let the experiences build around you.</p>
        </div>
      </div>
      <div class="fit-checklist-item">
        <?php echo rafiki_icon( 'check' ); ?>
        <div>
          <h3>Your Group Doesn't All Want the Same Thing</h3>
          <p>Good. They don't have to.</p>
        </div>
      </div>
      <div class="fit-checklist-item">
        <?php echo rafiki_icon( 'check' ); ?>
        <div>
          <h3>You Want a Side of Costa Rica That Feels Different From the Coast</h3>
          <p>A river valley. Forest. Small communities. Horses. Birds. And a few days that move at their own pace.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== 04. WHAT TWO OR THREE NIGHTS CAN LOOK LIKE ===== -->
<section class="section" style="background:var(--cream-2);" id="two-or-three-nights">
  <div class="container">
    <span class="eyebrow center">Imagine Your Stay</span>
    <h2 class="section-title center">YOU DON'T NEED A FULL WEEK TO FEEL FAR AWAY.</h2>

    <div class="itinerary" style="max-width:820px; margin:48px auto 40px;">
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Arrival Day</h3>
          <p>Leave the coast behind and head inland. Check into your safari tent. Find the pool. Walk around the lodge. Order a drink. Let the kids discover the water slide before you've even finished unpacking. Have dinner. Tomorrow can wait until tomorrow.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Your First Full Day</h3>
          <p>Coffee. Breakfast. Birds already moving around the lodge. Then the river — raft the Savegre through forest, rapids and swimming holes before coming back hungry for lunch. The afternoon is yours: pool, massage, hammock, porch, another swim. Whatever feels right.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>The Next Morning</h3>
          <p>Go again. Ride horseback through the valley. Take a forest hike. Look for birds. Visit waterfalls. Or decide yesterday was enough adventure and stay behind for a slower morning. You can split up. You can stay together. You'll meet again later.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Then Keep Going</h3>
          <p>Breakfast. One last look around. Back in the car. Continue toward Manuel Antonio, Dominical, Uvita or wherever the rest of Costa Rica is taking you. Except now your trip has one part that didn't feel quite like anywhere else.</p>
        </div>
      </div>
    </div>

    <p style="text-align:center;">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="btn btn-primary">Build Your Rafiki Stay</a>
    </p>
  </div>
</section>

<!-- ===== 05. EXPERIENCES FROM ONE BASE ===== -->
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
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="experience-card">
        <img src="https://rafikisafari.com/wp/wp-content/uploads/2016/12/beach-pool-700x420.jpg" alt="Pool at Rafiki Safari Lodge">
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

<!-- ===== 06. BRING YOUR PEOPLE (own block) ===== -->
<section class="cta-banner" id="groups">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_14a-700x420.jpg" alt="Group gathered together at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">14 Safari Tents. One Place to Come Back To.</span>
    <h2>SOME TRIPS ARE BETTER WHEN EVERYONE IS THERE.</h2>
    <p>Bring the family. Bring the grandparents. Bring your closest friends. Bring the people you've been saying you should travel with for years. Rafiki's 14 safari tents give groups room to stay together without requiring everyone to spend every hour doing the same thing.</p>
    <p>Some can raft. Some can ride. Some can stay at the lodge. Then everyone comes back to the same place at the end of the day — the same pool, the same table, different stories.</p>
    <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="btn btn-primary">Plan a Group Stay →</a>
  </div>
</section>

<!-- ===== 07. HOW DO YOU WANT TO EXPERIENCE RAFIKI? ===== -->
<section class="section" id="ways-to-stay">
  <div class="container">
    <span class="eyebrow center">Ways to Stay</span>
    <h2 class="section-title center">START WITH THE KIND OF TRIP YOU'RE PLANNING.</h2>

    <div class="plan-grid cols-4" style="margin-top:48px;">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Stay Two or Three Nights</h3>
        <p>The easiest way to add Rafiki to a Costa Rica road trip. Choose your tent, build a couple of experiences around your stay and leave enough time to actually enjoy the lodge.</p>
      </a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Let Us Build the Experience</h3>
        <p>Want the accommodation and activities to already make sense together? Start with one of Rafiki's multi-night packages.</p>
      </a>
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>Bring Your Group</h3>
        <p>Families, friends, reunions, retreats or people who simply want a few days together somewhere different.</p>
      </a>
      <a href="<?php echo $beach_camp_post ? esc_url( get_permalink( $beach_camp_post ) ) : esc_url( home_url( '/#beach-camp' ) ); ?>" class="plan-card" style="display:block;">
        <?php echo rafiki_icon( 'wave' ); ?>
        <h3>Forest + Beach</h3>
        <p>Combine time at Rafiki Safari Lodge with Rafiki Beach Camp near Playa Matapalo and experience two completely different parts of Costa Rica in one journey.</p>
      </a>
    </div>
  </div>
</section>

<!-- ===== 08. PACKAGES ===== -->
<?php if ( $packages ) : ?>
<section class="section" style="background:var(--cream-2);" id="packages">
  <div class="container">
    <span class="eyebrow center">Stay a Little Longer</span>
    <h2 class="section-title center">LET THE DAYS BUILD ON EACH OTHER.</h2>
    <div class="package-grid">
      <?php foreach ( $packages as $pkg ) :
        $p_id   = $pkg->ID;
        $p_tag  = get_post_meta( $p_id, 'rafiki_intro_eyebrow', true );
        $p_price = get_post_meta( $p_id, 'rafiki_price', true );
        $p_note  = get_post_meta( $p_id, 'rafiki_price_note', true );
        $p_sub   = get_post_meta( $p_id, 'rafiki_subtitle', true );
      ?>
        <a href="<?php echo esc_url( get_permalink( $pkg ) ); ?>" class="package-card" style="display:block;">
          <?php if ( $p_tag ) : ?><span class="package-name"><?php echo esc_html( $p_tag ); ?></span><?php endif; ?>
          <h3><?php echo esc_html( get_the_title( $pkg ) ); ?></h3>
          <p><?php echo esc_html( wp_trim_words( $p_sub, 18 ) ); ?></p>
          <?php if ( $p_price ) : ?><div class="package-price">$<?php echo esc_html( $p_price ); ?> <span><?php echo esc_html( $p_note ); ?></span></div><?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center; margin-top:36px;">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Compare Packages →</a>
    </p>
  </div>
</section>
<?php endif; ?>

<!-- ===== 09. GUEST PROOF ===== -->
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

    <p style="text-align:center; max-width:560px; margin:40px auto 0; color:var(--text-dark-muted); font-size:15px;">That's the part that's harder to explain before you arrive. A few days here gives people time to know your names, learn what your family enjoys and help the experience take its own shape.</p>
    <!-- TODO(Eduardo): link to the real Google/TripAdvisor review page once we have one to point to. -->
  </div>
</section>

<!-- ===== 10. A FAMILY PLACE, BUILT OVER TIME ===== -->
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

<!-- ===== 11. THE VALLEY MATTERS TOO ===== -->
<section class="section" id="conservation">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Community + Conservation</span>
    <h2 class="section-title center">KEEPING THE FOREST STANDING HAS TO MAKE SENSE FOR THE PEOPLE LIVING BESIDE IT.</h2>
    <p>Tourism here creates more than places for visitors to sleep. It creates work around guiding, horses, food, farming, transportation and experiences owned and operated by people from the surrounding communities.</p>
    <p>The river has value because people can build livelihoods around protecting and sharing it. The forest has value because visitors come to experience it alive. That connection between guests, local families and the landscape has always been part of Rafiki.</p>
    <p><a href="<?php echo esc_url( home_url( '/ecological-mission/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Our Approach to Conservation</a></p>
  </div>
</section>

<!-- ===== 12. GETTING TO RAFIKI ===== -->
<section class="section" style="background:var(--cream-2);" id="getting-here">
  <div class="container">
    <span class="eyebrow center">Road-Tripping Costa Rica?</span>
    <h2 class="section-title center">HERE'S WHERE RAFIKI FITS.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">If you're already traveling through Manuel Antonio, Dominical or Uvita, Rafiki takes you inland from the South Pacific coast and into the river valley for a few days. You don't need to know where Savegre is before your trip — we'll show you.</p>

    <div class="plan-grid cols-2" style="margin-bottom:40px;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'truck' ); ?>
        <h3>Driving Yourself?</h3>
        <p>Tell us where you're coming from and we'll send you the best current route, arrival information and what you should know before leaving the main coastal road.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Rather Not Think About the Drive?</h3>
        <p>We can help coordinate transportation and make the transition from the coast to Rafiki easier.</p>
      </div>
    </div>

    <div class="placeholder-photo" style="min-height:340px;">
      <span>Placeholder — add a short route video/map here (Manuel Antonio → coastal highway → Dominical → turn inland → Santo Domingo → Rafiki). Use real road footage; lead with Manuel Antonio/Dominical/Uvita, not Savegre.</span>
    </div>

    <p style="text-align:center; margin-top:40px;">
      <a href="<?php echo esc_url( home_url( '/plan-your-trip/' ) ); ?>" class="btn btn-primary">See How to Get Here</a>
    </p>
  </div>
</section>

<!-- ===== 13. PLAN YOUR RAFIKI STAY ===== -->
<section class="section" id="plan">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Not Sure How Rafiki Fits Your Route?</span>
    <h2 class="section-title center">TELL US WHAT YOUR COSTA RICA TRIP LOOKS LIKE.</h2>
    <p>Where are you staying before Rafiki? Where are you going afterward? Who's coming? What does your family actually enjoy doing?</p>
    <p>Send us the basics and our team can help you figure out whether two or three nights makes more sense, which experiences fit your group, what you can realistically do during your stay, how to get here, when to arrive, and how Rafiki fits into the rest of your Costa Rica itinerary.</p>
    <p>There's a real person on the other side of the message.</p>
    <p><a href="<?php echo esc_url( rafiki_whatsapp_link( "Hi! I'd like help planning my Costa Rica trip around a stay at Rafiki." ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Help Me Plan My Stay</a></p>
  </div>
</section>

<!-- ===== 14. FINAL CTA ===== -->
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
      <a href="#plan" class="btn btn-outline">Plan Your Rafiki Stay</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
