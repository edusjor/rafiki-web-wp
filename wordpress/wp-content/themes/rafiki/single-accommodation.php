<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
	$post_id      = get_the_ID();
	$hero_img     = rafiki_lead_image_url( $post_id, 'full' );
	$badges       = rafiki_rows( $post_id, 'rafiki_badges' );
	$facts        = rafiki_rows( $post_id, 'rafiki_quick_facts' );
	$gallery      = rafiki_rows( $post_id, 'rafiki_gallery' );
	$amenities    = rafiki_rows( $post_id, 'rafiki_amenities' );
	$room_options = rafiki_rows( $post_id, 'rafiki_room_options' );
	$rates        = rafiki_rows( $post_id, 'rafiki_rates' );

	$price       = get_post_meta( $post_id, 'rafiki_price', true );
	$price_unit  = get_post_meta( $post_id, 'rafiki_price_unit', true );
	$price_note  = get_post_meta( $post_id, 'rafiki_price_note', true );
	$t_source    = get_post_meta( $post_id, 'rafiki_testimonial_source', true );
	$t_text      = get_post_meta( $post_id, 'rafiki_testimonial_text', true );
	$t_author    = get_post_meta( $post_id, 'rafiki_testimonial_author', true );
	$cta_title   = get_post_meta( $post_id, 'rafiki_cta_title', true ) ?: 'READY TO PLAN YOUR STAY?';
	$cta_text    = get_post_meta( $post_id, 'rafiki_cta_text', true ) ?: 'Check availability and build your ideal package.';
	$cta_img_id  = get_post_meta( $post_id, 'rafiki_cta_image', true );
	$cta_img     = $cta_img_id ? wp_get_attachment_image_url( $cta_img_id, 'full' ) : $hero_img;

	$book_url  = rafiki_booking_link( $post_id ); // per-post "Booking Link" field, else the site-wide Beds24 URL
	$info_only = rafiki_is_info_only_stay( $post_id ); // Lekker Bar & Braai etc. — info page, no booking
	$ask_url   = rafiki_whatsapp_link( 'Hi! I have a question about ' . get_the_title() . ' at Rafiki Safari Lodge.' );
?>

<section class="page-hero">
  <div class="hero-media">
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php the_title_attribute(); ?>"><?php endif; ?>
    <div class="hero-overlay"<?php if ( get_the_title() === 'Main Lodge' ) : ?> style="background:linear-gradient(180deg, rgba(10,9,7,0.25) 0%, rgba(10,9,7,0.15) 45%, rgba(8,7,5,0.55) 100%);"<?php endif; ?>></div>
  </div>

  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>">Stay</a><span class="sep">/</span>
        <span class="current"><?php the_title(); ?></span>
      </p>
      <h1><?php the_title(); ?></h1>
      <?php $sub = get_post_meta( $post_id, 'rafiki_subtitle', true ); if ( $sub ) : ?>
        <p class="hero-sub"><?php echo esc_html( $sub ); ?></p>
      <?php endif; ?>
      <?php if ( $badges ) : ?>
        <div class="badge-row">
          <?php foreach ( $badges as $b ) : if ( empty( $b['text'] ) ) continue; ?>
            <span class="badge"><?php echo esc_html( $b['text'] ); ?></span>
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
      <?php $intro_title = get_post_meta( $post_id, 'rafiki_intro_title', true ); if ( $intro_title ) : ?>
        <h2><?php echo esc_html( $intro_title ); ?></h2>
      <?php endif; ?>
      <?php echo rafiki_paragraphs( get_post_meta( $post_id, 'rafiki_intro_text', true ) ); ?>
    </div>

    <aside class="facts-card">
      <h3>Quick Facts</h3>
      <?php if ( $price ) : ?>
        <span class="facts-price">$<?php echo esc_html( $price ); ?><span style="font-family:var(--font-body); font-size:14px; color:var(--text-muted);"> <?php echo esc_html( $price_unit ); ?></span></span>
        <?php if ( $price_note ) : ?><span class="facts-price-note"><?php echo esc_html( $price_note ); ?></span><?php endif; ?>
      <?php endif; ?>
      <?php if ( $facts ) : ?>
        <ul class="facts-list">
          <?php foreach ( $facts as $f ) : if ( empty( $f['label'] ) ) continue; ?>
            <li><span><?php echo esc_html( $f['label'] ); ?></span><span><?php echo esc_html( $f['value'] ); ?></span></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if ( $info_only ) : ?>
        <p style="font-size:13px; color:var(--text-muted); margin:0 0 12px;">Which meals are included depends on the package you choose &mdash; the Lekker Bar is open to every guest for lunch and dinner.</p>
        <a class="btn btn-primary" href="<?php echo esc_url( $ask_url ); ?>" target="_blank" rel="noopener">Ask on WhatsApp</a>
      <?php else : ?>
        <a class="btn btn-primary" href="<?php echo esc_url( $book_url ); ?>" target="_blank" rel="noopener">Check Availability &rarr;</a>
        <p style="font-size:12.5px; color:var(--text-muted); margin:10px 0 0;">Opens our booking system to choose your dates.</p>
        <a class="btn btn-outline" href="<?php echo esc_url( $ask_url ); ?>" target="_blank" rel="noopener" style="margin-top:10px;">Ask on WhatsApp</a>
      <?php endif; ?>
    </aside>
  </div>
