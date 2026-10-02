<?php
/**
 * Template Name: Home 2
 *
 * Backup copy of the Home page as it stood before the "why 2-3 nights at
 * Rafiki" copy rewrite. Kept selectable as a Page template (assign it to a
 * page with slug home-2) so the previous version stays reachable/compare-able
 * without living in the front-page.php slot anymore.
 */
?>
<?php get_header(); ?>

<?php
$stay_post = get_posts( array( 'post_type' => 'accommodation', 'posts_per_page' => 1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
$stay_post = $stay_post ? $stay_post[0] : null;

$activity_post = get_posts( array( 'post_type' => 'activity', 'posts_per_page' => 1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
$activity_post = $activity_post ? $activity_post[0] : null;

$beach_camp_post = rafiki_beach_camp_post();

$packages = get_posts( array( 'post_type' => 'package', 'posts_per_page' => 4, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );

$hero_img_url = wp_get_attachment_image_url( 125, 'full' );
if ( ! $hero_img_url ) $hero_img_url = rafiki_photo( 'property-and-food-19' );
?>

<!-- ===== HERO ===== -->
<section class="hero" id="top">
  <div class="hero-media">
    <img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Aerial view of the Rafiki Safari Lodge main lodge, pool and jungle">
    <div class="hero-overlay"></div>
  </div>

  <div class="container hero-content">
    <div class="hero-content-inner">
      <h1>
        THIS ISN'T A RESORT.<br>
        <span class="accent">IT'S REAL COSTA RICA.</span>
      </h1>
      <p class="hero-sub">For families, groups and travelers who want connection, adventure and nature — without crowds or schedules.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="btn btn-primary">Explore Experiences</a>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="btn btn-outline">Plan Your Stay</a>
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

<!-- ===== THIS PLACE IS FOR YOU IF ===== -->
<section class="section for-you">
  <div class="container">
    <h2 class="section-title center">THIS PLACE IS FOR YOU IF…</h2>

    <div class="for-you-grid">
      <div class="for-you-item">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>YOU WANT TIME TOGETHER</h3>
        <p>Reconnect with the people who matter most.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'mountain' ); ?>
        <h3>YOU LOVE NATURE AND ADVENTURE</h3>
        <p>Rivers, waterfalls, jungle and unforgettable views.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'shield' ); ?>
        <h3>YOU'RE PLANNING SOMETHING BIG</h3>
        <p>Retreats, celebrations and group experiences that leave a lasting impact.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>YOU TRAVEL DIFFERENTLY</h3>
        <p>You value authenticity over luxury. Real over perfect.</p>
      </div>
    </div>

    <div class="for-you-photos">
      <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-16' ) ); ?>" alt="Family sharing a meal at Rafiki">
      <img src="<?php echo esc_url( rafiki_photo( 'activities-11' ) ); ?>" alt="Group whitewater rafting on the Savegre River">
      <img src="<?php echo esc_url( rafiki_photo( 'activities-50' ) ); ?>" alt="Group activity outdoors at Rafiki">
      <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-1' ) ); ?>" alt="Exploring the tropical rainforest at Rafiki">
    </div>
  </div>
</section>

<!-- ===== CHOOSE YOUR EXPERIENCE ===== -->
<section class="section experience" id="experiences">
  <div class="container">
    <h2 class="section-title center">CHOOSE YOUR EXPERIENCE</h2>

    <div class="experience-grid">
      <a href="<?php echo $stay_post ? esc_url( get_permalink( $stay_post ) ) : esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>" class="experience-card">
        <img src="<?php echo $stay_post ? esc_url( rafiki_lead_image_url( $stay_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'tents-8' ); ?>" alt="Safari-style tents at Rafiki">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>STAY</h3>
          <p><?php echo $stay_post ? esc_html( get_the_title( $stay_post ) ) : 'Safari-style tents in the heart of the jungle.'; ?></p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $activity_post ? esc_url( get_permalink( $activity_post ) ) : esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="experience-card">
        <img src="<?php echo $activity_post ? esc_url( rafiki_lead_image_url( $activity_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'activities-25' ); ?>" alt="Rafting on the Savegre River">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>ADVENTURE</h3>
          <p><?php echo $activity_post ? esc_html( get_the_title( $activity_post ) ) : 'Rafting, waterfalls, horseback riding and more.'; ?></p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="experience-card">
        <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-14a' ) ); ?>" alt="Group and retreat space at Rafiki">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>GROUPS &amp; RETREATS</h3>
          <p>Spaces and experiences to connect and grow.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $beach_camp_post ? esc_url( get_permalink( $beach_camp_post ) ) : esc_url( home_url( '/#beach-camp' ) ); ?>" class="experience-card">
        <img src="<?php echo $beach_camp_post ? esc_url( rafiki_lead_image_url( $beach_camp_post->ID, 'rafiki-card' ) ) : rafiki_photo( 'beach-pool' ); ?>" alt="Rafiki Beach Camp at Playa Matapalo">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>BEACH CAMP</h3>
          <p>Sleep by the ocean, just 45 minutes away.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ===== MEET RAFIKI ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">MEET RAFIKI</h2>
    <p style="text-align:center; max-width:640px; margin:-24px auto 40px; color:var(--text-dark-muted); font-size:16px;">Most lodges don't have a Loki. Here's who's actually welcoming you at breakfast.</p>
    <div class="profile-grid" style="grid-template-columns: 1fr; max-width: 420px; margin-left: auto; margin-right: auto;">
      <div class="profile-card">
        <div class="placeholder-photo"><span>Placeholder — real photo of Loki needed</span></div>
        <div>
          <span class="profile-role">Founder</span>
          <h3>Loki</h3>
          <p><em>[Placeholder bio]</em> Built Rafiki from a piece of land on the Savegre River, and still greets guests at breakfast to this day. Doesn't want everyone here — just the people who'll appreciate it.</p>
        </div>
      </div>
    </div>
    <p style="text-align:center; margin-top:36px;">
      <a href="<?php echo esc_url( home_url( '/meet-rafiki/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Meet the Whole Team →</a>
    </p>
  </div>
</section>

<!-- ===== WHY RAFIKI ===== -->
<section class="section why-rafiki" id="about">
  <div class="why-grid">
    <div class="why-media">
      <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-24' ) ); ?>" alt="Savegre River surrounded by tropical rainforest">
      <div class="why-media-text">
        <h2>WHY RAFIKI?<br><span class="accent">WE DON'T PUT NATURE IN A BOX.</span></h2>
        <p>The animals live their life. We live in their world. This is Costa Rica, raw and real.</p>
        <a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>" class="btn btn-primary">Learn Our Story →</a>
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
        <strong>COUNTLESS MEMORIES</strong>
        <span>Made by thousands of happy guests.</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== GUEST STORIES ===== -->
<section class="section testimonials">
  <div class="container">
    <h2 class="section-title center">GUEST STORIES</h2>

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
  </div>
</section>

<!-- ===== PACKAGES ===== -->
<?php if ( $packages ) : ?>
<section class="section" style="background:var(--cream-2);" id="packages">
  <div class="container">
    <h2 class="section-title center">PLAN YOUR STAY</h2>
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
      <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">See All Packages →</a>
    </p>
  </div>
</section>
<?php endif; ?>

<!-- ===== CTA BANNER ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Group rafting in front of the lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>READY TO PLAN YOUR UNFORGETTABLE TRIP?</h2>
    <p>We'll help you build the experience that fits your people and your purpose.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to make a reservation at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
  </div>
</section>

<?php get_footer(); ?>
