<?php
/**
 * Template Name: Our Ecological Mission
 *
 * Condensed rewrite — the previous version (17 sections) made the point but
 * required far too much scroll. This keeps the same tone and the same core
 * claims, grounded in the real facts from the original page at
 * https://rafikisafari.com/wp/ecological_mission/ (Paso de la Danta, ASANA,
 * the Boshoff family arriving in 1999, the shift from logging/cattle to
 * tourism, the school's growth in Santo Domingo) rather than the looser,
 * more speculative framing the old version had (e.g. an unverified
 * employee headcount, an implied active "Save the Tapir Project"). Two
 * things are still deliberately soft-pedaled per Loki's guidance until
 * confirmed: (1) ASANA's expanded name — different sources render it
 * differently, use the acronym only until confirmed; (2) the "physical
 * footprint" section intentionally ships as a to-do checklist, not invented
 * specifics — only publish practices Rafiki can verify.
 */
get_header();
?>

<!-- ===== 01. HERO ===== -->
<section class="page-hero" style="min-height:56vh;">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-18' ) ); ?>" alt="Rainforest canopy in the Savegre Valley near Rafiki">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Our Ecological Mission</span></p>
      <span class="eyebrow">Our Ecological Mission</span>
      <h1>RAFIKI WAS BUILT TO GIVE THIS FOREST<br><span class="accent">ANOTHER REASON TO STAY FOREST.</span></h1>
      <p class="hero-sub">The river, wildlife and tropical forest around Rafiki aren't scenery — they're the reason the lodge exists. The idea has been simple since the beginning: if people can build a livelihood around protecting this landscape, the forest becomes worth more standing than cleared.</p>
    </div>
  </div>
</section>

<!-- ===== 02. PASO DE LA DANTA + WHY THE TAPIR ===== -->
<section class="section" id="corridor">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Paso de la Danta</span>
    <h2 class="section-title center">A FOREST IS STRONGER WHEN IT'S CONNECTED TO ANOTHER FOREST.</h2>
    <p>Rafiki sits at the northern edge of the Paso de la Danta Biological Corridor — a stretch of privately owned land connecting the Savegre Valley to the Osa Peninsula and Corcovado National Park. "Paso de la Danta" translates to "tapir crossing," named for the Baird's tapir, Central America's largest land mammal and one that needs large, connected forest to survive. Protect enough habitat for a tapir to move through, and you protect the route for pumas, jaguars, birds and everything else that depends on the same connected forest.</p>
    <p style="font-size:18px;">Rafiki works with ASANA, the local organization behind the corridor, because a patchwork of private properties only stays connected if enough of the landowners in it choose to keep it that way.</p>
  </div>
</section>

<!-- ===== 03. MAP ===== -->
<section class="section">
  <div class="container">
    <h2 class="section-title">THE FOREST WE ARE HERE TO PROTECT.</h2>
    <div class="gallery-grid"><?php rafiki_photo_grid( rafiki_photo_set( 'eco' ) ); ?></div>
  </div>
</section>

<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <!-- Visual for Eduardo: simple map — Savegre Valley / Rafiki -> Paso de la Danta
         Biological Corridor -> Osa Peninsula / Corcovado, with an overlay noting
         "Connected forest = room for wildlife to move." -->
    <div class="placeholder-photo" style="min-height:320px; max-width:820px; margin:0 auto;">
      <span>Placeholder — add a simple map here: Savegre Valley / Rafiki → Paso de la Danta Biological Corridor → Osa Peninsula / Corcovado, with a callout reading "Connected forest = room for wildlife to move."</span>
    </div>
  </div>
</section>

<!-- ===== 04. FROM LOGGING TO TOURISM (real 1999 history) ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">A Valley That Changed Once Already</span>
    <h2 class="section-title center">YOU CAN'T ASK A COMMUNITY TO PROTECT A FOREST THAT GIVES THEM NO WAY TO LIVE.</h2>
    <p>The lower Savegre was logged for hardwood, then cleared for cattle, long before it was protected. When Costa Rica tightened its forestry laws, the people who had depended on logging were left with no income and no forest left to log. When the Boshoff family arrived in the valley in 1999, the lesson was already clear: conservation only works here if it works for the people living beside the forest too.</p>
    <p>So Rafiki built the lodge with the community from the start — what began as clearing pasture and carrying construction materials grew into hotel staff, naturalist guides, mechanics and management. And when Rafiki opened in 2002, Santo Domingo had one schoolhouse teaching first through sixth grade. Today it has a pre-school, grade school and high school — built on an economy that gives families a reason to stay, and their kids more to choose from.</p>
  </div>
</section>

