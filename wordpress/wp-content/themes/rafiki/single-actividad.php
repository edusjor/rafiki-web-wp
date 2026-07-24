<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
	$post_id    = get_the_ID();
	$hero_img   = rafiki_lead_image_url( $post_id, 'full' );
	$badges     = rafiki_rows( $post_id, 'rafiki_badges' );
	$facts      = rafiki_rows( $post_id, 'rafiki_datos_rapidos' );
	$gallery    = rafiki_rows( $post_id, 'rafiki_galeria' );
	$amenities  = rafiki_rows( $post_id, 'rafiki_amenidades' );
	$itinerary  = rafiki_rows( $post_id, 'rafiki_itinerario' );
	$packages   = rafiki_rows( $post_id, 'rafiki_paquetes' );

	$precio       = get_post_meta( $post_id, 'rafiki_precio', true );
	$precio_und   = get_post_meta( $post_id, 'rafiki_precio_unidad', true );
	$precio_nota  = get_post_meta( $post_id, 'rafiki_precio_nota', true );
	$t_fuente     = get_post_meta( $post_id, 'rafiki_testimonio_fuente', true );
	$t_texto      = get_post_meta( $post_id, 'rafiki_testimonio_texto', true );
	$t_autor      = get_post_meta( $post_id, 'rafiki_testimonio_autor', true );
	$cta_titulo   = get_post_meta( $post_id, 'rafiki_cta_titulo', true ) ?: '¿LISTO PARA VIVIRLO?';
	$cta_texto    = get_post_meta( $post_id, 'rafiki_cta_texto', true ) ?: 'Consulta disponibilidad o súmalo a un paquete completo.';
	$cta_img_id   = get_post_meta( $post_id, 'rafiki_cta_imagen', true );
	$cta_img      = $cta_img_id ? wp_get_attachment_image_url( $cta_img_id, 'full' ) : $hero_img;
?>

<section class="page-hero">
  <div class="hero-media">
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php the_title_attribute(); ?>"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>

  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'actividad' ) ); ?>">Experiencias</a><span class="sep">/</span>
        <span class="current"><?php the_title(); ?></span>
      </p>
      <h1><?php the_title(); ?></h1>
      <?php $sub = get_post_meta( $post_id, 'rafiki_subtitulo', true ); if ( $sub ) : ?>
        <p class="hero-sub"><?php echo esc_html( $sub ); ?></p>
      <?php endif; ?>
      <?php if ( $badges ) : ?>
        <div class="badge-row">
          <?php foreach ( $badges as $b ) : if ( empty( $b['texto'] ) ) continue; ?>
            <span class="badge"><?php echo esc_html( $b['texto'] ); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container detail-intro">
    <div class="detail-intro-text">
      <?php $eyebrow = get_post_meta( $post_id, 'rafiki_intro_eyebrow', true ); if ( $eyebrow ) : ?>
        <span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
      <?php endif; ?>
      <?php $intro_titulo = get_post_meta( $post_id, 'rafiki_intro_titulo', true ); if ( $intro_titulo ) : ?>
        <h2><?php echo esc_html( $intro_titulo ); ?></h2>
      <?php endif; ?>
      <?php echo rafiki_paragraphs( get_post_meta( $post_id, 'rafiki_intro_texto', true ) ); ?>
    </div>

    <aside class="facts-card">
      <h3>Datos rápidos</h3>
      <?php if ( $precio ) : ?>
        <span class="facts-price">$<?php echo esc_html( $precio ); ?><span style="font-family:var(--font-body); font-size:14px; color:var(--text-muted);"> <?php echo esc_html( $precio_und ); ?></span></span>
        <?php if ( $precio_nota ) : ?><span class="facts-price-note"><?php echo esc_html( $precio_nota ); ?></span><?php endif; ?>
      <?php endif; ?>
      <?php if ( $facts ) : ?>
        <ul class="facts-list">
          <?php foreach ( $facts as $f ) : if ( empty( $f['label'] ) ) continue; ?>
            <li><span><?php echo esc_html( $f['label'] ); ?></span><span><?php echo esc_html( $f['valor'] ); ?></span></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="btn-group">
        <a href="<?php echo esc_url( rafiki_booking_link( $post_id ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Reservar Ahora</a>
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hola! Quiero reservar: ' . get_the_title( $post_id ) ) ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener"><?php echo rafiki_whatsapp_icon_svg(); ?> Reservar por WhatsApp</a>
      </div>
    </aside>
  </div>
