<?php
defined('ABSPATH') || exit;
// Oddziały i działy — edytowalne: Wygląd → Dostosuj → Rolmax (JSON) lub tutaj
$depts = [
  'Świdnik' => [['Sprzedaż maszyn','Pon.–Pt. 8:00–16:00',['81 723 69 60','606 363 398','606 815 418'],'rolmax@rolmax.net.pl'],['Dział części','Pon.–Pt. 7:00–16:00 · Sob. 8:00–14:00',['81 721 67 85','662 032 735'],'swidnik@rolmax-sklep.pl'],['Serwis','Pon.–Pt. 7:00–16:00 · żniwa 24/7',['81 723 69 54','509 497 101'],'serwis@rolmax.net.pl']],
  'Miączyn' => [['Sprzedaż maszyn','Pon.–Pt. 8:00–16:00',['84 639 71 86','606 363 398'],'rolmax@rolmax.net.pl'],['Dział części','Pon.–Pt. 7:00–16:00 · Sob. 8:00–14:00',['84 639 71 86','513 028 188'],'miaczyn@rolmax.net.pl'],['Serwis','Pon.–Pt. 7:00–15:00 · żniwa 24/7',['84 639 71 86','723 405 408'],'miaczyn@rolmax.net.pl']],
  'Rudnik'  => [['Sprzedaż maszyn','Pon.–Pt. 8:00–16:00',['504 044 574','606 363 398'],'rolmax@rolmax.net.pl'],['Dział części','Pon.–Pt. 7:00–16:00',['81 821 01 11','504 046 686'],'rudnik@rolmax.net.pl']],
  'Białka'  => [['Sprzedaż maszyn','Pon.–Pt. 8:00–16:00',['506 215 663','606 363 398'],'rolmax@rolmax.net.pl'],['Dział części','Pon.–Pt. 8:00–16:00',['781 809 702'],'bialka@rolmax.net.pl']],
];
$br = rx_branches();
?>
<section class="sec" id="oddzialy" style="background:var(--navy-950)"><div class="wrap">
  <div class="sec-head reveal"><div><div class="eyebrow">Kontakt</div><h2 class="display h2 chrome" style="margin-top:14px">Nasze oddziały</h2></div></div>
  <div class="branches reveal">
    <div class="br-tabs" id="brTabs"><?php foreach ($br as $i => $b): ?>
      <button class="<?php echo $i ? '' : 'on'; ?>" data-i="<?php echo $i; ?>"><b><?php echo esc_html($b['city'] === 'Wilkołaz' ? 'Rudnik' : ($b['city'] === 'Radzyń Podlaski' ? 'Białka' : $b['city'])); ?></b><span><?php echo esc_html($b['street'] . ', ' . $b['zip'] . ' ' . $b['city']); ?></span></button>
    <?php endforeach; ?></div>
    <div><?php $i = 0; foreach ($depts as $name => $list): ?>
      <div class="br-panel" data-branch="<?php echo $i; ?>" <?php echo $i++ ? 'hidden' : ''; ?>><?php foreach ($list as $d): ?>
        <div class="dept chrome-frame"><h4 class="chrome"><?php echo esc_html($d[0]); ?></h4><dl><dd><?php echo esc_html($d[1]); ?></dd>
          <?php foreach ($d[2] as $t): ?><dd>Tel. <a href="tel:+48<?php echo esc_attr(str_replace(' ', '', $t)); ?>"><?php echo esc_html($t); ?></a></dd><?php endforeach; ?>
          <dd><a href="mailto:<?php echo esc_attr($d[3]); ?>"><?php echo esc_html($d[3]); ?></a></dd></dl></div>
      <?php endforeach; ?></div>
    <?php endforeach; ?></div>
  </div>
</div></section>
