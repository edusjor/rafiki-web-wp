<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2026/04/Logo_Rafiki-WH-e1775497351524.png" alt="Rafiki Safari Lodge">
    </a>

    <nav class="main-nav" id="mainNav">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>">Stay With Us</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>">What You'll Do</a>
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>">Bring Your Group</a>
      <a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>">Why Rafiki</a>
      <a href="<?php echo esc_url( home_url( '/plan-your-trip/' ) ); ?>">Plan Your Trip</a>
      <a href="<?php echo esc_url( home_url( '/rafiki-journal/' ) ); ?>">Rafiki Journal</a>
    </nav>

    <div class="header-actions">
      <?php if ( shortcode_exists( 'language-switcher' ) ) : ?>
        <div class="lang-switcher"><?php echo do_shortcode( '[language-switcher]' ); ?></div>
      <?php endif; ?>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to make a reservation at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
      <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
