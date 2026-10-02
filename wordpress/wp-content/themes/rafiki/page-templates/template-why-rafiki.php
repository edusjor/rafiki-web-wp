<?php
/**
 * Template Name: Why Rafiki
 *
 * Condensed "Our Story" — the manifesto in ~6 blocks instead of 15, cut
 * roughly in half. Same voice and facts, fewer near-duplicate chapters.
 * Rhythm alternates contained text with image-driven sections:
 *   1. Hero — Rafiki was never supposed to be just another hotel
 *   2. From Africa to Costa Rica  (image + text, dark)
 *   3. Why this place             (text + image)
 *   4. The people & the community (image + text, dark)
 *   5. Family + philosophy        (text + "is this for you" split)
 *   6. Closing CTA
 * The full 15-section version is preserved as "Why Rafiki - Oficial"
 * (page-templates/template-why-rafiki-oficial.php). Facts (dates, names)
 * come from Loki directly.
 */
get_header();
?>

<style>
  /* Full-width image sections: keep the text aligned to the site container
     instead of jammed into the viewport's left corner on wide screens. */
  .wr-story .why-media { min-height: 520px; }
  .wr-story .why-media-text { padding: 64px 0; }
  .wr-story .why-media-text > .container { width: 100%; }
  .wr-story .why-media-text p { max-width: 620px; }
  @media (max-width: 700px) {
    .wr-story .why-media { min-height: 0; }
    .wr-story .why-media-text { padding: 44px 0; }
  }
  .wr-split-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; }
  .wr-split-grid p { color: var(--text-dark-muted); }
  .wr-split-grid img { width: 100%; height: 520px; object-fit: cover; border-radius: var(--radius); }
  @media (max-width: 900px) {
    .wr-split-grid { grid-template-columns: 1fr; gap: 28px; }
    .wr-split-grid img { height: 300px; }
  }
</style>

<!-- ===== 01. HERO — Rafiki was never supposed to be just another hotel ===== -->
<section class="page-hero" style="min-height:56vh;">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'tent-sunset' ) ); ?>" alt="Sunset over the Savegre Valley from a safari tent">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Our Story</span></p>
      <span class="eyebrow">The Story Behind Rafiki</span>
      <h1>RAFIKI WAS NEVER SUPPOSED<br>TO BE <span class="accent">JUST ANOTHER HOTEL.</span></h1>
      <p class="hero-sub">It started with a family, an idea carried from Africa, and a piece of land in Costa Rica that felt too special to treat like a property. A river. Forest that was still forest. A small community nearby. And enough distance from the usual route to feel completely different.</p>
      <p class="hero-sub">Rafiki grew from there &mdash; not into a resort, but into a place where people stay close to nature, do things together, and leave knowing the valley better than when they arrived.</p>
    </div>
  </div>
</section>

<!-- ===== 02. FROM AFRICA TO COSTA RICA ===== -->
<!-- Light split block (not a full-bleed photo) so it doesn't read as a second hero. -->
<section class="section wr-split">
  <div class="container wr-split-grid">
    <div>
      <span class="eyebrow">From Africa to Costa Rica</span>
      <h2 class="section-title">THE IDEA CAME FROM AFRICA.<br><span class="accent">COSTA RICA MADE IT ITS OWN.</span></h2>
      <p>Constant Boshoff grew up close to wilderness in Africa and built a family retreat around simple things &mdash; canvas tents, a fire, time outside, people together. Not luxury for its own sake: just comfortable enough to stay close to nature instead of walling yourself off from it.</p>
      <p>In 1999 he found a farm in Costa Rica's lower Savegre Valley &mdash; tropical forest, a warm river, birdlife, small communities, waterfalls. Rafiki opened in 2002. The safari tents came from Africa. Everything after that became Costa Rican.</p>
      <p style="font-weight:700;">You're not coming here for an African animal safari. Here, safari is the journey &mdash; the days, the people you travel with, and the places the river takes you.</p>
    </div>
    <img src="<?php echo esc_url( rafiki_photo( 'tents-15' ) ); ?>" alt="Safari tent deck with rocking chairs, open to the forest">
  </div>
</section>

<!-- ===== 03. WHY THIS PLACE ===== -->
<section class="section" style="padding-top:0;">
  <div class="container">
    <h2 class="section-title">AN AFRICAN SAFARI CAMP, IN THE COSTA RICAN FOREST.</h2>
    <div class="gallery-grid"><?php rafiki_photo_grid( rafiki_photo_set( 'story' ) ); ?></div>
  </div>
</section>

<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">The Place Came First</span>
    <h2 class="section-title center">RAFIKI IS HERE BECAUSE OF WHAT WAS ALREADY HERE.</h2>
    <p>This part of the Savegre is lower, warmer, tropical &mdash; the river moving through dense forest and communities that sit well away from Costa Rica's main tourism corridor.</p>
    <p style="font-size:18px;">The point was never to build somewhere convenient and manufacture a nature experience around it. The river was already here. The forest was already here. The people were already here. Rafiki had to learn how to belong to that.</p>
  </div>
  <div class="container">
    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-3' ) ); ?>" alt="Forest and river in the lower Savegre Valley at Rafiki" style="width:100%; height:440px; object-fit:cover; border-radius:var(--radius); margin-top:16px;">
  </div>
