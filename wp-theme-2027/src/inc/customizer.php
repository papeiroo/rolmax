<?php
/** Wygląd → Dostosuj → Rolmax: ustawienia globalne (licznik jubileuszu, przycisk w nagłówku). */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'customize_register', function ( $c ) {
	$c->add_section( 'rx_opts', array( 'title' => 'Rolmax', 'priority' => 30 ) );
	$fields = array(
		'rx_jub_date' => array( 'Licznik jubileuszu — data i godzina (ISO)', '2027-02-24T00:00:00+01:00' ),
		'rx_cta'      => array( 'Przycisk w nagłówku — tekst', 'Pokazy DEMO' ),
		'rx_phone'    => array( 'Stopka — telefon', '+48 81 721 67 85' ),
		'rx_mail'     => array( 'Stopka — e-mail', 'rolmax@rolmax.net.pl' ),
		'rx_about'    => array( 'Stopka — opis', 'Autoryzowany Dealer i Serwis New Holland. Oddziały: Świdnik, Miączyn, Rudnik, Białka.' ),
	);
	foreach ( $fields as $k => $f ) {
		$c->add_setting( $k, array( 'default' => $f[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$c->add_control( $k, array( 'label' => $f[0], 'section' => 'rx_opts', 'type' => 'text' ) );
	}
} );
function rx_mod( $k, $d ) { return get_theme_mod( $k, $d ); }
