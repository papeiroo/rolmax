#!/usr/bin/env python3
"""Buduje motyw WordPress rolmax-2027 z aktualnej wersji SPA (site/) + źródeł PHP (wp/src/)."""
import re, shutil, os, sys, json, zipfile
from pathlib import Path

SITE = Path('/home/claude/rolmax/site')
SRC = Path('/home/claude/rolmax/wp/src')
OUT = Path('/home/claude/rolmax/wp/build/rolmax-2027')

def js_assets(src: str) -> str:
    """Zamienia literały 'assets/...' w kodzie JS na RXT+'assets/...' (adres katalogu motywu)."""
    out = []; i = 0; n = len(src)
    stack = ['code']  # code | tpl | expr (inside ${})
    brace = []        # depth counters for ${ } nesting
    last_sig = ''
    def emit(s): out.append(s)
    while i < n:
        st = stack[-1]
        c = src[i]
        if st in ('code', 'expr'):
            if src.startswith('//', i):
                j = src.find('\n', i); j = n if j < 0 else j
                emit(src[i:j]); i = j; continue
            if src.startswith('/*', i):
                j = src.find('*/', i + 2); j = n if j < 0 else j + 2
                emit(src[i:j]); i = j; continue
            if c in '\'"':
                q = c; j = i + 1; buf = [c]
                while j < n and src[j] != q:
                    if src[j] == '\\': buf.append(src[j:j+2]); j += 2; continue
                    buf.append(src[j]); j += 1
                buf.append(q); s = ''.join(buf)
                s = s.replace('assets/', q + '+RXT+' + q + 'assets/')
                emit(s); i = j + 1; last_sig = 'str'; continue
            if c == '`':
                stack.append('tpl'); emit(c); i += 1; continue
            if c == '/':
                # regex literal heuristic
                if last_sig == '' or last_sig in '(,=:[!&|?{};+-*%~^<>' or last_sig in ('return', 'typeof'):
                    j = i + 1; inclass = False
                    while j < n:
                        ch = src[j]
                        if ch == '\\': j += 2; continue
                        if ch == '[': inclass = True
                        elif ch == ']': inclass = False
                        elif ch == '/' and not inclass: break
                        elif ch == '\n': break
                        j += 1
                    j += 1
                    while j < n and src[j].isalpha(): j += 1
                    emit(src[i:j]); i = j; last_sig = 'rx'; continue
            if st == 'expr':
                if c == '{': brace[-1] += 1
                elif c == '}':
                    if brace[-1] == 0:
                        brace.pop(); stack.pop(); emit(c); i += 1; continue
                    brace[-1] -= 1
            if not c.isspace():
                m = re.match(r'[A-Za-z_$][\w$]*', src[i:])
                if m:
                    last_sig = m.group(0); emit(m.group(0)); i += len(m.group(0)); continue
                last_sig = c
            emit(c); i += 1; continue
        # template literal text
        if c == '\\': emit(src[i:i+2]); i += 2; continue
        if c == '`': stack.pop(); emit(c); i += 1; last_sig = 'str'; continue
        if src.startswith('${', i):
            stack.append('expr'); brace.append(0); emit('${'); i += 2; last_sig = '('; continue
        if src.startswith('assets/', i):
            emit('${RXT}assets/'); i += 7; continue
        emit(c); i += 1
    return ''.join(out)

def php_assets(html: str) -> str:
    return re.sub(r'(src|srcset|poster|href)="assets/', r'\1="<?php echo esc_url( RX_URI ); ?>/assets/', html).replace(', assets/', ', <?php echo esc_url( RX_URI ); ?>/assets/')

def must(s, old, new, count=1):
    if old not in s: sys.exit('NIE ZNALEZIONO: ' + old[:90])
    return s.replace(old, new, count)

