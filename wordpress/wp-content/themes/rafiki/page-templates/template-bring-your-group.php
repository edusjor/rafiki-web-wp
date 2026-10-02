<?php
/**
 * Template Name: Bring Your Group
 *
 * For family reunions, retreats, workshops, celebrations and groups of
 * friends. This is Rafiki's #1 stated business priority, so it gets its
 * own page and its own inquiry form instead of hiding behind a generic
 * "Contact Us" link.
 */
get_header();

$status = isset( $_GET['group_inquiry'] ) ? sanitize_key( $_GET['group_inquiry'] ) : '';
?>

<section class="page-hero" style="min-height:46vh;">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-14a' ) ); ?>" alt="Group gathered at a long table at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Bring Your Group</span></p>
      <h1>BRING YOUR <span class="accent">PEOPLE.</span></h1>
      <p class="hero-sub">Family reunions. Retreats. Workshops. Celebrations. These are private experiences — the entire lodge is reserved exclusively for your group, with no other guests on the property.</p>
    </div>
  </div>
</section>

<section class="section" id="group-form">
  <div class="container group-layout">
    <div class="group-intro">
      <h2>A PLACE THAT HOLDS SPACE FOR YOUR PEOPLE</h2>
      <p>Group stays at Rafiki are private, whole-property experiences. When your group books, the entire lodge is reserved for you — every room, every gathering area and every activity — with no other guests sharing the property.</p>
      <p>Most venues can host a group. Very few can hold one — give it room to actually connect, away from schedules, WiFi pressure and hotel noise.</p>
      <p>Whether you're reuniting three generations of family, leading a retreat, running a workshop, or bringing a group of friends who haven't traveled together in years, we build the stay around your group, not the other way around.</p>
      <ul>
        <li><?php echo rafiki_icon( 'leaf' ); ?><span>Exclusive use of the whole lodge — the property is yours alone for the length of your stay.</span></li>
        <li><?php echo rafiki_icon( 'family' ); ?><span>Space for large families and multi-generational groups, all on one property.</span></li>
        <li><?php echo rafiki_icon( 'leaf' ); ?><span>Open-air gathering areas built for circles, workshops and shared meals — not banquet halls.</span></li>
        <li><?php echo rafiki_icon( 'guide' ); ?><span>One point of contact to help you plan activities, meals and logistics for the whole group.</span></li>
        <li><?php echo rafiki_icon( 'heart' ); ?><span>25+ years hosting groups who come back year after year.</span></li>
      </ul>
    </div>

    <div>
      <?php if ( 'success' === $status ) : ?>
        <div class="form-notice is-success">Thank you — your inquiry is in. Our team will follow up within 1–2 business days. For anything urgent, reach us on <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I just sent a group inquiry through the website.' ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>.</div>
      <?php elseif ( 'error' === $status ) : ?>
        <div class="form-notice is-error">Something went wrong sending your inquiry. Please try again, or reach us directly on <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to ask about bringing a group to Rafiki.' ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>.</div>
      <?php endif; ?>

      <form class="rafiki-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'rafiki_group_inquiry', 'rafiki_group_nonce' ); ?>
        <input type="hidden" name="action" value="rafiki_group_inquiry">

        <div class="rafiki-form-row rafiki-form-row-2">
          <div>
            <label for="group_name">Full Name</label>
            <input type="text" id="group_name" name="group_name" required>
          </div>
          <div>
            <label for="group_email">Email</label>
            <input type="email" id="group_email" name="group_email" required>
          </div>
        </div>

        <div class="rafiki-form-row rafiki-form-row-2">
          <div>
            <label for="group_phone">Phone / WhatsApp</label>
            <input type="text" id="group_phone" name="group_phone">
          </div>
          <div>
            <label for="group_size">Group Size</label>
            <input type="text" id="group_size" name="group_size" placeholder="e.g. 14 people" required>
          </div>
        </div>

        <div class="rafiki-form-row rafiki-form-row-2">
          <div>
            <label for="group_type">Type of Group</label>
            <select id="group_type" name="group_type">
              <option value="Family reunion">Family reunion</option>
              <option value="Retreat / wellness group">Retreat / wellness group</option>
              <option value="Workshop / corporate group">Workshop / corporate group</option>
              <option value="Celebration (wedding, anniversary, etc.)">Celebration (wedding, anniversary, etc.)</option>
              <option value="Group of friends">Group of friends</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div>
            <label for="group_dates">Preferred Dates</label>
            <input type="text" id="group_dates" name="group_dates" placeholder="e.g. mid-March 2027, 4 nights">
          </div>
        </div>

        <div class="rafiki-form-row">
          <label for="group_message">Tell us about your group</label>
          <textarea id="group_message" name="group_message" placeholder="What are you hoping this time together looks like?"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Send Inquiry</button>
      </form>
    </div>
  </div>
</section>

<section class="section" style="background:var(--cream-2);">
  <div class="container" style="max-width:760px;">
    <h3 style="text-align:center; font-size:22px; text-transform:uppercase; margin-bottom:32px;">Before You Send the Inquiry</h3>
    <div class="faq-list">
      <details class="faq-item">
        <summary>Will other guests be at the lodge during our stay?</summary>
        <p>No. Group bookings are private buyouts — the entire property is reserved exclusively for your group. The rooms, the open-air gathering areas and the activities are all yours for the duration of your stay.</p>
      </details>
      <details class="faq-item">
        <summary>Will a group trip to Rafiki feel like a school field trip?</summary>
        <p>Not if we design it properly. One big shared experience, enough freedom afterward, different options for different people, dinner back together. The itinerary should create common moments, not keep everyone attached to each other all day.</p>
      </details>
      <details class="faq-item">
        <summary>What if our group has very different ages?</summary>
        <p>That's actually one of the strongest reasons to use Rafiki as the base. Age differences matter less when the entire group doesn't need to choose one activity every day.</p>
      </details>
      <details class="faq-item">
        <summary>Can Rafiki help me organize this without me becoming the group's full-time travel agent?</summary>
        <p>Yes. Start by giving us the people, not the itinerary — adults, kids and ages, couples/families, dates, what people enjoy, where you're coming from and where you're heading. Rafiki helps structure the stay from there.</p>
      </details>
    </div>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Group activity at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>PREFER TO TALK IT THROUGH FIRST?</h2>
    <p>Message us directly and we'll help you figure out what fits your group.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to ask about bringing a group to Rafiki.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Chat With Us →</a>
  </div>
</section>

<?php get_footer(); ?>
