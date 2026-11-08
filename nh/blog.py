#!/usr/bin/env python3
"""Pierwsze wpisy bloga Rolmax (teksty autorskie, fakty z New Holland PL z linkiem do źródła) + kalendarz treści."""
import json

NH = "https://agriculture.newholland.com/pl-pl/europe/"
POSTS = [
 {
  "slug": "przeglad-posezonowy-kombajnu-i-ciagnika-checklista",
  "title": "Przegląd posezonowy kombajnu i ciągnika — checklista przed zimą",
  "date": "2026-11-09", "topic": "Serwis i poradniki", "img": "s_serwis",
  "meta": "Co sprawdzić w kombajnie i ciągniku po sezonie? Checklista przeglądu posezonowego od serwisu Rolmax — autoryzowanego serwisu New Holland w Świdniku i Miączynie.",
  "keywords": ["przegląd posezonowy kombajnu", "serwis New Holland lubelskie", "przygotowanie ciągnika do zimy"],
  "related": ["kombajny-zbozowe/cx7-i-cx8-stage-v", "kombajny-zbozowe/tc-stage-v", "traktory/t7-swb-stage-v"],
  "html": """<p>Zima to najlepszy moment, żeby maszyny wróciły do pełnej sprawności bez presji czasu. Usterka wykryta w listopadzie kosztuje mniej niż awaria w środku żniw — i nie zabiera najcenniejszego okna pogodowego. New Holland prowadzi w tym sezonie akcję <a href=\"""" + NH + """uslugi-i-rozwiazania/oferty-specjalne-i-promocje/posezonowy-przeglad\" rel=\"noopener\">Posezonowy przegląd</a>, a serwis Rolmax w Świdniku i Miączynie przyjmuje zgłoszenia już teraz.</p>
<h2>Kombajn — 8 punktów kontroli</h2>
<ol><li>Dokładne czyszczenie maszyny (resztki ziarna i plew przyciągają gryzonie i sprzyjają korozji).</li><li>Stan rotora lub bębna młócącego, klepiska i sit.</li><li>Łańcuchy i listwy przenośnika pochyłego, napięcie pasów.</li><li>Noże, palce i prowadnice kosy w hederze.</li><li>Łożyska i punkty smarowania — zgodnie z instrukcją obsługi.</li><li>Układ chłodzenia: chłodnice, płyn, filtry powietrza.</li><li>Instalacja elektryczna, czujniki i oprogramowanie (aktualizacje IntelliView).</li><li>Zabezpieczenie akumulatora i zbiornika paliwa na postój.</li></ol>
<h2>Ciągnik — co sprawdzić przed zimą</h2>
<ul><li>Olej silnikowy, przekładniowy i hydrauliczny oraz komplet filtrów.</li><li>Płyn chłodniczy — odporność na mróz.</li><li>Ciśnienie w ogumieniu, stan hamulców i oświetlenia (krótkie dni = więcej pracy po zmroku).</li><li>Kabina: filtry kabinowe, ogrzewanie, wycieraczki.</li></ul>
<h2>Oryginalne części i REMAN</h2>
<p>Do przeglądu warto zamówić części z wyprzedzeniem. Dział części Rolmax ma cztery punkty stacjonarne i <a href=\"https://www.rolmax-sklep.pl/\">sklep internetowy</a>. Dobrą alternatywą dla nowych podzespołów są fabrycznie regenerowane części REMAN — z gwarancją producenta, w niższej cenie.</p>
<h2>Jak umówić przegląd?</h2>
<p>Zadzwoń do serwisu: Świdnik <strong>81 723 69 54</strong>, Miączyn <strong>84 639 71 86</strong> albo wypełnij formularz zgłoszenia serwisowego na naszej stronie.</p>""",
  "faq": [["Kiedy najlepiej zrobić przegląd posezonowy?", "Najlepiej od listopada do lutego — serwis ma wtedy więcej wolnych terminów, a ewentualne części zdążą dotrzeć przed sezonem."],
          ["Czy przegląd w autoryzowanym serwisie wpływa na gwarancję?", "Regularne przeglądy w autoryzowanym serwisie New Holland z użyciem oryginalnych części pomagają zachować warunki gwarancji i wartość maszyny przy odsprzedaży."]],
  "source": NH + "uslugi-i-rozwiazania/oferty-specjalne-i-promocje/posezonowy-przeglad",
 },
 {
  "slug": "kombajn-new-holland-cr10-kukurydza",
  "title": "Kombajn New Holland CR10 w kukurydzy — więcej ziarna, mniej strat",
  "date": "2026-11-05", "topic": "Kombajny", "img": "b_kombajn-new-holland-cr10",
  "meta": "Jak kombajn New Holland CR10 z technologią Twin Rotor radzi sobie w omłocie kukurydzy? Najważniejsze rozwiązania i dla kogo jest ta maszyna — Rolmax, dealer New Holland.",
  "keywords": ["New Holland CR10", "kombajn do kukurydzy", "Twin Rotor"],
  "related": ["kombajny-zbozowe/seria-cr", "kombajny-zbozowe/cr11", "kombajny-zbozowe/hedery-do-zbioru-kukurydzy"],
  "html": """<p>Zbiór kukurydzy na ziarno to sprawdzian dla każdego kombajnu: duża masa, wilgotne łodygi i wysokie wymagania co do czystości ziarna. New Holland w listopadzie 2026 r. pokazał, jak w tych warunkach pracuje <strong>CR10</strong> z 16-rzędową przystawką do kukurydzy.</p>
<h2>Twin Rotor™ — omłot przez wycieranie, nie uderzanie</h2>
<p>Dwa wzdłużne rotory wycierają ziarno z kolb zamiast je rozbijać. Efekt to mniej połamanego ziarna i lepsza jakość plonu, co przekłada się na cenę w skupie i mniejsze straty przy suszeniu.</p>
<h2>Twin Clean™ — czystość przy dużej przepustowości</h2>
<p>Podwójny system czyszczący utrzymuje czystość ziarna nawet przy wysokim dziennym przerobie. To kluczowe dla gospodarstw wielkoobszarowych i firm usługowych, które liczą koszt w przeliczeniu na hektar.</p>
<h2>Dla kogo CR10?</h2>
<p>Dla gospodarstw z dużym udziałem kukurydzy i rzepaku oraz usługodawców, którzy potrzebują wydajności klasy CR w nieco mniejszym gabarycie niż flagowy <a href=\"/pl/maszyny/kombajny-zbozowe/cr11\">CR11</a>.</p>
<p>Chcesz zobaczyć CR10 w pracy albo porównać go z serią CX? Skontaktuj się z doradcą Rolmax — przygotujemy ofertę z finansowaniem CNH Capital.</p>""",
  "faq": [["Czym różni się CR10 od CR11?", "CR11 to flagowy kombajn z największym zbiornikiem (20 000 l) i najwyższą wydajnością. CR10 korzysta z tej samej technologii Twin Rotor w nieco mniejszej klasie mocy."]],
  "source": NH + "new-holland-world/wiadomosci/2026/kombajn-new-holland-cr10",
 },
 {
  "slug": "new-holland-t5-90s-najpopularniejszy-ciagnik-w-polsce",
  "title": "New Holland T5.90S — dlaczego to najczęściej wybierany ciągnik w Polsce?",
  "date": "2026-10-28", "topic": "Ciągniki", "img": "b_t5-90s-najczesciej-wybieranym-ciagnikiem",
  "meta": "T5.90S był najczęściej rejestrowanym ciągnikiem w Polsce w I półroczu 2025. Sprawdź, co wyróżnia model, ile ma mocy i dla jakich gospodarstw się sprawdzi.",
  "keywords": ["New Holland T5.90S", "ciągnik 90 KM", "najpopularniejszy ciągnik w Polsce"],
  "related": ["traktory/t5s", "traktory/t5-utility-stage-v", "traktory/t5-electro-command"],
  "html": """<p>Według danych Agritrac, które przytacza New Holland, model <strong>T5.90S</strong> był najczęściej wybieranym ciągnikiem w Polsce w pierwszym półroczu 2025 roku. To dobry moment, żeby przyjrzeć się, skąd ta popularność.</p>
<h2>Prosty, oszczędny, wszechstronny</h2>
<ul><li><strong>Silnik:</strong> czterocylindrowy FPT 3,6 l, 90 KM, norma Stage V.</li><li><strong>Kabina:</strong> czterosłupkowa, z klimatyzacją i widocznością 360°.</li><li><strong>Zastosowanie:</strong> główny ciągnik w średnim gospodarstwie albo maszyna pomocnicza w dużym — do ładowacza, transportu, prac z WOM.</li></ul>
<h2>Seria T5S</h2>
<p>W serii są modele T5.90S i T5.100S. Nowa generacja T5S na 2026 rok to m.in. więcej mocy w topowej konfiguracji i lepsza zwrotność.</p>
<h2>Finansowanie</h2>
<p>New Holland prowadzi dla T5S akcję z finansowaniem fabrycznym. Aktualne warunki i dostępność egzemplarzy na placu w Świdniku, Miączynie, Rudniku i Białce podamy telefonicznie: <strong>81 723 69 60</strong>.</p>""",
  "faq": [["Ile koni mechanicznych ma New Holland T5.90S?", "90 KM z czterocylindrowego silnika FPT o pojemności 3,6 l, zgodnego z normą Stage V."]],
  "source": NH + "new-holland-world/wiadomosci/2025/t5-90s-najczesciej-wybieranym-ciagnikiem-w-polsce-w-2025",
 },
 {
  "slug": "nowy-t7-swb-i-t7-xd-co-sie-zmienia",
  "title": "Nowy T7 SWB i T7 XD — co zmienia się w ciągnikach T7 na sezon 2027",
  "date": "2026-10-20", "topic": "Ciągniki", "img": "n_t7-xd",
  "meta": "Nowy New Holland T7 SWB (180–225 KM) i zupełnie nowa seria T7 XD (360–435 KM). Najważniejsze zmiany, nagrody i dla kogo są te ciągniki.",
  "keywords": ["New Holland T7 SWB 2026", "T7 XD", "ciągnik 200 KM"],
  "related": ["traktory/nowy-t7-swb", "traktory/t7-xd", "traktory/t7-lwb-z-plm-intelligence"],
  "html": """<p>Rodzina T7 przechodzi największą zmianę od lat. Na rynek trafiły dwie serie: odświeżony <strong>T7 SWB</strong> oraz zupełnie nowy <strong>T7 XD</strong>, który wprowadza markę w wyższą klasę mocy.</p>
<h2>Nowy T7 SWB (180–225 KM)</h2>
<ul><li>Nowa oś przednia z zawieszeniem i nowe opcje zawieszenia kabiny.</li><li>Cichsza, bardziej przestronna kabina i konfigurowalne sterowanie — od mechanicznego po w pełni elektroniczne.</li><li>Nowy wygląd Dynamic Blue — doceniony nagrodą Red Dot Design Award 2026.</li></ul>
<h2>T7 XD (360–435 KM)</h2>
<p>Modele T7.360 XD, T7.390 XD i T7.440 XD z silnikiem FPT Cursor 9 i przekładnią Auto Command. To ciągnik dla usługodawców i największych gospodarstw: ciężkie prace uprawowe, wymagające zadania z WOM i hydrauliką oraz szybki transport.</p>
<h2>Który wybrać?</h2>
<p>Jeśli potrzebujesz zwrotnego, uniwersalnego ciągnika do ładowacza, prasy i siewnika — T7 SWB. Jeśli Twoje maszyny uprawowe przekraczają możliwości klasycznego T7 — T7 XD. Doradcy Rolmax pomogą dobrać konfigurację i porównać koszty.</p>""",
  "faq": [["Ile mocy ma New Holland T7 XD?", "Seria obejmuje trzy modele o mocy około 360, 390 i 435 KM."]],
  "source": NH + "produkty/ciagniki-rolnicze/t7-xd",
 },
 {
  "slug": "finansowanie-maszyn-new-holland-cnh-capital",
  "title": "Finansowanie maszyn New Holland — jak działa CNH Capital?",
  "date": "2026-10-12", "topic": "Finansowanie", "img": "s_finansowanie",
  "meta": "Leasing, pożyczka czy finansowanie pomostowe? Jak sfinansować ciągnik lub kombajn New Holland przez CNH Capital i jakie dokumenty przygotować.",
  "keywords": ["leasing ciągnika", "finansowanie New Holland", "CNH Capital"],
  "related": ["traktory/t5s", "traktory/nowy-t7-swb", "kombajny-zbozowe/cx5-i-cx6-stage-v"],
  "html": """<p>Fabryczne finansowanie CNH Capital jest przygotowane wyłącznie dla klientów autoryzowanych dealerów New Holland. W Rolmax pomagamy dobrać produkt i złożyć wniosek w jednym miejscu.</p>
<h2>Dostępne produkty</h2>
<ul><li>Pożyczka</li><li>Leasing operacyjny i finansowy</li><li>Finansowanie pomostowe — dla inwestycji z dofinansowaniem UE</li></ul>
<h2>Elastyczne warunki</h2>
<p>Wpłata wstępna już od 0%, możliwość finansowania VAT i raty miesięczne, kwartalne, półroczne lub roczne — dopasowane do przychodów gospodarstwa. New Holland prowadzi też akcje promocyjne, m.in. z finansowaniem rozłożonym nawet na 10 lat; aktualne warunki zawsze potwierdza doradca.</p>
<h2>Jakie dokumenty przygotować?</h2>
<ul><li>potwierdzenie posiadanego areału,</li><li>dokumenty rejestrowe działalności,</li><li>dokumenty finansowe (np. faktury, umowy kontraktacyjne),</li><li>dowód osobisty.</li></ul>
<p>Kontakt do doradcy ds. finansowania: <strong>+48 513 024 012</strong>, finansowanie@rolmax.net.pl.</p>""",
  "faq": [["Czy mogę sfinansować VAT?", "Tak, CNH Capital umożliwia finansowanie podatku VAT."], ["Jaka jest minimalna wpłata własna?", "Wpłata wstępna może wynosić od 0% — zależnie od oferty i oceny wniosku."]],
  "source": NH + "uslugi-i-rozwiazania/oferty-specjalne-i-promocje/10-lat-finansowania",
 },
 {
  "slug": "roll-belt-plus-150-180-nowe-prasy-zwijajace",
  "title": "Roll-Belt™ 150 Plus i 180 Plus — nowe prasy zwijające New Holland",
  "date": "2026-10-02", "topic": "Prasy i zielonki", "img": "n_roll-belt-plus",
  "meta": "Nowe prasy zmiennokomorowe New Holland Roll-Belt 150 Plus i 180 Plus: wyższa gęstość beli, wzmocnione podbieracze i rotor ActiveSweep. Co zyskuje gospodarstwo?",
  "keywords": ["prasa zwijająca zmienna komora", "Roll-Belt Plus", "prasa New Holland"],
  "related": ["prasy/roll-belt-plus", "prasy/pro-belt", "prasy/roll-bar-utility"],
  "html": """<p>New Holland odświeżył gamę pras zwijających o zmiennej komorze. Nowe modele <strong>Roll-Belt™ 150 Plus</strong> i <strong>180 Plus</strong> mają formować najgęstsze bele w ofercie marki.</p>
<h2>Co nowego?</h2>
<ul><li><strong>Wyższa gęstość beli</strong> — mniej beli na hektar, niższe koszty folii, składowania i transportu.</li><li><strong>Wzmocnione podbieracze</strong> przygotowane na duże pokosy.</li><li><strong>Rotor ActiveSweep™</strong> — delikatne podawanie lucerny i traw bez strat liści.</li><li>Wybór systemu owijania siatką lub folią.</li></ul>
<h2>Prasa dla każdego gospodarstwa</h2>
<p>Dla usługodawców New Holland ma profesjonalną <a href=\"/pl/maszyny/prasy/pro-belt\">Pro-Belt™</a>, a dla mniejszych gospodarstw prostą i przystępną <a href=\"/pl/maszyny/prasy/roll-bar-utility\">Roll-Bar™ Utility</a>.</p>""",
  "faq": [["Jaka jest średnica beli z prasy Roll-Belt 180 Plus?", "Oznaczenie modelu odnosi się do maksymalnej średnicy beli — w modelu 180 Plus jest to ok. 1,80 m. Szczegółowe dane podaje broszura producenta."]],
  "source": NH + "new-holland-world/wiadomosci/2025/roll-belt-180-plus-i-150-plus-nowe-prasy",
 },
]

