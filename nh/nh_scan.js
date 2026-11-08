// ROLMAX — skan katalogu New Holland PL (uruchom raz na kwartał)
// 1) Otwórz https://agriculture.newholland.com/pl-pl/europe w Chrome
// 2) F12 -> zakładka Console -> wklej cały ten plik -> Enter
// 3) Po ~1 min pobierze się plik nh.json — wrzuć go do folderu nh/ i uruchom: python build_sync.py
(async () => {
  const B = location.origin, P = h => new DOMParser().parseFromString(h, 'text/html');
  const get = async u => { const c = new AbortController(); const t = setTimeout(() => c.abort(), 15000);
    try { const r = await fetch(B + u, { signal: c.signal }); return [r.status, P(await r.text())]; } finally { clearTimeout(t); } };
  const tree = {}, prods = {}, brochures = {}, seen = new Set();
  const q = ['/pl-pl/europe/produkty', '/pl-pl/europe/uslugi-i-rozwiazania'];
  while (q.length && Object.keys(tree).length < 250) {
    const batch = q.splice(0, 6).filter(u => !seen.has(u)); batch.forEach(u => seen.add(u));
    await Promise.all(batch.map(async u => { try {
      const [s, d] = await get(u);
      tree[u] = { s, h1: d.querySelector('h1')?.textContent.trim() || '', d: d.querySelector('meta[name=description]')?.content || '', og: d.querySelector('meta[property="og:image"]')?.content || '' };
      if (/\/produkty\/[^/]+\/[^/]+/.test(u)) {
        const m = d.querySelector('main') || d.body; const txt = m.textContent;
        prods[u] = { ...tree[u], km: [...new Set(txt.match(/\d{2,3}\s?[-–]\s?\d{2,3}\s?KM|\d{2,3}\s?KM/g) || [])].slice(0, 6),
          hs: [...m.querySelectorAll('h2,h3')].map(e => e.textContent.trim()).filter(Boolean).slice(0, 25), body: txt.replace(/\s+/g, ' ').slice(0, 1800), links: [] };
        brochures[u.split('/produkty/')[1]] = [...d.querySelectorAll('[data-a2a-url]')].map(a => [a.getAttribute('data-a2a-title'), a.getAttribute('data-a2a-url'), '']);
      }
      d.querySelectorAll('a[href]').forEach(a => { let h = (a.getAttribute('href') || '').split(/[?#]/)[0]; if (h.startsWith(B)) h = h.slice(B.length);
        if (h.startsWith(u + '/') && h.split('/').length <= u.split('/').length + 2 && !seen.has(h) && !q.includes(h)) q.push(h); });
    } catch (e) { tree[u] = { s: -1 }; } }));
  }
  const blob = new Blob([JSON.stringify({ prods, tree, brochures, news: {}, newsIndex: {} })], { type: 'application/json' });
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'nh.json'; a.click();
  console.log('Gotowe:', Object.keys(prods).length, 'produktów');
})();
