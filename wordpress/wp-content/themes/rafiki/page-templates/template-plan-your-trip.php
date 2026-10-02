<?php
/**
 * Template Name: Plan Your Trip
 *
 * Redesigned from a traditional FAQ accordion into a walked-through journey
 * ("Before You Get Here") that follows the order a guest actually thinks in:
 * coast -> road -> arrival -> tent -> experiences -> food -> booking.
 * Kept the "Plan Your Trip" template name/slug (nav and
 * footer already link here) but reframed the on-page identity per the new
 * copy. Facts below (drive times, ages, etc.) are still placeholders where
 * marked — verify with Loki before publishing.
 */
get_header();
?>

<section class="page-hero" style="min-height:46vh;">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-12' ) ); ?>" alt="Road to Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Plan Your Trip</span></p>
      <span class="eyebrow">Planning Rafiki?</span>
      <h1>BEFORE YOU GET HERE.<br><span class="accent">LET'S WALK THROUGH IT.</span></h1>
      <p class="hero-sub">You're looking at Rafiki and probably trying to figure out the same things most guests want to know before coming. Where exactly is it? How do I get there? How many nights? What will the kids do? What do we eat?</p>
      <p class="hero-sub">So instead of a wall of questions, let's take the trip in order.</p>
    </div>
  </div>
</section>

<!-- ===== STEP 01 — WHERE EXACTLY IS RAFIKI? ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Costa Rica Route</p>
    <span class="eyebrow center">Step 01 — You're Still on the Coast</span>
    <h2 class="section-title center">"WHERE EXACTLY IS RAFIKI?"</h2>
    <p>You're probably looking at Manuel Antonio, Dominical or Uvita on your Costa Rica route. Start there. Rafiki is where you turn inland for a few days and enter the lower Savegre Valley.</p>
    <p style="font-size:18px;">You don't need to know Savegre before planning the trip. We'll show you where it fits.</p>
    <p><a href="<?php echo esc_url( home_url( '/#getting-here' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">See It on the Map →</a></p>
  </div>
</section>

<!-- ===== STEP 02 — THE ROAD ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Costa Rica Route → Getting Here</p>
    <span class="eyebrow center">Step 02 — Now You're Wondering About the Road</span>
    <h2 class="section-title center">"DO I NEED A 4X4?"</h2>
    <p>Roughly 3.5–4 hours by car from San José (SJO), 16 km off the Coastal Highway near Savegre River, Pérez Zeledón. The last stretch is unpaved — 4x4 is required, not just recommended, especially in green season.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"CAN YOU ARRANGE TRANSPORTATION?"</h3>
    <p>Yes. Tell us where you're sleeping before Rafiki and where you're heading afterward. We'll help connect the route.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"WHAT WILL THE FINAL PART OF THE DRIVE LOOK LIKE?"</h3>
    <p style="font-size:18px;">Show it. Don't just explain it.</p>
    <!-- Insert a short real journey video here: coastal road -> turn inland ->
         Santo Domingo -> road toward Rafiki -> entrance -> arrival. Goal:
         reduce booking anxiety — the visitor should think "okay, now I
         understand how I get there." -->
    <div class="placeholder-photo" style="min-height:280px; margin-top:16px;">
      <span>Placeholder — short real video/photo sequence: coastal road → turn inland → Santo Domingo → road toward Rafiki → entrance → arrival.</span>
    </div>
  </div>
</section>

<!-- ===== STEP 03 — THE SAFARI TENTS ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Costa Rica Route → Getting Here → Arrival</p>
    <span class="eyebrow center">Step 03 — You Arrive</span>
    <h2 class="section-title center">"WHAT ARE THE SAFARI TENTS ACTUALLY LIKE?"</h2>
    <p>Canvas outside. A proper bed inside. Private bathroom. Hot shower. Rocking chairs and your own porch. Forest around you. You hear more of Costa Rica's nature without having to sleep like you're camping.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"ARE THERE BUGS?"</h3>
    <p style="font-size:18px;">Yes. You're in a rainforest. Most of them belong outside your tent. Keep the screens and tent properly closed and let the forest stay where it belongs.</p>
    <!-- Ideally a short POV-style walkthrough: entrance -> tent exterior ->
         bed -> bathroom -> porch -> view. Goal: "this is what I'll
         experience when I arrive." -->
    <div class="placeholder-photo" style="min-height:280px; margin-top:16px;">
      <span>Placeholder — POV-style walkthrough video/photos: entrance → safari tent exterior → bed → bathroom → porch → view.</span>
    </div>
    <p style="margin-top:24px;"><a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">See the Safari Tents →</a></p>
  </div>
