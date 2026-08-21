<?php
/**
 * Custom template for Massage — matched automatically by WordPress via
 * the post slug (massage). Deliberately short: the sell here is the scene
 * (forest, quiet, nowhere to rush back to), not a service-menu page, so it
 * doesn't carry the quick-facts/gallery-grid weight the adventure pages do.
 */
get_header();

while ( have_posts() ) : the_post();
	$post_id   = get_the_ID();
	$hero_img  = rafiki_lead_image_url( $post_id, 'full' );
	$cta_img_id = get_post_meta( $post_id, 'rafiki_cta_image', true );
	$cta_img   = $cta_img_id ? wp_get_attachment_image_url( $cta_img_id, 'full' ) : $hero_img;
?>

<!-- ===== HERO =====
     Art direction for Eduardo: avoid a tight shot of hands on a back — use
     a wide frame that shows the small massage table inside the surrounding
     forest, so scale sells the setting before the treatment. -->
<section class="page-hero" style="min-height:64vh;">
  <div class="hero-media">
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="Massage table set up in the open air, surrounded by rainforest at Rafiki"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">Experiences</a><span class="sep">/</span>
        <span class="current">Massage in the Rainforest</span>
      </p>
      <span class="eyebrow">A Quieter Side of Rafiki</span>
      <h1>LET THE FOREST<br><span class="accent">GO QUIET AROUND YOU.</span></h1>
      <p class="hero-sub">No traffic. No phones ringing. No spa music trying to make the room feel peaceful. Just the sound of the forest, the breeze moving through the trees and someone taking care of you while everything else can wait.</p>
      <p class="hero-sub">At Rafiki, a massage is a chance to stop completely for a while — without having to leave the nature you came all this way to experience.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to book a massage during my stay at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Make Time to Slow Down</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== NOTHING TO LISTEN TO BUT WHAT'S ALREADY HERE ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:720px;">
    <h2 class="section-title center">NOTHING TO LISTEN TO BUT WHAT'S ALREADY HERE.</h2>
    <p>Close your eyes. You may hear birds somewhere beyond the trees. Rain moving in. Leaves shifting with the wind. Maybe the river in the distance. That's it.</p>
    <p style="font-size:18px;">No need to manufacture calm when you're already surrounded by it.</p>
  </div>
</section>

<!-- ===== LET YOUR BODY CATCH UP WITH THE TRIP ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:720px;">
    <h2 class="section-title center">LET YOUR BODY CATCH UP WITH THE TRIP.</h2>
    <p>Maybe you've spent the morning rafting. Maybe yesterday was the Aqua Hike. Maybe you've been driving across Costa Rica for a week. Or maybe you don't need a reason at all.</p>
    <p>Lie down. Breathe. Let someone else take over for a while. And trust the hands taking care of you.</p>
  </div>
</section>

<!-- ===== DON'T RUSH THE PART AFTER ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:720px;">
    <h2 class="section-title center">DON'T RUSH THE PART AFTER.</h2>
    <p>When the massage ends, nothing has to start immediately. Stay still. Go back to your porch. Sit by the pool. Have something cold to drink. Take a nap.</p>
    <p style="font-size:18px;">The best part of slowing down at Rafiki is that there is nowhere you need to rush back to.</p>
  </div>
</section>

<!-- ===== ANOTHER WAY TO EXPERIENCE THE FOREST ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center; max-width:720px;">
    <h2 class="section-title center">ANOTHER WAY TO EXPERIENCE THE FOREST.</h2>
    <p>Some people want to feel Rafiki from a raft. Some from horseback. Some while walking through the water and forest. And sometimes the best way to experience it is with your eyes closed.</p>
    <p style="font-size:18px;">The forest is still there. You're just finally quiet enough to notice it.</p>
  </div>
</section>

<!-- ===== FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>MAKE TIME TO SLOW DOWN.</h2>
    <p>Ask us to book a massage during your stay — arranged right where you're sleeping, forest included.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to book a massage during my stay at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Make Time to Slow Down →</a>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
