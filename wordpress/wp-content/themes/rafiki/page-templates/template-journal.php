<?php
/**
 * Template Name: Rafiki Journal
 *
 * Landing page for the Journal — standard WordPress posts, styled as a
 * magazine grid instead of a generic blog list. Articles should educate,
 * position Loki/the team as experts, and pre-qualify the reader (someone
 * who finishes "Why Rafiki isn't a safari" and gets annoyed was never
 * going to be a good booking anyway).
 */
get_header();

$journal_query = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 24,
	'post_status'    => 'publish',
) );
?>

<section class="page-hero" style="min-height:40vh;">
  <div class="hero-media">
    <img src="<?php echo esc_url( rafiki_photo( 'place-wildlife-9' ) ); ?>" alt="Rainforest at Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span class="current">Rafiki Journal</span></p>
      <h1>THE RAFIKI <span class="accent">JOURNAL.</span></h1>
      <p class="hero-sub">Stories, guides and honest answers for families, groups and retreat leaders planning a trip to Rafiki.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( $journal_query->have_posts() ) : ?>
      <div class="journal-grid">
        <?php while ( $journal_query->have_posts() ) : $journal_query->the_post();
          $cats = get_the_category();
          $cat_name = $cats ? $cats[0]->name : 'Rafiki Journal';
        ?>
          <a href="<?php the_permalink(); ?>" class="journal-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'rafiki-card' ); ?>
            <?php else : ?>
              <div class="placeholder-photo"><span>Placeholder — add a featured image for this article</span></div>
            <?php endif; ?>
            <div class="journal-card-body">
              <span class="journal-card-cat"><?php echo esc_html( $cat_name ); ?></span>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p>No articles published yet.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <img src="<?php echo esc_url( rafiki_photo( 'activities-44' ) ); ?>" alt="Family exploring Rafiki Safari Lodge">
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>READY TO SEE IT FOR YOURSELF?</h2>
    <p>Reading about Rafiki is one thing. Let's plan your trip.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I was reading the Rafiki Journal and would like to plan a trip.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Plan Your Trip →</a>
  </div>
</section>

<?php get_footer(); ?>
