<?php
/**
 * Template Name: Stay - Copia
 *
 * Shortened comparison copy of archive-accommodation.php (the /stay/
 * archive), wired up as a normal Page so it shows in wp-admin as
 * "Stay - Copia" without touching the live /stay/ archive. Same wording,
 * kept sections only: hero, tent description, who it's for, nights,
 * amenities, food, group CTA, and the actual tent listing grid. Dropped
 * both FAQ blocks (10 questions total — heavily repetitive with each other
 * and with content elsewhere), the day-rhythm itinerary (duplicates Home),
 * the reflective "what Rafiki doesn't try to be" section, the second
 * (forest+beach) CTA banner, and the getting-here / "not sure which stay"
 * blocks (both covered by Plan Your Trip). Does not touch the original file.
 */
get_header();

$all_accommodations = get_posts( array( 'post_type' => 'accommodation', 'posts_per_page' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
$tent_post   = $all_accommodations ? $all_accommodations[0] : null;
$tent_amenities = $tent_post ? rafiki_rows( $tent_post->ID, 'rafiki_amenities' ) : array();
?>

<!-- ===== 01. HERO ===== -->
<section class="page-hero">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_19.jpeg" alt="Safari tent at Rafiki Safari Lodge, surrounded by rainforest">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Stay</span></p>
      <span class="eyebrow">Stay at Rafiki</span>
      <h1>SLEEP IN THE FOREST.<br><span class="accent">WAKE UP WITH SOMEWHERE TO GO.</span></h1>
      <p class="hero-sub">Fourteen safari tents sit among the trees at Rafiki. You get a proper bed, private bathroom and your own porch — with the river valley, birds and forest just outside.</p>
      <p class="hero-sub">Stay two or three nights and use Rafiki as your base for the days you want to spend exploring this side of Costa Rica.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
        <a href="#tents" class="btn btn-outline">See the Safari Tents</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. THIS ISN'T CAMPING ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Safari Tent Living</span>
    <h2 class="section-title center" style="margin-bottom:20px;">CLOSE TO NATURE DOESN'T HAVE TO MEAN SLEEPING ON THE GROUND.</h2>
    <p>The idea came from the safari camps Constant Boshoff knew in Africa. A canvas tent lets you hear more of what's happening outside. But inside, it still needs to feel good.</p>
    <p>Proper beds. Private bathrooms. Hot showers. Space for your things. A porch to sit on when you've had enough adventure for the day.</p>
    <p>It's simple in the right places and comfortable in the places that matter.</p>
  </div>

  <div class="container">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_1.jpeg" alt="Inside a safari tent at Rafiki Safari Lodge" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius); margin-top:16px;">
  </div>
</section>

<!-- ===== 03. 14 SAFARI TENTS ===== -->
<section class="section for-you">
  <div class="container">
    <span class="eyebrow center">Your Place in the Forest</span>
    <h2 class="section-title center">ENOUGH ROOM TO COME AS TWO.<br>ENOUGH TENTS TO BRING EVERYONE.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">Rafiki has 14 safari tents spread through the property. That means the lodge works just as naturally for a couple or family as it does when several families, friends or generations want to travel together. You're staying in the same place. But you still have your own space to disappear to at the end of the day.</p>

    <div class="for-you-grid cols-3">
      <div class="for-you-item">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>FOR COUPLES</h3>
        <p>A few days somewhere completely different from the beach hotels and towns already on your route. Adventure when you want it. Quiet when you don't.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>FOR FAMILIES</h3>
        <p>Enough space for real family travel. Days that can include rafting, horses, hiking, swimming and the kind of downtime kids usually decide for themselves.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>FOR GROUPS</h3>
        <p>Fourteen tents make it possible to bring more of your people without turning the trip into a logistical puzzle. Different tents. Different plans during the day. One place to meet again later.</p>
      </div>
    </div>

    <p style="text-align:center; margin-top:16px;">
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Planning a Group Stay?</a>
    </p>
  </div>
</section>

<!-- ===== 04. STAY LONG ENOUGH TO STOP RUSHING ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <span class="eyebrow center">How Many Nights?</span>
    <h2 class="section-title center">TWO NIGHTS WORK.<br>THREE NIGHTS FEEL BETTER.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">One night tells you what Rafiki looks like. Two nights give you time for a real adventure. Three nights let you have another one without feeling like you're already leaving.<br><br>For most road trips through Costa Rica, we recommend making room for two or three nights — long enough to unpack and do something memorable, short enough to fit naturally between the other places already on your itinerary.</p>

    <div class="plan-grid cols-2" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Two Nights</h3>
        <p>Arrive from the coast. Settle in. Spend one full day on the river, horseback or exploring the forest. Wake up one more morning before continuing your trip.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>Three Nights</h3>
        <p>Arrive without rushing. Choose two different kinds of days. Leave room for the pool, birds, food, conversation and doing nothing for an afternoon. This is where staying at Rafiki starts to feel different from simply coming for an activity.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 05. WHAT YOU'LL FIND IN YOUR SAFARI TENT ===== -->
<section class="section">
  <div class="container">
    <span class="eyebrow center">The Practical Part</span>
    <h2 class="section-title center">WILD OUTSIDE. COMFORTABLE INSIDE.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px;">Each Rafiki safari tent is designed to give you the feeling of sleeping in the forest without giving up the essentials you actually care about at the end of a full day.</p>

    <?php if ( $tent_amenities ) : ?>
      <div class="amenities-grid">
        <?php foreach ( $tent_amenities as $a ) : if ( empty( $a['title'] ) ) continue; ?>
          <div class="amenity-item">
            <?php echo rafiki_icon( $a['icon'] ); ?>
            <div><strong><?php echo esc_html( $a['title'] ); ?></strong><span><?php echo esc_html( $a['text'] ); ?></span></div>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center; margin-top:36px;">
        <a href="<?php echo esc_url( $tent_post ? get_permalink( $tent_post ) : get_post_type_archive_link( 'accommodation' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">See Full Tent Details →</a>
      </p>
    <?php endif; ?>
  </div>
</section>

<!-- ===== 06. FOOD IS PART OF THE RHYTHM ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Around the Table</span>
    <h2 class="section-title center">ADVENTURE MAKES PEOPLE HUNGRY.</h2>
    <p>Meals at Rafiki are simple, generous and made for the kind of days people have here. Breakfast before heading out. Lunch when you return. Dinner when everyone finally slows down again.</p>
    <p>The food doesn't need to compete with the landscape. It needs to make you want to sit down, eat well and stay at the table a little longer.</p>
    <p><a href="<?php echo esc_url( home_url( '/lekker-bar-braai/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Food at Rafiki →</a></p>
  </div>
</section>

<!-- ===== 07. BRING YOUR PEOPLE ===== -->
<section class="cta-banner">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_14a-700x420.jpg" alt="Group gathered together at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Group Stays</span>
    <h2>YOU CAN TRAVEL TOGETHER WITHOUT SPENDING EVERY MINUTE TOGETHER.</h2>
    <p>With 14 safari tents, Rafiki gives families and groups something that's surprisingly difficult to find on a trip: room for everyone to come. Some people can raft. Some can ride. Some can stay behind with the pool. Someone can go looking for birds. Someone else can do absolutely nothing.</p>
    <p>Then everyone comes back to the same lodge at the end of the day. Same table. Different stories.</p>
    <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="btn btn-primary">Plan a Group Stay →</a>
  </div>
</section>

<!-- ===== CHOOSE YOUR STAY (dynamic tent/accommodation grid) ===== -->
<section class="section experience" id="tents">
  <div class="container">
    <span class="eyebrow center">Choose Your Stay</span>
    <h2 class="section-title center">WHICH SETUP FITS YOUR TRIP?</h2>
    <div class="experience-grid archive-grid">
      <?php if ( $all_accommodations ) : foreach ( $all_accommodations as $acc ) :
        $img = rafiki_lead_image_url( $acc->ID, 'rafiki-card' );
        $sub = get_post_meta( $acc->ID, 'rafiki_subtitle', true );
      ?>
        <a href="<?php echo esc_url( get_permalink( $acc ) ); ?>" class="experience-card">
          <?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title( $acc ) ); ?>"><?php endif; ?>
          <div class="experience-card-overlay"></div>
          <div class="experience-card-content">
            <h3><?php echo esc_html( get_the_title( $acc ) ); ?></h3>
            <?php if ( $sub ) : ?><p><?php echo esc_html( wp_trim_words( $sub, 14 ) ); ?></p><?php endif; ?>
            <span class="arrow-link">→</span>
          </div>
        </a>
      <?php endforeach; else : ?>
        <p>No accommodations published yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ===== FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_34.jpeg" alt="Group rafting in front of the lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Your Base for a Few Wild Days</span>
    <h2>UNPACK ONCE. SEE WHAT HAPPENS.</h2>
    <p>Stay for two or three nights and make Rafiki the part of your Costa Rica trip where the river, forest and adventure all start from the same place.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="btn btn-outline">Explore Packages</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
