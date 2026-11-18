<?php
/**
 * Sekcje strony głównej Rolmax — definicje pól (edytowalne w Elementorze) i wartości domyślne 1:1 z projektu.
 * Każda sekcja: tytuł, ikona, pola. Typy pól: text, textarea, url, media, switcher, repeater (z polami 'fields').
 * W tekstach można użyć znaczników: {total} = liczba modeli w ofercie, {count} = liczba nowości.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function rx_sections() {
	static $S = null;
	if ( $S ) return $S;
	$S = array(
		'hero' => array( 'title' => 'Rolmax · Hero (wideo)', 'icon' => 'eicon-video-camera', 'fields' => array(
			'badge_eyebrow' => array( 'Plakietka — napis', 'text', 'Napędzani pasją' ),
			'badge_year'    => array( 'Plakietka — rok', 'text', 'od 1997' ),
			'eyebrow'       => array( 'Nadtytuł', 'text', 'Autoryzowany Dealer · Lubelszczyzna' ),
			'title1'        => array( 'Tytuł — linia 1', 'text', 'Klasa premium' ),
			'title2'        => array( 'Tytuł — linia 2', 'text', 'New Holland' ),
			'text'          => array( 'Opis', 'textarea', 'Od 1997 roku dostarczamy gospodarstwom Lubelszczyzny maszyny jednej z najbardziej prestiżowych marek rolniczych świata — z pełnym serwisem, częściami i finansowaniem w jednym miejscu.' ),
			'btn1_text'     => array( 'Przycisk 1 — tekst', 'text', 'Poznaj ofertę' ),
			'btn1_url'      => array( 'Przycisk 1 — link', 'url', '#oferta' ),
			'btn2_text'     => array( 'Przycisk 2 — tekst', 'text', 'Serwis 24/7 w żniwa' ),
			'btn2_url'      => array( 'Przycisk 2 — link', 'url', '#usluga-serwis' ),
			'video_mp4'     => array( 'Wideo MP4', 'url', 'assets/hero.mp4' ),
			'video_webm'    => array( 'Wideo WEBM', 'url', 'assets/hero.webm' ),
			'poster'        => array( 'Obraz zastępczy wideo', 'media', 'assets/poster.jpg' ),
			'anniv_num'     => array( 'Jubileusz — liczba', 'text', '30' ),
			'anniv_lbl'     => array( 'Jubileusz — podpis', 'text', 'lat Rolmax' ),
			'anniv_yrs'     => array( 'Jubileusz — lata', 'text', '1997 — 2027' ),
			'anniv_url'     => array( 'Jubileusz — link', 'url', '#jubileusz' ),
		) ),
		'kategorie' => array( 'title' => 'Rolmax · Kategorie maszyn', 'icon' => 'eicon-gallery-grid', 'fields' => array(
			'wheel' => array( 'Pokaz kategorii przy pierwszej wizycie', 'switcher', 'yes' ),
		) ),
		'uslugi' => array( 'title' => 'Rolmax · Usługi', 'icon' => 'eicon-flip-box', 'fields' => array(
			'eyebrow'    => array( 'Nadtytuł', 'text', 'Najważniejsze usługi' ),
			'title'      => array( 'Tytuł', 'text', 'Wszystko dla Twojej maszyny' ),
			'lead'       => array( 'Opis', 'textarea', 'Sprzedaż, serwis, części i finansowanie — kompletna obsługa w czterech oddziałach i online.' ),
			'shop_tag'   => array( 'Sklep — etykieta', 'text', 'Online 24h' ),
			'shop_title' => array( 'Sklep — tytuł', 'text', 'Sklep internetowy' ),
			'shop_text'  => array( 'Sklep — opis', 'text', 'Tysiące części i akcesoriów z wysyłką — rolmax-sklep.pl' ),
			'shop_more'  => array( 'Sklep — link (tekst)', 'text', 'Przejdź do sklepu' ),
			'shop_url'   => array( 'Sklep — adres', 'url', 'https://www.rolmax-sklep.pl/' ),
			'shop_img'   => array( 'Sklep — zdjęcie', 'media', 'assets/img/sklep-banner.webp' ),
		) ),
		'jubileusz' => array( 'title' => 'Rolmax · Jubileusz 30 lat', 'icon' => 'eicon-counter', 'fields' => array(
			'big'     => array( 'Duża liczba', 'text', '30' ),
			'big_sub' => array( 'Podpis liczby', 'text', 'Lat · 1997 — 2027' ),
			'eyebrow' => array( 'Nadtytuł', 'text', 'Jubileusz 2027' ),
			'title'   => array( 'Tytuł', 'text', '30 lat z rolnikami Lubelszczyzny' ),
			'lead'    => array( 'Opis', 'textarea', 'W 2027 roku Rolmax obchodzi 30-lecie. Trzy dekady pracy, tysiące obsłużonych maszyn i zaufanie gospodarstw, które rosły razem z nami. Dziękujemy — i zapraszamy na jubileuszowy rok pełen wydarzeń, pokazów i ofert specjalnych.' ),
			'stats'   => array( 'Liczniki', 'repeater', array(
				array( 'value' => '30', 'label' => 'lat doświadczenia' ),
				array( 'value' => '4', 'label' => 'oddziały w woj. lubelskim' ),
				array( 'value' => '{total}', 'label' => 'modeli New Holland w ofercie' ),
				array( 'value' => '24/7', 'label' => 'serwis w sezonie żniwnym' ),
			), array( 'value' => array( 'Wartość (liczba animuje się; {total} = liczba modeli)', 'text' ), 'label' => array( 'Podpis', 'text' ) ), 'value' ),
			'timeline' => array( 'Oś czasu', 'repeater', array(
				array( 'year' => '1997', 'text' => 'Założenie firmy Rolmax' ),
				array( 'year' => 'NH', 'text' => 'Autoryzowany Dealer i Serwis New Holland' ),
				array( 'year' => '4', 'text' => 'Świdnik · Miączyn · Rudnik · Białka' ),
				array( 'year' => '2027', 'text' => 'Jubileusz 30-lecia' ),
			), array( 'year' => array( 'Rok / znak', 'text' ), 'text' => array( 'Opis', 'text' ) ), 'year' ),
		) ),
		'premium' => array( 'title' => 'Rolmax · Pasek wideo New Holland', 'icon' => 'eicon-youtube', 'fields' => array(
			'mask'    => array( 'Napis w masce', 'text', 'NEW HOLLAND' ),
			'eyebrow' => array( 'Nadtytuł', 'text', 'New Holland Agriculture' ),
			'title1'  => array( 'Tytuł — linia 1', 'text', 'Technologia.' ),
			'title2'  => array( 'Tytuł — linia 2', 'text', 'Prestiż. Niezawodność.' ),
			'lead'    => array( 'Opis', 'textarea', 'New Holland to marka premium, wybierana przez najbardziej wymagające gospodarstwa w Europie. Precyzja inżynierii, komfort kabin klasy luksusowej i inteligentne systemy PLM — teraz z lokalnym wsparciem Rolmax.' ),
			'points'  => array( 'Wyróżniki', 'repeater', array(
				array( 'title' => 'Inżynieria premium', 'text' => 'Ciągniki T8 GENESIS™, T7 Heavy Duty i kombajny CR Revelation — flagowe konstrukcje marki.' ),
				array( 'title' => 'Komfort klasy lux', 'text' => 'Kabiny Horizon™, konfigurowalne sterowanie i pełna łączność z gospodarstwem.' ),
				array( 'title' => 'Pewność inwestycji', 'text' => 'Oryginalne części, autoryzowany serwis i fabryczne finansowanie CNH Capital.' ),
			), array( 'title' => array( 'Tytuł', 'text' ), 'text' => array( 'Opis', 'textarea' ) ), 'title' ),
			'video_mp4'  => array( 'Wideo MP4', 'url', 'assets/hero.mp4' ),
			'video_webm' => array( 'Wideo WEBM', 'url', 'assets/hero.webm' ),
			'start'      => array( 'Start wideo (sekunda)', 'text', '8' ),
		) ),
		'oferta' => array( 'title' => 'Rolmax · Oferta (eksplorator)', 'icon' => 'eicon-products', 'fields' => array(
			'eyebrow' => array( 'Nadtytuł', 'text', 'Oferta maszyn' ),
			'title'   => array( 'Tytuł', 'text', 'Wybierz kategorię' ),
			'lead'    => array( 'Opis ({total} = liczba modeli)', 'textarea', '{total} modeli w 9 kategoriach. Najedź na kategorię po lewej, aby zobaczyć maszyny.' ),
		) ),
		'serwis' => array( 'title' => 'Rolmax · Serwis 24/7', 'icon' => 'eicon-tools', 'fields' => array(
			'eyebrow'  => array( 'Nadtytuł', 'text', 'Serwis Rolmax' ),
			'title1'   => array( 'Tytuł — część 1', 'text', 'W żniwa' ),
			'title2'   => array( 'Tytuł — część 2', 'text', 'jesteśmy 24/7' ),
			'lead'     => array( 'Opis', 'textarea', 'Wiemy, że w rolnictwie nie można czekać do jutra. W sezonie żniwnym nasz serwis pracuje całą dobę.' ),
			'btn_text' => array( 'Przycisk — tekst', 'text', 'Zgłoś serwis' ),
			'btn_url'  => array( 'Przycisk — link', 'url', '#usluga-serwis' ),
			'phones'   => array( 'Telefony', 'text', '81 723 69 54 · 509 497 101' ),
			'video_mp4'  => array( 'Wideo MP4', 'url', 'assets/hero.mp4' ),
			'video_webm' => array( 'Wideo WEBM', 'url', 'assets/hero.webm' ),
			'start'      => array( 'Start wideo (sekunda)', 'text', '3' ),
		) ),
		'nowosci' => array( 'title' => 'Rolmax · Nowości (karuzela)', 'icon' => 'eicon-slider-push', 'fields' => array(
			'eyebrow' => array( 'Nadtytuł', 'text', 'Nowości New Holland 2026/2027' ),
			'title'   => array( 'Tytuł', 'text', 'Najnowsze maszyny w ofercie' ),
			'lead'    => array( 'Opis ({count} = liczba nowości)', 'textarea', 'Oferta zsynchronizowana z katalogiem New Holland Polska — {count} nowych modeli i rozwiązań.' ),
		) ),
		'promocje' => array( 'title' => 'Rolmax · Pasek promocji', 'icon' => 'eicon-price-list', 'fields' => array(
			'items' => array( 'Promocje', 'repeater', array(
				array( 'tag' => 'Serwis', 'title' => 'Przegląd posezonowy', 'text' => 'Umów przegląd kombajnu i ciągnika na zimę — terminy w Świdniku i Miączynie.', 'url' => '#wpis-przeglad-posezonowy-kombajnu-i-ciagnika-checklista' ),
				array( 'tag' => 'Finansowanie', 'title' => 'Nawet do 10 lat', 'text' => 'Akcja New Holland z finansowaniem CNH Capital — zapytaj doradcę o warunki.', 'url' => '#wpis-finansowanie-maszyn-new-holland-cnh-capital' ),
				array( 'tag' => 'Najpopularniejszy w PL', 'title' => 'T5S na placu', 'text' => 'T5.90S — najczęściej wybierany ciągnik w Polsce. Finansowanie fabryczne.', 'url' => '#maszyna-t5s' ),
				array( 'tag' => 'Nowość', 'title' => 'T7 XD 360–435 KM', 'text' => 'Nowa, najmocniejsza seria T7 — dla usługodawców i dużych gospodarstw.', 'url' => '#maszyna-t7-xd' ),
			), array( 'tag' => array( 'Etykieta', 'text' ), 'title' => array( 'Tytuł', 'text' ), 'text' => array( 'Opis', 'textarea' ), 'url' => array( 'Link', 'url' ) ), 'title' ),
		) ),
		'blog' => array( 'title' => 'Rolmax · Blog (3 wpisy)', 'icon' => 'eicon-posts-grid', 'fields' => array(
			'eyebrow'  => array( 'Nadtytuł', 'text', 'Blog Rolmax' ),
			'title'    => array( 'Tytuł', 'text', 'Wiedza z pola i serwisu' ),
			'btn_text' => array( 'Przycisk — tekst', 'text', 'Wszystkie artykuły' ),
			'btn_url'  => array( 'Przycisk — link', 'url', '#blog' ),
		) ),
		'aktualnosci' => array( 'title' => 'Rolmax · Aktualności', 'icon' => 'eicon-post-list', 'fields' => array(
			'eyebrow'  => array( 'Nadtytuł', 'text', 'Aktualności' ),
			'title'    => array( 'Tytuł', 'text', 'Co nowego' ),
			'btn_text' => array( 'Przycisk — tekst', 'text', 'Wszystkie wpisy' ),
			'btn_url'  => array( 'Przycisk — link', 'url', '#aktualnosci' ),
		) ),
		'galeria' => array( 'title' => 'Rolmax · Galeria', 'icon' => 'eicon-gallery-masonry', 'fields' => array(
			'eyebrow'  => array( 'Nadtytuł', 'text', 'Galeria' ),
			'title'    => array( 'Tytuł', 'text', 'Rolmax w terenie' ),
			'btn_text' => array( 'Przycisk — tekst', 'text', 'Wszystkie galerie' ),
			'btn_url'  => array( 'Przycisk — link', 'url', '#galeria' ),
		) ),
		'oddzialy' => array( 'title' => 'Rolmax · Oddziały (mapa)', 'icon' => 'eicon-map-pin', 'fields' => array(
			'eyebrow' => array( 'Nadtytuł', 'text', 'Kontakt' ),
			'title'   => array( 'Tytuł', 'text', 'Nasze oddziały' ),
			'lead'    => array( 'Uwaga', 'textarea', '* W sezonie jesienno-zimowym (do końca lutego) działy części nie pracują w soboty.' ),
		) ),
	);
	return $S;
}

/** Wartości domyślne sekcji. */
function rx_section_defaults( $name ) {
	$out = array();
	$S   = rx_sections();
	foreach ( $S[ $name ]['fields'] as $k => $f ) $out[ $k ] = $f[2];
	return $out;
}

