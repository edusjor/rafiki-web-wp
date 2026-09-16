<?php
/**
 * Custom template for Horseback Riding — matched automatically by
 * WordPress via the post slug (horseback-riding). The Duarte family is the
 * proof behind Rafiki's "community" claims, so their story leads instead of
 * hiding under a "local outfitters" note. Price stays out of the narrative;
 * it still surfaces via the WhatsApp/availability CTAs.
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
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="Horseback riding through the Savegre Valley at Rafiki"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">Experiences</a><span class="sep">/</span>
        <span class="current">Horseback Riding</span>
      </p>
      <span class="eyebrow">Horseback Riding at Rafiki</span>
      <h1>SEE THE VALLEY THE WAY<br><span class="accent">PEOPLE HERE HAVE FOR GENERATIONS.</span></h1>
      <p class="hero-sub">Before horseback riding became something guests came to experience, horses were simply part of getting around this valley. They still are.</p>
      <p class="hero-sub">From Rafiki, ride through the tropical forest, along the Savegre River and into parts of the valley you would never experience from the main road. No rush. No engine. Just the trail, the horse beneath you and a very different view of where you've come to stay.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_booking_link( get_the_ID() ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to add horseback riding to my stay at Rafiki.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Ask About Horseback Riding</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. THIS ISN'T SOMETHING WE BROUGHT IN FOR TOURISTS ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Part of Life Here</span>
    <h2 class="section-title center">THE HORSES WERE HERE FIRST.</h2>
    <p>Horses have been part of daily life in the Savegre Valley for generations. People used them to move through a landscape where roads didn't always go where they needed to go. And even today, riding is still part of life for people living around the valley.</p>
    <p style="font-size:18px;">That's what makes horseback riding at Rafiki different. You're not riding around a purpose-built resort trail. You're following a way of moving through the landscape that belongs here.</p>
  </div>
</section>

<!-- ===== 03. MEET THE DUARTE FAMILY ===== -->
<section class="section why-rafiki">
  <div class="why-media">
    <img src="<?php echo esc_url( $hero_img ? $hero_img : 'https://rafikisafari.com/wp/wp-content/uploads/2016/12/horseback-ceibadoctored-1.jpg' ); ?>" alt="The Duarte family's horses at Rafiki">
    <div class="why-media-text" style="max-width:none;">
      <span class="eyebrow">Local Family. Local Horses.</span>
      <h2>THIS STORY STARTED<br><span class="accent">LONG BEFORE YOUR RIDE.</span></h2>
      <p style="max-width:640px;">Rafiki has worked with the Duarte family since the lodge's early days in 2002. Memo Duarte grew up in the Savegre Valley. When logging was restricted in the 1990s and work became harder to find locally, he had to leave the valley. Later, an opportunity appeared to come home.</p>
      <p style="max-width:640px;">With microloans, bartering and a lot of work, Memo began building a business around something he already understood well: horses. Over time, that relationship grew alongside Rafiki — better horses, better trails, a business that allowed a local family to keep building a life in the same valley.</p>
      <p style="max-width:640px; font-weight:700; color:#fff;">So when you ride here, you're not simply using Rafiki's horses. You're riding with a family whose own story is tied to this landscape.</p>
    </div>
  </div>
</section>

<!-- ===== 04. WHY THAT MATTERS ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Tourism That Stays Local</span>
    <h2 class="section-title center">YOUR RIDE DOES MORE THAN FILL AN AFTERNOON.</h2>
    <p>This is the kind of relationship Rafiki wants tourism to create. A traveler comes because riding through the valley sounds incredible. A local family provides the horses, knowledge and years of experience that make it possible.</p>
    <div style="max-width:600px; margin:32px auto 0; padding:28px 32px; background:var(--dark); border-radius:var(--radius); border-left:4px solid var(--orange);">
      <p style="color:#fff; font-family:var(--font-head); font-size:20px; text-transform:uppercase; letter-spacing:0.4px; margin:0;">The guest gets much more than a generic horseback tour.</p>
      <p style="color:var(--text-muted); margin:10px 0 0; font-size:15px;">And the value of tourism stays connected to the people living here. That's a better exchange for everyone.</p>
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

<!-- ===== 05. WHAT THE RIDE FEELS LIKE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">On the Trail</span>
    <h2 class="section-title center">EVERYTHING LOOKS DIFFERENT FROM A HORSE.</h2>
    <p>The pace changes first. You start noticing the river. The shape of the valley. Where the forest gets thicker. Where the trail climbs. The sounds you wouldn't hear from inside a vehicle.</p>
    <p>Depending on the route and conditions, rides can follow the Savegre River, move through Rafiki's forested areas, climb into steeper terrain and reach waterfalls or swimming areas.</p>
    <p style="font-size:18px;">There's enough happening to make it an adventure. But enough time to actually see where you are.</p>
  </div>
</section>

<!-- ===== 06. FIRST TIME ON A HORSE? ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <h2 class="section-title center">YOU DON'T NEED TO BE A RIDER.<br>TELL US WHAT YOU'RE COMFORTABLE WITH.</h2>
    <p>Some guests have spent years around horses. Others are meeting one properly for the first time. Both can belong here. Rafiki works with different routes depending on riding experience and comfort level, and each trip goes out with local horse handlers and guiding support.</p>
    <p>You don't need to prove anything. The goal isn't to ride the hardest trail. It's to have a good day in the valley.</p>
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

<!-- ===== 07. THE VALLEY LOOP ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">One Way to Experience It</span>
    <h2 class="section-title center">RIVER BELOW. FOREST ABOVE.</h2>
    <p>One of the rides follows the flatter section of the Savegre Valley before moving higher into the surrounding terrain. You'll see the river from a completely different perspective, ride through changing forest and reach areas of Rafiki's property that would be much harder to explore any other way.</p>
    <p>Depending on the route, the ride can also include waterfall country and steeper sections of the property. This isn't about covering the most kilometers. It's about getting access to places the road doesn't reach.</p>
  </div>
</section>

<!-- ===== 08. MAKE IT A BIGGER DAY ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Horses + River</span>
    <h2 class="section-title center">RIDE FIRST. RAFT AFTER.</h2>
    <p>For travelers who want to go all in, horseback riding can also be combined with Rafiki's whitewater rafting experience. One version of the route crosses Rafiki's reserve toward Río Blanco before transitioning into the rafting portion of the day.</p>
    <p style="font-size:18px;">Two completely different ways of moving through the same valley. Horse beneath you first. Paddle in your hand later.</p>
    <p><a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to ask about combining horseback riding with rafting at Rafiki.' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);" target="_blank" rel="noopener">Ask Us About Combining Horses + Rafting</a></p>
  </div>
</section>

<!-- ===== 09. COME BACK TO THE LODGE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Part of Your Stay</span>
    <h2 class="section-title center">YOU DON'T NEED ANOTHER HOTEL AT THE END OF THE TRAIL.</h2>
    <p>This is what makes doing the experience while staying at Rafiki different. Start your morning at the lodge. Ride the valley. Come back. Lunch. Pool. Shower. Massage if your legs are voting for that option.</p>
    <p>And tomorrow, you can do something completely different. Raft. Take the Aqua Hike. Go birding. Or sleep a little longer.</p>
  </div>
</section>

<!-- ===== 10. GOOD TO KNOW ===== -->
<section class="section">
  <div class="container">
    <h2 class="section-title center">GOOD TO KNOW</h2>
    <div class="amenities-grid">
      <div class="amenity-item"><?php echo rafiki_icon( 'guide' ); ?><div><strong>Experience</strong><span>Guided horseback riding through the Savegre Valley and surrounding Rafiki landscape.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'family' ); ?><div><strong>Good for</strong><span>First-time riders, families, couples and more experienced riders looking for a different way to explore the valley.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'shield' ); ?><div><strong>Guiding</strong><span>Bilingual guiding and local horse-handling support from the Duarte family.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'mountain' ); ?><div><strong>Routes</strong><span>Vary depending on experience level, terrain and conditions.</span></div></div>
    </div>
    <p style="text-align:center; max-width:600px; margin:36px auto 0; color:var(--text-dark-muted);">What to bring: closed-toe shoes or boots, long pants, sun protection, bug spray, camera or phone.</p>
  </div>
</section>

<!-- ===== 11. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">See More Than the Road Shows You</span>
    <h2>RIDE INTO THE VALLEY.</h2>
    <p>Add horseback riding to your Rafiki stay and experience the forest, river and surrounding community at a pace that still belongs here.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_booking_link( get_the_ID() ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to add horseback riding to my stay at Rafiki.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Ask About Horseback Riding</a>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
