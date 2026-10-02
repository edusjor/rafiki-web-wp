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
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-19' ) ); ?>" alt="Rafiki Safari Lodge main deck">
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
    <div class="profile-grid" style="grid-template-columns: 1fr; max-width: 420px; margin-left: auto; margin-right: auto;">
      <div class="profile-card">
        <div class="placeholder-photo"><span>Placeholder — real photo of Loki and Mauren needed (portrait, ideally candid, greeting guests or in the jungle)</span></div>
        <div>
          <span class="profile-role">Founders</span>
          <h3>Loki and Mauren</h3>
          <p><em>[Placeholder bio — replace with Loki and Mauren's real story in their own words.]</em> Loki and Mauren built Rafiki from a piece of land on the Savegre River into what it is today, without ever wanting it to become another resort. They still greet guests at breakfast, still tell the story of how the property came to be, and still mean it when they say they don't want everyone here — just the people who'll actually appreciate it.</p>
          <p>Ask them about the animals, the river, or why the tents came from South Africa. They'll talk for an hour if you let them.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">THE TEAM THAT MAKES IT REAL</h2>
    <div class="intro-block" style="max-width:760px; margin:0 auto 32px; text-align:center;">
      <p>Most of the guides, cooks and staff at Rafiki grew up in the Savegre Valley — guests mention them by name in almost every review.</p>
    </div>
    <div class="placeholder-photo" style="max-width:820px; margin:0 auto; min-height:340px;"><span>Placeholder — one general photo of the whole Rafiki team together</span></div>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-11' ) ); ?>" alt="Guides and guests at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>COME MEET US IN PERSON.</h2>
    <p>The best way to understand Rafiki is still to sit at the Lekker Bar and hear the stories yourself.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I just read Meet Rafiki and would like to plan a visit.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Plan Your Visit →</a>
  </div>
</section>

<?php get_footer(); ?>
