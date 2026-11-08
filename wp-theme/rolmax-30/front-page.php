<?php
defined('ABSPATH') || exit;
get_header();
$cats  = rx_categories();
$svcs  = rx_services();
$total = (int) wp_count_posts('maszyna')->publish;
$hero  = get_theme_mod('rx_hero_video');
$band  = get_theme_mod('rx_band_video') ?: $hero;
$svcv  = get_theme_mod('rx_service_video') ?: $hero;
$video = function ($src, $start = 0) {
    if (!$src) return;
    $type = str_ends_with(strtolower($src), '.webm') ? 'video/webm' : 'video/mp4';
    printf('<video autoplay muted loop playsinline preload="metadata" %s><source src="%s" type="%s"></video>',
        $start ? 'data-start="' . (int) $start . '"' : '', esc_url($src), $type);
};
?>
<section class="hero">
  <?php $video($hero); ?>
  <div class="wrap">
    <div class="reveal">
      <div class="eyebrow">Autoryzowany Dealer · Lubelszczyzna</div>
      <h1 class="display" style="margin-top:18px"><span class="chrome-sheen"><?php echo esc_html(get_theme_mod('rx_hero_title', 'Klasa premium')); ?></span><span class="nh chrome">New Holland</span></h1>
      <p><?php echo esc_html(get_theme_mod('rx_hero_text')); ?></p>
      <div class="actions"><a class="btn btn-chrome" href="<?php echo esc_url(get_post_type_archive_link('maszyna')); ?>">Poznaj ofertę</a>
        <a class="btn btn-ghost" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'uslugi/serwis')); ?>">Serwis 24/7 w żniwa</a></div>
    </div>
    <a href="#jubileusz" class="anniv chrome-frame reveal"><div class="num chrome-sheen">30</div><div class="lbl chrome">lat Rolmax</div><div class="yrs">1997 — 2027</div></a>
  </div>
</section>

<nav class="catstrip" aria-label="Kategorie maszyn"><div class="wrap">
  <?php foreach ($cats as $c): $first = rx_machines($c->term_id, 1); ?>
    <a href="<?php echo esc_url(get_term_link($c)); ?>"><?php if ($first) echo rx_img($first[0], 'rx-tile'); ?><span><?php echo esc_html($c->name); ?></span></a>
  <?php endforeach; ?>
</div></nav>

<section class="sec bg-steel" id="uslugi"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Najważniejsze usługi</div><h2 class="display h2 chrome" style="margin-top:14px">Wszystko dla Twojej maszyny</h2></div>
    <p class="lead">Sprzedaż, serwis, części i finansowanie — kompletna obsługa w czterech oddziałach i online.</p></div>
  <div class="svc-grid">
    <?php foreach ($svcs as $i => $s): ?>
      <a class="svc chrome-frame reveal <?php echo $i < 2 ? 'wide' : ''; ?>" href="<?php echo esc_url(get_permalink($s)); ?>">
        <?php echo get_the_post_thumbnail($s, 'large', ['loading' => 'lazy']); ?>
        <div class="body"><span class="tag"><?php echo esc_html(get_post_meta($s->ID, 'rx_tag', true)); ?></span>
          <h3 class="chrome"><?php echo esc_html(get_the_title($s)); ?></h3><p><?php echo esc_html(get_the_excerpt($s)); ?></p><span class="more">Szczegóły</span></div></a>
    <?php endforeach; ?>
    <a class="svc banner chrome-frame reveal" href="https://www.rolmax-sklep.pl/" style="background:linear-gradient(110deg,#1550b3,#061430 70%)">
      <div class="body"><span class="tag">Online 24h</span><h3 class="chrome">Sklep internetowy</h3><p>Tysiące części i akcesoriów z wysyłką — rolmax-sklep.pl</p><span class="more">Przejdź do sklepu</span></div></a>
  </div>
</div></section>

<section class="sec jubilee" id="jubileusz"><div class="wrap jub-grid">
  <div class="reveal"><div class="big30 chrome-sheen">30</div><div class="big30-sub chrome">Lat · 1997 — 2027</div></div>
  <div class="reveal">
    <div class="eyebrow">Jubileusz 2027</div>
    <h2 class="display h2 chrome" style="margin:14px 0 18px">30 lat z rolnikami Lubelszczyzny</h2>
    <p class="lead"><?php echo esc_html(get_theme_mod('rx_jubilee_text')); ?></p>
    <div class="stats">
      <div class="stat"><b class="chrome" data-count="30">30</b><span>lat doświadczenia</span></div>
      <div class="stat"><b class="chrome" data-count="4">4</b><span>oddziały w woj. lubelskim</span></div>
      <div class="stat"><b class="chrome" data-count="<?php echo $total; ?>"><?php echo $total; ?></b><span>modeli New Holland w ofercie</span></div>
      <div class="stat"><b class="chrome">24/7</b><span>serwis w sezonie żniwnym</span></div>
    </div>
  </div>
</div></section>

<section class="vband" aria-label="New Holland — marka premium"><?php $video($band, 8); ?>
  <div class="wrap reveal">
    <div class="eyebrow">New Holland Agriculture</div>
    <h2 class="display h2" style="margin-top:16px"><span class="chrome-sheen">Technologia.</span><br><span class="chrome">Prestiż. Niezawodność.</span></h2>
    <p class="lead" style="margin-top:20px">New Holland to marka premium, wybierana przez najbardziej wymagające gospodarstwa w Europie — teraz z lokalnym wsparciem Rolmax.</p>
  </div>
</section>

