# rolmax-strona — nowa strona Rolmax (30 lat · 1997–2027)

Nowa strona **rolmax.net.pl** — autoryzowanego dealera New Holland. Motyw WordPress, podgląd front-endu, scraper starej strony, przekierowania SEO i synchronizacja z katalogiem New Holland PL.

## Struktura repozytorium

```
docs/                 podgląd strony (statyczny HTML) — gotowy pod GitHub Pages
wp-theme/rolmax-30/   motyw WordPress (back end + szablony)
scraper/              scrape_rolmax.py (pełne pobranie starej strony) + clean_data.py (czyszczenie martwych linków)
seo/                  mapa URL, przekierowania 301 (.htaccess / nginx / Redirection CSV), robots.txt, raport martwych linków
nh/                   synchronizacja z New Holland PL (nh_sync.json, blog.json, nh_scan.js)
data/                 dane z analizy starej strony
NH-SYNC.md            raport: porównanie oferty z New Holland, plan bloga i SEO
```

## Podgląd na GitHub Pages
Settings → Pages → Source: *Deploy from a branch* → Branch: `main`, folder: `/docs` → Save.
Po chwili podgląd będzie pod adresem `https://papeiroo.github.io/rolmax-strona/`.

## Instalacja motywu w WordPressie
Spakuj folder `wp-theme/rolmax-30` do ZIP i wgraj: Wygląd → Motywy → Dodaj nowy → Wyślij motyw.
Szczegóły wdrożenia poniżej.

---

## 1. Co wykazała analiza starej strony (8.11.2026)

| | |
|---|---|
| Przeskanowane adresy | **1037** (PL 284 · EN 251 · DE 251 · UK 251) |
| Kategorie maszyn | 9: Traktory (18), Ładowarki teleskopowe (1), Kombajny zbożowe (5), Maszyny zielonkowe (8), Prasy (6), Sieczkarnie samobieżne (1), Pługi (4), Maszyny uprawne (5), Maszyny budowlane (6) |
| Usługi | Części, Serwis, Finansowanie, Likwidacja szkód, Rolnictwo precyzyjne |
| Aktualności / galerie | 159 wpisów, 45 galerii |
| Wideo | 1 plik hero `public/images/slides/video.mp4` (1920×1080, 28 s) |
| **Martwe linki** | **37** — 33× `/pl/galeria/…` (powinno być `/galerie/`) + 4× błędny link „Rolnictwo precyzyjne 2026” w menu |
| Brakujące elementy SEO | brak `sitemap.xml`, brak `robots.txt`, wersje EN/DE/UK to kopie treści PL (duplikaty) |

## 2. Strategia SEO — „nic nie tracimy”

1. **Te same adresy URL 1:1.** Motyw odtwarza dokładnie strukturę: `/pl/maszyny/{kategoria}/{model}`, `/pl/uslugi/{usługa}`, `/pl/aktualnosci/{wpis}`, `/pl/galerie/{galeria}`, `/pl/strony/o-nas`, `/pl/kontakt`. Importer zachowuje slugi. Dla 1000 działających adresów nie potrzeba żadnego przekierowania — pełna lista w `seo/url-map.csv`.
2. **301 tylko tam, gdzie dziś jest błąd** (`seo/.htaccess`, `seo/nginx-redirects.conf` albo import do wtyczki Redirection: `seo/redirection-wordpress.csv`).
3. **Te same tytuły i opisy meta** — importer przenosi meta description do Yoast / Rank Math.
4. **Nowości, które podniosą pozycje:** sitemap XML (Yoast/Rank Math), robots.txt, dane strukturalne JSON-LD (4 oddziały jako AutoDealer, Product na kartach maszyn), hreflang przez Polylang, lżejsze obrazy WebP, szybsze ładowanie.
5. **Wersje EN/DE/UK:** dziś zawierają polski tekst. Rekomendacja: przetłumaczyć najważniejsze strony; nieprzetłumaczone oznaczyć `noindex` do czasu tłumaczenia (adresy zostają, nic nie zwraca 404).
6. Po starcie: dodać sitemapę w Google Search Console, przez 2–4 tygodnie obserwować raport „Strony” i błędy 404.

## 3. Back end — WordPress (motyw `rolmax-30`)

- **Typy treści:** `maszyna` (+ taksonomia `kategoria_maszyn` z podkategoriami), `usluga`, `galeria`, wpisy = aktualności.
- **Panel dla pracowników:** maszyny, kategorie, usługi i galerie edytuje się w kokpicie WP; pola dodatkowe (broszura, link producenta, wideo, galeria, wyróżnik usługi, punkty usługi) w ramce „Rolmax — dane dodatkowe”.
- **Mega menu Oferta/Usługi** budowane automatycznie z bazy — panel wyboru po lewej, kafelki ze zdjęciami po prawej (jak na starej stronie).
- **Wideo:** Wygląd → Dostosuj → „Rolmax — strona główna”: osobny film dla hero, sekcji New Holland premium i sekcji serwisu (MP4/WebM 1080p lub 4K; odtwarzane tylko gdy widoczne).
- **Logo:** Wygląd → Dostosuj → Tożsamość witryny → Logo.
- Wymagane wtyczki: Polylang (języki z prefiksem `/pl/`), Yoast SEO lub Rank Math, Redirection, formularz (np. Contact Form 7 / Fluent Forms dla `/pl/kontakt/order` i `/pl/kontakt/support`).

## 4. Wdrożenie krok po kroku

1. Na komputerze z dostępem do internetu: `pip install requests beautifulsoup4 lxml` → `python scraper/scrape_rolmax.py --check-external` (powstanie folder `out/` z danymi i wszystkimi plikami w oryginalnej jakości).
2. Postaw WordPress na stagingu (np. `nowa.rolmax.net.pl`), zainstaluj wtyczki z pkt 3, wgraj motyw `wp-theme/rolmax-30`.
3. Ustawienia → Bezpośrednie odnośniki → „Nazwa wpisu”. Polylang: język PL z prefiksem w URL.
4. `python scraper/clean_data.py` (czyści martwe linki), potem `wp rolmax import /ścieżka/do/out`, `wp rolmax nh_sync nh/nh_sync.json`, `wp rolmax blog nh/blog.json` — import maszyn, kategorii, usług, aktualności i galerii ze zdjęciami.
5. Utwórz strony: `strony` → `o-nas`, `praca` (+ oferty pracy), `kontakt` → `order`, `support`; strona „Aktualności” jako strona wpisów.
6. Wklej `seo/.htaccess` nad blokiem WordPressa, wgraj `seo/robots.txt`.
7. Test: każdy adres z `scraper/out/old_urls.txt` musi zwracać 200 (skrypt: `xargs -I{} curl -s -o /dev/null -w "%{http_code} {}\n" https://nowa.rolmax.net.pl{} < old_urls.txt | grep -v ^200`).
8. Przełączenie domeny, zgłoszenie sitemapy w Search Console.
