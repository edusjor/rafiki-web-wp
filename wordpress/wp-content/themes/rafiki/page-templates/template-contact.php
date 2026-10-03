<?php
/**
 * Template Name: Contact
 *
 * General contact page: direct details (phone/WhatsApp, email, address),
 * a short message form (inc/contact-form.php) and a map. Replaces the old
 * site's /wp/contact/ page.
 */
get_header();

$status = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : '';
?>

<section class="page-hero" style="min-height:46vh;">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'lodge-heliconia' ) ); ?>" alt="The Main Lodge seen through heliconia flowers">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Contact</span></p>
      <h1>TALK TO A REAL <span class="accent">PERSON.</span></h1>
      <p class="hero-sub">Questions about a stay, an activity, a group or the road in? Write to us or call — the people answering are the same people who'll welcome you at the lodge.</p>
    </div>
  </div>
</section>

<section class="section" id="contact-form">
  <div class="container group-layout">
    <div class="group-intro contact-details">
      <h2>GET IN TOUCH</h2>
      <p>The fastest way to reach us is WhatsApp. For anything longer — trip planning, special requests, partnerships — send us a message and we'll reply within 1–2 business days.</p>
      <ul>
        <li><?php echo rafiki_icon( 'phone' ); ?><span><strong>Phone / WhatsApp</strong><a href="tel:+50683689944">+506 8368 9944</a><a href="tel:+50684196832">+506 8419 6832</a> <em>(alternative)</em></span></li>
        <li><?php echo rafiki_icon( 'mail' ); ?><span><strong>Email</strong><a href="mailto:rafikireservations@gmail.com">rafikireservations@gmail.com</a></span></li>
        <li><?php echo rafiki_icon( 'pin' ); ?><span><strong>Address</strong>16 km from the Coastal Highway<br>Savegre River, Pérez Zeledón<br>Costa Rica</span></li>
      </ul>
      <div class="btn-group" style="margin-top:28px;">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I have a question about Rafiki Safari Lodge.' ) ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">WhatsApp Us</a>
        <a href="<?php echo esc_url( home_url( '/plan-your-trip/' ) ); ?>" class="btn btn-outline" style="border-color:var(--text-dark); color:var(--text-dark);">How to Get Here</a>
      </div>
    </div>

    <div>
      <?php if ( 'success' === $status ) : ?>
        <div class="form-notice is-success">Thank you — your message is in. We'll get back to you within 1–2 business days. For anything urgent, reach us on <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I just sent a message through the website.' ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>.</div>
      <?php elseif ( 'error' === $status ) : ?>
        <div class="form-notice is-error">Something went wrong sending your message. Please check the fields and try again, or reach us directly on <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I have a question about Rafiki Safari Lodge.' ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>.</div>
      <?php endif; ?>

      <form class="rafiki-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'rafiki_contact', 'rafiki_contact_nonce' ); ?>
        <input type="hidden" name="action" value="rafiki_contact">
        <div class="rafiki-form-hp" aria-hidden="true"><label for="contact_website">Website</label><input type="text" id="contact_website" name="contact_website" tabindex="-1" autocomplete="off"></div>

        <div class="rafiki-form-row rafiki-form-row-2">
          <div>
            <label for="contact_name">Full Name</label>
            <input type="text" id="contact_name" name="contact_name" required>
          </div>
          <div>
            <label for="contact_email">Email</label>
            <input type="email" id="contact_email" name="contact_email" required>
          </div>
        </div>

        <div class="rafiki-form-row rafiki-form-row-2">
          <div>
            <label for="contact_phone">Phone / WhatsApp</label>
            <input type="text" id="contact_phone" name="contact_phone">
          </div>
          <div>
            <label for="contact_topic">Topic</label>
            <select id="contact_topic" name="contact_topic">
              <option value="Stay / availability">Stay / availability</option>
              <option value="Activities">Activities</option>
              <option value="Packages">Packages</option>
              <option value="Getting here">Getting here</option>
              <option value="Partnerships / press">Partnerships / press</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>

        <div class="rafiki-form-row">
          <label for="contact_message">Message</label>
          <textarea id="contact_message" name="contact_message" placeholder="How can we help?" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </div>
</section>

<section class="contact-map" aria-label="Map to Rafiki Safari Lodge">
  <iframe src="https://www.google.com/maps?q=Rafiki+Safari+Lodge,+Savegre,+Costa+Rica&amp;z=11&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Rafiki Safari Lodge on Google Maps"></iframe>
</section>

<?php get_footer(); ?>
