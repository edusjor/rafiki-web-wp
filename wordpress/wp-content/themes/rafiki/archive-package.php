<?php get_header(); ?>

<section class="page-hero" style="min-height:46vh;">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg" alt="Packages at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Packages</span></p>
      <h1>OUR <span class="accent">PACKAGES</span></h1>
      <p class="hero-sub">Lodging, meals and activities bundled together — the easiest way to plan your trip.</p>
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
        <p>No packages published yet.</p>
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
    <h2>NOT SURE WHICH PACKAGE FITS?</h2>
    <p>Tell us how many days you have and what you want to experience.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like help choosing a package at Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Ask Us →</a>
  </div>
</section>

<?php get_footer(); ?>
