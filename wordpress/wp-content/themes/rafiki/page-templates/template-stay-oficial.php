<?php
/**
 * Template Name: Stay - Oficial
 *
 * Preserved copy of the original /stay/ accommodation archive layout. Kept as
 * an assignable Page template after archive-accommodation.php switched to the
 * booking-first "Stay - Copia" layout. Nothing was deleted — assign this
 * template to a Page to view the original /stay/ layout.
 */
get_header(); ?>

<?php
/* Real amenities pulled from the Luxury Safari Tents accommodation post,
   so section 08 shows confirmed, admin-editable facts instead of guessed copy. */
$tent_post   = get_posts( array( 'post_type' => 'accommodation', 'posts_per_page' => 1, 'orderby' => 'menu_order date', 'order' => 'ASC', 'post_status' => 'publish' ) );
$tent_post   = $tent_post ? $tent_post[0] : null;
$tent_amenities = $tent_post ? rafiki_rows( $tent_post->ID, 'rafiki_amenities' ) : array();
?>

<!-- ===== 01. HERO ===== -->
<section class="page-hero">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'tents-19' ) ); ?>" alt="Safari tent at Rafiki Safari Lodge, surrounded by rainforest">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Stay</span></p>
      <span class="eyebrow">Stay at Rafiki</span>
      <h1>SLEEP IN THE FOREST.<br><span class="accent">WAKE UP WITH SOMEWHERE TO GO.</span></h1>
      <p class="hero-sub">Fourteen safari tents sit among the trees at Rafiki. You get a proper bed, private bathroom and your own porch — with the river valley, birds and forest just outside.</p>
      <p class="hero-sub">Stay two or three nights and use Rafiki as your base for the days you want to spend exploring this side of Costa Rica.</p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability</a>
        <a href="#tents" class="btn btn-outline">See the Safari Tents</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== 02. THIS ISN'T CAMPING ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Safari Tent Living</span>
    <h2 class="section-title center" style="margin-bottom:20px;">CLOSE TO NATURE DOESN'T HAVE TO MEAN SLEEPING ON THE GROUND.</h2>
    <p>The idea came from the safari camps Constant Boshoff knew in Africa. A canvas tent lets you hear more of what's happening outside. But inside, it still needs to feel good.</p>
    <p>Proper beds. Private bathrooms. Hot showers. Space for your things. A porch to sit on when you've had enough adventure for the day.</p>
    <p>It's simple in the right places and comfortable in the places that matter.</p>
  </div>

  <div class="container">
    <img src="<?php echo esc_url( rafiki_photo( 'tents-1' ) ); ?>" alt="Inside a safari tent at Rafiki Safari Lodge" style="width:100%; height:420px; object-fit:cover; border-radius:var(--radius); margin-top:16px;">
  </div>
</section>

<!-- ===== 03. THE BEST PART IS WHAT'S OUTSIDE ===== -->
<section class="section why-rafiki">
  <div class="why-media">
    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-3' ) ); ?>" alt="Forest view from a safari tent porch at Rafiki">
    <div class="why-media-text">
      <span class="eyebrow">Morning at Rafiki</span>
      <h2>YOU'LL PROBABLY HEAR THE FOREST<br><span class="accent">BEFORE YOU SEE IT.</span></h2>
      <p style="max-width:620px;">Some mornings start with birds around the lodge. Others with rain on the canvas. Someone heading toward breakfast. Kids already talking about the water slide. Or a quiet few minutes on the porch before everybody else wakes up.</p>
      <p style="max-width:620px;">There's no television competing with what's outside. There really doesn't need to be.</p>
    </div>
  </div>
</section>

