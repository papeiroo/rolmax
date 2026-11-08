<?php defined('ABSPATH') || exit; $u = fn($p) => esc_url(home_url('/' . ROLMAX_PREFIX . $p)); ?>
</main>
<footer class="footer">
  <div class="chrome-line"></div>
  <div class="wrap">
    <div class="f-grid">
      <div class="f-brand">
        <a href="<?php echo $u(''); ?>" aria-label="GK Rolmax"><?php
          echo has_custom_logo() ? wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'f-logo'])
            : '<img class="f-logo" src="' . esc_url(get_theme_file_uri('assets/logo-rolmax.png')) . '" alt="GK Rolmax" width="904" height="211">'; ?></a>
        <span class="jubilee-chip">30 LAT · 1997–2027</span>
        <p>Autoryzowany Dealer i Serwis New Holland. Oddziały: Świdnik, Miączyn, Rudnik, Białka.</p>
        <p class="f-contact"><a href="tel:+48817216785">+48 81 721 67 85</a><a href="mailto:rolmax@rolmax.net.pl">rolmax@rolmax.net.pl</a></p>
        <nav class="f-icons" aria-label="Rolmax w mediach społecznościowych">
        <a href="https://www.facebook.com/RolmaxNewHolland" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.6V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.2H7.9v3h2.6V21z" fill="currentColor"/></svg></a>
        <a href="https://www.instagram.com/rolmaxswidnik" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="4" y="4" width="16" height="16" rx="4.5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17" cy="7" r=".6" fill="currentColor"/></svg></a>
        <a href="https://www.tiktok.com/@rolmaxswidnik" target="_blank" rel="noopener" aria-label="TikTok" title="TikTok"><svg viewBox="0 0 24 24"><path d="M14 4h2.6c.3 1.9 1.5 3.2 3.4 3.4V10c-1.3 0-2.5-.4-3.4-1v5.6A5.1 5.1 0 1 1 11.5 9.5v2.7a2.4 2.4 0 1 0 2.5 2.4z" fill="currentColor"/></svg></a>
        <a href="https://www.youtube.com/@rolmaxswidnik616" target="_blank" rel="noopener" aria-label="YouTube" title="YouTube"><svg viewBox="0 0 24 24"><rect x="2.5" y="5.5" width="19" height="13" rx="4" fill="currentColor"/><path d="M10 9.2v5.6l4.8-2.8z" fill="#020713"/></svg></a>
      </nav>
      </div>
      <div><h5>Oferta</h5><ul><?php foreach (rx_categories() as $c) printf('<li><a href="%s">%s</a></li>', esc_url(get_term_link($c)), esc_html($c->name)); ?></ul></div>
      <div><h5>Usługi</h5><ul><?php foreach (rx_services() as $s) printf('<li><a href="%s">%s</a></li>', esc_url(get_permalink($s)), esc_html(get_the_title($s))); ?></ul></div>
      <div><h5>Rolmax</h5><?php
        if (has_nav_menu('footer')) { wp_nav_menu(['theme_location' => 'footer', 'container' => false]); }
        else { ?><ul>
          <li><a href="<?php echo esc_url(get_post_type_archive_link('promocja')); ?>">Promocje</a></li>
          <li><a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>">Blog</a></li>
          <li><a href="<?php echo $u('aktualnosci'); ?>">Aktualności</a></li>
          <li><a href="<?php echo esc_url(get_post_type_archive_link('galeria')); ?>">Galeria</a></li>
          <li><a href="<?php echo $u('strony/praca'); ?>">Praca</a></li>
          <li><a href="<?php echo $u('kontakt'); ?>">Kontakt</a></li>
          <li><a href="https://www.rolmax-sklep.pl/">Sklep internetowy</a></li>
          <li><a href="https://agriculture.newholland.com/pl-pl/europe/uslugi-i-rozwiazania/konfigurator">Konfigurator New Holland</a></li>
        </ul><?php } ?></div>
    </div>
    <div class="f-bottom"><span>© Rolmax <?php echo esc_html(wp_date('Y')); ?> · Wszelkie prawa zastrzeżone.</span>
      <span>New Holland jest znakiem towarowym CNH Industrial.</span></div>
  </div>
</footer>
<?php wp_footer(); ?>
</body></html>