</section>

<!-- ===== 04. THE PEOPLE & THE COMMUNITY ===== -->
<section class="section why-rafiki wr-story">
  <div class="why-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-16' ) ); ?>" alt="Swimming in the Savegre River below Rafiki">
    <div class="why-media-text">
      <div class="container">
        <span class="eyebrow">Santo Domingo &amp; the Valley</span>
        <h2>RAFIKI DIDN'T<br><span class="accent">GROW ALONE.</span></h2>
        <p>Most of the team comes from Santo Domingo and the surrounding valley. The knowledge guests come here for can't be imported &mdash; the guides know the river because they've spent years around it: how it changes with rain, the trails, the horses, the birds, the places worth stopping.</p>
        <p>Over the years Rafiki became a way to build work around a landscape people already knew. The Duarte family's horses were part of the valley before any tourist arrived. Neighboring families in Quebrada Arroyo run Los Campesinos &mdash; their own forest-and-waterfall project, on their own terms. Rafiki embraces the local culture and allows our guests to respectfully interact with the community.</p>
        <p style="font-weight:700; color:#fff;">A forest is easier to protect when people can build a life around it. That knowledge isn't a layer on top of the experience &mdash; it is the experience.</p>
        <p><a href="<?php echo esc_url( home_url( '/ecological-mission/' ) ); ?>" class="btn btn-outline">Our Ecological Mission &rarr;</a></p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 05. FAMILY + PHILOSOPHY ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Rafiki Today</span>
    <h2 class="section-title center">THE FAMILY CHANGED.<br>THE RESPONSIBILITY DIDN'T.</h2>
    <p>Constant's son Loki, a biologist, and Mauren, who grew up in the valley and became its first college graduate, run Rafiki today alongside the local team. The job now isn't to make Rafiki bigger at any cost &mdash; it's to protect what makes it worth coming for while keeping an independent lodge alive in a remote valley.</p>
    <p style="font-size:18px;">That means choosing: who Rafiki is for, how many people make sense, which experiences belong here. The best version of Rafiki happens when guests stay a few nights &mdash; long enough for the guides to know who they're taking out, long enough for the place to stop feeling like an activity and start feeling like somewhere you've actually been.</p>
    <div style="max-width:560px; margin:32px auto 0; padding:28px 32px; background:var(--dark); border-radius:var(--radius); border-left:4px solid var(--orange);">
      <p style="color:#fff; font-family:var(--font-head); font-size:24px; text-transform:uppercase; letter-spacing:0.4px; margin:0;">Rafiki doesn't need everyone.</p>
      <p style="color:var(--text-muted); margin:10px 0 0; font-size:15px;">It's trying to give the right people a better experience once they're here.</p>
    </div>
  </div>

  <div class="container">
    <div class="filter-split">
      <div class="filter-split-col">
        <h3>This might not be for you if…</h3>
        <ul>
          <li>You want room service and a poolside cocktail menu.</li>
          <li>You'd rather see wildlife guaranteed, on a schedule, in an enclosure.</li>
          <li>You need strong WiFi and full phone signal at all times.</li>
          <li>Your ideal vacation needs nightlife, shopping nearby, or a schedule where nature never interferes with the plan.</li>
        </ul>
        <p style="margin-top:18px; font-size:14.5px;">And that's okay. We'd rather you know that before you book than after.</p>
      </div>
      <div class="filter-split-col is-yes">
        <h3>This is for you if…</h3>
        <ul>
          <li>You want nature that's real, not staged.</li>
          <li>You want to actually talk to the people you came with.</li>
          <li>You're fine trading a few conveniences for something you'll remember.</li>
          <li>You want a place with a story, not a chain with a logo.</li>
        </ul>
        <p style="margin-top:18px;"><a href="<?php echo esc_url( home_url( '/meet-rafiki/' ) ); ?>" class="btn btn-outline">Meet the Whole Team &rarr;</a></p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 06. CLOSING CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-1' ) ); ?>" alt="Exploring the tropical rainforest at Rafiki">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">What "All-Inclusive Nature" Means</span>
    <h2>NOT EVERYTHING INCLUDED.<br>EVERYTHING CONNECTED.</h2>
    <p>Your tent, the river, the forest, the guides, the meals and the experiences belong to the same place. You don't wake up searching for another company every morning &mdash; you wake up at Rafiki and decide what kind of nature day you want.</p>
    <p>Come to raft, come for the birds, come because your family needs a few days off the usual route. The reason you book doesn't have to be what you remember most &mdash; that's happened here plenty of times.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="btn btn-primary">Plan Your Rafiki Stay</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I just read Our Story and would like to check availability at Rafiki.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Check Availability</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