<!-- ===== 04. 14 SAFARI TENTS ===== -->
<section class="section for-you">
  <div class="container">
    <span class="eyebrow center">Your Place in the Forest</span>
    <h2 class="section-title center">ENOUGH ROOM TO COME AS TWO.<br>ENOUGH TENTS TO BRING EVERYONE.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">Rafiki has 14 safari tents spread through the property. That means the lodge works just as naturally for a couple or family as it does when several families, friends or generations want to travel together. You're staying in the same place. But you still have your own space to disappear to at the end of the day.</p>

    <div class="for-you-grid cols-3">
      <div class="for-you-item">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>FOR COUPLES</h3>
        <p>A few days somewhere completely different from the beach hotels and towns already on your route. Adventure when you want it. Quiet when you don't.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'family' ); ?>
        <h3>FOR FAMILIES</h3>
        <p>Enough space for real family travel. Days that can include rafting, horses, hiking, swimming and the kind of downtime kids usually decide for themselves.</p>
      </div>
      <div class="for-you-item">
        <?php echo rafiki_icon( 'guide' ); ?>
        <h3>FOR GROUPS</h3>
        <p>Fourteen tents make it possible to bring more of your people without turning the trip into a logistical puzzle. Different tents. Different plans during the day. One place to meet again later.</p>
      </div>
    </div>

    <p style="text-align:center; margin-top:16px;">
      <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Planning a Group Stay?</a>
    </p>

    <div style="max-width:760px; margin:56px auto 0;">
      <h3 style="text-align:center; font-size:22px; text-transform:uppercase; margin-bottom:32px;">By Kind of Traveler</h3>
      <div class="faq-list">
        <details class="faq-item">
          <summary>My kids have already done ziplines, beaches and national parks. What's different here?</summary>
          <p>At Rafiki, the experience isn't one isolated activity. They wake up inside the forest, raft the river, come back to the same lodge, find the pool and water slide, see wildlife around where they're sleeping — then do something completely different the following day. The novelty comes from living inside it for several days, not checking off another tour.</p>
        </details>
        <details class="faq-item">
          <summary>What if one child is adventurous and the other isn't?</summary>
          <p>Don't build the whole trip around the most adventurous person — that's exactly why Rafiki works as a base. One experience can be shared, the next day can split. Everyone still returns to the same place.</p>
        </details>
        <details class="faq-item">
          <summary>Is Rafiki too family-oriented for a couple?</summary>
          <p>No. Families use Rafiki one way; couples use it completely differently — early birding, rafting together, horseback riding, massage, long afternoons by the pool, dinner and nowhere else you need to go afterward.</p>
        </details>
        <details class="faq-item">
          <summary>Do I have to be an "adventure traveler" to enjoy Rafiki?</summary>
          <p>No. You can come with someone who wants rafting and never get into a raft yourself — birding from breakfast, reading, the pool, a massage, a slower trail. Active people have plenty to do without making quieter travelers feel like they're doing the trip wrong.</p>
        </details>
      </div>
    </div>
  </div>
</section>

<!-- ===== 05. STAY LONG ENOUGH TO STOP RUSHING ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <span class="eyebrow center">How Many Nights?</span>
    <h2 class="section-title center">TWO NIGHTS WORK.<br>THREE NIGHTS FEEL BETTER.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px; line-height:1.75;">One night tells you what Rafiki looks like. Two nights give you time for a real adventure. Three nights let you have another one without feeling like you're already leaving.<br><br>For most road trips through Costa Rica, we recommend making room for two or three nights — long enough to unpack and do something memorable, short enough to fit naturally between the other places already on your itinerary.</p>

    <div class="plan-grid cols-2" style="margin-bottom:0;">
      <div class="plan-card">
        <?php echo rafiki_icon( 'tent' ); ?>
        <h3>Two Nights</h3>
        <p>Arrive from the coast. Settle in. Spend one full day on the river, horseback or exploring the forest. Wake up one more morning before continuing your trip.</p>
      </div>
      <div class="plan-card">
        <?php echo rafiki_icon( 'heart' ); ?>
        <h3>Three Nights</h3>
        <p>Arrive without rushing. Choose two different kinds of days. Leave room for the pool, birds, food, conversation and doing nothing for an afternoon. This is where staying at Rafiki starts to feel different from simply coming for an activity.</p>
      </div>
    </div>

    <p style="text-align:center; margin-top:40px;">
      <a href="<?php echo esc_url( home_url( '/#two-or-three-nights' ) ); ?>" class="btn btn-primary">See What 3 Nights Can Look Like</a>
    </p>
  </div>