CALENDAR = [
 ["2026-12", "Ciągnik na prezent dla gospodarstwa — Boomer i T4 do prac przydomowych", "ciągnik komunalny, Boomer 50"],
 ["2026-12", "Podsumowanie roku New Holland: nagrody EIMA 2026, Red Dot, Zedelgem 120 lat", "New Holland nowości 2026"],
 ["2027-01", "Rolmax 30 lat — jubileuszowy rok 1997–2027 (start kampanii)", "Rolmax 30-lecie, dealer New Holland lubelskie"],
 ["2027-01", "Dotacje ARiMR na maszyny rolnicze 2027 — harmonogram naborów", "dotacje na ciągnik 2027, modernizacja gospodarstw"],
 ["2027-02", "FieldOps — darmowa łączność z maszyną: jak uruchomić krok po kroku", "FieldOps New Holland, rolnictwo precyzyjne"],
 ["2027-02", "Agrotech Kielce 2027 — co pokaże New Holland", "Agrotech 2027 New Holland"],
 ["2027-03", "Jak dobrać ciągnik do ładowacza czołowego? T5 vs T6", "ciągnik do ładowacza"],
 ["2027-03", "IntelliSteer i sygnały korekcyjne RTK — ile oszczędzasz na nakładkach", "automatyczne prowadzenie ciągnika"],
 ["2027-04", "Przegląd przedżniwny kombajnu — terminy i promocje w Rolmax", "przegląd kombajnu przed żniwami"],
 ["2027-04", "Agro-Park Lublin 2027 — zapraszamy na stoisko Rolmax", "targi rolnicze Lublin"],
 ["2027-05", "Hedery Varifeed czy Superflex? Dobór hedera do upraw", "heder do kombajnu"],
 ["2027-05", "W170D+ Forage Power — ładowarka na pryzmę kiszonki", "ładowarka do kiszonki"],
 ["2027-06", "Serwis 24/7 w żniwa — jak działa dyżur Rolmax", "serwis kombajnów 24h lubelskie"],
 ["2027-06", "Pokazy polowe Rolmax 30 lat — relacja", "pokazy polowe New Holland"],
 ["2027-07", "CR11 vs CX8 — który kombajn dla gospodarstwa 500+ ha", "kombajn rotorowy czy klawiszowy"],
 ["2027-08", "Rejestracje ciągników w Polsce — podsumowanie półrocza", "rejestracje ciągników 2027"],
 ["2027-09", "Agro Show Bednary 2027 — premiery New Holland", "Agro Show 2027"],
 ["2027-09", "Metan i biometan w rolnictwie — T6/T7 Methane Power w praktyce", "ciągnik na biometan"],
 ["2027-10", "Zbiór kukurydzy — ustawienia kombajnu CR krok po kroku", "kombajn do kukurydzy ustawienia"],
 ["2027-11", "Rolmax 30 lat — podziękowanie klientom i oferta jubileuszowa", "Rolmax jubileusz"],
]
json.dump({"posts": POSTS, "calendar": CALENDAR}, open("nh/blog.json", "w"), ensure_ascii=False, indent=1)
print(len(POSTS), len(CALENDAR))
