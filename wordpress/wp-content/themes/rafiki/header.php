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
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-rafiki-white.png' ); ?>" alt="Rafiki Safari Lodge">
    </a>

    <?php
    $stay_url         = get_post_type_archive_link( 'accommodation' );
    $activities_url   = get_post_type_archive_link( 'activity' );
    $packages_url     = get_post_type_archive_link( 'package' );
    $stay_items       = get_posts( array(
      'post_type'      => 'accommodation',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
      'post_status'    => 'publish',
    ) );
    $activity_items   = get_posts( array(
      'post_type'      => 'activity',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
      'post_status'    => 'publish',
    ) );
    $package_items    = get_posts( array(
      'post_type'      => 'package',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order date',
      'order'          => 'ASC',
      'post_status'    => 'publish',
    ) );
    ?>
    <nav class="main-nav" id="mainNav">
      <div class="nav-item has-sub">
        <a href="<?php echo esc_url( $stay_url ); ?>">Stay With Us</a>
        <?php if ( $stay_items ) : ?>
        <div class="sub-menu">
          <?php foreach ( $stay_items as $stay_item ) : ?>
            <a href="<?php echo esc_url( get_permalink( $stay_item ) ); ?>"><?php echo esc_html( get_the_title( $stay_item ) ); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="nav-item has-sub">
        <a href="<?php echo esc_url( $activities_url ); ?>">What You'll Do</a>
        <?php if ( $activity_items ) : ?>
        <div class="sub-menu">
          <a href="<?php echo esc_url( $activities_url ); ?>">All Activities</a>
          <?php foreach ( $activity_items as $activity_item ) : ?>
            <a href="<?php echo esc_url( get_permalink( $activity_item ) ); ?>"><?php echo esc_html( get_the_title( $activity_item ) ); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="nav-item has-sub">
        <a href="<?php echo esc_url( $packages_url ); ?>">Packages</a>
        <?php if ( $package_items ) : ?>
        <div class="sub-menu">
          <a href="<?php echo esc_url( $packages_url ); ?>">All Packages</a>
          <?php foreach ( $package_items as $package_item ) : ?>
            <a href="<?php echo esc_url( get_permalink( $package_item ) ); ?>"><?php echo esc_html( get_the_title( $package_item ) ); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="nav-item has-sub">
        <a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>">Why Rafiki</a>
        <div class="sub-menu">
          <a href="<?php echo esc_url( home_url( '/why-rafiki/' ) ); ?>">Our Story</a>
          <a href="<?php echo esc_url( home_url( '/ecological-mission/' ) ); ?>">Our Ecological Mission</a>
          <?php if ( rafiki_page_is_published( 'meet-rafiki' ) ) : ?><a href="<?php echo esc_url( home_url( '/meet-rafiki/' ) ); ?>">Meet Rafiki</a><?php endif; ?>
          <a href="<?php echo esc_url( home_url( '/plan-your-trip/' ) ); ?>">Before You Get Here</a>
        </div>
      </div>
      <div class="nav-item">
        <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>">Bring Your Group</a>
      </div>
      <div class="nav-item">
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
      </div>
      <?php /* Rafiki Journal oculto del menú
      <a href="<?php echo esc_url( home_url( '/rafiki-journal/' ) ); ?>">Rafiki Journal</a>
      */ ?>
    </nav>

    <div class="header-actions">
      <?php if ( shortcode_exists( 'language-switcher' ) ) : ?>
        <div class="lang-switcher"><?php echo do_shortcode( '[language-switcher]' ); ?></div>
      <?php endif; ?>
      <a href="<?php echo esc_url( rafiki_beds24_url() ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
      <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