</section>

<?php if ( $itinerary ) : ?>
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title">CÓMO ES TU DÍA</h2>
    <div class="itinerary">
      <?php foreach ( $itinerary as $step ) : if ( empty( $step['titulo'] ) ) continue; ?>
        <div class="itinerary-step">
          <div class="itinerary-step-num"></div>
          <div class="itinerary-step-body">
            <h3><?php echo esc_html( $step['titulo'] ); ?></h3>
            <p><?php echo esc_html( $step['texto'] ); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $gallery ) : ?>
<section class="section">
  <div class="container">
    <h2 class="section-title">GALERÍA</h2>
    <div class="gallery-grid">
      <?php foreach ( $gallery as $i => $g ) : if ( empty( $g['imagen'] ) ) continue;
        $url = wp_get_attachment_image_url( $g['imagen'], 'large' );
        if ( ! $url ) continue;
        $span = ( 0 === $i ) ? ' span-2 span-2-row' : ( ( 4 === $i ) ? ' span-2' : '' );
      ?>
        <img class="<?php echo esc_attr( ltrim( $span ) ); ?>" src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $g['alt'] ); ?>">
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $amenities ) : ?>
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title"><?php echo esc_html( get_post_meta( $post_id, 'rafiki_amenidades_titulo', true ) ?: 'LO QUE INCLUYE' ); ?></h2>
    <div class="amenities-grid">
      <?php foreach ( $amenities as $a ) : if ( empty( $a['titulo'] ) ) continue; ?>
        <div class="amenity-item">
          <?php echo rafiki_icon( $a['icono'] ); ?>
          <div><strong><?php echo esc_html( $a['titulo'] ); ?></strong><span><?php echo esc_html( $a['texto'] ); ?></span></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $packages ) : ?>
<section class="section" id="paquetes">
  <div class="container">
    <h2 class="section-title center">PAQUETES QUE INCLUYEN ESTA ACTIVIDAD</h2>
    <div class="package-grid">
      <?php foreach ( $packages as $p ) : if ( empty( $p['nombre'] ) ) continue; ?>
        <div class="package-card">
          <?php if ( ! empty( $p['etiqueta'] ) ) : ?><span class="package-name"><?php echo esc_html( $p['etiqueta'] ); ?></span><?php endif; ?>
          <h3><?php echo esc_html( $p['nombre'] ); ?></h3>
          <p><?php echo esc_html( $p['texto'] ); ?></p>
          <?php if ( ! empty( $p['precio'] ) ) : ?>
            <div class="package-price">$<?php echo esc_html( $p['precio'] ); ?> <span><?php echo esc_html( $p['precio_nota'] ); ?></span></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $t_texto ) : ?>
<section class="section testimonials" style="background:var(--cream-2);">
  <div class="container">
    <h2 class="section-title center">LO QUE DICEN NUESTROS HUÉSPEDES</h2>
    <div class="testimonial-grid" style="grid-template-columns: 1fr; max-width:640px; margin:0 auto;">
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( $t_fuente ); ?><span><?php echo esc_html( rafiki_testimonial_source_label( $t_fuente ) ); ?></span></div>
        <div class="stars">★★★★★</div>
        <p>"<?php echo esc_html( $t_texto ); ?>"</p>
        <?php if ( $t_autor ) : ?><span class="testimonial-author"><?php echo esc_html( $t_autor ); ?></span><?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="cta-banner" id="reservar">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2><?php echo esc_html( $cta_titulo ); ?></h2>
    <p><?php echo esc_html( $cta_texto ); ?></p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_booking_link( $post_id ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Reservar Ahora →</a>
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hola! Quiero reservar: ' . get_the_title( $post_id ) ) ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener"><?php echo rafiki_whatsapp_icon_svg(); ?> Reservar por WhatsApp</a>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