def main():
    html = (SITE / 'index.html').read_text(encoding='utf-8')
    lines = html.split('\n')
    # --- JS aplikacji ---
    a = html.index('<script>\n', html.index('<script src="data.js')) + len('<script>\n')
    b = html.rindex('</script>')
    js = html[a:b]
    js = must(js, "Date.parse('2027-02-24T00:00:00+01:00')", "Date.parse((window.RXWP&&RXWP.jub)||'2027-02-24T00:00:00+01:00')")
    js = must(js, "function route(){\n  const h=location.hash.slice(1); const app=$('#app');",
              "function route(){\n  if(!RXWP.front) return;\n  const h=location.hash.slice(1); const app=$('#app');")
    js = must(js, "else { if(!$('.hero video')||app.dataset.view!=='home'){ app.innerHTML=home(); app.dataset.view='home'; mountHome(); animate(); }",
              "else { if(app.dataset.view!=='home'){ rxRestoreHome(app); app.dataset.view='home'; mountHome(); animate(); }")
    js = must(js, "  app.dataset.view='sub'; mountHome(); animate(); window.scrollTo(0,0);",
              "  app.dataset.view='sub'; rxHydrate(app); mountHome(); animate(); window.scrollTo(0,0);")
    js = must(js, "document.addEventListener('click',e=>{const a=e.target.closest('a[href=\"#home\"]'); if(!a) return; e.preventDefault();",
              "document.addEventListener('click',e=>{const a=e.target.closest('a[href=\"#home\"]'); if(!a) return; e.preventDefault(); if(!RXWP.front){ location.href=RXWP.home; return; }")
    js = must(js, "window.addEventListener('hashchange',route); route(); newsletter();",
              "rxBoot(); window.addEventListener('hashchange',route); route(); newsletter();")
    js = js_assets(js)
    wpjs = (SRC / 'js' / 'rx-wp.js').read_text(encoding='utf-8')
    # fragmenty strony głównej → funkcje wypełniające sekcje
    home = js[js.index('function home(){ return `'):]
    home = home[:home.index('</section>`; }') + 14]
    def between(s, start, end):
        i = s.index(start) + len(start); j = s.index(end, i); return s[i:j]
    strip = between(home, '<nav class="catstrip${catWheelOn()?\' cw-hidden\':\'\'}" id="kategorie" aria-label="Kategorie maszyn"><div class="wrap">\n  ${', '}\n</div></nav>')
    svc = between(home, '<div class="svc-grid">\n      ${', "`).join('')}\n      <a class=\"svc banner") + "`).join('')"
    J = "`).join('')"
    rail = between(home, '<div class="rail rail-auto reveal">${', J + '}</div>') + J
    news = between(home, '<div class="news-grid">${NEWS.map', J + '}</div>') + J
    gal = between(home, '<div class="gal gal-collage" id="galC">${', J + '}</div>') + J
    frag = ("const RXH={\n"
            " kategorie:t=>{ const w=t.dataset.wheel!=='0'; const on=w&&!document.body.classList.contains('elementor-editor-active')&&catWheelOn();\n"
            "   return `${on?catWheel():''}<nav class=\"catstrip${on?' cw-hidden':''}\" id=\"kategorie\" aria-label=\"Kategorie maszyn\"><div class=\"wrap\">\n  ${" + strip + "}\n</div></nav>${(w&&!on)?`<div class=\"cw-replay\"><button onclick=\"replayWheel()\">▶ Zobacz kategorie w trybie pokazu</button></div>`:''}`; },\n"
            " svc:()=>" + svc + ",\n"
            " rail:()=>" + rail + ",\n"
            " blog3:()=>BLOG.slice(0,3).map(blogCard).join(''),\n"
            " news:()=>NEWS.map" + news + ",\n"
            " gal:()=>" + gal + "\n};\n")
    js = must(js, 'rxBoot(); window.addEventListener', '/* ===== WordPress / Elementor ===== */\n' + frag + wpjs + '\nrxBoot(); window.addEventListener')
    OUT.parent.mkdir(parents=True, exist_ok=True)
    if OUT.exists(): shutil.rmtree(OUT)
    shutil.copytree(SRC, OUT, ignore=shutil.ignore_patterns('rx-wp.js', 'header.tpl', 'footer.tpl'))
    (OUT / 'js').mkdir(exist_ok=True)
    (OUT / 'js' / 'app.js').write_text(js, encoding='utf-8')
    data = (SITE / 'data.js').read_text(encoding='utf-8')
    (OUT / 'js' / 'data.js').write_text(js_assets(data), encoding='utf-8')
    # --- zasoby ---
    shutil.copytree(SITE / 'assets', OUT / 'assets')
    shutil.copy(SRC / 'assets-wp.css', OUT / 'assets' / 'rolmax-wp.css') if (SRC / 'assets-wp.css').exists() else None
    if (OUT / 'assets-wp.css').exists(): (OUT / 'assets-wp.css').unlink()
    # --- nagłówek i stopka ---
    i_top = html.index('<div class="topbar">'); i_main = html.index('<main id="app"></main>')
    i_end = html.index('</footer>', i_main) + len('</footer>')
    head_html = html[i_top:i_main]
    foot_html = html[i_main + len('<main id="app"></main>'):i_end]
    head_html = must(head_html, 'Pokazy DEMO</a>', "<?php echo esc_html( rx_mod( 'rx_cta', 'Pokazy DEMO' ) ); ?></a>")
    foot_html = must(foot_html, '<li><a href="tel:+48817216785">+48 81 721 67 85</a></li><li><a href="mailto:rolmax@rolmax.net.pl">rolmax@rolmax.net.pl</a></li>',
                     "<?php $rx_ph = rx_mod( 'rx_phone', '+48 81 721 67 85' ); $rx_ml = rx_mod( 'rx_mail', 'rolmax@rolmax.net.pl' ); ?><li><a href=\"tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $rx_ph ) ); ?>\"><?php echo esc_html( $rx_ph ); ?></a></li><li><a href=\"mailto:<?php echo esc_attr( $rx_ml ); ?>\"><?php echo esc_html( $rx_ml ); ?></a></li>")
    desc = re.search(r'<meta name="description" content="([^"]*)"', html).group(1)
    header = ("<?php if ( ! defined( 'ABSPATH' ) ) exit; ?><!doctype html>\n<html <?php language_attributes(); ?>>\n<head>\n"
              "<meta charset=\"<?php bloginfo( 'charset' ); ?>\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
              "<?php if ( is_front_page() ) : ?><meta name=\"description\" content=\"" + desc + "\"><?php endif; ?>\n"
              "<?php wp_head(); ?>\n</head>\n<body <?php body_class(); ?>>\n<?php wp_body_open(); ?>\n\n"
              + php_assets(head_html) + "<main id=\"app\">\n")
    footer = ("</main>" + php_assets(foot_html) + "\n<?php wp_footer(); ?>\n</body>\n</html>\n")
    (OUT / 'header.php').write_text(header, encoding='utf-8')
    (OUT / 'footer.php').write_text(footer, encoding='utf-8')
    print('OK', OUT)

if __name__ == '__main__':
    main()
