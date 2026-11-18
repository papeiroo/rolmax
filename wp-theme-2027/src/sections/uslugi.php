<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<section class="sec bg-steel" id="uslugi">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><div class="eyebrow"><?php echo rx_e( $s, 'eyebrow' ); ?></div><h2 class="display h2 chrome" style="margin-top:14px"><?php echo rx_e( $s, 'title' ); ?></h2></div>
      <p class="lead"><?php echo rx_p( $s, 'lead' ); ?></p>
    </div>
    <div class="svc-grid">
      <template data-rx="svc"></template>
      <a class="svc banner shop-banner chrome-frame reveal" href="<?php echo rx_url( $s, 'shop_url' ); ?>">
        <?php $img = rx_asset( $s['shop_img'] ); $def = ( is_string( $s['shop_img'] ) && $s['shop_img'] === 'assets/img/sklep-banner.webp' ); ?>
        <img src="<?php echo esc_url( $img ); ?>"<?php if ( $def ) : ?> srcset="<?php echo esc_url( rx_asset( 'assets/img/sklep-banner-1200.webp' ) ); ?> 1200w, <?php echo esc_url( $img ); ?> 2172w" sizes="(max-width:900px) 100vw, 1300px"<?php endif; ?> alt="Części i akcesoria do maszyn rolniczych — sklep Rolmax" loading="lazy" width="2172" height="724">
        <div class="body"><span class="tag"><?php echo rx_e( $s, 'shop_tag' ); ?></span><h3 class="chrome"><?php echo rx_e( $s, 'shop_title' ); ?></h3><p><?php echo rx_e( $s, 'shop_text' ); ?></p><span class="more"><?php echo rx_e( $s, 'shop_more' ); ?></span></div></a>
    </div>
  </div>
</section>
