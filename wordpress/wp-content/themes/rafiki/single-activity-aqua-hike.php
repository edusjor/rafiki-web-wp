<?php
/**
 * Custom template for the Aqua Hike (formerly "Hiking") — matched
 * automatically by WordPress via the post slug (aqua-hike). This is the
 * Conservation Through Adventure story: Rafiki didn't build Los Campesinos,
 * it brings guests into something the community already created.
 */
get_header();

while ( have_posts() ) : the_post();
	$post_id   = get_the_ID();
	$hero_img  = rafiki_lead_image_url( $post_id, 'full' );
	$gallery   = rafiki_rows( $post_id, 'rafiki_gallery' );
	$t_source  = get_post_meta( $post_id, 'rafiki_testimonial_source', true );
	$t_text    = get_post_meta( $post_id, 'rafiki_testimonial_text', true );
	$t_author  = get_post_meta( $post_id, 'rafiki_testimonial_author', true );
	$cta_img_id = get_post_meta( $post_id, 'rafiki_cta_image', true );
	$cta_img   = $cta_img_id ? wp_get_attachment_image_url( $cta_img_id, 'full' ) : $hero_img;
?>

<!-- ===== 01. HERO ===== -->
<section class="page-hero">
  <div class="hero-media">
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="Aqua Hike waterfall and rainforest trail at Rafiki"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">Experiences</a><span class="sep">/</span>
        <span class="current">Aqua Hike</span>
      </p>
      <span class="eyebrow">Forest. Water. Community.</span>
      <h1>THIS ONE WAS MEANT<br><span class="accent">TO GET YOU WET.</span></h1>
      <p class="hero-sub">Cross the Savegre. Climb into the rainforest. Follow the water upstream. Swim below a waterfall. Eat lunch with the people who call this mountain home. Then walk across one of those bridges that looks considerably higher once you're standing in the middle of it.</p>
      <p class="hero-sub">The Aqua Hike takes you beyond Rafiki and into Quebrada Arroyo and Los Campesinos — a part of the valley most travelers driving Costa Rica's Pacific coast would never know was here.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to add the Aqua Hike to my stay at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Add the Aqua Hike to Your Stay</a>
        <a href="<?php echo esc_url( home_url( '/#plan' ) ); ?>" class="btn btn-outline">Plan Your Rafiki Stay</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. WHY WE CALL IT THE AQUA HIKE ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Not Your Usual Forest Walk</span>
    <h2 class="section-title center">THE WATER IS PART OF THE TRAIL.</h2>
    <p>The day starts around the Savegre River before heading into the forest toward Quebrada Arroyo. From there, you follow a landscape shaped by springs, streams and waterfalls. You'll climb. You'll descend. You'll cross water.</p>
    <p style="font-size:18px;">And when you reach the waterfall, you're not expected to stand beside it and take a picture. Get in. That's half the reason you walked here.</p>
  </div>
</section>

<!-- ===== 03. THE DAY STARTS AT RAFIKI ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">From Your Lodge Into the Forest</span>
    <h2 class="section-title center">BREAKFAST HERE. THEN GO SOMEWHERE MOST PEOPLE MISS.</h2>
    <p>One of the advantages of staying at Rafiki is that a day like this doesn't begin with figuring out where the tour company is or how you're getting there. You're already here.</p>
    <p>Meet your guide. Leave Rafiki behind for a few hours. Cross the Savegre and start walking. The road trip can wait. Today you're going in on foot.</p>
  </div>
</section>

