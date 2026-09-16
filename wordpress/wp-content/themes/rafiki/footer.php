
<footer class="site-footer">
  <div class="container footer-top">
    <div class="footer-brand">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-rafiki-white.png' ); ?>" alt="Rafiki Safari Lodge">
      <p>Rafiki Safari Lodge<br>Costa Rica</p>
    </div>

    <div class="footer-col">
      <h4>Stay With Us</h4>
      <?php
      $accommodations = get_posts( array( 'post_type' => 'accommodation', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC', 'post_status' => 'publish' ) );
      if ( $accommodations ) :
        foreach ( $accommodations as $a ) : ?>
          <a href="<?php echo esc_url( get_permalink( $a ) ); ?>"><?php echo esc_html( get_the_title( $a ) ); ?></a>
        <?php endforeach;
      else : ?>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>">View Accommodations</a>
      <?php endif; ?>
    </div>

    <div class="footer-col">
      <h4>What You'll Do</h4>
      <?php
      $activities = get_posts( array( 'post_type' => 'activity', 'posts_per_page' => 5, 'post_status' => 'publish' ) );
      if ( $activities ) :
        foreach ( $activities as $act ) : ?>
          <a href="<?php echo esc_url( get_permalink( $act ) ); ?>"><?php echo esc_html( get_the_title( $act ) ); ?></a>
        <?php endforeach;
      else : ?>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">View Experiences</a>
      <?php endif; ?>
    </div>

    <div class="footer-col">
      <h4>Packages</h4>
      <?php
      $packages = get_posts( array( 'post_type' => 'package', 'posts_per_page' => 4, 'post_status' => 'publish' ) );
      if ( $packages ) :
        foreach ( $packages as $pkg ) : ?>
          <a href="<?php echo esc_url( get_permalink( $pkg ) ); ?>"><?php echo esc_html( get_the_title( $pkg ) ); ?></a>
        <?php endforeach;
      else : ?>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>">View Packages</a>
      <?php endif; ?>
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>">Bring Your Group</a>
    </div>

    <div class="footer-col">
      <h4>Why Rafiki</h4>
      <a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>">Our Story</a>
      <a href="<?php echo esc_url( home_url( '/ecological-mission/' ) ); ?>">Our Ecological Mission</a>
      <a href="<?php echo esc_url( home_url( '/meet-rafiki/' ) ); ?>">Meet Rafiki</a>
      <a href="<?php echo esc_url( home_url( '/plan-your-trip/' ) ); ?>">Before You Get Here</a>
    </div>

    <div class="footer-col footer-contact">
      <h4>Contact</h4>
      <a href="mailto:rafikireservations@gmail.com">rafikireservations@gmail.com</a>
      <a href="tel:+50683689944">+506 8368 9944</a>
      <a href="tel:+50684196832">+506 8419 6832</a>
      <p>16 km from the Coastal Highway<br>Savegre River, Pérez Zeledón<br>Costa Rica</p>
      <div class="social-icons">
        <a href="https://www.instagram.com/rafikisafari/" target="_blank" rel="noopener" aria-label="Instagram">
          <svg viewBox="0 0 24 24" width="18" height="18"><rect x="2" y="2" width="20" height="20" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4.5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg>
        </a>
        <a href="https://www.facebook.com/profile.php?id=100063555731486" target="_blank" rel="noopener" aria-label="Facebook">
          <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M13.5 21v-7.2h2.4l.36-2.8h-2.76V9.1c0-.81.22-1.36 1.39-1.36h1.48V5.2c-.26-.03-1.14-.11-2.16-.11-2.14 0-3.6 1.31-3.6 3.7v2.21H8.2v2.8h2.41V21h2.89z"/></svg>
        </a>
        <a href="<?php echo esc_url( rafiki_whatsapp_link() ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
          <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.36A10 10 0 1 0 12 2zm5.9 14.2c-.25.7-1.45 1.34-2 1.42-.5.08-1.15.11-1.86-.12-.43-.14-.98-.32-1.68-.63-2.96-1.28-4.9-4.24-5.04-4.44-.15-.2-1.2-1.6-1.2-3.05 0-1.45.76-2.16 1.03-2.46.27-.3.6-.37.8-.37h.57c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.07.15.12.33.02.53-.1.2-.15.32-.3.5-.15.18-.32.4-.45.53-.15.15-.31.32-.13.62.18.3.8 1.32 1.72 2.14 1.18 1.05 2.18 1.38 2.48 1.53.3.15.48.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.28.1 1.75.83 2.05 .98.3.15.5.22.57.35.08.13.08.75-.17 1.45z"/></svg>
        </a>
      </div>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Rafiki Safari Lodge. All rights reserved.</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
