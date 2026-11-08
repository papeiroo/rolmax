<?php defined('ABSPATH') || exit; get_header(); $cur = is_tax('temat') ? get_queried_object() : null; ?>
<section class="page-hero"><div class="wrap">
  <div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i><a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>">Blog</a><?php if ($cur) echo '<i>/</i>' . esc_html($cur->name); ?></div>
  <h1 class="display h2 chrome-sheen"><?php echo $cur ? esc_html($cur->name) : 'Blog Rolmax'; ?></h1>
  <p class="lead" style="margin-top:14px">Nowości New Holland, poradniki serwisowe i finansowanie — dla rolników z Lubelszczyzny.</p>
  <div class="filters"><a class="chip <?php echo $cur ? '' : 'on'; ?>" href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>">Wszystkie</a>
    <?php foreach (get_terms(['taxonomy' => 'temat', 'hide_empty' => true]) as $t) printf('<a class="chip %s" href="%s">%s</a>', $cur && $cur->term_id === $t->term_id ? 'on' : '', esc_url(get_term_link($t)), esc_html($t->name)); ?>
  </div>
</div></section>
<section class="sec" style="padding-top:50px"><div class="wrap">
  <div class="news-grid"><?php while (have_posts()): the_post(); get_template_part('template-parts/blog-card'); endwhile; ?></div>
  <div style="margin-top:40px"><?php the_posts_pagination(); ?></div>
</div></section>
<?php get_footer();
