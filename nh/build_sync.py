#!/usr/bin/env python3
"""Buduje nh_sync.json: porównanie oferty Rolmax z aktualnym katalogiem New Holland PL (stan 8.11.2026)."""
import json

NH = "https://agriculture.newholland.com/pl-pl/europe/produkty/"
d = json.load(open("nh/nh.json"))
BR = d["brochures"]
br = lambda k: (BR.get(k) or [[None, None]])[0][1]

# --- 1. Istniejące maszyny Rolmax -> odpowiednik NH --------------------------------------------
# status: aktualny | archiwalny (301 na następcę) | brak-w-katalogu-nh (do potwierdzenia przez sprzedaż)
EXISTING = {
 "traktory": {
  "t8-genesis": ("ciagniki-rolnicze/t8-genesis", "aktualny", None),
  "t7-heavy-duty-z-plm-intelligence": ("ciagniki-rolnicze/t7-heavy-duty-z-plm-intelligence", "aktualny", None),
  "t7-heavy-duty-stage-v": (None, "archiwalny", "t7-heavy-duty-z-plm-intelligence"),
  "t7-heavy-duty": (None, "archiwalny", "t7-heavy-duty-z-plm-intelligence"),
  "t7-lwb-stage-v": ("ciagniki-rolnicze/t7-lwb-stage-v", "aktualny", None),
  "t7-swb-stage-v": ("ciagniki-rolnicze/t7-swb-stage-v", "aktualny", None),
  "t7s-stage-v": ("ciagniki-rolnicze/t7s-stage-v", "aktualny", None),
  "t6-methane-power": ("ciagniki-rolnicze/t6-methane-power", "aktualny", None),
  "t6-electro-dynamic-auto-command-stage-v": ("ciagniki-rolnicze/t6-stage-v", "aktualny", None),
  "t6-electro-command-dynamic-command-auto-command": (None, "archiwalny", "t6-electro-dynamic-auto-command-stage-v"),
  "t5-dynamic-command-i-auto-command": ("ciagniki-rolnicze/t5-dynamic-command-auto-command", "aktualny", None),
  "t5-electro-command": ("ciagniki-rolnicze/t5-electro-command-stage-v", "aktualny", None),
  "t5-utility-stage-v": ("ciagniki-rolnicze/t5-utility-stage-v", "aktualny", None),
  "t4-f-n-v": ("ciagniki-rolnicze/t4-fnv-stage-v", "aktualny", None),
  "t4-stage-v": ("ciagniki-rolnicze/t4-stage-v", "aktualny", None),
  "t4s-stage-v": ("ciagniki-rolnicze/t4s-stage-v", "aktualny", None),
  "t3f": ("ciagniki-rolnicze/t3f-lp", "aktualny", None),
  "boomer-25-c-55": ("ciagniki-rolnicze/boomer-25c-55", "aktualny", None),
 },
 "ladowarki-teleskopowe": {"serii-th": ("ladowarki-teleskopowe/serii-th", "aktualny", None)},
 "kombajny-zbozowe": {
  "ch": ("kombajny-zbozowe/ch", "aktualny", None),
  "cr-revelation-stage-v": ("kombajny-zbozowe/seria-cr", "aktualny", None),
  "cx7-i-cx8-stage-v": ("kombajny-zbozowe/cx7-i-cx8-stage-v", "aktualny", None),
  "cx5-i-cx6-stage-v": ("kombajny-zbozowe/cx5-i-cx6-stage-v", "aktualny", None),
  "tc-stage-v": ("kombajny-zbozowe/tc-stage-v", "aktualny", None),
 },
 "prasy": {
  "bigbaler-high-density": ("prasy/bigbaler-high-density", "aktualny", None),
  "bigbaler-plus": ("prasy/bigbaler-plus", "aktualny", None),
  "roll-baler": ("prasy/roll-baler", "aktualny", None),
  "roll-belt": ("prasy/rollbelt", "aktualny", None),
  "roll-bar-125": ("prasy/roll-bar-125", "aktualny", None),
  "br-100": ("prasy/br-100", "aktualny", None),
 },
 "sieczkarnie-samobiezne": {"fr-forage-cruiser-stage-v": ("sieczkarnie-samobiezne/fr-forage-cruiser-stage-v", "aktualny", None)},
 "maszyny-budowlane": {
  "kompaktowe-ladowarki-kolowe": ("budownictwo/kompaktowe-ladowarki-kolowe", "aktualny", None),
  "ladowarki-kolowe": ("budownictwo/ladowarki-kolowe-stage-v", "aktualny", None),
  "koparko-ladowarki": ("budownictwo/koparko-ladowarki-stage-v", "aktualny", None),
  "miniladowarki-burtowe-kolowe-i-gasienicowe-serii-300": ("budownictwo/miniladowarki-burtowe-kolowe-i-gasienicowe-serii-300", "aktualny", None),
  "miniladowarki-burtowe-kolowe-i-gasienicowe-serii-200": (None, "archiwalny", "miniladowarki-burtowe-kolowe-i-gasienicowe-serii-300"),
  "mini-koparki-gasienicowe": ("budownictwo/mini-koparki-gasienicowe-serii-d", "aktualny", None),
 },
}
NOT_IN_NH = {"maszyny-zielonkowe", "plugi", "maszyny-uprawne"}

