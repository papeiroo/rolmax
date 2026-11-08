<?php defined('ABSPATH') || exit; get_header(); the_post();
$terms = get_the_terms(get_the_ID(), 'temat');
$faq = array_filter(array_map(fn($l) => array_map('trim', explode('|', $l, 2)), explode("\n", (string) get_post_meta(get_the_ID(), 'rx_faq', true))), fn($r) => count($r) === 2 && $r[0]);
$related = array_filter(array_map(fn($s) => get_page_by_path(trim($s), OBJECT, 'maszyna'), explode(',', (string) get_post_meta(get_the_ID(), 'rx_related', true))));
$src = get_post_meta(get_the_ID(), 'rx_source', true);
// spis treści z nagłówków H2
$content = apply_filters('the_content', get_the_content()); $toc = []; $i = 0;
$content = preg_replace_callback('#<h2([^>]*)>(.*?)</h2>#s', function ($m) use (&$toc, &$i) { $id = 's' . $i++; $toc[$id] = wp_strip_all_tags($m[2]); return "<h2 id=\"$id\"{$m[1]}>{$m[2]}</h2>"; }, $content);
$share = rawurlencode(get_permalink());
?>
<section class="page-hero post-hero"><div class="wrap">
  <div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i><a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>">Blog</a><?php if ($terms) echo '<i>/</i><a href="' . esc_url(get_term_link($terms[0])) . '">' . esc_html($terms[0]->name) . '</a>'; ?></div>
  <h1 class="display chrome-sheen" style="font-size:clamp(2.2rem,4.6vw,4rem);max-width:22ch"><?php the_title(); ?></h1>
  <p class="lead" style="margin-top:16px"><?php echo esc_html(get_the_date('d.m.Y')); ?> · <?php the_author(); ?> · <?php echo rx_reading_time(get_post()); ?> min czytania</p>
</div></section>
<section class="sec" style="padding-top:40px"><div class="wrap post-grid">
  <article class="entry post">
    <?php the_post_thumbnail('large', ['class' => 'post-img', 'alt' => get_the_title()]); ?>
    <?php echo $content; ?>
    <?php if ($faq): ?><h2>Najczęstsze pytania</h2><?php foreach ($faq as [$q, $a]) printf('<details class="faq"><summary>%s</summary><p>%s</p></details>', esc_html($q), esc_html($a)); endif; ?>
    <?php if ($src): ?><p class="src">Źródło informacji o produktach: <a href="<?php echo esc_url($src); ?>" target="_blank" rel="noopener">New Holland Polska ↗</a></p><?php endif; ?>
    <div class="share"><span>Udostępnij:</span>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share; ?>" target="_blank" rel="noopener">Facebook</a>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share; ?>" target="_blank" rel="noopener">LinkedIn</a>
      <a href="https://wa.me/?text=<?php echo $share; ?>" target="_blank" rel="noopener">WhatsApp</a></div>
  </article>
  <aside class="post-side">
    <?php if ($toc): ?><div class="dept chrome-frame"><h4 class="chrome">W artykule</h4><?php foreach ($toc as $id => $t) printf('<a class="toc" href="#%s">%s</a>', esc_attr($id), esc_html($t)); ?></div><?php endif; ?>
    <?php if ($related): ?><div class="dept chrome-frame" style="margin-top:16px"><h4 class="chrome">Maszyny z artykułu</h4>
      <?php foreach ($related as $m) printf('<a class="side-m" href="%s">%s<span>%s</span></a>', esc_url(get_permalink($m)), get_the_post_thumbnail($m, 'thumbnail'), esc_html(get_the_title($m))); ?></div><?php endif; ?>
    <div class="dept chrome-frame cta-box" style="margin-top:16px"><h4 class="chrome">Masz pytanie?</h4><p>Doradca Rolmax odpowie i przygotuje ofertę z finansowaniem.</p>
      <a class="btn btn-chrome" href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX . 'kontakt')); ?>">Kontakt</a></div>
  </aside>
</div></section>
<section class="sec" style="padding-top:0"><div class="wrap"><h2 class="display chrome" style="font-size:2.2rem;margin-bottom:24px">Czytaj także</h2>
  <div class="news-grid"><?php $q = new WP_Query(['post_type' => 'blog', 'posts_per_page' => 3, 'post__not_in' => [get_the_ID()]]); while ($q->have_posts()): $q->the_post(); get_template_part('template-parts/blog-card'); endwhile; wp_reset_postdata(); ?></div>
</div></section>
<?php
// Dane strukturalne: Article + FAQPage
$ld = ['@context' => 'https://schema.org', '@graph' => [[
  '@type' => 'BlogPosting', 'headline' => get_the_title(), 'datePublished' => get_the_date('c'), 'dateModified' => get_the_modified_date('c'),
  'author' => ['@type' => 'Organization', 'name' => 'Rolmax'], 'publisher' => ['@type' => 'Organization', 'name' => 'Rolmax'],
  'image' => get_the_post_thumbnail_url(null, 'full') ?: null, 'mainEntityOfPage' => get_permalink(), 'description' => wp_strip_all_tags(get_the_excerpt())]]];
if ($faq) $ld['@graph'][] = ['@type' => 'FAQPage', 'mainEntity' => array_map(fn($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]], array_values($faq))];
echo '<script type="application/ld+json">' . wp_json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
get_footer();