</section>

<!-- ===== 06. YOUR TENT IS ONLY THE BASE ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center; max-width:820px;">
    <span class="eyebrow center">What Happens Between Nights</span>
    <h2 class="section-title center">YOUR TENT IS ONLY THE BASE.</h2>
    <p style="font-size:18px;">You don't come all this way just for the bed. Breakfast might lead to rafting. A horseback ride might end back at the lodge for lunch. A hike can take most of the day. Or you may decide that the water slide, pool and porch are enough for a while.</p>
    <p>That's why we think of Rafiki as all-inclusive nature. Not because every person has to follow the same schedule — because once you're here, there are several ways to experience the landscape without changing hotels every morning.</p>
    <p><a href="<?php echo esc_url( get_post_type_archive_link( 'activity' ) ); ?>" class="btn btn-primary">Explore Rafiki Experiences</a></p>
  </div>
</section>

<!-- ===== 07. A DAY CAN BE FULL WITHOUT FEELING BUSY ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <span class="eyebrow center">Life at the Lodge</span>
    <h2 class="section-title center">THERE'S TIME BETWEEN THE ADVENTURES TOO.</h2>

    <div class="itinerary" style="max-width:820px; margin:48px auto 0;">
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Breakfast</h3>
          <p>Coffee, breakfast and whatever has decided to visit the trees or bird feeders that morning.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Out for the Day</h3>
          <p>Raft. Ride. Hike. Look for birds. Follow the river.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Back at Rafiki</h3>
          <p>Lunch. Pool. Water slide. Massage. Porch. A drink. Someone telling a story about what happened on the river.</p>
        </div>
      </div>
      <div class="itinerary-step">
        <div class="itinerary-step-num"></div>
        <div class="itinerary-step-body">
          <h3>Dinner</h3>
          <p>Everyone eventually finds their way back to the table. And tomorrow doesn't have to look anything like today.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== 08. WHAT YOU'LL FIND IN YOUR SAFARI TENT ===== -->
