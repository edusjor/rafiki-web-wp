<?php
/**
 * Rafiki's signature experience gets its own template instead of the
 * generic single-activity.php — matched automatically by WordPress via
 * the post slug (white-water-rafting). Sells staying at Rafiki to raft,
 * not rafting as a generic day tour; technical/safety facts come after
 * the guest has already pictured themselves on the river.
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
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="Whitewater rafting on the Savegre River at Rafiki"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">Experiences</a><span class="sep">/</span>
        <span class="current">Whitewater Rafting</span>
      </p>
      <span class="eyebrow">Rafiki's Signature Experience</span>
      <h1>THIS IS THE RIVER<br><span class="accent">WE BUILT RAFIKI AROUND.</span></h1>
      <p class="hero-sub">You don't need to wake up early, get in a van and spend the morning driving across Costa Rica to find the adventure. When you stay at Rafiki, the Savegre River is already part of your day.</p>
      <p class="hero-sub">Head out with our guides for Class II–III rapids, tropical forest, clear water and a waterfall stop along the way — then come back to the lodge for lunch, the pool and whatever you decide to do next.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to add whitewater rafting to my stay at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Add Rafting to Your Stay</a>
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Check Availability</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. NOT JUST ANOTHER TOUR ON YOUR ITINERARY ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Raft From Rafiki</span>
    <h2 class="section-title center">WAKE UP HERE. RAFT HERE. COME BACK HERE.</h2>
    <p style="font-size:18px;">That's one of the things guests often don't understand until they arrive. Rafting isn't something you need to build another travel day around. You're already staying beside the landscape you came to experience.</p>
    <p>Have breakfast at Rafiki. Meet your guides. Get ready for the river. Spend the next few hours somewhere completely different. Then come back to the same lodge where you woke up that morning.</p>
    <p>No changing hotels. No chasing another tour company. No losing half the day getting there and back.</p>
  </div>
</section>

<!-- ===== 03. WHAT THE DAY ACTUALLY FEELS LIKE ===== -->
<section class="section why-rafiki">
  <div class="why-media">
    <img src="<?php echo esc_url( $hero_img ? $hero_img : 'https://rafikisafari.com/wp/wp-content/uploads/2026/02/rafting2-scaled.jpg' ); ?>" alt="Rafting the Class II-III rapids of the Savegre River">
    <div class="why-media-text" style="max-width:none;">
      <span class="eyebrow">On the Savegre</span>
      <h2>FIRST RAPID: EVERYONE IS<br><span class="accent">STILL TRYING TO STAY DRY.</span></h2>
      <p style="max-width:620px;">That usually doesn't last long. The Savegre gives you the excitement of Class II–III whitewater without making every minute feel intense. There are rapids. Then the river opens up. Forest surrounds you. There are calmer stretches where you can actually look around.</p>
      <p style="max-width:620px;">A waterfall stop gives everyone a chance to get out of the raft and into the water for a different reason. Then another rapid reminds you why you came.</p>
    </div>
  </div>
</section>

<?php if ( $gallery ) : ?>
<section class="section" style="padding-bottom:0;">
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

<!-- ===== 04. A RIVER FAMILIES CAN EXPERIENCE TOGETHER ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Bring the Family</span>
    <h2 class="section-title center">THE BEST PART IS OFTEN WHO'S IN THE RAFT WITH YOU.</h2>
    <p>Parents. Kids. Brothers and sisters. Friends who swore they weren't nervous five minutes ago. Rafting works differently when you're experiencing the same thing together.</p>
    <p>You paddle together. Get wet together. Laugh at whoever wasn't paying attention when the wave hit. And come back with one story everyone was actually there for.</p>
    <p style="font-size:14px; color:var(--text-dark-muted);">The Savegre section used by Rafiki has Class II–III rapids and is suitable for many families and first-time rafters. Participation depends on age, comfort in the water and current river conditions.</p>
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

<section class="section">
  <div class="container" style="max-width:760px;">
    <h3 style="text-align:center; font-size:22px; text-transform:uppercase; margin-bottom:32px;">Before You Say Yes</h3>
    <div class="faq-list">
      <details class="faq-item">
        <summary>Do we have to leave Rafiki to go rafting?</summary>
        <p>No. The river experience is integrated into your Rafiki stay. You wake up at the lodge, meet the team and the day is coordinated from there — no morning spent searching for another tour operator.</p>
      </details>
      <details class="faq-item">
        <summary>What does Class II–III actually mean?</summary>
        <p>Enough moving water and rapids to make the day exciting, with calmer sections in between. You don't need to understand rafting classifications before arriving — your guide will explain the river you're actually going to experience that day.</p>
      </details>
      <details class="faq-item">
        <summary>What if someone in our family is nervous?</summary>
        <p>Tell the guide. That's exactly the kind of information they need before you get on the water. The goal isn't to prove how brave anyone is — it's for the group to have a great river day together.</p>
      </details>
      <details class="faq-item">
        <summary>Will we fall out of the raft?</summary>
        <p>Hopefully not. But you should arrive expecting to get wet. Your guide will explain what to do before the trip and what happens if anyone ends up in the water.</p>
      </details>
      <details class="faq-item">
        <summary>Can grandparents go rafting?</summary>
        <p>That depends less on the word "grandparent" and more on the individual — physical ability, comfort in the water, river conditions and the guide's assessment all matter. Tell us who's coming and we'll give you a straight answer.</p>
      </details>
    </div>
  </div>
</section>

<!-- ===== 05. SAFETY COMES BEFORE THE STORY ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Know the River</span>
    <h2 class="section-title center">A GOOD GUIDE KNOWS WHEN THE RIVER IS DIFFERENT TODAY.</h2>
    <p>The Savegre is not a ride with an on/off switch. It's a real river. Rain changes it. Water levels change it. Seasons change it.</p>
    <p>That's why the person guiding your raft matters. Before getting on the water, the team reviews the day's conditions, explains what to expect and makes the call based on the river in front of them — not simply what the itinerary said when you booked.</p>
    <p style="font-size:14px; color:var(--text-dark-muted);">Children from around age six may be able to participate, but this depends on water levels and individual conditions. Guests should be comfortable in water and in appropriate physical condition.</p>
    <div style="max-width:560px; margin:32px auto 0; padding:28px 32px; background:var(--dark); border-radius:var(--radius); border-left:4px solid var(--orange);">
      <p style="color:#fff; font-family:var(--font-head); font-size:22px; text-transform:uppercase; letter-spacing:0.4px; margin:0;">You don't need to understand river classifications before arriving.</p>
      <p style="color:var(--text-muted); margin:10px 0 0; font-size:15px;">That's our job.</p>
    </div>
  </div>
</section>

<!-- ===== 06. WHY THE SAVEGRE? ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">The River Behind Rafiki</span>
    <h2 class="section-title center">YOU'RE NOT JUST PASSING THROUGH IT.</h2>
    <p>The Savegre shapes this valley. It influences the forest, the communities, the wildlife, the roads — and a lot of life around Rafiki.</p>
    <p>On the water, you see the landscape differently than you ever could from a car. Round river rocks beneath clear tropical water. Dense forest rising beside you. Places along the river that aren't visible from the road. And stretches where the only thing to do is paddle and look around.</p>
  </div>
</section>

<!-- ===== 07. YOUR GUIDE IS PART OF THE EXPERIENCE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Local Knowledge</span>
    <h2 class="section-title center">THEY'VE SEEN THIS RIVER ON MORE THAN ONE KIND OF DAY.</h2>
    <p>A guide isn't just there to tell you when to paddle. They know which lines work at different water levels, which places deserve a second look, when the group needs more instruction, when everyone can relax — and when the river has changed enough that today's plan needs to change with it.</p>
    <p>You aren't simply being taken down a river. You're being shown a place by people who know how it behaves.</p>
  </div>
</section>

<!-- ===== 08. WHAT HAPPENS AFTER THE RAFTING MATTERS TOO ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Back at Rafiki</span>
    <h2 class="section-title center">ADVENTURE BEFORE LUNCH. POOL AFTER.</h2>
    <p style="font-size:18px;">This is where staying at Rafiki changes the experience. The rafting doesn't have to consume your entire day.</p>
    <p>Come back. Eat. Jump in the pool. Let the kids find the water slide again. Book a massage. Sit on your porch. Or start discussing what everyone wants to do tomorrow. Horseback? Aqua Hike? Birding? Nothing? You still have another day here.</p>
  </div>
</section>

<!-- ===== 09. QUICK DETAILS ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">QUICK DETAILS</h2>
    <div class="badge-row" style="justify-content:center; margin-bottom:40px;">
      <span class="badge" style="border-color:rgba(32,28,22,0.2); color:var(--text-dark); background:transparent;">Whitewater level: Class II–III</span>
      <span class="badge" style="border-color:rgba(32,28,22,0.2); color:var(--text-dark); background:transparent;">Water temperature: ~25–27°C / 77–81°F</span>
      <span class="badge" style="border-color:rgba(32,28,22,0.2); color:var(--text-dark); background:transparent;">Children from ~6 years, conditions permitting</span>
    </div>
    <p style="text-align:center; max-width:640px; margin:0 auto 40px; color:var(--text-dark-muted);">Good for families, friends, first-time rafters and travelers looking for an adventurous day without extreme whitewater. River conditions change — final participation and route decisions are always made according to conditions on the day.</p>
    <div class="amenities-grid">
      <div class="amenity-item"><?php echo rafiki_icon( 'wave' ); ?><div><strong>Rapids</strong><span>Class II–III, exciting without being extreme.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'leaf' ); ?><div><strong>Tropical forest</strong><span>Dense rainforest the whole way down.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'mountain' ); ?><div><strong>Waterfall stop</strong><span>Get out of the raft and into the water.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'wave' ); ?><div><strong>Swimming</strong><span>Calm pools between rapids.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'guide' ); ?><div><strong>Local guides</strong><span>They know this river on more than one kind of day.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'tent' ); ?><div><strong>Return to Rafiki</strong><span>Back to the lodge, not another hotel.</span></div></div>
    </div>
  </div>
</section>

<!-- ===== 10. WHAT TO BRING ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center;">
    <h2 class="section-title center">WHAT TO BRING</h2>
    <p>Clothes that can get wet. Secure water shoes. Sun protection. A change of clothes. And ideally nothing you're going to be upset about getting soaked.</p>
    <p>We'll take care of the river part.</p>
  </div>
</section>

<!-- ===== 11. BUILD IT INTO YOUR RAFIKI STAY ===== -->
<section class="cta-banner">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_34.jpeg" alt="Group rafting in front of the lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Two or Three Nights Work Beautifully</span>
    <h2>DON'T DRIVE ALL THE WAY HERE JUST TO RAFT AND LEAVE.</h2>
    <p>Stay. Raft one day. Do something completely different the next. Ride through the valley. Take the Aqua Hike. Wake up for birds. Or decide one big adventure was enough and spend the next morning doing very little.</p>
    <p>Rafting may be Rafiki's signature experience. It doesn't have to be the only reason you came.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( home_url( '/#plan' ) ); ?>" class="btn btn-primary">Plan Your Rafiki Stay</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="btn btn-outline">Explore All Experiences</a>
    </div>
  </div>
</section>

<!-- ===== 12. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Ready for the River?</span>
    <h2>COME STAY BESIDE IT.</h2>
    <p>Add a day on the Savegre to two or three nights at Rafiki and experience the river as part of the place you're staying — not another stop on the schedule.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I have a question about whitewater rafting at Rafiki.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Ask Us About Rafting</a>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
