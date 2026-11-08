/* Rolmax 30 — interakcje: mega menu (panel wyboru po lewej), reveal, liczniki, oddziały, wideo */
(() => {
  // Mega menu: hover/klik na kategorii po lewej przełącza panel po prawej (jak na starej stronie)
  document.querySelectorAll('[data-mega]').forEach(li => {
    let t, ot = 0;
    const open = () => { clearTimeout(t); ot = Date.now(); document.querySelectorAll('[data-mega]').forEach(x => x !== li && x.classList.remove('open')); li.classList.add('open'); li.querySelector('button').setAttribute('aria-expanded', 'true'); };
    const close = () => { t = setTimeout(() => { li.classList.remove('open'); li.querySelector('button').setAttribute('aria-expanded', 'false'); }, 180); };
    if (matchMedia('(hover: hover)').matches) { li.addEventListener('mouseenter', open); li.addEventListener('mouseleave', close); }
    li.querySelector('button').addEventListener('click', () => { if (Date.now() - ot < 400) return; li.classList.contains('open') ? close() : open(); });
  });
  document.querySelectorAll('.mega-side, .explorer .mega-side').forEach(side => {
    const main = side.nextElementSibling;
    const show = slug => {
      side.querySelectorAll('button[data-c]').forEach(b => b.classList.toggle('on', b.dataset.c === slug));
      main.querySelectorAll('[data-pane]').forEach(p => p.hidden = p.dataset.pane !== slug);
    };
    side.querySelectorAll('button[data-c]').forEach(b => { b.addEventListener('mouseenter', () => show(b.dataset.c)); b.addEventListener('click', () => show(b.dataset.c)); });
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('[data-mega]').forEach(x => x.classList.remove('open')); });
  const burger = document.querySelector('.burger');
  burger && burger.addEventListener('click', () => document.getElementById('nav').classList.toggle('mobile-open'));

  // Reveal + liczniki
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (!e.isIntersecting) return;
    e.target.classList.add('in');
    e.target.querySelectorAll('[data-count]').forEach(el => { const to = +el.dataset.count; let n = 0; const step = () => { n += Math.max(1, Math.ceil(to / 40)); el.textContent = Math.min(n, to); if (n < to) requestAnimationFrame(step); }; step(); });
    io.unobserve(e.target);
  }), { threshold: .15 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  // Oddziały — zakładki
  const tabs = document.getElementById('brTabs');
  if (tabs) tabs.querySelectorAll('button').forEach(b => b.addEventListener('click', () => {
    tabs.querySelectorAll('button').forEach(x => x.classList.toggle('on', x === b));
    document.querySelectorAll('[data-branch]').forEach(p => p.hidden = p.dataset.branch !== b.dataset.i);
  }));

  // Wideo: odtwarzaj tylko widoczne (oszczędność transferu), start od zadanej sekundy
  const vio = new IntersectionObserver(es => es.forEach(e => { const v = e.target; e.isIntersecting ? v.play().catch(() => {}) : v.pause(); }), { threshold: .1 });
  document.querySelectorAll('video[autoplay]').forEach(v => {
    if (v.dataset.start) v.addEventListener('loadedmetadata', () => { v.currentTime = +v.dataset.start; }, { once: true });
    vio.observe(v);
  });


  /* telefon: mega menu tuż pod wstążką; klik poza menu zamyka */
  const hdr = document.querySelector('.site-header');
  if (hdr) { const set = () => document.documentElement.style.setProperty('--hh', hdr.getBoundingClientRect().bottom + 'px');
    ['load', 'resize', 'scroll'].forEach(e => addEventListener(e, set, { passive: true })); set();
    document.addEventListener('click', e => { if (!e.target.closest('.site-header')) document.querySelectorAll('.site-header .open').forEach(x => x.classList.remove('open')); }); }
})();
