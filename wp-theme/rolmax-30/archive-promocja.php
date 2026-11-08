<?php defined('ABSPATH') || exit; get_header(); ?>
<section class="page-hero"><div class="wrap"><div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i>Promocje</div>
<h1 class="display h2 chrome-sheen">Oferty specjalne</h1><p class="lead" style="margin-top:14px">Aktualne promocje Rolmax i New Holland w jednym miejscu.</p></div></section>
<section class="sec" style="padding-top:50px"><div class="wrap promo-grid">
<?php foreach (rx_promos() as $p): ?>
  <a class="promo chrome-frame" href="<?php echo esc_url(get_post_meta($p->ID, 'rx_link', true) ?: get_permalink($p)); ?>">
    <span class="tag"><?php echo esc_html(get_post_meta($p->ID, 'rx_tag', true)); ?></span><b class="chrome"><?php echo esc_html(get_the_title($p)); ?></b><small><?php echo esc_html(get_the_excerpt($p)); ?></small></a>
<?php endforeach; ?>
</div></section>
<?php get_footer();
