<?php
/**
 * Template Name: Why Rafiki
 *
 * Not an "About Us" bio page — it exists to answer one question before a
 * visitor books: "why this place, and not a resort?" Copy below is a first
 * draft the client should fact-check (dates, numbers, specific claims).
 */
get_header();
?>

<section class="page-hero" style="min-height:46vh;">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_24.jpeg" alt="Savegre River surrounded by tropical rainforest">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Why Rafiki</span></p>
      <h1>WE DON'T PUT NATURE <span class="accent">IN A BOX.</span></h1>
      <p class="hero-sub">The animals live their life. We live in their world. This is Costa Rica, raw and real.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container intro-block">
    <p>Rafiki isn't a resort, and it isn't trying to be one. It's not a safari in the African sense either — there are no lions here, no jeeps circling fenced enclosures. What we have is 600 acres of private rainforest on the Savegre River, where wildlife comes and goes on its own schedule, and where a family, a group of friends, or a retreat can slow down enough to actually notice it.</p>
    <p>If you're looking for room service, a swim-up bar and a schedule of poolside activities, you probably won't love it here. If you're looking for real conversations, a slower pace, and nature that isn't performing for you — welcome.</p>
  </div>

  <div class="container">
    <div class="filter-split">
      <div class="filter-split-col">
        <h3>This might not be for you if…</h3>
        <ul>
          <li>You want room service and a poolside cocktail menu.</li>
          <li>You'd rather see wildlife guaranteed, on a schedule, in an enclosure.</li>
          <li>You need strong WiFi and full phone signal at all times.</li>
          <li>You're looking for a high-rise view of the ocean.</li>
        </ul>
      </div>
      <div class="filter-split-col is-yes">
        <h3>This is for you if…</h3>
        <ul>
          <li>You want nature that's real, not staged.</li>
          <li>You want to actually talk to the people you came with.</li>
          <li>You're fine trading a few conveniences for something you'll remember.</li>
          <li>You want a place with a story, not a chain with a logo.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="why-grid why-grid-boxed">
      <div class="why-media">
        <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_18.jpeg" alt="Main Lodge at Rafiki Safari Lodge">
        <div class="why-media-text">
          <h2>25+ YEARS<br><span class="accent">FAMILY-OWNED.</span></h2>
          <p>Built from the ground up on the Savegre River, long before "eco lodge" was a marketing word.</p>
          <a href="<?php echo esc_url( home_url( '/meet-rafiki/' ) ); ?>" class="btn btn-primary">Meet the People Behind It →</a>
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
          <strong>600 ACRES</strong>
          <span>Private reserve between jungle and river.</span>
        </div>
        <div class="stat">
          <?php echo rafiki_icon( 'heart' ); ?>
          <strong>COUNTLESS MEMORIES</strong>
          <span>Made by thousands of returning guests.</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="max-width:820px;">
    <h2 class="section-title">"LET AND LIVE LET."</h2>
    <p>It's how Loki, Rafiki's founder, describes the property's approach to wildlife: the animals live their life, and we live in their world — not the other way around. There's no feeding schedule to guarantee a sighting, no fences built for a photo. What you see, you see because it wanted to be seen.</p>
    <p><em>[Placeholder quote — replace with Loki's own words. This section is built to hold a direct quote or short video from him; it's one of the strongest assets Rafiki has and doesn't yet exist anywhere on the site.]</em></p>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_1.jpeg" alt="Exploring the tropical rainforest at Rafiki">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>SOUNDS LIKE YOUR KIND OF PLACE?</h2>
    <p>Let's talk about what you're looking for and when.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I just read Why Rafiki and would like to plan a visit.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Plan Your Visit →</a>
  </div>
</section>

<?php get_footer(); ?>