<!-- ===== 05. TWO LOCAL PARTNERSHIPS ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">What Local Opportunity Looks Like</span>
    <h2 class="section-title center">TWO FAMILIES. TWO EXAMPLES.</h2>
    <p>The Duarte family already knew horses before Rafiki existed — tourism just gave that knowledge another economic use, and grew it into the horseback-riding trips guests take today. In Quebrada Arroyo, families built Los Campesinos around their own forest, waterfalls and trails; Rafiki simply connects guests to it through experiences like the Aqua Hike. In both cases, the value stays with the people already living on that land.</p>
    <p style="display:flex; gap:14px; justify-content:center; flex-wrap:wrap; margin:20px 0 0;">
      <a href="<?php echo esc_url( home_url( '/experiences/horseback-riding/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Explore Horseback Riding →</a>
      <a href="<?php echo esc_url( home_url( '/experiences/aqua-hike/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Explore the Aqua Hike →</a>
    </p>
  </div>
</section>

<!-- ===== 06. YOUR STAY'S FOOTPRINT + OPERATIONS (still a to-do) ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Your Stay Has a Footprint</span>
    <h2 class="section-title center">THE QUESTION IS WHAT KIND.</h2>
    <p>Every traveler uses resources, and every lodge does too — so this isn't about pretending to leave nothing behind. It's staying local, using local guides, choosing community-owned experiences, respecting river conditions and wildlife, and remembering that the people showing you Costa Rica live here after your vacation ends. It's also why Rafiki doesn't chase day-tour volume: too many visitors changes the river, the trails and the experience itself.</p>
    <div class="placeholder-photo" style="min-height:180px; margin-top:24px;">
      <span>Placeholder — only publish practices Rafiki can currently verify (water, waste, energy, single-use plastics, trail management). "We're eco-friendly" isn't the goal here; "here's exactly what we do" is.</span>
    </div>
  </div>
</section>

<!-- ===== BEFORE YOU TAKE OUR WORD FOR IT ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container" style="max-width:760px;">
    <h3 style="text-align:center; font-size:22px; text-transform:uppercase; margin-bottom:32px;">Before You Take Our Word for It</h3>
    <div class="faq-list">
      <details class="faq-item">
        <summary>Is this actual conservation or just eco-hotel marketing?</summary>
        <p>That's exactly why we need specifics. Rafiki's conservation story includes participation in the Paso de la Danta biological corridor with ASANA, alongside local employment and community-based tourism. We'd rather show the projects and relationships than ask you to believe the word "sustainable."</p>
      </details>
      <details class="faq-item">
        <summary>Does my money actually stay in the area?</summary>
        <p>Some of the strongest examples: local lodge employment, local guides, the Duarte family, Los Campesinos, food and service relationships. We won't claim "100% stays local" unless that's verifiable — what we can tell you is which parts of your stay create local livelihoods.</p>
      </details>
      <details class="faq-item">
        <summary>Will we see a tapir?</summary>
        <p>Possibly, but we'll never promise it. The Baird's tapir matters to Rafiki's ecological story because protecting enough connected habitat for tapirs protects much more than one species.</p>
      </details>
    </div>
  </div>
</section>

<!-- ===== THE MISSION IN ONE LINE ===== -->
<section class="section">
  <div class="container">
    <h2 class="section-title center">FOREST. WILDLIFE. COMMUNITY. WORK.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">They aren't four different Rafiki projects. They're connected.</p>

    <div class="itinerary" style="max-width:760px; margin:0 auto;">
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Forest</h3>
          <p>Protect the forest and wildlife has somewhere to move.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Wildlife</h3>
          <p>Connected habitat is what a corridor like Paso de la Danta exists to protect.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Community</h3>
          <p>Create work around the forest and communities have more reasons to protect it.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Work</h3>
          <p>Bring travelers into that system responsibly and tourism becomes part of the solution instead of simply another pressure on the landscape.</p>
        </div>
      </div>
    </div>
    <p style="text-align:center; margin-top:40px; font-size:18px; font-weight:700;">That's the ecological mission behind Rafiki.</p>
  </div>
</section>

<!-- ===== FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-9' ) ); ?>" alt="Wildlife in the forest surrounding Rafiki">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Come See What Your Stay Is Connected To</span>
    <h2>YOU'RE NOT JUST SLEEPING IN THE FOREST.</h2>
    <p>You're spending time inside a living landscape — and supporting people who are working to keep it that way.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( home_url( '/#plan' ) ); ?>" class="btn btn-primary">Plan Your Rafiki Stay</a>
      <a href="#corridor" class="btn btn-outline">Learn About Paso de la Danta</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
