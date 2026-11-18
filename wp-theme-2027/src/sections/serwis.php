<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<section class="vband" style="min-height:60vh" aria-label="Serwis w żniwa">
  <video autoplay muted loop playsinline data-start="<?php echo esc_attr( (int) $s['start'] ); ?>" poster="<?php echo esc_url( rx_asset( 'assets/poster.jpg' ) ); ?>"><source src="<?php echo esc_url( rx_asset( $s['video_webm'] ) ); ?>" type="video/webm"><source src="<?php echo esc_url( rx_asset( $s['video_mp4'] ) ); ?>" type="video/mp4"></video>
  <div class="wrap reveal" style="text-align:left">
    <div class="eyebrow"><?php echo rx_e( $s, 'eyebrow' ); ?></div>
    <h2 class="display h2" style="margin-top:16px"><span class="chrome-sheen"><?php echo rx_e( $s, 'title1' ); ?></span> <span class="chrome"><?php echo rx_e( $s, 'title2' ); ?></span></h2>
    <p class="lead" style="margin:18px 0 28px"><?php echo rx_p( $s, 'lead' ); ?></p>
    <a class="btn btn-chrome" href="<?php echo rx_url( $s, 'btn_url' ); ?>"><?php echo rx_e( $s, 'btn_text' ); ?></a> &nbsp; <span style="font-family:var(--ff-display);font-size:1.4rem;letter-spacing:.08em" class="chrome"><?php echo rx_e( $s, 'phones' ); ?></span>
  </div>
</section>
