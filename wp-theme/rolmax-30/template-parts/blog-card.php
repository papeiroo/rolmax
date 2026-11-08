<?php defined('ABSPATH') || exit; $t = get_the_terms(get_the_ID(), 'temat'); ?>
<a class="news blogcard chrome-frame reveal" href="<?php the_permalink(); ?>">
  <div class="bimg"><?php the_post_thumbnail('large', ['loading' => 'lazy', 'alt' => '']); ?></div>
  <?php if ($t) echo '<span class="topic">' . esc_html($t[0]->name) . '</span>'; ?>
  <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
  <h3 class="chrome"><?php the_title(); ?></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
  <span class="more">Czytaj →</span>
</a>
