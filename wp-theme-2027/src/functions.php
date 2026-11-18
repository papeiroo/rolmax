<?php
/**
 * Rolmax 2027 — motyw WordPress (Autoryzowany Dealer New Holland).
 * Wygląd 1:1 z projektu rolmax.net.pl 2027. Strona główna edytowalna w Elementorze (widżety „Rolmax · …").
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RX_VER', '1.0.0' );
define( 'RX_URI', get_template_directory_uri() );

require_once __DIR__ . '/inc/sections.php';
require_once __DIR__ . '/inc/elementor.php';
require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/customizer.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'elementor' );
	register_nav_menus( array( 'rx_footer' => 'Stopka — kolumna „Rolmax"' ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	$after = wp_style_is( 'elementor-frontend', 'registered' ) ? array( 'elementor-frontend' ) : array();
	wp_enqueue_style( 'rx-fonts', 'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=Barlow:wght@400;500;600&display=swap', array(), null );
	wp_enqueue_style( 'rolmax', RX_URI . '/assets/rolmax.css', $after, RX_VER );
	wp_enqueue_style( 'rolmax-wp', RX_URI . '/assets/rolmax-wp.css', array( 'rolmax' ), RX_VER );

	wp_register_script( 'rx-data', RX_URI . '/js/data.js', array(), RX_VER, true );
	wp_add_inline_script( 'rx-data', 'window.RXT=' . wp_json_encode( trailingslashit( RX_URI ) ) . ';window.RXWP=' . wp_json_encode( array(
		'front' => is_front_page() ? 1 : 0,
		'home'  => home_url( '/' ),
		'jub'   => get_theme_mod( 'rx_jub_date', '2027-02-24T00:00:00+01:00' ),
	) ) . ';', 'before' );
	wp_enqueue_script( 'rx-app', RX_URI . '/js/app.js', array( 'rx-data' ), RX_VER, true );
}, 999 );

/* Lżejszy <head>: bez emoji. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_filter( 'body_class', function ( $c ) { $c[] = 'rx'; if ( is_front_page() ) $c[] = 'rx-front'; return $c; } );