<!-- ===== 04. FOLLOW THE WATER ===== -->
<section class="section why-rafiki">
  <div class="why-media">
    <img src="<?php echo esc_url( $hero_img ? $hero_img : 'https://rafikisafari.com/wp/wp-content/uploads/2017/01/streamcalm.jpg' ); ?>" alt="Trail above Quebrada Arroyo on the Aqua Hike">
    <div class="why-media-text" style="max-width:none;">
      <span class="eyebrow">Quebrada Arroyo</span>
      <h2>THE FOREST CHANGES WHEN<br><span class="accent">YOU WALK THROUGH IT SLOWLY.</span></h2>
      <p style="max-width:620px;">The trail climbs above the Savegre Valley before dropping toward Quebrada Arroyo. Up here, the Pacific can appear in the distance. Down in the forest, everything gets smaller again. Water. Plants. Frogs. Lizards. Birds. Things you would never notice from a moving car.</p>
      <p style="max-width:620px;">Your guide isn't there simply to make sure you stay on the trail. They're there to help you understand what you're walking through.</p>
    </div>
  </div>
</section>

<!-- ===== 05. THEN YOU HEAR THE WATERFALL ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">The Reward</span>
    <h2 class="section-title center">YOU WALKED HERE. YOU MIGHT AS WELL GET IN.</h2>
    <p>Eventually the forest opens onto a waterfall and the pool below it. This isn't the place for staying dry. Shoes off. Into the water. Cool down. Look up. And take a minute before somebody decides it's time to keep moving.</p>
    <p style="font-size:18px;">There aren't many road-trip stops in Costa Rica where the itinerary reads: hike through rainforest → swim below a waterfall → go find lunch. This is one of them.</p>
  </div>
</section>

<?php if ( $gallery ) : ?>
<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="gallery-grid">
      <?php foreach ( $gallery as $i => $g ) : if ( empty( $g['image'] ) ) continue;
        $url = wp_get_attachment_image_url( $g['image'], 'large' );
        if ( ! $url ) continue;
        $span = ( 0 === $i ) ? ' span-2 span-2-row' : ( ( 4 === $i ) ? ' span-2' : '' );
      ?>
        <img class="<?php echo esc_attr( ltrim( $span ) ); ?>" src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $g['alt'] ); ?>">
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== 06. LOS CAMPESINOS ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">More Than a Beautiful Place</span>
    <h2 class="section-title center">THE PEOPLE HERE CHOSE A DIFFERENT FUTURE FOR THIS FOREST.</h2>
    <p>Quebrada Arroyo was traditionally an agricultural community. The families living here recognized that the waterfalls, forest and landscape surrounding them had another kind of value. So they began building something of their own: Los Campesinos — a community-run rural tourism project that gives visitors a reason to come into the forest while giving local families a reason to keep protecting it.</p>
    <div style="max-width:640px; margin:32px auto 0; padding:28px 32px; background:var(--dark); border-radius:var(--radius); border-left:4px solid var(--orange);">
      <p style="color:#fff; font-family:var(--font-head); font-size:22px; text-transform:uppercase; letter-spacing:0.4px; margin:0;">Rafiki didn't create this place.</p>
      <p style="color:var(--text-muted); margin:10px 0 0; font-size:15px;">We get to bring our guests into something the community created for itself. And that's an important difference.</p>
    </div>
  </div>
</section>

<!-- ===== 07. LUNCH TASTES BETTER AFTER THE WALK ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Around the Table</span>
    <h2 class="section-title center">THIS IS NOT THE SNACK AT THE TURNAROUND POINT.</h2>
    <p>After the morning in the forest, the trail leads to Los Campesinos for a home-cooked lunch. Sit down. Eat. Talk. Cool off.</p>
    <p>This part of the experience matters just as much as the waterfall. Because Costa Rica isn't only the forest you're walking through. It's also the people who live beside it.</p>
  </div>
</section>

<!-- ===== 08. YES, THERE'S A BRIDGE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">340 Feet Across</span>
    <h2 class="section-title center">DON'T LOOK DOWN.</h2>
    <p>Or do. The suspension bridge at Los Campesinos stretches roughly 340 feet across the landscape. From the middle, the forest falls away beneath you and suddenly the scale of where you've been walking all morning makes considerably more sense.</p>
    <p>Take the photo. Then keep walking.</p>
  </div>
</section>

