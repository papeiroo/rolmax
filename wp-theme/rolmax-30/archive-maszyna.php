<?php defined('ABSPATH') || exit; get_header(); $cats = rx_categories(); ?>
<section class="page-hero"><div class="wrap"><div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i>Oferta</div>
<h1 class="display h2 chrome-sheen">Oferta New Holland</h1></div></section>
<section class="sec" style="padding-top:60px"><div class="wrap"><div class="explorer">
  <div class="mega-side"><?php foreach ($cats as $i => $c): ?><button data-c="<?php echo esc_attr($c->slug); ?>" class="<?php echo $i ? '' : 'on'; ?>"><?php echo esc_html($c->name); ?><small><?php echo (int) $c->count; ?></small></button><?php endforeach; ?></div>
  <div class="mega-main"><?php foreach ($cats as $i => $c): ?><div data-pane="<?php echo esc_attr($c->slug); ?>" <?php echo $i ? 'hidden' : ''; ?>>
    <div class="mega-head"><h3><?php echo esc_html($c->name); ?></h3><a href="<?php echo esc_url(get_term_link($c)); ?>">Zobacz wszystkie →</a></div>
    <div class="mega-grid"><?php foreach (rx_machines($c->term_id) as $m): ?><a class="tile" href="<?php echo esc_url(get_permalink($m)); ?>"><?php echo rx_badge($m) . rx_img($m); ?><span><?php echo esc_html(get_the_title($m)); ?></span></a><?php endforeach; ?></div>
  </div><?php endforeach; ?></div>
</div></div></section>
<?php get_footer();