</section>

<?php if ( $gallery ) : ?>
<section class="section" style="padding-top:0;">
  <div class="container">
    <h2 class="section-title">GALLERY</h2>
    <div class="gallery-grid">
      <?php foreach ( $gallery as $i => $g ) : if ( empty( $g['image'] ) ) continue;
        $url = wp_get_attachment_image_url( $g['image'], 'large' );
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
    <h2 class="section-title"><?php echo esc_html( get_post_meta( $post_id, 'rafiki_amenities_title', true ) ?: 'WHAT\'S INCLUDED' ); ?></h2>
    <div class="amenities-grid">
      <?php foreach ( $amenities as $a ) : if ( empty( $a['title'] ) ) continue; ?>
        <div class="amenity-item">
          <?php echo rafiki_icon( $a['icon'] ); ?>
          <div><strong><?php echo esc_html( $a['title'] ); ?></strong><span><?php echo esc_html( $a['text'] ); ?></span></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $room_options ) : ?>
<section class="section">
  <div class="container">
    <h2 class="section-title">ROOM OPTIONS</h2>
    <div class="variant-grid">
      <?php foreach ( $room_options as $v ) : if ( empty( $v['title'] ) ) continue;
        $vimg = ! empty( $v['image'] ) ? wp_get_attachment_image_url( $v['image'], 'rafiki-card' ) : '';
      ?>
        <div class="variant-card">
          <?php if ( $vimg ) : ?><img src="<?php echo esc_url( $vimg ); ?>" alt="<?php echo esc_attr( $v['title'] ); ?>"><?php endif; ?>
          <div class="variant-card-body">
            <h3><?php echo esc_html( $v['title'] ); ?></h3>
            <p><?php echo esc_html( $v['text'] ); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $rates ) : ?>
<section class="section" style="background:var(--cream-2);" id="rates">
  <div class="container">
    <h2 class="section-title">RATES</h2>
    <div style="overflow-x:auto;">
      <table class="rate-table">
        <thead><tr><th>Season</th><th>Double Occupancy</th><th>Extra Person</th><th>Upgrade</th></tr></thead>
        <tbody>
          <?php foreach ( $rates as $r ) : if ( empty( $r['season'] ) ) continue; ?>
            <tr>
              <td><?php echo esc_html( $r['season'] ); ?></td>
              <td><strong><?php echo esc_html( $r['double'] ); ?></strong></td>
              <td><?php echo esc_html( $r['extra'] ); ?></td>
              <td><?php echo esc_html( $r['upgrade'] ); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $t_text ) : ?>
<section class="section testimonials">
  <div class="container">
    <h2 class="section-title center">WHAT OUR GUESTS SAY</h2>
    <div class="testimonial-grid" style="grid-template-columns: 1fr; max-width:640px; margin:0 auto;">
      <div class="testimonial-card">
        <div class="testimonial-source"><?php echo rafiki_testimonial_source_svg( $t_source ); ?><span><?php echo esc_html( rafiki_testimonial_source_label( $t_source ) ); ?></span></div>
        <div class="stars">★★★★★</div>
        <p>"<?php echo esc_html( $t_text ); ?>"</p>
        <?php if ( $t_author ) : ?><span class="testimonial-author"><?php echo esc_html( $t_author ); ?></span><?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $cta_img ) : ?><img src="<?php echo esc_url( $cta_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2><?php echo esc_html( $cta_title ); ?></h2>
    <p><?php echo esc_html( $cta_text ); ?></p>
    <div class="btn-group">
      <?php if ( $info_only ) : ?>
        <a class="btn btn-primary" href="<?php echo esc_url( $ask_url ); ?>" target="_blank" rel="noopener">Ask on WhatsApp</a>
      <?php else : ?>
        <a class="btn btn-primary" href="<?php echo esc_url( $book_url ); ?>" target="_blank" rel="noopener">Check Availability &rarr;</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
