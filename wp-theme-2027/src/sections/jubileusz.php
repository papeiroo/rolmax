<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<section class="sec jubilee" id="jubileusz">
  <div class="wrap jub-grid">
    <div class="reveal">
      <div class="big30 chrome-sheen"><?php echo rx_e( $s, 'big' ); ?></div>
      <div class="big30-sub chrome"><?php echo rx_e( $s, 'big_sub' ); ?></div>
    </div>
    <div class="reveal">
      <div class="eyebrow"><?php echo rx_e( $s, 'eyebrow' ); ?></div>
      <h2 class="display h2 chrome" style="margin:14px 0 18px"><?php echo rx_e( $s, 'title' ); ?></h2>
      <p class="lead"><?php echo rx_p( $s, 'lead' ); ?></p>
      <div class="stats">
        <?php foreach ( (array) $s['stats'] as $st ) : $v = isset( $st['value'] ) ? trim( $st['value'] ) : ''; ?>
        <?php if ( $v === '{total}' ) : ?><div class="stat"><b class="chrome" data-count="total">0</b><span><?php echo esc_html( $st['label'] ); ?></span></div>
        <?php elseif ( ctype_digit( $v ) ) : ?><div class="stat"><b class="chrome" data-count="<?php echo esc_attr( $v ); ?>">0</b><span><?php echo esc_html( $st['label'] ); ?></span></div>
        <?php else : ?><div class="stat"><b class="chrome"><?php echo esc_html( $v ); ?></b><span><?php echo esc_html( $st['label'] ); ?></span></div><?php endif; ?>
        <?php endforeach; ?>
      </div>
      <div class="timeline tl-draw" id="tlDraw"><span class="tl-spark" aria-hidden="true"></span>
        <?php foreach ( (array) $s['timeline'] as $t ) : ?><div class="tl"><b class="chrome"><?php echo esc_html( $t['year'] ); ?></b><p><?php echo esc_html( $t['text'] ); ?></p></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
