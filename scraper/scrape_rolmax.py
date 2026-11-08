#!/usr/bin/env python3
"""
ROLMAX — pełny scraper starej strony rolmax.net.pl
==================================================
Pobiera: wszystkie podstrony (PL/EN/DE/UK), kategorie i podkategorie maszyn,
karty produktów, usługi, aktualności, galerie (z paginacją), zdjęcia,
wideo (MP4/YouTube), broszury/PDF — oraz sprawdza martwe linki.

Wynik (folder ./out):
  pages.json          – inwentarz wszystkich URL (status, title, description, h1, canonical)
  products.json       – kategorie -> maszyny (nazwa, slug, opis HTML, zdjęcia, broszura, link producenta)
  services.json       – usługi (Części, Serwis, Finansowanie, Likwidacja szkód, Rolnictwo precyzyjne)
  news.json           – aktualności (tytuł, data, treść HTML, zdjęcia)
  galleries.json      – galerie i wszystkie zdjęcia
  media_manifest.csv  – każdy plik multimedialny: URL źródłowy -> ścieżka lokalna
  dead_links.csv      – martwe linki wewnętrzne i zewnętrzne (z informacją, gdzie występują)
  old_urls.txt        – lista wszystkich działających URL (do mapy przekierowań / Search Console)
  media/...           – pobrane pliki w ORYGINALNEJ jakości (ta sama struktura katalogów co na serwerze)

Użycie:
  pip install requests beautifulsoup4 lxml
  python scrape_rolmax.py                     # crawl + pobranie mediów
  python scrape_rolmax.py --no-media          # tylko dane
  python scrape_rolmax.py --check-external    # dodatkowo test linków zewnętrznych
  python scrape_rolmax.py --langs pl          # tylko wersja polska
"""
import argparse, csv, json, os, re, sys, time
from collections import defaultdict, deque
from concurrent.futures import ThreadPoolExecutor
from urllib.parse import urljoin, urlparse, urlunparse, parse_qs, urlencode

import requests
from bs4 import BeautifulSoup

BASE = "https://rolmax.net.pl"
UA = "RolmaxSiteMigration/1.0 (+site owner migration)"
MEDIA_EXT = re.compile(r"\.(jpe?g|png|webp|gif|svg|mp4|webm|mov|pdf|zip)(\?.*)?$", re.I)
SKIP_SCHEMES = ("mailto:", "tel:", "javascript:", "#")
LAZY_ATTRS = ("src", "data-src", "data-lazy", "data-original", "data-srcset", "srcset", "data-bg")

S = requests.Session()
S.headers.update({"User-Agent": UA, "Accept-Language": "pl,en;q=0.8"})


def norm(url: str, keep_page=True):
    """Normalizacja URL: bez fragmentu, bez końcowego '/', zachowany tylko ?page= (paginacja galerii/aktualności)."""
    p = urlparse(url)
    q = parse_qs(p.query)
    query = urlencode({"page": q["page"][0]}) if keep_page and "page" in q else ""
    path = re.sub(r"/+$", "", p.path) or "/"
    return urlunparse((p.scheme or "https", p.netloc, path, "", query, ""))


def is_internal(url):
    return urlparse(url).netloc in ("", "rolmax.net.pl", "www.rolmax.net.pl")


def get(url, **kw):
    for attempt in range(3):
        try:
            r = S.get(url, timeout=25, allow_redirects=True, **kw)
            return r
        except requests.RequestException:
            time.sleep(1.5 * (attempt + 1))
    return None


def soup_of(r):
    return BeautifulSoup(r.text, "lxml")


def abs_url(href, page_url):
    # strona używa <base href="/"> — linki względne liczymy od korzenia
    if href.startswith(("http://", "https://")):
        return href
    return urljoin(BASE + "/", href.lstrip("/")) if not href.startswith("/") else BASE + href


def media_in(node, page_url):
    out = set()
    for el in node.select("img, source, video, a[href], [style]"):
        for a in LAZY_ATTRS + ("href", "poster"):
            v = el.get(a)
            if not v:
                continue
            for part in re.split(r",\s*", v):
                cand = part.strip().split(" ")[0]
                if cand and MEDIA_EXT.search(cand) and "loader.svg" not in cand and "/template/" not in cand:
                    out.add(abs_url(cand, page_url))
        st = el.get("style") or ""
        for m in re.findall(r"url\(['\"]?([^'\")]+)", st):
            if "/template/" not in m:
                out.add(abs_url(m, page_url))
    for f in node.select("iframe[src], iframe[data-src]"):
        out.add(f.get("src") or f.get("data-src"))
    return sorted(out)


