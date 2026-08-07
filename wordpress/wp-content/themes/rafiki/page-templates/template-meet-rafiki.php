<?php
/**
 * Template Name: Meet Rafiki
 *
 * Loki is a brand asset most lodges don't have — this page (and the Home
 * teaser section) is where that finally shows up on the site. All bios
 * below are placeholder text in Rafiki's voice for the client to correct
 * and personalize; photos are marked placeholder until real ones are supplied.
 */
get_header();
?>

<section class="page-hero" style="min-height:40vh;">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_19.jpeg" alt="Rafiki Safari Lodge main deck">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Meet Rafiki</span></p>
      <h1>MEET THE PEOPLE <span class="accent">BEHIND RAFIKI.</span></h1>
      <p class="hero-sub">Most lodges don't have a Loki. Here's who's actually welcoming you at breakfast.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="profile-grid">
      <div class="profile-card">
        <div class="placeholder-photo"><span>Placeholder — real photo of Loki needed (portrait, ideally candid, greeting guests or in the jungle)</span></div>
        <div>
          <span class="profile-role">Founder</span>
          <h3>Loki</h3>
          <p><em>[Placeholder bio — replace with Loki's real story in his own words.]</em> Loki built Rafiki from a piece of land on the Savegre River into what it is today, without ever wanting it to become another resort. He still greets guests at breakfast, still tells the story of how the property came to be, and still means it when he says he doesn't want everyone here — just the people who'll actually appreciate it.</p>
          <p>Ask him about the animals, the river, or why the tents came from South Africa. He'll talk for an hour if you let him.</p>
        </div>
      </div>

      <div class="profile-card">
        <div class="placeholder-photo"><span>Placeholder — real photo of Mauren needed (portrait, ideally in the lodge or with guests)</span></div>
        <div>
          <span class="profile-role">Co-Founder</span>
          <h3>Mauren</h3>
          <p><em>[Placeholder bio — replace with Mauren's real story.]</em> Mauren is the other half of Rafiki — the one making sure every family, group and retreat that comes through actually feels taken care of, from the first WhatsApp message to the last breakfast. Ask any long-time guest and they'll likely mention her by name.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">THE TEAM THAT MAKES IT REAL</h2>
    <div class="intro-block" style="max-width:760px; margin:0 auto; text-align:center;">
      <p><em>[Placeholder — this section is built to hold real names and photos of the guides, cooks and staff, most of whom grew up in the Savegre Valley. Guests mention them by name in almost every review; right now that isn't reflected anywhere on the site.]</em></p>
    </div>
    <div class="profile-grid">
      <div class="profile-card">
        <div class="placeholder-photo"><span>Placeholder — photo of a lead guide (e.g. rafting or horseback)</span></div>
        <div>
          <span class="profile-role">Lead Guide</span>
          <h3>[Guide Name]</h3>
          <p><em>[Placeholder — short bio: how long they've worked at Rafiki, what they're known for, where they grew up.]</em></p>
        </div>
      </div>
      <div class="profile-card">
        <div class="placeholder-photo"><span>Placeholder — photo of the kitchen / Lekker Bar team</span></div>
        <div>
          <span class="profile-role">Kitchen &amp; Lekker Bar</span>
          <h3>[Team Name]</h3>
          <p><em>[Placeholder — short bio: the story behind the food, who's cooking, what they're proud of.]</em></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_11.jpeg" alt="Guides and guests at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>COME MEET US IN PERSON.</h2>
    <p>The best way to understand Rafiki is still to sit at the Lekker Bar and hear the stories yourself.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I just read Meet Rafiki and would like to plan a visit.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Plan Your Visit →</a>
  </div>
</section>

<?php get_footer(); ?>
