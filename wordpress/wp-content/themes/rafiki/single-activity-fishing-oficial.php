<?php
/**
 * Custom template for Fishing — matched automatically by WordPress via
 * the post slug (fishing). Deliberately not "rafting without the rapids":
 * a quieter, slower river day built around reading the water and the
 * machaca, not adrenaline. Conditions vary with the river, so "ask about
 * current conditions" carries real weight here instead of a fixed promise.
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
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="Fishing the lower Savegre River from a raft at Rafiki"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">Experiences</a><span class="sep">/</span>
        <span class="current">Fishing</span>
      </p>
      <span class="eyebrow">Fishing at Rafiki</span>
      <h1>A SLOWER WAY TO<br><span class="accent">KNOW THE SAVEGRE.</span></h1>
      <p class="hero-sub">Rafting shows you what the river can do. Fishing makes you stop long enough to notice how it works.</p>
      <p class="hero-sub">Spend the day moving through the lower Savegre by raft, stopping at deep pools and slower stretches of water where tropical freshwater species hide. No crowds. No marina. Just the river, the forest and a guide helping you read what is happening beneath the surface.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_booking_link( get_the_ID() ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to ask about current fishing conditions at Rafiki.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Ask About Current Conditions</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. THIS ISN'T TROUT FISHING IN THE MOUNTAINS ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">The Lower Savegre</span>
    <h2 class="section-title center">SAME RIVER. COMPLETELY DIFFERENT WATER.</h2>
    <p>Higher in the mountains, the Savegre is known for trout. By the time it reaches Rafiki, the river has dropped into warm tropical lowlands and the fishing changes with it. Different water. Different insects. Different fish. Even the flies can look different.</p>
    <p>Rafiki's guides use patterns inspired by the things actually found around the river — including large tropical insects, flowers and even nuts. The lodge currently notes more than eight freshwater species in these waters, along with occasional saltwater species farther downstream.</p>
    <p style="font-size:18px;">This isn't about recreating the fishing you already know somewhere else. It's about figuring out this river.</p>
  </div>
</section>

<!-- ===== 03. THE RAFT GETS YOU WHERE THE ROAD CAN'T ===== -->
<section class="section why-rafiki">
  <div class="why-media">
    <img src="<?php echo esc_url( $hero_img ? $hero_img : 'https://rafikisafari.com/wp/wp-content/uploads/2017/03/fish3-1.jpg' ); ?>" alt="Fishing the Savegre River from a whitewater raft">
    <div class="why-media-text" style="max-width:none;">
      <span class="eyebrow">Fish the River From the River</span>
      <h2>SOME OF THE BEST POOLS<br><span class="accent">AREN'T BESIDE A PARKING LOT.</span></h2>
      <p style="max-width:620px;">Rafiki uses a 13-foot whitewater raft with an oar frame to move anglers through the river. The raft can move through whitewater, then pull into the deeper, slower pools where you actually want to spend time fishing.</p>
      <p style="max-width:620px;">So instead of standing in one spot waiting for something to happen, you're exploring the Savegre as you fish it. Move. Stop. Cast. Watch the water. Try another pool. Keep going. The journey between fishing spots is part of the day.</p>
    </div>
  </div>
</section>

<!-- ===== 04. MEET THE MACHACA ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">The Fish With an Attitude</span>
    <h2 class="section-title center">THIS ONE DOESN'T MAKE IT EASY FOR YOU.</h2>
    <p>One of the main species Rafiki targets is the machaca — a strong, clever freshwater fish known for putting up a serious fight once it's on the line. Rafiki describes the deep, slower pools of the lower Savegre as prime machaca territory.</p>
    <p>And apparently, finesse isn't always the whole strategy. Sometimes the trick is simply figuring out what makes them react. That's part of why fishing here stays interesting.</p>
    <p style="font-size:18px;">You aren't casting into a stocked pond knowing exactly what's waiting. You're trying to understand a wild tropical river.</p>
  </div>
</section>

<?php if ( $gallery ) : ?>
<section class="section" style="padding-bottom:0;">
  <div class="container">
    <div class="gallery-grid">
      <?php foreach ( $gallery as $i => $g ) : if ( empty( $g['image'] ) ) continue;
        $url = wp_get_attachment_image_url( $g['image'], 'large' );
        if ( ! $url ) continue;
        $span = ( 0 === $i ) ? ' span-2 span-2-row' : '';
      ?>
        <img class="<?php echo esc_attr( ltrim( $span ) ); ?>" src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $g['alt'] ); ?>">
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== 05. THE DAY IS ABOUT MORE THAN CATCHING SOMETHING ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Look Around Between Casts</span>
    <h2 class="section-title center">YOU'RE STILL IN THE SAVEGRE VALLEY.</h2>
    <p>The river moves through tropical forest below Rafiki, and the fishing puts you right inside that landscape. Birds overhead. Forest along the banks. Clear water moving over rock. Quiet pools separated by faster sections of river. And long stretches where nobody needs to say much.</p>
    <p style="font-size:18px;">Of course you want the fish. But if the only thing you remember at the end of the day is what was on the line, you probably weren't looking around enough.</p>
  </div>
</section>

<!-- ===== 06. FISHING WITH SOMEONE WHO KNOWS THE WATER ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Local River Knowledge</span>
    <h2 class="section-title center">THE CAST IS YOUR PART. FINDING THE WATER IS THEIRS.</h2>
    <p>A good river guide is constantly reading what has changed. Water level. Current. Weather. Which pools are worth stopping at. Where the fish have been holding. Which fly might make sense today.</p>
    <p>That matters on a wild river where conditions are never exactly the same twice. You don't need to arrive knowing the Savegre. That's why you're fishing it with someone who does.</p>
  </div>
</section>

<!-- ===== 07. FOR PEOPLE WHO LIKE THEIR ADVENTURE A LITTLE QUIETER ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">Who This Is For</span>
    <h2 class="section-title center">NOT EVERYONE NEEDS RAPIDS TO HAVE A GOOD RIVER DAY.</h2>
    <p>Fishing is a great fit if you're the person in the group who wants to spend more time on the river, slow the day down, learn something, try a different kind of freshwater fishing — or disappear for a few hours while everyone else is doing something louder.</p>
    <p style="font-size:18px;">You don't have to make the whole family come. That's one of the advantages of staying at Rafiki. Somebody fishes. Somebody raids the water slide. Someone else goes birding. You all find each other later.</p>
  </div>
</section>

<!-- ===== 08. A FULL DAY ON THE RIVER ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">How It Works</span>
    <h2 class="section-title center">START NEAR RAFIKI. FOLLOW THE WATER.</h2>
    <p>Rafiki currently offers fishing as a full-day river experience beginning near the lodge. The whitewater raft allows the trip to move between faster sections and deeper fishing pools throughout the day.</p>
    <p>Because fishing conditions change with the river, this is not the experience to book based on a rigid promise about what you'll catch.</p>
    <p><a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to ask about current fishing conditions at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Ask About Current Fishing Conditions</a></p>
  </div>
</section>

<!-- ===== 09. PART OF A RAFIKI STAY ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">One River. Two Completely Different Days.</span>
    <h2 class="section-title center">FISH IT TODAY. RAFT IT TOMORROW.</h2>
    <p>This is one of the things we like most about staying beside the Savegre. The same river can give you completely different experiences. One day you're sitting quietly over a deep pool trying to figure out a machaca. The next, your entire family is paddling through Class II–III rapids.</p>
    <p style="font-size:18px;">Same river. Different mood. And both start from Rafiki.</p>
  </div>
</section>

<!-- ===== 10. GOOD TO KNOW ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">GOOD TO KNOW</h2>
    <div class="amenities-grid">
      <div class="amenity-item"><?php echo rafiki_icon( 'wave' ); ?><div><strong>Experience</strong><span>Full-day fishing on the lower Savegre River.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'guide' ); ?><div><strong>Style</strong><span>River fishing using a whitewater raft to access multiple pools and sections of water.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'leaf' ); ?><div><strong>Target</strong><span>Tropical freshwater species, including machaca.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'family' ); ?><div><strong>Best for</strong><span>Anglers, curious beginners and travelers looking for a quieter river experience.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'shield' ); ?><div><strong>Conditions</strong><span>Fishing varies with water levels, weather and current river conditions.</span></div></div>
      <div class="amenity-item"><?php echo rafiki_icon( 'guide' ); ?><div><strong>Before you book</strong><span>Contact Rafiki so the team can advise you on current conditions and how fishing fits into your stay.</span></div></div>
    </div>
  </div>
</section>

<!-- ===== 11. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Read the River Differently</span>
    <h2>TAKE A DAY AND SEE WHAT'S BELOW THE SURFACE.</h2>
    <p>Add fishing to your Rafiki stay and experience the Savegre at a pace that gives you time to understand it.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_booking_link( get_the_ID() ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability &rarr;</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to ask about fishing at Rafiki.' ) ); ?>" class="btn btn-outline" target="_blank" rel="noopener">Ask About Fishing</a>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
