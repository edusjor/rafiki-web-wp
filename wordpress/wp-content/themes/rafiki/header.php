<!DOCTYPE html>
<html lang="es">
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
      <a href="<?php echo esc_url( get_post_type_archive_link( 'alojamiento' ) ); ?>">Stay</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'actividad' ) ); ?>">Experiencias</a>
      <a href="<?php echo esc_url( home_url( '/#paquetes' ) ); ?>">Paquetes</a>
      <a href="<?php echo esc_url( home_url( '/#grupos' ) ); ?>">Grupos &amp; Retiros</a>
      <a href="<?php echo esc_url( home_url( '/#beach-camp' ) ); ?>">Beach Camp</a>
      <a href="<?php echo esc_url( home_url( '/#nosotros' ) ); ?>">Sobre Rafiki</a>
    </nav>

    <div class="header-actions">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hola! Quiero hacer una reserva en Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Reservar Ahora</a>
      <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