<section class="section">
  <div class="container">
    <span class="eyebrow center">The Practical Part</span>
    <h2 class="section-title center">WILD OUTSIDE. COMFORTABLE INSIDE.</h2>
    <p style="text-align:center; max-width:680px; margin:-24px auto 48px; color:var(--text-dark-muted); font-size:16px;">Each Rafiki safari tent is designed to give you the feeling of sleeping in the forest without giving up the essentials you actually care about at the end of a full day.</p>

    <?php if ( $tent_amenities ) : ?>
      <div class="amenities-grid">
        <?php foreach ( $tent_amenities as $a ) : if ( empty( $a['title'] ) ) continue; ?>
          <div class="amenity-item">
            <?php echo rafiki_icon( $a['icon'] ); ?>
            <div><strong><?php echo esc_html( $a['title'] ); ?></strong><span><?php echo esc_html( $a['text'] ); ?></span></div>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center; margin-top:36px;">
        <a href="<?php echo esc_url( $tent_post ? get_permalink( $tent_post ) : get_post_type_archive_link( 'accommodation' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">See Full Tent Details →</a>
      </p>
    <?php endif; ?>

    <div style="max-width:760px; margin:56px auto 0;">
      <h3 style="text-align:center; font-size:22px; text-transform:uppercase; margin-bottom:32px;">Still Picturing It?</h3>
      <div class="faq-list">
        <details class="faq-item">
          <summary>We already have Manuel Antonio and Uvita on our route. Why add Rafiki?</summary>
          <p>Those places give you the Pacific side of Costa Rica. Rafiki changes the landscape completely — you leave the coast for a few days and wake up with the river, forest, horses and trails becoming part of where you're staying. You're not adding another version of the same stop. You're adding contrast to the trip.</p>
        </details>
        <details class="faq-item">
          <summary>Is this glamping?</summary>
          <p>We don't really think of it that way. The safari tents are comfortable — proper beds, private bathrooms, hot showers and a porch — but the point isn't a luxury room under canvas. It's letting you stay closer to the forest without giving up a good night's sleep.</p>
        </details>
        <details class="faq-item">
          <summary>Will we hear animals at night?</summary>
          <p>Probably. You're sleeping in tropical forest, not inside a sealed resort building. Rain, frogs, insects, birds and whatever else is moving outside become part of the soundtrack. For many guests, that's one of the things they remember most.</p>
        </details>
        <details class="faq-item">
          <summary>Is Rafiki too remote for a family?</summary>
          <p>Rafiki feels remote once you're there — that's different from being unsupported. You still have your tent, meals, guides, lodge team, pool and experiences organized around the property. The remoteness is what gives you the forest. The team is what makes that remoteness feel manageable.</p>
        </details>
        <details class="faq-item">
          <summary>Is it too rustic for someone who doesn't like camping?</summary>
          <p>If what you dislike about camping is sleeping on the ground, shared bathrooms and cooking dinner outside, Rafiki is a very different experience — fixed safari tents with real beds and private bathrooms.</p>
        </details>
        <details class="faq-item">
          <summary>Is there air conditioning?</summary>
          <p><em>[Placeholder — confirm current answer with Loki, and explain how the tent stays comfortable if there's no A/C.]</em></p>
        </details>
      </div>
    </div>
  </div>
</section>

<!-- ===== 09. WHAT RAFIKI DOESN'T TRY TO BE ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Good to Know</span>
    <h2 class="section-title center">THIS IS A LODGE IN THE FOREST.</h2>
    <p>That means you may hear rain at night. Birds in the morning. Insects outside. A river nearby. And occasionally something moving through the trees that makes everyone stop talking for a minute.</p>
    <p>That's part of the reason to come. Rafiki isn't trying to separate you from Costa Rica. It's trying to give you a comfortable way to stay in the middle of it.</p>
  </div>
</section>

<!-- ===== 10. FOOD IS PART OF THE RHYTHM ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Around the Table</span>
    <h2 class="section-title center">ADVENTURE MAKES PEOPLE HUNGRY.</h2>
    <p>Meals at Rafiki are simple, generous and made for the kind of days people have here. Breakfast before heading out. Lunch when you return. Dinner when everyone finally slows down again.</p>
    <p>The food doesn't need to compete with the landscape. It needs to make you want to sit down, eat well and stay at the table a little longer.</p>
    <p><a href="<?php echo esc_url( home_url( '/lekker-bar-braai/' ) ); ?>" class="btn btn-outline" style="border-color: var(--text-dark); color: var(--text-dark);">Food at Rafiki →</a></p>
  </div>
</section>

<!-- ===== 11. BRING YOUR PEOPLE ===== -->
<section class="cta-banner">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'property-and-food-14a' ) ); ?>" alt="Group gathered together at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Group Stays</span>
    <h2>YOU CAN TRAVEL TOGETHER WITHOUT SPENDING EVERY MINUTE TOGETHER.</h2>
    <p>With 14 safari tents, Rafiki gives families and groups something that's surprisingly difficult to find on a trip: room for everyone to come. Some people can raft. Some can ride. Some can stay behind with the pool. Someone can go looking for birds. Someone else can do absolutely nothing.</p>
    <p>Then everyone comes back to the same lodge at the end of the day. Same table. Different stories.</p>
    <a href="<?php echo esc_url( home_url( '/bring-your-group/' ) ); ?>" class="btn btn-primary">Plan a Group Stay →</a>
  </div>
</section>