</section>

<!-- ===== STEP 04 — THE FAMILY HAS OPINIONS ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Your Stay → Experiences</p>
    <span class="eyebrow center">Step 04 — Now the Family Has Opinions</span>
    <h2 class="section-title center">"DO WE ALL HAVE TO DO THE SAME THING?"</h2>
    <p>No. One part of the family can raft. Someone can go birding. Someone stays at the lodge. Someone books a massage. Everyone comes back later.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"IS RAFIKI GOOD FOR CHILDREN?"</h3>
    <p style="font-size:18px;">Rafiki works particularly well for families because there is enough adventure to make the trip exciting and enough freedom that every day doesn't have to be organized around the kids. Pool. Water slide. River. Horses. Wildlife. Forest. And plenty of dirt. Usually a good combination.</p>
  </div>
</section>

<!-- ===== STEP 05 — THE RIVER ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Experiences</p>
    <span class="eyebrow center">Step 05 — You're Thinking About the River</span>
    <h2 class="section-title center">"I'VE NEVER RAFTED. IS THAT A PROBLEM?"</h2>
    <p>No. Your guide handles the river knowledge. You handle listening and paddling. Current water conditions determine how the day operates.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"CAN MY CHILD RAFT?"</h3>
    <p>If your little one is under six, we can always talk about it. If they're comfortable in the water, adventurous, and happy to go down the slide, there's a good chance we can make it work. We'd just want to make sure it feels right and safe for them first.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"WHAT IF THE RIVER CHANGES?"</h3>
    <p style="font-size:18px;">Then the plan changes. A real river doesn't run according to a booking calendar. Rafiki's guides make decisions based on what the Savegre is doing that day.</p>
    <p><a href="<?php echo esc_url( home_url( '/experiences/white-water-rafting/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Explore Whitewater Rafting →</a></p>
  </div>
</section>

<!-- ===== STEP 06 — FOOD ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Experiences → Food</p>
    <span class="eyebrow center">Step 06 — Now You're Hungry</span>
    <h2 class="section-title center">"WHAT WILL WE EAT?"</h2>
    <p>Breakfast, lunch and dinner are served at Lekker Bar &amp; Braai. And yes — you can see the menus before arriving.</p>
    <p><a href="<?php echo esc_url( home_url( '/lekker-bar-braai/' ) ); ?>" class="btn btn-primary">See What's Cooking →</a></p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"CAN YOU HANDLE DIETARY RESTRICTIONS?"</h3>
    <p><em>[Placeholder — confirm current policy for vegetarian, vegan, gluten-free, allergies and children's meals with Loki.]</em></p>
  </div>
</section>

<!-- ===== STEP 07 — BOOKING / LENGTH OF STAY ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Booking</p>
    <span class="eyebrow center">Step 07 — You've Been Here Two Days</span>
    <h2 class="section-title center">"IS TWO NIGHTS ENOUGH?"</h2>
    <p>Two nights gives you one strong Rafiki experience. Three nights gives the place room to work — two different adventure days, time at the lodge, breakfast without rushing, a slower afternoon. For most first-time guests, three nights gives you a much fuller sense of Rafiki.</p>
    <h3 style="font-size:20px; margin:28px 0 8px;">"WHAT IF WE HAVE MORE TIME?"</h3>
    <p style="font-size:18px;">Forest Beach and Super Lekker continue the Rafiki journey toward Beach Camp and the Pacific.</p>
    <p><a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Compare Rafiki Journeys →</a></p>
  </div>
</section>

<!-- ===== STEP 08 — ALL-INCLUSIVE NATURE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <p style="text-align:center; font-size:12px; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-dark-muted); margin-bottom:8px;">You're here: Booking</p>
    <span class="eyebrow center">Step 08 — You're Already Thinking About Booking</span>
    <h2 class="section-title center">"WHAT DOES ALL-INCLUSIVE NATURE ACTUALLY MEAN?"</h2>
    <p style="font-size:18px;">Not everything included. Everything connected.</p>
    <p>Your safari tent. The forest. The river. The people guiding you. Experiences. A pool to return to. Different ways to spend the day without starting your vacation planning process all over again every morning. That's Rafiki's version of all-inclusive.</p>
  </div>
</section>

<!-- ===== FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-16' ) ); ?>" alt="Family sharing a meal at Rafiki">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Still Wondering About Something?</span>
    <h2>ASK RAFIKI.</h2>
    <p>There is a real person on the other side. Tell us what you're trying to figure out.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I have a question before booking my trip to Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Ask Us a Question →</a>
  </div>
</section>

<?php get_footer(); ?>
