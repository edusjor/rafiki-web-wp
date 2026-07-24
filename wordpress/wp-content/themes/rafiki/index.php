<?php get_header(); ?>

<section class="section">
  <div class="container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article style="margin-bottom:40px;">
        <h2 class="section-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div><?php the_excerpt(); ?></div>
      </article>
    <?php endwhile; else : ?>
      <p>No se encontró contenido.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
