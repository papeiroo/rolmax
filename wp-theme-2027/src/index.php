<?php
/** Wpisy, strony i archiwa WordPressa w stylu Rolmax. */
get_header(); ?>
<section class="page-hero rx-page-hero"><div class="wrap">
  <div class="eyebrow">Rolmax</div>
  <h1 class="display h2 chrome"><?php
    if ( is_singular() ) single_post_title();
    elseif ( is_search() ) printf( 'Szukaj: %s', esc_html( get_search_query() ) );
    elseif ( is_archive() ) the_archive_title();
    else echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
</div></section>
<div class="rx-page"><div class="wrap">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <?php if ( is_singular() ) : ?>
    <article <?php post_class( 'rx-article' ); ?>><?php the_content(); ?></article>
  <?php else : ?>
    <article <?php post_class( 'news chrome-frame rx-card' ); ?>><time><?php echo esc_html( get_the_date() ); ?></time><h3 class="chrome"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><?php the_excerpt(); ?><a class="more" href="<?php the_permalink(); ?>">Czytaj więcej →</a></article>
  <?php endif; ?>
<?php endwhile; the_posts_pagination(); else : ?>
  <p>Brak treści.</p>
<?php endif; ?>
</div></div>
<?php get_footer();
