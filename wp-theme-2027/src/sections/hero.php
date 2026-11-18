<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="hero-pin" id="heroPin">
<section class="hero" id="heroSec">
  <div class="hero-frame" aria-hidden="true"></div>
  <div class="hero-badge" aria-hidden="true"><span class="eyebrow"><?php echo rx_e( $s, 'badge_eyebrow' ); ?></span><b class="chrome-sheen"><?php echo rx_e( $s, 'badge_year' ); ?></b></div>
  <video autoplay muted loop playsinline poster="<?php echo rx_media( $s, 'poster' ); ?>"><source src="<?php echo esc_url( rx_asset( $s['video_webm'] ) ); ?>" type="video/webm"><source src="<?php echo esc_url( rx_asset( $s['video_mp4'] ) ); ?>" type="video/mp4"></video>
  <div class="wrap">
    <div class="reveal">
      <div class="eyebrow"><?php echo rx_e( $s, 'eyebrow' ); ?></div>
      <h1 class="display" style="margin-top:18px"><span class="chrome-sheen hx-l"><?php echo rx_e( $s, 'title1' ); ?></span><span class="nh chrome hx-r"><?php echo rx_e( $s, 'title2' ); ?></span></h1>
      <p><?php echo rx_p( $s, 'text' ); ?></p>
      <div class="actions"><a class="btn btn-chrome" href="<?php echo rx_url( $s, 'btn1_url' ); ?>"><?php echo rx_e( $s, 'btn1_text' ); ?></a><a class="btn btn-ghost" href="<?php echo rx_url( $s, 'btn2_url' ); ?>"><?php echo rx_e( $s, 'btn2_text' ); ?></a></div>
    </div>
    <a href="<?php echo rx_url( $s, 'anniv_url' ); ?>" class="anniv chrome-frame reveal">
      <div class="num chrome-sheen"><?php echo rx_e( $s, 'anniv_num' ); ?></div>
      <div class="lbl chrome"><?php echo rx_e( $s, 'anniv_lbl' ); ?></div>
      <div class="yrs"><?php echo rx_e( $s, 'anniv_yrs' ); ?></div>
    </a>
  </div>
  <div class="scroll-cue" aria-hidden="true"></div>
</section>
</div>
