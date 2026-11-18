<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<section class="sec bg-steel" id="galeria">
  <div class="wrap">
    <div class="sec-head reveal"><div><div class="eyebrow"><?php echo rx_e( $s, 'eyebrow' ); ?></div><h2 class="display h2 chrome" style="margin-top:14px"><?php echo rx_e( $s, 'title' ); ?></h2></div><a class="btn btn-ghost" href="<?php echo rx_url( $s, 'btn_url' ); ?>"><?php echo rx_e( $s, 'btn_text' ); ?></a></div>
    <div class="gal gal-collage" id="galC"><template data-rx="gal"></template></div>
  </div>
</section>
