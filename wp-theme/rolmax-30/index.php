<?php defined('ABSPATH') || exit; get_header(); ?>
<section class="page-hero"><div class="wrap"><h1 class="display h2 chrome-sheen"><?php echo is_singular() ? esc_html(get_the_title()) : wp_kses_post(get_the_archive_title() ?: 'Aktualności'); ?></h1></div></section>
<section class="sec" style="padding-top:60px"><div class="wrap" style="max-width:<?php echo is_singular() ? '980px' : 'var(--maxw)'; ?>">
<?php if (is_singular()): the_post(); ?>
  <?php if (has_post_thumbnail()) the_post_thumbnail('large', ['style' => 'border-radius:18px;margin-bottom:30px']); ?>
  <div class="entry"><?php the_content(); ?></div>
<?php else: ?>
  <div class="news-grid"><?php while (have_posts()): the_post(); ?>
    <article class="news chrome-frame"><time><?php echo esc_html(get_the_date('d.m.Y')); ?></time><h3 class="chrome"><?php the_title(); ?></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 26)); ?></p><a class="more" href="<?php the_permalink(); ?>">Czytaj więcej →</a></article>
  <?php endwhile; ?></div>
  <div style="margin-top:40px"><?php the_posts_pagination(); ?></div>
<?php endif; ?>
</div></section>
<?php get_footer();
