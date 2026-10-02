<?php get_header(); ?>

<?php
$packages = get_posts( array( 'post_type' => 'package', 'posts_per_page' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );

/* Editorial "mood" layer for the 4 known journeys — matched by title so it
   stays connected to whatever real package posts exist, without forcing
   this narrative framing into post meta that other templates would need
   to know about too. Falls back to plain badge/subtitle text if a title
   doesn't match (e.g. a new package the admin adds later). */
$journey_moods = array(
	'Safarito'           => array( 'label' => 'The Detour',           'quote' => "I have my own car and I like taking the turn most people miss." ),
	'Rafiki Safari'      => array( 'label' => 'The Family Base',      'quote' => "I'm traveling with my family and want us to actually experience Costa Rica together." ),
	'Savegre Adventure'  => array( 'label' => 'The Two-World Journey','quote' => "I came for the rainforest and the Pacific. I want both." ),
	'Super Lekker Safari'=> array( 'label' => 'The Deep Stay',        'quote' => "I'd rather go deeper than keep changing hotels." ),
);
?>

<!-- ===== 01. HERO ===== -->
<section class="page-hero">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-25' ) ); ?>" alt="Rafiki Safari Lodge and Rafiki Beach Camp">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Packages</span></p>
      <span class="eyebrow">Ways to Experience Rafiki</span>
      <h1>THERE'S MORE THAN<br><span class="accent">ONE WAY TO DO RAFIKI.</span></h1>
      <p class="hero-sub">Some travelers have two nights, a rental car and a route already mapped out. Some are bringing the family and want a few days where everyone actually does something together. Others came to Costa Rica for both the rainforest and the Pacific and don't want to experience them as disconnected stops.</p>
      <p class="hero-sub">That's why Rafiki has different journeys. Not because everyone needs the same itinerary — because the best stay is the one that fits the way you're already traveling.</p>
      <p class="hero-sub" style="font-style:italic; color:#fff;">Choose your time. Choose your rhythm. We'll take care of how the days fit together.</p>
      <div class="hero-actions">
        <a href="#match" class="btn btn-primary">Find My Rafiki Journey</a>
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Check Availability</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. START WITH YOUR TRIP (self-select before reading a single package in full) ===== -->
<?php if ( $packages ) : ?>
<section class="section" id="match">
  <div class="container">
    <span class="eyebrow center">Which One Sounds Most Like You?</span>
    <h2 class="section-title center">DON'T START WITH THE ACTIVITIES.<br>START WITH HOW YOU TRAVEL.</h2>

    <div class="plan-grid cols-2" style="margin-top:48px;">
      <?php foreach ( $packages as $pkg ) :
        $p_id    = $pkg->ID;
        $p_title = get_the_title( $pkg );
        $mood    = isset( $journey_moods[ $p_title ] ) ? $journey_moods[ $p_title ] : array( 'label' => $p_title, 'quote' => '' );
        $p_tag   = get_post_meta( $p_id, 'rafiki_intro_eyebrow', true );
        $p_sub   = get_post_meta( $p_id, 'rafiki_subtitle', true );
        $badges  = rafiki_rows( $p_id, 'rafiki_badges' );
      ?>
        <a href="<?php echo esc_url( get_permalink( $pkg ) ); ?>" class="plan-card" style="display:block;">
          <?php if ( $mood['quote'] ) : ?><p style="font-style:italic; color:var(--text-dark-muted); margin:0 0 14px;">"<?php echo esc_html( $mood['quote'] ); ?>"</p><?php endif; ?>
          <span class="eyebrow" style="margin-bottom:6px;"><?php echo esc_html( $mood['label'] ); ?></span>
          <h3><?php echo esc_html( strtoupper( $p_title ) ); ?></h3>
          <?php if ( $badges && ! empty( $badges[0]['text'] ) ) : ?><p style="font-weight:600; color:var(--text-dark); margin-bottom:10px;"><?php echo esc_html( $badges[0]['text'] ); ?><?php if ( ! empty( $badges[1]['text'] ) ) echo ' · ' . esc_html( $badges[1]['text'] ); ?></p><?php endif; ?>
          <p><?php echo esc_html( wp_trim_words( $p_sub, 24 ) ); ?></p>
          <span class="arrow-link" style="margin-top:14px;">→</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== 07. COMPARE BY MOOD, NOT JUST NIGHTS ===== -->
<?php if ( $packages ) : ?>
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <span class="eyebrow center">Your Rafiki Match</span>
    <h2 class="section-title center">WHAT DO YOU WANT THIS PART OF COSTA RICA TO FEEL LIKE?</h2>

    <div class="plan-grid cols-4" style="margin-top:48px; margin-bottom:0;">
      <?php foreach ( $packages as $pkg ) :
        $p_id    = $pkg->ID;
        $p_title = get_the_title( $pkg );
        $mood    = isset( $journey_moods[ $p_title ] ) ? $journey_moods[ $p_title ] : array( 'label' => $p_title, 'quote' => '' );
        $badges  = rafiki_rows( $p_id, 'rafiki_badges' );
      ?>
        <a href="<?php echo esc_url( get_permalink( $pkg ) ); ?>" class="plan-card" style="display:block; background:#fff;">
          <span class="eyebrow"><?php echo esc_html( $mood['label'] ); ?></span>
          <h3><?php echo esc_html( strtoupper( $p_title ) ); ?></h3>
          <?php if ( $mood['quote'] ) : ?><p>"<?php echo esc_html( $mood['quote'] ); ?>"</p><?php endif; ?>
          <?php if ( $badges && ! empty( $badges[0]['text'] ) ) : ?>
            <p style="font-weight:700; color:var(--orange); margin-top:14px; margin-bottom:0; font-size:13px; letter-spacing:0.4px; text-transform:uppercase;"><?php echo esc_html( $badges[0]['text'] ); ?><?php if ( ! empty( $badges[1]['text'] ) ) echo ' · ' . esc_html( $badges[1]['text'] ); ?></p>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section" style="background:var(--cream-2);">
  <div class="container" style="max-width:760px;">
    <h3 style="text-align:center; font-size:22px; text-transform:uppercase; margin-bottom:32px;">Before You Pick One</h3>
    <div class="faq-list">
      <details class="faq-item">
        <summary>Why wouldn't I just book the room and decide everything later?</summary>
        <p>You can. Packages are for people who want the rhythm of the trip already solved — how many nights, how many major experiences, when to leave space for the lodge, and (for Forest Beach and Super Lekker) how the forest and Pacific portions connect. You're not buying less freedom. You're removing planning work.</p>
      </details>
      <details class="faq-item">
        <summary>Are packages only for people who want to be busy all day?</summary>
        <p>No — the whole point is the opposite. A good Rafiki journey gives the adventures space around them. If every hour is full, we've missed the point.</p>
      </details>
      <details class="faq-item">
        <summary>Do all members of our family have to do the same experience?</summary>
        <p>Not necessarily. One of Rafiki's strengths is allowing people to spend parts of the day differently. <em>[Placeholder — confirm with Loki how this affects package inclusions/pricing.]</em></p>
      </details>
      <details class="faq-item">
        <summary>Which package is best for a first visit?</summary>
        <p>For travelers with enough time, Rafiki Safari's three-night format gives you two different experience days and still leaves room to enjoy the lodge. Safarito works better when you're road-tripping and only have two nights.</p>
      </details>
      <details class="faq-item">
        <summary>Which package is best if we have our own car?</summary>
        <p>Safarito is especially natural for independent road-trippers who want to turn inland for two nights before returning to their Costa Rica route.</p>
      </details>
      <details class="faq-item">
        <summary>If we book three nights, will we regret not staying longer?</summary>
        <p>Possibly — that's why the journeys have different purposes. Three nights is enough to understand Rafiki properly. Five or six nights turns the trip into a forest-to-Pacific journey. It isn't that more is automatically better — it's how large a role you want Rafiki to play in your Costa Rica trip.</p>
      </details>
    </div>
  </div>
</section>

<!-- ===== 08. ONE FAMILY DOESN'T ALWAYS MEAN ONE PLAN ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Your Days Can Flex</span>
    <h2 class="section-title center">STAY TOGETHER. EXPERIENCE RAFIKI DIFFERENTLY.</h2>
    <p>Choosing a package doesn't mean everyone has to follow the same rhythm every hour. One person wakes up for birding. The family rafts together later. Someone chooses horseback riding the next day. Someone else stays at the lodge. The kids go back to the pool. A massage fills the afternoon for someone who has officially done enough.</p>
    <p style="font-size:18px;">The package gives the trip structure. It doesn't take away your freedom.</p>
  </div>
</section>

<!-- ===== 09. WHY BOOK A RAFIKI JOURNEY? ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <h2 class="section-title center">THE POINT ISN'T MORE ACTIVITIES.<br>IT'S A BETTER-FLOWING TRIP.</h2>
    <p>You already have enough decisions to make while planning Costa Rica. Where to stay. How long. What to book. How far everything is. What the kids will actually enjoy. Whether you have enough time.</p>
    <p>A Rafiki package takes those moving pieces and makes them belong to the same journey. Your room is here. Your experiences are connected to the stay. Your team already knows the valley. And the days are built around where you're sleeping instead of sending you across the map every morning.</p>
    <p style="font-size:18px;">That's the value.</p>
  </div>
</section>

<!-- ===== 10. WHAT GUESTS SAY ===== -->
<section class="section testimonials">
  <div class="container">
    <span class="eyebrow center">The Part You Remember Afterward</span>
    <h2 class="section-title center">PEOPLE RARELY COME HOME TALKING ABOUT THE PACKAGE NAME.</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16px;">They talk about the river. The guide who knew their kids' names. The horse ride. The waterfall. The birds at breakfast. The food after a long day. The water slide. The people. And the feeling that, for a few days, everyone was actually doing the trip together.</p>

    <div class="testimonial-grid">
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'tripadvisor' ); ?><span>TripAdvisor Review</span></div>
        <div class="stars">★★★★★</div>
        <p>"Lovely cabins, excellent food, and the rafting was a life-changing experience. The whole family loved every minute."</p>
        <span class="testimonial-author">TaikoM</span>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'google' ); ?><span>Google Review</span></div>
        <div class="stars">★★★★★</div>
        <p>"We've been back 5 times in 15 years. All the guides grew up in the area and that's what makes the trip truly special. Pura vida."</p>
        <span class="testimonial-author">Lance R.</span>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'tripadvisor' ); ?><span>TripAdvisor Review</span></div>
        <div class="stars">★★★★★</div>
        <p>"The beauty of Rafiki alone is worth the visit. I can't recommend the Rafiki Safari Lodge enough."</p>
        <span class="testimonial-author">Frank T.</span>
      </div>
    </div>
    <!-- TODO(Eduardo): swap in 3 verified reviews prioritizing family/shared experiences, staff-guide connection, and multi-activity stays — avoid price-focused reviews. -->
  </div>
</section>

<!-- ===== 11. STILL DECIDING? ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">You Don't Have to Figure It Out Alone</span>
    <h2 class="section-title center">TELL US HOW YOU'RE TRAVELING.</h2>
    <p>Send us your dates, who's coming, where you're coming from, where you're going next and what kind of days your group enjoys.</p>
    <p>We'll tell you which Rafiki journey makes the most sense. Not which one has the highest price. Which one fits the trip you're actually taking.</p>
    <p><a href="<?php echo esc_url( rafiki_whatsapp_link( "Hi! I'm not sure which Rafiki package fits my trip — can you help me choose?" ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Help Me Choose My Rafiki Journey</a></p>
  </div>
</section>

<!-- ===== 12. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Choose Your Way Into Rafiki</span>
    <h2>TWO NIGHTS. SIX NIGHTS. FOREST. BEACH. RIVER. SLOW DAYS.</h2>
    <p>The right Rafiki journey is the one that fits naturally into the Costa Rica trip you're already building. Choose yours. We'll help with the rest.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( "Hi! I'm not sure which Rafiki package fits my trip — can you help me choose?" ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Help Me Choose</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