<!-- ===== 09. WHY RAFIKI LOVES THIS EXPERIENCE ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Conservation Through Adventure</span>
    <h2 class="section-title center">THIS IS WHAT WE MEAN WHEN WE SAY TOURISM CAN DO SOMETHING USEFUL.</h2>
    <p>You came because the waterfall looked amazing. That's perfectly fine. But your day also supports people who found a way to build livelihoods around keeping this landscape worth visiting.</p>
    <p style="font-size:18px;">That's the kind of tourism Rafiki believes in. The adventure brings you here. The people give the place meaning. And your visit helps make protecting it worthwhile.</p>
  </div>
</section>

<?php if ( $t_text ) : ?>
<section class="section testimonials">
  <div class="container">
    <div class="testimonial-grid" style="grid-template-columns: 1fr; max-width:640px; margin:0 auto;">
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( $t_source ); ?><span><?php echo esc_html( rafiki_testimonial_source_label( $t_source ) ); ?></span></div>
        <div class="stars">★★★★★</div>
        <p>"<?php echo esc_html( $t_text ); ?>"</p>
        <?php if ( $t_author ) : ?><span class="testimonial-author"><?php echo esc_html( $t_author ); ?></span><?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== 10. WHAT THE DAY LOOKS LIKE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">WHAT THE DAY LOOKS LIKE</h2>
    <div class="itinerary" style="max-width:820px; margin:48px auto 40px;">
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Start</h3><p>Rafiki Safari Lodge.</p></div></div>
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Cross</h3><p>The Savegre River.</p></div></div>
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Hike</h3><p>Into the rainforest above Quebrada Arroyo.</p></div></div>
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Swim</h3><p>At the waterfall.</p></div></div>
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Lunch</h3><p>With Los Campesinos.</p></div></div>
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Cross</h3><p>The 340-foot suspension bridge.</p></div></div>
      <div class="itinerary-step"><div class="itinerary-step-num"></div><div class="itinerary-step-body"><h3>Return</h3><p>Back toward Rafiki by 4x4.</p></div></div>
    </div>
    <p style="text-align:center; color:var(--text-dark-muted);">Approximate experience: around 6 hours.</p>
  </div>
</section>

<!-- ===== 11. IS THE AQUA HIKE FOR YOU? ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Good to Know</span>
    <h2 class="section-title center">YOU DON'T NEED TO BE A HARDCORE HIKER.</h2>
    <p>But you should want to walk. The Aqua Hike includes uneven forest trails, climbs, descents, water and several hours outside. It's a much better fit for someone who sees getting muddy or wet as part of the day rather than something that went wrong.</p>
    <p>If your idea of Costa Rica includes actually getting into the landscape instead of only looking at it, you're in the right place.</p>
  </div>
</section>

<!-- ===== 12. WHAT TO BRING ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <h2 class="section-title center">WHAT TO BRING</h2>
    <p>Shoes you're comfortable walking in — and don't mind getting dirty. Swimwear. Lightweight clothing. Sun protection. Water. Insect repellent. A camera or phone. And something dry waiting for you back at Rafiki.</p>
  </div>
</section>

<!-- ===== 13. COME BACK TO RAFIKI ===== -->
<section class="cta-banner">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_16.jpeg" alt="Back at Rafiki after the Aqua Hike">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">One Day of a Much Bigger Stay</span>
    <h2>TIRED LEGS. GOOD AFTERNOON.</h2>
    <p>Come back from the Aqua Hike. Shower. Pool. Drink. Porch. Massage if you've planned this particularly well.</p>
    <p>And tomorrow? Don't hike again just because you're staying in the forest. Go rafting. Ride horses. Look for birds. Or don't go anywhere. That's why you're staying a few nights.</p>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="btn btn-primary">Explore All Rafiki Experiences →</a>
  </div>
</section>

<!-- ===== 14. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Walk Into This Side of Costa Rica</span>
    <h2>STAY AT RAFIKI. WE'LL SHOW YOU THE WAY IN.</h2>
    <p>Add the Aqua Hike to your stay and spend a day moving through the forest, water and community beyond the lodge.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to add the Aqua Hike to my stay at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Add Aqua Hike to My Stay</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Check Availability</a>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