<section class="sec bg-steel"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Oferta maszyn</div><h2 class="display h2 chrome" style="margin-top:14px">Wybierz kategorię</h2></div></div>
  <div class="explorer reveal">
    <div class="mega-side"><?php foreach ($cats as $i => $c): ?><button data-c="<?php echo esc_attr($c->slug); ?>" class="<?php echo $i ? '' : 'on'; ?>"><?php echo esc_html($c->name); ?><small><?php echo (int) $c->count; ?></small></button><?php endforeach; ?></div>
    <div class="mega-main"><?php foreach ($cats as $i => $c): ?>
      <div data-pane="<?php echo esc_attr($c->slug); ?>" <?php echo $i ? 'hidden' : ''; ?>>
        <div class="mega-head"><h3><?php echo esc_html($c->name); ?></h3><a href="<?php echo esc_url(get_term_link($c)); ?>">Zobacz wszystkie →</a></div>
        <div class="mega-grid"><?php foreach (rx_machines($c->term_id) as $m): ?><a class="tile" href="<?php echo esc_url(get_permalink($m)); ?>"><?php echo rx_badge($m) . rx_img($m); ?><span><?php echo esc_html(get_the_title($m)); ?></span></a><?php endforeach; ?></div>
      </div>
    <?php endforeach; ?></div>
  </div>
</div></section>

<section class="vband" style="min-height:60vh"><?php $video($svcv, 3); ?>
  <div class="wrap reveal"><div class="eyebrow">Serwis Rolmax</div>
    <h2 class="display h2" style="margin-top:16px"><span class="chrome-sheen">W żniwa</span> <span class="chrome">jesteśmy 24/7</span></h2>
    <p class="lead" style="margin:18px 0 28px">W sezonie żniwnym nasz serwis pracuje całą dobę.</p>
    <a class="btn btn-chrome" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'kontakt/support')); ?>">Zgłoś serwis</a></div>
</section>

<?php $nowe = get_posts(['post_type' => 'maszyna', 'numberposts' => 20, 'meta_key' => 'rx_badge', 'meta_compare' => '!=', 'meta_value' => '']); if ($nowe): ?>
<section class="sec bg-steel" id="nowosci"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Nowości New Holland</div><h2 class="display h2 chrome" style="margin-top:14px">Najnowsze maszyny w ofercie</h2></div></div>
  <div class="rail reveal"><?php foreach ($nowe as $m): ?>
    <a class="rcard" href="<?php echo esc_url(get_permalink($m)); ?>"><div class="rimg"><?php echo get_the_post_thumbnail($m, 'large', ['loading' => 'lazy']) . rx_badge($m); ?></div>
      <b><?php echo esc_html(get_the_title($m)); ?></b><small><?php echo esc_html(get_post_meta($m->ID, 'rx_power', true)); ?></small></a>
  <?php endforeach; ?></div>
</div></section>
<?php endif; ?>

<?php if ($promos = rx_promos()): ?>
<section class="promo-strip" aria-label="Oferty specjalne"><div class="wrap promo-grid">
  <?php foreach (array_slice($promos, 0, 4) as $p): ?>
    <a class="promo chrome-frame reveal" href="<?php echo esc_url(get_post_meta($p->ID, 'rx_link', true) ?: get_permalink($p)); ?>"><span class="tag"><?php echo esc_html(get_post_meta($p->ID, 'rx_tag', true)); ?></span><b class="chrome"><?php echo esc_html(get_the_title($p)); ?></b><small><?php echo esc_html(get_the_excerpt($p)); ?></small></a>
  <?php endforeach; ?>
</div></section>
<?php endif; ?>

<section class="sec" id="blog-home" style="background:var(--navy-950)"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Blog Rolmax</div><h2 class="display h2 chrome" style="margin-top:14px">Wiedza z pola i serwisu</h2></div>
    <a class="btn btn-ghost" href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>">Wszystkie artykuły</a></div>
  <div class="news-grid"><?php foreach (rx_blog_posts(3) as $post): setup_postdata($GLOBALS['post'] = $post); get_template_part('template-parts/blog-card'); endforeach; wp_reset_postdata(); ?></div>
</div></section>

<section class="sec" id="aktualnosci" style="background:var(--navy-950)"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Aktualności</div><h2 class="display h2 chrome" style="margin-top:14px">Co nowego</h2></div>
    <a class="btn btn-ghost" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'aktualnosci')); ?>">Wszystkie wpisy</a></div>
  <div class="news-grid"><?php foreach (get_posts(['numberposts' => 3]) as $n): ?>
    <article class="news chrome-frame reveal"><time datetime="<?php echo esc_attr(get_the_date('c', $n)); ?>"><?php echo esc_html(get_the_date('d.m.Y', $n)); ?></time>
      <h3 class="chrome"><?php echo esc_html(get_the_title($n)); ?></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt($n), 26)); ?></p>
      <a class="more" href="<?php echo esc_url(get_permalink($n)); ?>">Czytaj więcej →</a></article>
  <?php endforeach; ?></div>
</div></section>

<section class="sec bg-steel" id="galeria"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Galeria</div><h2 class="display h2 chrome" style="margin-top:14px">Rolmax w terenie</h2></div>
    <a class="btn btn-ghost" href="<?php echo esc_url(get_post_type_archive_link('galeria')); ?>">Wszystkie galerie</a></div>
  <div class="gal reveal"><?php foreach (get_posts(['post_type' => 'galeria', 'numberposts' => 5]) as $g): ?>
    <figure><a href="<?php echo esc_url(get_permalink($g)); ?>"><?php echo get_the_post_thumbnail($g, 'large', ['loading' => 'lazy']); ?><figcaption><?php echo esc_html(get_the_title($g)); ?></figcaption></a></figure>
  <?php endforeach; ?></div>
</div></section>

<?php get_template_part('template-parts/branches'); ?>
<?php get_footer(); ?>
