<?php defined('ABSPATH') || exit; get_header(); the_post();
$pts = array_filter(array_map('trim', explode("\n", (string) get_post_meta(get_the_ID(), 'rx_points', true)))); ?>
<section class="hero" style="min-height:70vh"><?php the_post_thumbnail('rx-hero', ['style' => 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2', 'alt' => '']); ?>
  <div class="wrap" style="grid-template-columns:1fr"><div class="reveal">
    <div class="crumbs"><a href="<?php echo esc_url(home_url('/' . ROLMAX_PREFIX)); ?>">Home</a><i>/</i>Usługi<i>/</i><?php the_title(); ?></div>
    <h1 class="display chrome-sheen" style="font-size:clamp(3rem,7vw,6rem)"><?php the_title(); ?></h1>
    <p><?php echo esc_html(get_the_excerpt()); ?></p>
  </div></div></section>
<section class="sec bg-steel"><div class="wrap" style="display:grid;grid-template-columns:1.3fr 1fr;gap:50px">
  <div class="entry reveal"><?php the_content(); ?></div>
  <aside class="reveal"><?php foreach ($pts as $p) echo '<div class="pp chrome-frame" style="margin-bottom:14px"><b class="chrome">' . esc_html($p) . '</b></div>'; ?></aside>
</div></section>
<?php get_template_part('template-parts/branches'); get_footer();