/** Adres pliku: ścieżki "assets/…" wskazują na katalog motywu. */
function rx_asset( $v ) {
	if ( is_array( $v ) ) $v = isset( $v['url'] ) ? $v['url'] : '';
	$v = (string) $v;
	if ( $v === '' ) return '';
	if ( strpos( $v, 'assets/' ) === 0 ) return get_template_directory_uri() . '/' . $v;
	return $v;
}
function rx_tok( $html ) {
	return str_replace( array( '{total}', '{count}' ), array( '<span data-rxv="total"></span>', '<span data-rxv="news"></span>' ), $html );
}
function rx_e( $s, $k ) { return rx_tok( esc_html( isset( $s[ $k ] ) ? $s[ $k ] : '' ) ); }
function rx_p( $s, $k ) { return rx_tok( wp_kses_post( isset( $s[ $k ] ) ? $s[ $k ] : '' ) ); }
function rx_url( $s, $k ) {
	$v = isset( $s[ $k ] ) ? $s[ $k ] : '';
	if ( is_array( $v ) ) $v = isset( $v['url'] ) ? $v['url'] : '';
	return esc_attr( $v === '' ? '#' : $v );
}
function rx_media( $s, $k ) { return esc_url( rx_asset( isset( $s[ $k ] ) ? $s[ $k ] : '' ) ); }

/** Wyświetla sekcję (używane przez widżety Elementora i przez stronę główną bez Elementora). */
function rx_section( $name, $settings = array() ) {
	$S = rx_sections();
	$s = rx_section_defaults( $name );
	foreach ( (array) $settings as $k => $v ) {
		if ( ! isset( $S[ $name ]['fields'][ $k ] ) ) continue;
		$type = $S[ $name ]['fields'][ $k ][1];
		if ( $type === 'switcher' ) { $s[ $k ] = $v; continue; }
		if ( is_array( $v ) && $type === 'media' ) { if ( ! empty( $v['url'] ) ) $s[ $k ] = $v; continue; }
		if ( $v !== null && $v !== '' ) $s[ $k ] = $v;
	}
	$file = get_template_directory() . '/sections/' . $name . '.php';
	if ( file_exists( $file ) ) include $file;
}
