<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="vb-pin" id="vbPin">
<section class="vband" id="vbSec" aria-label="New Holland — marka premium">
  <div class="vb-mask" aria-hidden="true"><svg id="vbSvg" width="100%" height="100%"><defs><mask id="vbM" maskUnits="userSpaceOnUse"><rect id="vbR" x="0" y="0" width="100%" height="100%" fill="#fff"/><g id="vbG"><text id="vbT" x="50%" y="50%" text-anchor="middle" dominant-baseline="central" fill="#000"><?php echo rx_e( $s, 'mask' ); ?></text></g></mask></defs><rect x="0" y="0" width="100%" height="100%" fill="#030a1a" mask="url(#vbM)"/></svg><div class="vb-cue">Przewiń</div></div>
  <video autoplay muted loop playsinline data-start="<?php echo esc_attr( (int) $s['start'] ); ?>" poster="<?php echo esc_url( rx_asset( 'assets/poster.jpg' ) ); ?>"><source src="<?php echo esc_url( rx_asset( $s['video_webm'] ) ); ?>" type="video/webm"><source src="<?php echo esc_url( rx_asset( $s['video_mp4'] ) ); ?>" type="video/mp4"></video>
  <div class="wrap reveal">
    <div class="eyebrow"><?php echo rx_e( $s, 'eyebrow' ); ?></div>
    <h2 class="display h2" style="margin-top:16px"><span class="chrome-sheen"><?php echo rx_e( $s, 'title1' ); ?></span><br><span class="chrome"><?php echo rx_e( $s, 'title2' ); ?></span></h2>
    <p class="lead" style="margin-top:20px"><?php echo rx_p( $s, 'lead' ); ?></p>
    <div class="premium-points">
      <?php foreach ( (array) $s['points'] as $pt ) : ?><div class="pp chrome-frame"><b class="chrome"><?php echo esc_html( $pt['title'] ); ?></b><p><?php echo esc_html( $pt['text'] ); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
</div>
