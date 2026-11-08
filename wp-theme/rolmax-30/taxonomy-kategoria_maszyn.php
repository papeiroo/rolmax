<?php defined('ABSPATH') || exit; get_header(); $t = get_queried_object(); ?>
<section class="page-hero"><div class="wrap">
  <div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i><a href="<?php echo esc_url(get_post_type_archive_link('maszyna')); ?>">Oferta</a><i>/</i><?php echo esc_html($t->name); ?></div>
  <h1 class="display h2 chrome-sheen"><?php echo esc_html($t->name); ?></h1>
  <?php if ($t->description) echo '<p class="lead" style="margin-top:14px">' . esc_html($t->description) . '</p>'; ?>
</div></section>
<?php $children = get_terms(['taxonomy' => 'kategoria_maszyn', 'parent' => $t->term_id, 'hide_empty' => true]); ?>
<section class="sec" style="padding-top:60px"><div class="wrap">
  <?php if ($children): ?><div class="sec-head" style="margin-bottom:30px"><?php foreach ($children as $ch) printf('<a class="btn btn-ghost" href="%s">%s</a>', esc_url(get_term_link($ch)), esc_html($ch->name)); ?></div><?php endif; ?>
  <div class="cat-grid"><?php while (have_posts()): the_post(); ?>
    <a class="pcard reveal" href="<?php the_permalink(); ?>"><?php echo rx_badge(get_post()) . rx_img(get_post()); ?><b><?php the_title(); ?></b><small><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 18)); ?></small></a>
  <?php endwhile; ?></div>
</div></section>
<?php get_footer();