def main_content(s):
    w = s.select_one(".wrapper") or s.body
    for sel in ("header", "footer", "nav", "script", "style", ".header-menu-element-dropdown", ".cookies"):
        for el in w.select(sel):
            el.decompose()
    return w


def crawl(langs, max_pages=5000):
    seen, pages, linkmap = set(), {}, defaultdict(set)
    q = deque(norm(f"{BASE}/{l}") for l in langs)
    q.extend(norm(f"{BASE}/{l}/galerie?page={i}") for l in langs for i in range(1, 8))
    q.extend(norm(f"{BASE}/{l}/aktualnosci?page={i}") for l in langs for i in range(1, 30))
    while q and len(pages) < max_pages:
        url = q.popleft()
        if url in seen:
            continue
        seen.add(url)
        r = get(url)
        status = r.status_code if r is not None else -1
        rec = {"url": url, "path": urlparse(url).path, "status": status}
        pages[url] = rec
        if r is None or status != 200 or "text/html" not in r.headers.get("content-type", ""):
            continue
        s = soup_of(r)
        rec.update({
            "title": (s.title.string or "").strip() if s.title else "",
            "description": (s.select_one('meta[name="description"]') or {}).get("content", ""),
            "canonical": (s.select_one('link[rel="canonical"]') or {}).get("href", ""),
            "og_image": (s.select_one('meta[property="og:image"]') or {}).get("content", ""),
        })
        mc = main_content(BeautifulSoup(r.text, "lxml"))
        h1 = mc.select_one("h1") or mc.select_one("h2")
        rec["h1"] = h1.get_text(" ", strip=True) if h1 else ""
        rec["media"] = media_in(mc, url)
        rec["html"] = str(mc)
        for a in s.select("a[href]"):
            href = a["href"].strip()
            if not href or href.startswith(SKIP_SCHEMES):
                continue
            u = abs_url(href, url)
            if is_internal(u):
                n = norm(u)
                linkmap[n].add(url)
                if MEDIA_EXT.search(urlparse(n).path):
                    continue
                if re.match(r"^/(%s)(/|$)" % "|".join(langs), urlparse(n).path) and n not in seen:
                    q.append(n)
            else:
                linkmap[u.split("#")[0]].add(url)
        print(f"[{len(pages):4}] {status} {rec['path']}", file=sys.stderr)
    return pages, linkmap


def parse_products(pages):
    cats = defaultdict(list)
    for url, p in pages.items():
        parts = p["path"].strip("/").split("/")
        if len(parts) == 4 and parts[1] == "maszyny" and p.get("status") == 200 and parts[0] == "pl":
            mc = BeautifulSoup(p["html"], "lxml")
            desc = max((d for d in mc.select("[class*=desc]")), key=lambda d: len(d.get_text()), default=None)
            links = [a.get("href") for a in mc.select("a[href]")]
            cats[parts[2]].append({
                "slug": parts[3], "url": p["path"], "name": p.get("h1") or p.get("title"),
                "title": p.get("title"), "meta_description": p.get("description"),
                "description_html": str(desc) if desc else "",
                "images": [m for m in p["media"] if "/products/" in m and "/thumbs/" not in m],
                "brochure": next((l for l in links if l and "broszur" in l), None),
                "manufacturer_url": next((l for l in links if l and "agriculture.newholland.com" in l and "broszur" not in l), None),
            })
    cat_pages = {p["path"].split("/")[3]: p for p in pages.values()
                 if re.fullmatch(r"/pl/maszyny/[^/]+", p["path"]) and p.get("status") == 200}
    return [{"slug": c, "url": f"/pl/maszyny/{c}", "name": cat_pages.get(c, {}).get("h1", c),
             "meta_description": cat_pages.get(c, {}).get("description", ""), "items": items}
            for c, items in cats.items()]