# --- 2. Nowe pozycje z katalogu NH, których Rolmax nie ma (opisy napisane od nowa, fakty z NH) ----
NEW = [
 ("traktory", "t7-xd", "T7 XD", "ciagniki-rolnicze/t7-xd", "360–435 KM", True,
  "Zupełnie nowa, najmocniejsza rodzina ciągników T7. Modele T7.360 XD, T7.390 XD i T7.440 XD z silnikiem FPT Cursor 9, przekładnią Auto Command i zintegrowanym rolnictwem precyzyjnym PLM powstały z myślą o usługodawcach i największych gospodarstwach polowych, które potrzebują mocy na WOM, wydajnej hydrauliki i szybkiego transportu w jednej maszynie."),
 ("traktory", "nowy-t7-swb", "Nowy T7 SWB", "ciagniki-rolnicze/nowy-t7-swb", "180–225 KM", True,
  "Nowa generacja najpopularniejszej serii T7 ze standardowym rozstawem osi. Przeprojektowana oś przednia z zawieszeniem, nowe opcje zawieszenia kabiny, cichsze i bardziej przestronne wnętrze, konfigurowalne sterowanie i nowy design Dynamic Blue. Seria została wyróżniona nagrodą Red Dot Design Award 2026."),
 ("traktory", "t7-lwb-z-plm-intelligence", "T7 LWB z PLM Intelligence™", "ciagniki-rolnicze/t7-lwb-z-plm-intelligence", "do 300 KM", True,
  "Ciągnik o długim rozstawie osi z fabrycznie zintegrowaną platformą PLM Intelligence™. Kabina Horizon™ Ultra, podłokietnik SideWinder™ Ultra, zawieszenie kabiny Comfort Ride™ i silniki ECOBlue™ HI-eSCR 2 — dla gospodarstw, które łączą ciężkie prace polowe z pełną kontrolą danych."),
 ("traktory", "t7-270-methane-power-z-plm-intelligence", "T7.270 Methane Power z PLM Intelligence™", "ciagniki-rolnicze/t7-270-methane-power-z-plm-intelligence", "270 KM", True,
  "Ciągnik zasilany biometanem lub CNG o parametrach porównywalnych z klasycznym T7. Silnik FPT NEF 6,7 l, zbiorniki gazu o łącznej pojemności 201 kg wkomponowane w konstrukcję i niższe koszty paliwa — rozwiązanie dla gospodarstw z własną biogazownią i firm stawiających na zrównoważoną produkcję."),
 ("traktory", "t4f-s", "T4F S", "ciagniki-rolnicze/t4f-s", "75 KM", True,
  "Kompaktowy ciągnik specjalistyczny do sadów, winnic i ciasnych zabudowań. Silnik S8000, przekładnie Synchro Shuttle™ 12x12 lub 20x20 z biegiem pełzającym albo Powershuttle, wydajna hydraulika i nowa kabina z widocznością 360°."),
 ("traktory", "t5s", "T5S (T5.90S / T5.100S)", "PROMO:uslugi-i-rozwiazania/oferty-specjalne-i-promocje/t590s-finasowanie-fabryczne-0", "90–100 KM", True,
  "Najczęściej wybierany ciągnik w Polsce w pierwszym półroczu 2025 roku (dane Agritrac). Czterocylindrowy silnik FPT 3,6 l o mocy 90 KM spełniający normę Stage V, czterosłupkowa kabina z klimatyzacją i widocznością 360° — uniwersalny ciągnik dla średnich gospodarstw i świetna maszyna pomocnicza w dużych."),
 ("ladowarki-teleskopowe", "ladowarki-th6-26", "TH6.26", "ladowarki-teleskopowe/ladowarki-th6-26", "73 KM", True,
  "Najmniejsza ładowarka teleskopowa New Holland: szerokość poniżej 2 m i wysokość podnoszenia 6 m. Idealna do obór, kurników, sadów i magazynów, gdzie liczy się zwrotność — bez rezygnacji z komfortu pełnowymiarowej kabiny."),
 ("kombajny-zbozowe", "cr11", "CR11", "kombajny-zbozowe/cr11", "flagowy", True,
  "Flagowy kombajn rotorowy nowej generacji z technologią Twin Rotor™. Zbiornik ziarna 20 000 l, rozładunek do 210 l/s, automatyka ustawień i kamera oceny jakości ziarna. Współpracuje z hederami MacDon FD2 o szerokości ponad 15 m. Pierwszy egzemplarz w Polsce przekazano w 2025 roku."),
 ("kombajny-zbozowe", "seria-cr", "Seria CR (CR10 i CR9.90)", "kombajny-zbozowe/seria-cr", "do 653 KM", True,
  "Rodzina kombajnów z podwójnym rotorem Twin Rotor™ — delikatny omłot, wysoka jakość ziarna i słomy, kabina Harvest Suite™ Ultra i silniki zgodne z normą Stage V. W 2026 roku New Holland szczególnie promuje model CR10 do omłotu kukurydzy."),
 ("kombajny-zbozowe", "hedery-zbozowe-varifeed", "Hedery zbożowe Varifeed™", "kombajny-zbozowe/hedery-zbozowe-varifeed", "4,9–12,5 m", True,
  "Hedery z regulacją położenia kosy w zakresie 575 mm — jeden zespół żniwny do zbóż i rzepaku. Szerokości od 4,9 do 12,5 m, zaawansowane prowadzenie hedera i pełna integracja z kombajnami New Holland."),
 ("kombajny-zbozowe", "hedery-superflex", "Hedery Superflex", "kombajny-zbozowe/hedery-superflex", "6,1–10,67 m", True,
  "Heder z elastyczną listwą tnącą, która kopiuje ukształtowanie terenu i zostawia równe, niskie ściernisko — dobry wybór do soi, grochu i zbóż wylegających. Szerokości od 6,1 do 10,67 m."),
 ("kombajny-zbozowe", "hedery-zbozowe-high-capacity", "Hedery zbożowe High-Capacity", "kombajny-zbozowe/hedery-zbozowe-high-capacity", "4,5–12,5 m", True,
  "Niezawodne hedery o szerokości od 4,5 do 12,5 m, kompatybilne z kombajnami TC, CX, CH i CR. Prosta konstrukcja, wysoka wydajność i szybkie zbiory w standardowych warunkach."),
 ("kombajny-zbozowe", "hedery-do-zbioru-kukurydzy", "Hedery do zbioru kukurydzy", "kombajny-zbozowe/hedery-do-zbioru-kukurydzy", "sztywne i składane", True,
  "Przystawki do kukurydzy w wersjach sztywnych i składanych, opcjonalnie z rozdrabnianiem łodyg. Niskie straty kolb i ziarna, przygotowane pole pod kolejną uprawę."),
 ("kombajny-zbozowe", "zaawansowane-podbieracze", "Podbieracze do kombajnów", "kombajny-zbozowe/zaawansowane-podbieracze", "3,65–4,57 m", True,
  "Podbieracze o szerokości od 3,65 do 4,57 m do zbioru z pokosów: traw nasiennych, rzepaku i grochu. Współpracują z kombajnami CR i CX."),
 ("kombajny-zbozowe", "zespoly-zniwne-fd2-flexdraper-firmy-macdon", "MacDon FD2 FlexDraper®", "kombajny-zbozowe/zespoly-zniwne-fd2-flexdraper-firmy-macdon", "do ponad 15 m", True,
  "Heder taśmowy MacDon z elastyczną listwą tnącą zapewnia równomierne podawanie materiału nawet przy największych szerokościach roboczych. Kompatybilny z kombajnami New Holland CR i CX."),
 ("prasy", "roll-belt-plus", "Roll-Belt™ Plus (150 Plus / 180 Plus)", "prasy/roll-belt-plus", "zmienna komora", True,
  "Nowe prasy zwijające ze zmienną komorą i najwyższą gęstością beli w gamie New Holland. Wzmocnione podbieracze, rotor ActiveSweep™ do delikatnych materiałów i wybór systemu owijania — mniej kosztów składowania i transportu paszy."),
 ("prasy", "pro-belt", "Pro-Belt™", "prasy/pro-belt", "zmienna komora", True,
  "Profesjonalna prasa zmiennokomorowa dla usługodawców: rotor 52 cm, systemy SuperFeed™ lub CropCutter® z 13 albo 25 nożami, przekładnia TwinDrive i bardzo gęste, równe bele w każdych warunkach."),
 ("prasy", "roll-bar-utility", "Roll-Bar™ Utility (120 / 150)", "prasy/roll-bar-utility", "stała komora", True,
  "Prosta, wytrzymała i przystępna cenowo prasa zwijająca dla małych i średnich gospodarstw. Bele o średnicy 1,20 m lub 1,50 m, łatwa regulacja gęstości i nowa stylistyka z bocznymi osłonami ułatwiającymi serwis."),
 ("maszyny-budowlane", "ladowarki-kolowe-w100d", "Ładowarka kołowa W100D", "budownictwo/ladowarki-kolowe-w100d", "112 KM", True,
  "Kompaktowa ładowarka kołowa do prac w gospodarstwie i firmie: napęd na wszystkie koła, inteligentna hydraulika z regulacją reakcji, szybka wymiana osprzętu, silnik Stage V i kabina z widocznością 360°."),
 ("maszyny-budowlane", "ladowarki-kolowe-w170d-forage-power", "Ładowarka kołowa W170D+ Forage Power", "budownictwo/ladowarki-kolowe-w170d-forage-power", "255 KM", True,
  "Ładowarka stworzona do pracy na pryzmie kiszonki i w biogazowniach. Zbiornik paliwa 400 l na pełną zmianę, 6-biegowa przekładnia maksymalizująca siłę pchania i kabina D+ z widocznością niemal 360°. Tytuł FARM MACHINE 2026 na targach Agritechnica."),
 ("maszyny-budowlane", "midi-koparki-gasienicowe-serii-d", "Midi koparki gąsienicowe serii D", "budownictwo/midi-koparki-gasienicowe-serii-d", "5 modeli", True,
  "Pięć modeli midi koparek z wysięgnikiem przednim lub bocznym, automatycznym smarowaniem i zintegrowanym lemieszem. Kompaktowe, a wydajne — do budowy, kształtowania terenu i prac komunalnych."),
 ("maszyny-budowlane", "mini-ladowarka-gasienicowa", "Mini ładowarka gąsienicowa C314 / C314X", "budownictwo/mini-ladowarka-gasienicowa", "C314X — elektryczna", True,
  "Najmniejsza ładowarka gąsienicowa New Holland do pracy w ciasnych miejscach, z szeroką gamą osprzętu (świdry, koparki do rowów, chwytaki). Wersja C314X jest w pełni elektryczna — cicha i bezemisyjna."),
 ("technologia-precyzyjna", "fieldops", "New Holland FieldOps™", "technologia-precyzyjna/lacznosc-i-fieldops/fieldops", "aplikacja", True,
  "Platforma do zarządzania gospodarstwem na telefonie, tablecie i komputerze: lokalizacja i stan maszyn w czasie rzeczywistym, poziom paliwa, postęp prac, mapy plonów i zdalny podgląd wyświetlacza — także dla flot mieszanych."),
 ("technologia-precyzyjna", "intellisteer", "Automatyczne prowadzenie IntelliSteer™", "technologia-precyzyjna/rozwiazania-w-zakresie-prowadzenia-i-sterowania/intellisteer", "15 cm – 2,5 cm RTK+", True,
  "Fabryczny autopilot do ciągników, kombajnów i pras z kompensacją terenu i dokładnością od 15 cm do 2,5 cm (RTK+). Mniej nakładek i omijaków, mniejsze zmęczenie operatora i niższe zużycie środków."),
]

