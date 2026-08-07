<?php
/**
 * Template Name: Plan Your Trip
 *
 * Logistics page whose only job is to lower booking anxiety: how to get
 * here, what to pack, weather, connectivity, and FAQs. Facts below (drive
 * times, electricity, etc.) are placeholder estimates pulled loosely from
 * the property's general location — the client must verify every specific
 * before this goes live.
 */
get_header();
?>

<section class="page-hero" style="min-height:40vh;">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_12.jpeg" alt="Road to Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Plan Your Trip</span></p>
      <h1>PLANNING YOUR <span class="accent">TRIP.</span></h1>
      <p class="hero-sub">Everything you need to know before you go — so the only thing left to think about once you arrive is who you're with.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="plan-grid">
      <div class="plan-card">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>Getting Here</h3>
        <p><em>[Placeholder — verify]</em> Roughly 3.5–4 hours by car from San José (SJO), 16 km off the Coastal Highway near Savegre River, Pérez Zeledón. The last stretch is unpaved — 4x4 recommended, especially in green season. Private transfers and shared shuttles can be arranged on request.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'leaf' ); ?>
        <h3>Weather</h3>
        <p><em>[Placeholder — verify]</em> Warm and humid year-round. High season (Dec–Apr) is drier and busier; green season (May–Nov) brings afternoon rain, lush scenery and quieter trails. Rafting runs well in both.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'bolt' ); ?>
        <h3>Electricity &amp; Connectivity</h3>
        <p><em>[Placeholder — verify]</em> Hydro-electric power, available 24 hours. WiFi is available at the Main Lodge, and intentionally limited elsewhere — part of what makes this a place to unplug.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>What to Pack</h3>
        <p><em>[Placeholder — verify]</em> Light, quick-dry clothing, a rain jacket, closed-toe shoes for hikes, swimwear, insect repellent, reef-safe sunscreen, and a reusable water bottle. Evenings can be cool — bring a light layer.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'meal' ); ?>
        <h3>Meals</h3>
        <p><em>[Placeholder — verify]</em> Home-cooked breakfast, lunch and dinner served at the Lekker Bar and Braai, included with most stays. Vegetarian and plant-based options available with advance notice.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'shield' ); ?>
        <h3>Health &amp; Safety</h3>
        <p><em>[Placeholder — verify]</em> No specific vaccinations required for most travelers. Activities are guided by trained, local staff, with safety gear provided for rafting, hiking and horseback riding.</p>
      </div>
    </div>

    <h2 class="section-title center">FREQUENTLY ASKED QUESTIONS</h2>
    <div class="faq-list">
      <details class="faq-item">
        <summary>Is Rafiki suitable for young kids and grandparents in the same trip?</summary>
        <p><em>[Placeholder — verify with operations team]</em> Yes — that's most of what we do. Activities range from gentle (birding, the Main Lodge pool, short walks) to more active (rafting, longer hikes), so a multi-generational group can mix and match rather than doing everything together.</p>
      </details>
      <details class="faq-item">
        <summary>Do we need to book activities in advance, or can we decide once we arrive?</summary>
        <p><em>[Placeholder — verify]</em> We recommend booking your core activities ahead of time, especially in high season, but there's flexibility to adjust once you're here depending on weather and group energy.</p>
      </details>
      <details class="faq-item">
        <summary>What if it rains during our stay?</summary>
        <p><em>[Placeholder — verify]</em> Rain is part of the rainforest — most activities run in light rain, and rafting is often better with more water in the river. Guides will always tell you if conditions aren't safe.</p>
      </details>
      <details class="faq-item">
        <summary>Can Rafiki accommodate a large family group or retreat all on the same property?</summary>
        <p>Yes — this is our specialty. See the <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>">Bring Your Group</a> page to tell us about your group size and dates.</p>
      </details>
      <details class="faq-item">
        <summary>Is there cell signal at the lodge?</summary>
        <p><em>[Placeholder — verify]</em> Signal is limited on the property — WiFi is available at the Main Lodge for anything urgent. Most guests tell us that's one of their favorite parts of the trip.</p>
      </details>
      <details class="faq-item">
        <summary>How do we pay, and is a deposit required?</summary>
        <p><em>[Placeholder — verify]</em> We accept card and bank transfer / SINPE Móvil. Reach out and we'll walk you through availability, pricing and deposit details for your dates.</p>
      </details>
    </div>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_16.jpeg" alt="Family sharing a meal at Rafiki">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>STILL HAVE QUESTIONS?</h2>
    <p>Send us a message — a real person will answer, not a bot.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I have a question before booking my trip to Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Ask Us Anything →</a>
  </div>
</section>

<?php get_footer(); ?>
