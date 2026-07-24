<?php get_header(); ?>

<?php
$stay_post = get_posts( array( 'post_type' => 'alojamiento', 'posts_per_page' => 1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
$stay_post = $stay_post ? $stay_post[0] : null;

$activity_post = get_posts( array( 'post_type' => 'actividad', 'posts_per_page' => 1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
$activity_post = $activity_post ? $activity_post[0] : null;
?>

<!-- ===== HERO ===== -->
<section class="hero" id="top">
  <div class="hero-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_19-700x480.jpeg" alt="Familia disfrutando la vista a la selva desde el deck de Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>

  <div class="container hero-content">
    <div class="hero-content-inner">
      <h1>
        ESTO NO ES UN RESORT.<br>
        <span class="accent">ES LA COSTA RICA REAL.</span>
      </h1>
      <p class="hero-sub">Para familias, grupos y viajeros que buscan conexión, aventura y naturaleza — sin multitudes ni horarios.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'actividad' ) ); ?>" class="btn btn-primary">Explorar Experiencias</a>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'alojamiento' ) ); ?>" class="btn btn-outline">Planear tu Estadía</a>
      </div>
    </div>
  </div>

  <div class="hero-features">
    <div class="container hero-features-inner">
      <div class="feature-item">
        <?php echo rafiki_icon( 'tent' ); ?>
        <div>
          <strong>14 tiendas estilo safari</strong>
          <span>Rodeadas de naturaleza, no de edificios.</span>
        </div>
      </div>
      <div class="feature-item">
        <?php echo rafiki_icon( 'guide' ); ?>
        <div>
          <strong>Rafting en la propiedad</strong>
          <span>El único lodge en el río con su propio put-in.</span>
        </div>
      </div>
      <div class="feature-item">
        <?php echo rafiki_icon( 'wave' ); ?>
        <div>
          <strong>Playa y selva</strong>
          <span>Dos mundos increíbles. Un viaje inolvidable.</span>
        </div>
      </div>
      <div class="feature-item">
        <?php echo rafiki_icon( 'heart' ); ?>
        <div>
          <strong>Familiar desde hace 25 años</strong>
          <span>Pasión, propósito y gente.</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== THIS PLACE IS FOR YOU IF ===== -->
<section class="section for-you">
  <div class="container">
    <h2 class="section-title center">ESTE LUGAR ES PARA TI SI…</h2>

    <div class="for-you-grid">
      <div class="for-you-item">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>QUIERES TIEMPO JUNTOS</h3>
        <p>Reencuéntrate con las personas que más importan.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'mountain' ); ?>
        <h3>AMAS LA NATURALEZA Y LA AVENTURA</h3>
        <p>Ríos, cataratas, selva y vistas inolvidables.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'shield' ); ?>
        <h3>ESTÁS PLANEANDO ALGO GRANDE</h3>
        <p>Retiros, celebraciones y experiencias grupales que dejan huella.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>VIAJAS DIFERENTE</h3>
        <p>Valoras la autenticidad sobre el lujo. Lo real sobre lo perfecto.</p>
      </div>
    </div>

    <div class="for-you-photos">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_16.jpeg" alt="Familia compartiendo una comida en Rafiki">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_11.jpeg" alt="Grupo haciendo rafting en el río Savegre">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_50.jpeg" alt="Actividad grupal al aire libre en Rafiki">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_1.jpeg" alt="Explorando la selva tropical de Rafiki">
    </div>
  </div>
</section>

