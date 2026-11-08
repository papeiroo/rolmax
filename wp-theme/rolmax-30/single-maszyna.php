<?php defined('ABSPATH') || exit; get_header(); the_post();
$terms = get_the_terms(get_the_ID(), 'kategoria_maszyn'); $cat = $terms ? $terms[0] : null;
$br = get_post_meta(get_the_ID(), 'rx_brochure', true); $man = get_post_meta(get_the_ID(), 'rx_manufacturer', true);
$vid = get_post_meta(get_the_ID(), 'rx_video', true);
$pw = get_post_meta(get_the_ID(), 'rx_power', true); $ask = get_post_meta(get_the_ID(), 'rx_status', true) === 'zapytaj';
$gal = array_filter(array_map('intval', explode(',', (string) get_post_meta(get_the_ID(), 'rx_gallery', true))));
?>
<section class="page-hero"><div class="wrap">
  <div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i><a href="<?php echo esc_url(get_post_type_archive_link('maszyna')); ?>">Oferta</a><?php if ($cat): ?><i>/</i><a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a><?php endif; ?><i>/</i><?php the_title(); ?></div>
  <div class="product">
    <div class="stage chrome-frame reveal"><?php the_post_thumbnail('large', ['alt' => 'New Holland ' . get_the_title()]); ?></div>
    <div class="reveal">
      <div class="eyebrow">New Holland<?php echo $cat ? ' · ' . esc_html($cat->name) : ''; ?></div>
      <h1 class="display chrome-sheen" style="margin-top:14px"><?php the_title(); ?></h1>
      <div class="desc"><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 45)); ?></div>
      <?php if ($pw): ?><p class="pw chrome"><?php echo esc_html($pw); ?></p><?php endif; ?>
      <?php if ($ask): ?><p class="ask">Dostępność tej maszyny potwierdzi doradca Rolmax.</p><?php endif; ?>
      <div class="actions">
        <a class="btn btn-chrome" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'kontakt/order')); ?>"><?php echo $ask ? 'Zapytaj o dostępność' : 'Zapytaj o cenę'; ?></a>
        <?php if ($br): ?><a class="btn btn-blue" href="<?php echo esc_url($br); ?>" target="_blank" rel="noopener">Pobierz broszurę</a><?php endif; ?>
        <a class="btn btn-ghost" href="https://agriculture.newholland.com/pl-pl/europe/uslugi-i-rozwiazania/konfigurator" target="_blank" rel="noopener">Konfigurator</a>
        <a class="btn btn-ghost" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'uslugi/finansowanie')); ?>">Finansowanie</a>
      </div>
      <?php if ($man): ?><p style="margin-top:14px"><a class="more-link" href="<?php echo esc_url($man); ?>" target="_blank" rel="noopener">Pełna specyfikacja na stronie New Holland ↗</a></p><?php endif; ?>
      <div>
      </div>
    </div>
  </div>
</div></section>
<?php if ($vid): ?><section class="vband" style="min-height:70vh"><video autoplay muted loop playsinline preload="metadata"><source src="<?php echo esc_url($vid); ?>"></video></section><?php endif; ?>
<section class="sec bg-steel"><div class="wrap" style="max-width:980px"><div class="entry"><?php the_content(); ?></div>
  <?php if (count($gal) > 1): ?><div class="gal" style="margin-top:40px"><?php foreach ($gal as $a) echo '<figure>' . wp_get_attachment_image($a, 'large') . '</figure>'; ?></div><?php endif; ?>
</div></section>
<?php if ($cat): $rel = array_filter(rx_machines($cat->term_id, 5), fn($p) => $p->ID !== get_the_ID()); ?>
<section class="sec" style="padding-top:70px"><div class="wrap">
  <div class="sec-head"><h2 class="display chrome" style="font-size:2.4rem">Inne modele — <?php echo esc_html($cat->name); ?></h2><a class="btn btn-ghost" href="<?php echo esc_url(get_term_link($cat)); ?>">Wszystkie</a></div>
  <div class="cat-grid"><?php foreach (array_slice($rel, 0, 4) as $m): ?><a class="pcard" href="<?php echo esc_url(get_permalink($m)); ?>"><?php echo rx_img($m); ?><b><?php echo esc_html(get_the_title($m)); ?></b></a><?php endforeach; ?></div>
</div></section>
<?php endif; get_footer();
