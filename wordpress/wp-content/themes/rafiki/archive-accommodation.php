<?php get_header(); ?>

<section class="page-hero" style="min-height:46vh;">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_19.jpeg" alt="Accommodations at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Stay</span></p>
      <h1>OUR <span class="accent">ACCOMMODATIONS</span></h1>
      <p class="hero-sub">Every space at Rafiki is built to sleep surrounded by jungle, without giving up comfort.</p>
    </div>
  </div>
</section>

<section class="section experience">
  <div class="container">
    <div class="experience-grid archive-grid">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
        $img = rafiki_lead_image_url( get_the_ID(), 'rafiki-card' );
        $sub = get_post_meta( get_the_ID(), 'rafiki_subtitle', true );
      ?>
        <a href="<?php the_permalink(); ?>" class="experience-card">
          <?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>"><?php endif; ?>
          <div class="experience-card-overlay"></div>
          <div class="experience-card-content">
            <h3><?php the_title(); ?></h3>
            <?php if ( $sub ) : ?><p><?php echo esc_html( wp_trim_words( $sub, 14 ) ); ?></p><?php endif; ?>
            <span class="arrow-link">→</span>
          </div>
        </a>
      <?php endwhile; else : ?>
        <p>No accommodations published yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_34.jpeg" alt="Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>READY TO CHOOSE YOUR SPACE?</h2>
    <p>Tell us how many you are and what you're looking for, and we'll recommend the best fit.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability for accommodations at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
  </div>
</section>

<?php get_footer(); ?>
