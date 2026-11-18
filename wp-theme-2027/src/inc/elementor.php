<?php
/**
 * Widżety Elementora „Rolmax · …" — każda sekcja strony głównej jako osobny, edytowalny widżet.
 * Wygląd 1:1 z projektu; teksty, linki, zdjęcia i wideo zmieniasz w panelu Elementora.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'elementor/elements/categories_registered', function ( $m ) {
	$m->add_category( 'rolmax', array( 'title' => 'Rolmax', 'icon' => 'fa fa-plug' ) );
} );

add_action( 'elementor/widgets/register', function ( $wm ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) return;
	if ( ! class_exists( 'RX_Section_Widget' ) ) {
		require_once __DIR__ . '/elementor-widget.php';
	}
	foreach ( array_keys( rx_sections() ) as $name ) {
		$cls = 'RX_W_' . $name;
		if ( class_exists( $cls ) ) $wm->register( new $cls() );
	}
} );

/* Elementor nie nakłada własnych domyślnych kolorów i czcionek na motyw. */
add_action( 'after_switch_theme', function () {
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
} );