<!-- ===== CHOOSE YOUR EXPERIENCE ===== -->
<section class="section experience" id="experiencias">
  <div class="container">
    <h2 class="section-title center">ELIGE TU EXPERIENCIA</h2>

    <div class="experience-grid">
      <a href="<?php echo $stay_post ? esc_url( get_permalink( $stay_post ) ) : esc_url( get_post_type_archive_link( 'alojamiento' ) ); ?>" class="experience-card">
        <img src="<?php echo $stay_post ? esc_url( rafiki_lead_image_url( $stay_post->ID, 'rafiki-card' ) ) : 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_8.jpeg'; ?>" alt="Tiendas estilo safari en Rafiki">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>STAY</h3>
          <p><?php echo $stay_post ? esc_html( get_the_title( $stay_post ) ) : 'Tiendas estilo safari en el corazón de la selva.'; ?></p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo $activity_post ? esc_url( get_permalink( $activity_post ) ) : esc_url( get_post_type_archive_link( 'actividad' ) ); ?>" class="experience-card">
        <img src="<?php echo $activity_post ? esc_url( rafiki_lead_image_url( $activity_post->ID, 'rafiki-card' ) ) : 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg'; ?>" alt="Rafting en el río Savegre">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>ADVENTURE</h3>
          <p><?php echo $activity_post ? esc_html( get_the_title( $activity_post ) ) : 'Rafting, cataratas, cabalgatas y más.'; ?></p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo esc_url( home_url( '/#grupos' ) ); ?>" class="experience-card" id="grupos">
        <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_14a-700x420.jpg" alt="Espacio para grupos y retiros en Rafiki">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>GROUPS &amp; RETREATS</h3>
          <p>Espacios y experiencias para conectar y crecer.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
      <a href="<?php echo esc_url( home_url( '/#beach-camp' ) ); ?>" class="experience-card" id="beach-camp">
        <img src="https://rafikisafari.com/wp/wp-content/uploads/2016/12/beach-pool-700x420.jpg" alt="Rafiki Beach Camp en Playa Matapalo">
        <div class="experience-card-overlay"></div>
        <div class="experience-card-content">
          <h3>BEACH CAMP</h3>
          <p>Duerme junto al mar, a solo 45 minutos.</p>
          <span class="arrow-link">→</span>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ===== WHY RAFIKI ===== -->
<section class="section why-rafiki" id="nosotros">
  <div class="why-grid">
    <div class="why-media">
      <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_24.jpeg" alt="Río Savegre rodeado de selva tropical">
      <div class="why-media-text">
        <h2>¿POR QUÉ RAFIKI?<br><span class="accent">NO METEMOS LA NATURALEZA EN UNA CAJA.</span></h2>
        <p>Los animales viven su vida. Nosotros vivimos en su mundo. Esta es la Costa Rica cruda y real.</p>
        <a href="<?php echo esc_url( home_url( '/#nosotros' ) ); ?>" class="btn btn-primary">Conoce Nuestra Historia →</a>
      </div>
    </div>

    <div class="why-stats">
      <div class="stat">
        <?php echo rafiki_icon( 'family' ); ?>
        <strong>25+ AÑOS</strong>
        <span>Familiar, propiedad y operación.</span>
      </div>
      <div class="stat">
        <?php echo rafiki_icon( 'leaf' ); ?>
        <strong>100% COSTARRICENSE</strong>
        <span>Equipo local. Impacto local.</span>
      </div>
      <div class="stat">
        <?php echo rafiki_icon( 'shield' ); ?>
        <strong>1 UBICACIÓN INCREÍBLE</strong>
        <span>Entre la selva y el océano — 600 acres.</span>
      </div>
      <div class="stat">
        <?php echo rafiki_icon( 'heart' ); ?>
        <strong>INCONTABLES RECUERDOS</strong>
        <span>Creados por miles de huéspedes felices.</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section testimonials">
  <div class="container">
    <h2 class="section-title center">LO QUE DICEN NUESTROS HUÉSPEDES</h2>

    <div class="testimonial-grid">
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'google' ); ?><span>Reseña de Google</span></div>
        <div class="stars">★★★★★</div>
        <p>"Un lodge hermoso, con inspiración africana, para los amantes de la vida silvestre. Guías increíbles y tiendas de lujo que superaron nuestras expectativas."</p>
        <span class="testimonial-author">Yooperchick</span>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'tripadvisor' ); ?><span>Reseña de TripAdvisor</span></div>
        <div class="stars">★★★★★</div>
        <p>"Cabañas encantadoras, comida excelente, y el rafting fue una experiencia que nos cambió la vida. Toda la familia lo disfrutó al máximo."</p>
        <span class="testimonial-author">TaikoM</span>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( 'facebook' ); ?><span>Reseña de Facebook</span></div>
        <div class="stars">★★★★★</div>
        <p>"Llevamos 5 veces en 15 años. Todos los guías crecieron en la zona y eso hace que el viaje sea especial de verdad. Pura vida."</p>
        <span class="testimonial-author">Lance R.</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section class="cta-banner" id="reservar">
  <div class="cta-media">
    <img src="https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_34.jpeg" alt="Grupo haciendo rafting frente al lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>¿LISTO PARA PLANEAR TU VIAJE INOLVIDABLE?</h2>
    <p>Te ayudamos a crear la experiencia perfecta para tu gente y tu propósito.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hola! Quiero hacer una reserva en Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Consultar Disponibilidad →</a>
  </div>
</section>

<?php get_footer(); ?>
