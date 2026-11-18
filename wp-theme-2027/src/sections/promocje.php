<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<section class="promo-strip" aria-label="Oferty specjalne">
  <div class="wrap promo-grid">
    <?php foreach ( (array) $s['items'] as $p ) : $u = isset( $p['url'] ) ? ( is_array( $p['url'] ) ? $p['url']['url'] : $p['url'] ) : '#'; ?><a class="promo chrome-frame reveal" href="<?php echo esc_attr( $u ); ?>"><span class="tag"><?php echo esc_html( $p['tag'] ); ?></span><b class="chrome"><?php echo esc_html( $p['title'] ); ?></b><small><?php echo esc_html( $p['text'] ); ?></small></a><?php endforeach; ?>
  </div>
</section>