<!-- ===== 12. FOREST FIRST. BEACH AFTER. ===== -->
<?php $beach_camp_post = rafiki_beach_camp_post(); ?>
<section class="cta-banner">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'beach-pool' ) ); ?>" alt="Rafiki Beach Camp near Playa Matapalo">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Lodge + Beach Camp</span>
    <h2>TWO SIDES OF COSTA RICA. ONE RAFIKI JOURNEY.</h2>
    <p>If your trip has a little more room, combine the rainforest experience at Rafiki Safari Lodge with Rafiki Beach Camp near Playa Matapalo. Start inland with the river, forest and adventure. Then continue toward the Pacific and slow everything down again.</p>
    <a href="<?php echo $beach_camp_post ? esc_url( get_permalink( $beach_camp_post ) ) : esc_url( home_url( '/#beach-camp' ) ); ?>" class="btn btn-primary">Explore Rafiki Beach Camp →</a>
  </div>
</section>

<!-- ===== 13. GETTING HERE IS PART OF THE ROAD TRIP ===== -->
<section class="section">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Where Rafiki Fits</span>
    <h2 class="section-title center">COME INLAND FOR A FEW DAYS.</h2>
    <p>Rafiki sits away from Costa Rica's main South Pacific coastal route. If Manuel Antonio, Dominical or Uvita is already part of your trip, we'll help you understand where Rafiki fits before you start driving.</p>
    <p>Tell us where you're coming from. We'll help with the route. And if you'd rather not drive yourself, ask us about transportation.</p>
    <p><a href="<?php echo esc_url( home_url( '/#getting-here' ) ); ?>" class="btn btn-primary">Getting to Rafiki</a></p>
  </div>
</section>

<!-- ===== 14. NOT SURE WHICH STAY MAKES SENSE? ===== -->
<section class="section" style="background:var(--cream-2);">
  <div class="container intro-block" style="text-align:center;">
    <span class="eyebrow center">Ask Us</span>
    <h2 class="section-title center">TELL US WHO'S COMING.</h2>
    <p>Couple? Young kids? Teenagers who want adventure? Grandparents coming too? Several families?</p>
    <p>Tell us your dates, who's traveling and what the rest of your Costa Rica itinerary looks like. We'll help you figure out which tent setup, number of nights and experiences make the most sense.</p>
    <p>There's a real person answering.</p>
    <p><a href="<?php echo esc_url( rafiki_whatsapp_link( "Hi! I'd like help planning my stay at Rafiki." ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Help Me Plan My Stay</a></p>
  </div>
</section>

<!-- ===== CHOOSE YOUR STAY (dynamic tent/accommodation grid) ===== -->
<section class="section experience" id="tents">
  <div class="container">
    <span class="eyebrow center">Choose Your Stay</span>
    <h2 class="section-title center">WHICH SETUP FITS YOUR TRIP?</h2>
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
        <p>No accommodations published yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section" style="padding:48px 0;">
  <div class="container intro-block" style="text-align:center; max-width:680px;">
    <p style="font-family:var(--font-head); font-size:24px; text-transform:uppercase; letter-spacing:0.4px; color:var(--text-dark);">What will we actually remember about Rafiki?</p>
    <p style="color:var(--text-dark-muted);">We can't decide that for you. But guests rarely talk only about the tent — they talk about the rafting, the staff, the food, the wildlife, the people they were traveling with, and the feeling of being somewhere very different for a few days.</p>
  </div>
</section>

<!-- ===== 15. FINAL CTA ===== -->
<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-34' ) ); ?>" alt="Group rafting in front of the lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <span class="eyebrow">Your Base for a Few Wild Days</span>
    <h2>UNPACK ONCE. SEE WHAT HAPPENS.</h2>
    <p>Stay for two or three nights and make Rafiki the part of your Costa Rica trip where the river, forest and adventure all start from the same place.</p>
    <div class="btn-group">
      <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I would like to check availability at Rafiki Safari Lodge.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Check Availability →</a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'package' ) ); ?>" class="btn btn-outline">Explore Packages</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
