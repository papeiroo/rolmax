<?php defined('ABSPATH') || exit; $cats = rx_categories(); $svcs = rx_services(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="topbar"><div class="wrap">
  <nav class="langs" aria-label="Język"><?php
    if (function_exists('pll_the_languages')) {
        foreach (pll_the_languages(['raw' => 1]) as $l) printf('<a class="%s" href="%s" hreflang="%s">%s</a>', $l['current_lang'] ? 'on' : '', esc_url($l['url']), esc_attr($l['slug']), esc_html(strtoupper($l['slug'])));
    } else { echo '<a class="on" href="' . esc_url(home_url('/pl')) . '">PL</a>'; }
  ?></nav>
  <span class="jubilee-chip">30 LAT · 1997–2027</span>
  <div class="contact"><a href="tel:+48817216785">+48 81 721 67 85</a><a href="mailto:rolmax@rolmax.net.pl">rolmax@rolmax.net.pl</a></div>
</div></div>

<header class="site-header" id="top"><div class="wrap">
  <a class="logo" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>" aria-label="Rolmax — strona główna">
    <?php if (has_custom_logo()) { echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'logo-img']); }
          else { ?><img class="logo-img" src="<?php echo esc_url(get_theme_file_uri('assets/logo-rolmax.png')); ?>" alt="GK Rolmax" width="904" height="211"><?php } ?>
  </a>
  <ul class="nav" id="nav">

    <li data-mega="oferta"><button aria-expanded="false">Oferta <svg class="caret" viewBox="0 0 10 10"><path d="M1 3l4 4 4-4" stroke="currentColor" fill="none" stroke-width="1.6"/></svg></button>
      <div class="mega"><div class="mega-inner">
        <div class="mega-side"><?php foreach ($cats as $i => $c): ?>
          <button data-c="<?php echo esc_attr($c->slug); ?>" class="<?php echo $i ? '' : 'on'; ?>"><?php echo esc_html($c->name); ?><small><?php echo (int) $c->count; ?></small></button>
        <?php endforeach; ?></div>
        <div class="mega-main"><?php foreach ($cats as $i => $c): ?>
          <div class="mega-pane" data-pane="<?php echo esc_attr($c->slug); ?>" <?php echo $i ? 'hidden' : ''; ?>>
            <div class="mega-head"><h3><?php echo esc_html($c->name); ?></h3><a href="<?php echo esc_url(get_term_link($c)); ?>">Zobacz wszystkie →</a></div>
            <div class="mega-grid"><?php foreach (rx_machines($c->term_id) as $m): ?>
              <a class="tile" href="<?php echo esc_url(get_permalink($m)); ?>"><?php echo rx_badge($m) . rx_img($m); ?><span><?php echo esc_html(get_the_title($m)); ?></span></a>
            <?php endforeach; ?></div>
          </div>
        <?php endforeach; ?></div>
      </div></div>
    </li>

    <li data-mega="uslugi"><button aria-expanded="false">Usługi <svg class="caret" viewBox="0 0 10 10"><path d="M1 3l4 4 4-4" stroke="currentColor" fill="none" stroke-width="1.6"/></svg></button>
      <div class="mega"><div class="mega-inner">
        <div class="mega-side"><?php foreach ($svcs as $i => $s): ?>
          <button data-c="<?php echo esc_attr($s->post_name); ?>" class="<?php echo $i ? '' : 'on'; ?>"><?php echo esc_html(get_the_title($s)); ?><small><?php echo esc_html(get_post_meta($s->ID, 'rx_tag', true)); ?></small></button>
        <?php endforeach; ?>
          <a class="mega-side-link" href="https://www.rolmax-sklep.pl/" style="display:block;padding:14px 26px;font-family:var(--ff-display);letter-spacing:.1em;text-transform:uppercase;color:#dfe9fb">Sklep internetowy ↗</a>
        </div>
        <div class="mega-main"><?php foreach ($svcs as $i => $s): $pts = array_filter(array_map('trim', explode("\n", (string) get_post_meta($s->ID, 'rx_points', true)))); ?>
          <div class="mega-pane svc-panel" data-pane="<?php echo esc_attr($s->post_name); ?>" <?php echo $i ? 'hidden' : ''; ?>>
            <?php echo get_the_post_thumbnail($s, 'large', ['loading' => 'lazy']); ?>
            <div><h3><?php echo esc_html(get_the_title($s)); ?></h3><p><?php echo esc_html(get_the_excerpt($s)); ?></p>
              <?php if ($pts): ?><ul><?php foreach ($pts as $p) echo '<li>' . esc_html($p) . '</li>'; ?></ul><?php endif; ?>
              <a class="btn btn-blue" href="<?php echo esc_url(get_permalink($s)); ?>">Przejdź do usługi</a></div>
          </div>
        <?php endforeach; ?></div>
      </div></div>
    </li>

<?php
    // Pozostałe pozycje z Wygląd → Menu (Sklep, Promocje, Blog, Aktualności, O nas, Galeria, Kontakt)
    wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'items_wrap' => '%3$s', 'depth' => 2, 'fallback_cb' => false]);
    ?>
    <li class="nav-cta"><a class="btn btn-chrome" style="height:auto;padding:11px 20px" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'kontakt')); ?>">Zapytaj o cenę</a></li>
  </ul>
</div></header>

<aside class="cta-dock" aria-label="Szybki kontakt">
  <a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'kontakt/order')); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17h2l2-8h10l2 8h2"/></svg>Zainteresowany zakupem? Kontakt</a>
  <a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'kontakt/support')); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a4 4 0 00-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 005.4-5.4l-2.5 2.5-2.5-2.5z"/></svg>Potrzebujesz serwisu? Formularz</a>
</aside>
<main id="app">
