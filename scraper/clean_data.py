#!/usr/bin/env python3
"""
ROLMAX — czyszczenie danych ze scrapera PRZED importem do WordPressa.

Co robi (na plikach w out/, z kopią zapasową *.bak.json):
  1. Naprawia wewnętrzne martwe linki w treściach:
       /xx/galeria/<slug>                    -> /xx/galerie/<slug>
       /xx/strony/https:/cnhi-dealer-...      -> https://cnhi-dealer-...
  2. Usuwa link (zostawia sam tekst) do zewnętrznych adresów, które zwróciły 404/500 w dead_links.csv.
  3. Czyści pole "link producenta" w maszynach, jeśli prowadzi do martwej strony (przycisk się nie pokaże).
     Linki ze statusem -1 (timeout — strona New Holland blokuje boty) zostają, ale trafiają do raportu do ręcznego sprawdzenia.
  4. Usuwa z opisów maszyn zbędny nagłówek "Opis".
  5. Zapisuje raport: out/clean_report.txt

Użycie:  python clean_data.py            (domyślnie folder out)
         python clean_data.py --out out
"""
import argparse, csv, json, os, re, shutil
from collections import defaultdict

FILES = ["products.json", "services.json", "news.json", "galleries.json", "static_pages.json"]
INTERNAL_FIX = [
    (re.compile(r'(href=["\'])(?:https?://(?:www\.)?rolmax\.net\.pl)?/?(pl|en|de|uk)/galeria/'), r'\1/\2/galerie/'),
    (re.compile(r'(href=["\'])(?:https?://(?:www\.)?rolmax\.net\.pl)?/?(?:pl|en|de|uk)/strony/(https?):/+'), r'\1\2://'),
]


def load_dead(path):
    dead, unsure = set(), set()
    with open(path, encoding="utf-8") as f:
        for row in csv.DictReader(f):
            if row["type"] != "external":
                continue
            (unsure if row["status"] == "-1" else dead).add(row["dead_url"])
    return dead, unsure


def unlink(html, url, stats):
    pat = re.compile(r'<a\b[^>]*href=["\']' + re.escape(url) + r'["\'][^>]*>(.*?)</a>', re.S | re.I)
    new, n = pat.subn(r'\1', html)
    if n:
        stats["usuniete_linki_zewn"] += n
    return new


def clean_html(html, dead, stats):
    if not html:
        return html
    for pat, rep in INTERNAL_FIX:
        html, n = pat.subn(rep, html)
        stats["naprawione_linki_wewn"] += n
    for url in dead:
        if url in html:
            html = unlink(html, url, stats)
    return html


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--out", default="out")
    a = ap.parse_args()
    dead, unsure = load_dead(os.path.join(a.out, "dead_links.csv"))
    stats, report = defaultdict(int), []

    for name in FILES:
        path = os.path.join(a.out, name)
        if not os.path.exists(path):
            continue
        shutil.copyfile(path, path.replace(".json", ".bak.json"))
        data = json.load(open(path, encoding="utf-8"))

        if name == "products.json":
            seen_desc = defaultdict(list)
            for cat in data:
                for m in cat["items"]:
                    html = clean_html(m.get("description_html", ""), dead, stats)
                    html = re.sub(r'(>\s*)Opis(\s*<)', r'\1\2', html, count=1)
                    m["description_html"] = html
                    man = m.get("manufacturer_url")
                    if man in dead:
                        report.append(f"[link producenta usunięty – 404] {cat['slug']}/{m['slug']}: {man}")
                        m["manufacturer_url"] = None
                        stats["usuniete_linki_producenta"] += 1
                    elif man in unsure:
                        report.append(f"[link producenta do ręcznego sprawdzenia – timeout] {cat['slug']}/{m['slug']}: {man}")
                    text = re.sub(r"\s+", " ", re.sub("<[^>]+>", " ", html)).strip()
                    if len(text) < 50:
                        report.append(f"[BRAK OPISU – uzupełnij w WordPressie] {cat['slug']}/{m['slug']}")
                    else:
                        seen_desc[text].append(f"{cat['slug']}/{m['slug']}")
                    if (m.get("meta_description") or "").startswith("Szeroki asortyment"):
                        report.append(f"[ogólny meta description – napisz własny] {cat['slug']}/{m['slug']}")
            for slugs in seen_desc.values():
                if len(slugs) > 1:
                    report.append("[IDENTYCZNY OPIS – duplikat treści dla Google] " + ", ".join(slugs))
        else:
            for row in data:
                for key in ("content_html",):
                    row[key] = clean_html(row.get(key, ""), dead, stats)

        json.dump(data, open(path, "w", encoding="utf-8"), ensure_ascii=False, indent=1)

    lines = ["RAPORT CZYSZCZENIA DANYCH ROLMAX", "=" * 40]
    lines += [f"{k}: {v}" for k, v in sorted(stats.items())]
    lines += ["", "Do decyzji / uzupełnienia:"] + sorted(set(report))
    open(os.path.join(a.out, "clean_report.txt"), "w", encoding="utf-8").write("\n".join(lines))
    print("\n".join(lines))


if __name__ == "__main__":
    main()
