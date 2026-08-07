<?php
/**
 * Single Rafiki Journal article. Standard WordPress post — styled to
 * match the rest of the site instead of falling back to the bare index.php
 * loop.
 */
get_header();

while ( have_posts() ) : the_post();
	$hero_img = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
	$cats     = get_the_category();
	$cat_name = $cats ? $cats[0]->name : 'Rafiki Journal';
?>

<section class="page-hero">
  <div class="hero-media">
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php the_title_attribute(); ?>"><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container page-hero-content">
    <div class="page-hero-content-inner">
      <p class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>
        <a href="<?php echo esc_url( home_url( '/rafiki-journal/' ) ); ?>">Rafiki Journal</a><span class="sep">/</span>
        <span class="current"><?php the_title(); ?></span>
      </p>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
</section>

<section class="section">
  <div class="container journal-article">
    <span class="journal-article-meta"><?php echo esc_html( $cat_name ); ?> · <?php echo esc_html( get_the_date() ); ?></span>
    <div class="journal-article-body">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<section class="cta-banner" id="book">
  <div class="cta-media">
    <?php if ( $hero_img ) : ?><img src="<?php echo esc_url( $hero_img ); ?>" alt=""><?php endif; ?>
    <div class="hero-overlay"></div>
  </div>
  <div class="container cta-content">
    <h2>READY TO SEE IT FOR YOURSELF?</h2>
    <p>Reading about Rafiki is one thing. Let's plan your trip.</p>
    <a href="<?php echo esc_url( rafiki_whatsapp_link( 'Hi! I was reading ' . get_the_title() . ' on the Rafiki Journal and would like to plan a trip.' ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Plan Your Trip →</a>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