def collect(pages, prefix, depth):
    out = []
    for p in pages.values():
        parts = p["path"].strip("/").split("/")
        if p.get("status") == 200 and p["path"].startswith(prefix) and len(parts) == depth:
            mc = BeautifulSoup(p["html"], "lxml")
            date = re.search(r"\b(\d{2}\.\d{2}\.\d{4})\b", mc.get_text(" "))
            out.append({"slug": parts[-1], "url": p["path"], "title": p.get("h1") or p.get("title"),
                        "date": date.group(1) if date else None, "meta_description": p.get("description"),
                        "content_html": p["html"], "media": p["media"]})
    return out


def download_media(all_media, outdir, workers=8):
    rows = []
    def dl(u):
        if not u or not u.startswith("http") or not is_internal(u):
            return (u, "", "external-skip")
        local = os.path.join(outdir, "media", urlparse(u).path.lstrip("/"))
        if os.path.exists(local):
            return (u, local, "cached")
        os.makedirs(os.path.dirname(local), exist_ok=True)
        r = get(u, stream=True)
        if r is None or r.status_code != 200:
            return (u, "", f"HTTP {getattr(r, 'status_code', 'ERR')}")
        with open(local, "wb") as f:
            for ch in r.iter_content(1 << 16):
                f.write(ch)
        return (u, local, "ok")
    with ThreadPoolExecutor(workers) as ex:
        for res in ex.map(dl, sorted(all_media)):
            rows.append(res)
            print("media", res[2], res[0], file=sys.stderr)
    return rows


def check_external(urls, workers=12):
    def chk(u):
        try:
            r = S.head(u, timeout=15, allow_redirects=True)
            if r.status_code in (405, 403):
                r = S.get(u, timeout=15, allow_redirects=True, stream=True)
            return u, r.status_code, r.url
        except requests.RequestException as e:
            return u, -1, type(e).__name__
    with ThreadPoolExecutor(workers) as ex:
        return list(ex.map(chk, urls))


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--out", default="out")
    ap.add_argument("--langs", default="pl,en,de,uk")
    ap.add_argument("--no-media", action="store_true")
    ap.add_argument("--check-external", action="store_true")
    a = ap.parse_args()
    os.makedirs(a.out, exist_ok=True)
    langs = a.langs.split(",")

    pages, linkmap = crawl(langs)
    dump = lambda name, obj: json.dump(obj, open(os.path.join(a.out, name), "w", encoding="utf-8"), ensure_ascii=False, indent=1)

    dump("pages.json", [{k: v for k, v in p.items() if k != "html"} for p in pages.values()])
    dump("products.json", parse_products(pages))
    dump("services.json", collect(pages, "/pl/uslugi/", 3))
    dump("news.json", collect(pages, "/pl/aktualnosci/", 3))
    dump("galleries.json", collect(pages, "/pl/galerie/", 3))
    dump("static_pages.json", collect(pages, "/pl/strony/", 3) + collect(pages, "/pl/kontakt", 2))
    with open(os.path.join(a.out, "old_urls.txt"), "w") as f:
        for p in sorted(pages.values(), key=lambda x: x["path"]):
            if p["status"] == 200 and "?page=" not in p["url"]:
                f.write(p["path"] + "\n")

    # martwe linki
    rows = []
    for url, p in pages.items():
        if p["status"] != 200:
            for src in sorted(linkmap.get(url, []))[:20]:
                rows.append(["internal", p["status"], p["path"], urlparse(src).path])
    if a.check_external:
        ext = [u for u in linkmap if not is_internal(u)]
        for u, st, final in check_external(ext):
            if st == -1 or st >= 400:
                for src in sorted(linkmap[u])[:20]:
                    rows.append(["external", st, u, urlparse(src).path])
    with open(os.path.join(a.out, "dead_links.csv"), "w", newline="", encoding="utf-8") as f:
        w = csv.writer(f); w.writerow(["type", "status", "dead_url", "found_on"]); w.writerows(rows)

    if not a.no_media:
        all_media = {m for p in pages.values() for m in p.get("media", [])}
        all_media |= {BASE + "/public/images/slides/video.mp4"}
        man = download_media(all_media, a.out)
        with open(os.path.join(a.out, "media_manifest.csv"), "w", newline="", encoding="utf-8") as f:
            w = csv.writer(f); w.writerow(["source_url", "local_path", "result"]); w.writerows(man)
    print(f"\nGotowe: {len(pages)} URL, martwe linki: {len(rows)} -> {a.out}/", file=sys.stderr)


if __name__ == "__main__":
    main()