out = {"generated": "2026-11-08", "source": "https://agriculture.newholland.com/pl-pl/europe", "existing": [], "new": [], "redirects": [], "categories_new": []}
for cat, items in EXISTING.items():
    for slug, (nh, status, succ) in items.items():
        row = {"category": cat, "slug": slug, "status": status}
        if nh:
            row.update(manufacturer_url=NH + nh, brochure_pdf=br(nh))
        if succ:
            row["successor"] = succ
            out["redirects"].append([f"/pl/maszyny/{cat}/{slug}", f"/pl/maszyny/{cat}/{succ}"])
        out["existing"].append(row)
for cat in NOT_IN_NH:
    out["existing"].append({"category": cat, "slug": "*", "status": "brak-w-katalogu-nh",
                            "note": "Kategoria nie występuje w aktualnym katalogu New Holland PL — potwierdź w dziale sprzedaży, czy Rolmax nadal ją oferuje. Do tego czasu strony zostają (bez utraty pozycji), z przyciskiem „Zapytaj o dostępność”."})
for cat, slug, name, nh, power, is_new, desc in NEW:
    if nh.startswith("PROMO:"):
        out["new"].append({"category": cat, "slug": slug, "name": name, "url": f"/pl/maszyny/{cat}/{slug}", "power": power, "badge": "NAJPOPULARNIEJSZY",
                           "manufacturer_url": "https://agriculture.newholland.com/pl-pl/europe/" + nh[6:], "brochure_pdf": None, "image_url": None, "description": desc})
        continue
    out["new"].append({"category": cat, "slug": slug, "name": name, "url": f"/pl/maszyny/{cat}/{slug}", "power": power,
                       "badge": "NOWOŚĆ", "manufacturer_url": NH + nh, "brochure_pdf": br(nh),
                       "image_url": d["prods"]["/pl-pl/europe/produkty/" + nh]["og"], "description": desc})
out["categories_new"].append({"slug": "technologia-precyzyjna", "name": "Technologia precyzyjna", "url": "/pl/maszyny/technologia-precyzyjna"})
json.dump(out, open("nh/nh_sync.json", "w"), ensure_ascii=False, indent=1)
print(len(out["existing"]), len(out["new"]), len(out["redirects"]), sum(1 for n in out["new"] if not n["brochure_pdf"]))
