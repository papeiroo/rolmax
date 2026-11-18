<?php
/**
 * Pierwsze uruchomienie: tworzy stronę główną „Strona główna Rolmax" złożoną z widżetów Elementora
 * i ustawia ją jako stronę główną witryny. Można to powtórzyć z kokpitu (Wygląd → Rolmax).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function rx_elementor_home_data() {
	$els = array();
	$i   = 0;
	foreach ( array_keys( rx_sections() ) as $name ) {
		$els[] = array(
			'id'         => substr( md5( 'rx' . $name ), 0, 7 ),
			'elType'     => 'widget',
			'widgetType' => 'rx_' . $name,
			'settings'   => new stdClass(),
			'elements'   => array(),
		);
		$i++;
	}
	$zero = array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true );
	return array( array(
		'id'       => 'rxhome0',
		'elType'   => 'container',
		'isInner'  => false,
		'settings' => array(
			'content_width' => 'full',
			'flex_direction' => 'column',
			'flex_gap'      => array( 'unit' => 'px', 'size' => 0, 'column' => '0', 'row' => '0', 'isLinked' => true ),
			'padding'       => $zero,
			'margin'        => $zero,
			'css_classes'   => 'rx-home',
		),
		'elements' => $els,
	) );
}

function rx_create_home_page( $force = false ) {
	$existing = (int) get_option( 'rx_home_page_id' );
	if ( $existing && get_post_status( $existing ) && ! $force ) return $existing;

	$id = wp_insert_post( array(
		'post_title'   => 'Strona główna Rolmax',
		'post_name'    => 'strona-glowna',
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_content' => '<!-- Strona główna Rolmax: edytuj w Elementorze. Bez Elementora motyw pokazuje sekcje domyślne. -->',
	) );
	if ( ! $id || is_wp_error( $id ) ) return 0;

	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $id, '_wp_page_template', 'elementor_header_footer' );
	update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( rx_elementor_home_data() ) ) );
	if ( defined( 'ELEMENTOR_VERSION' ) ) update_post_meta( $id, '_elementor_version', ELEMENTOR_VERSION );
	update_post_meta( $id, '_rx_home', 1 );

	update_option( 'rx_home_page_id', $id );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $id );
	return $id;
}

add_action( 'after_switch_theme', function () { rx_create_home_page(); } );

/* Wygląd → Rolmax: status i przycisk do odtworzenia strony głównej. */
add_action( 'admin_menu', function () {
	add_theme_page( 'Rolmax', 'Rolmax', 'manage_options', 'rolmax', 'rx_admin_page' );
} );
function rx_admin_page() {
	$id = (int) get_option( 'rx_home_page_id' );
	echo '<div class="wrap"><h1>Motyw Rolmax 2027</h1>';
	if ( isset( $_GET['rx_done'] ) ) echo '<div class="notice notice-success"><p>Utworzono nową stronę główną Rolmax.</p></div>';
	echo '<p><b>Elementor:</b> ' . ( defined( 'ELEMENTOR_VERSION' ) ? 'aktywny (' . esc_html( ELEMENTOR_VERSION ) . ')' : 'nieaktywny — zainstaluj wtyczkę Elementor, aby edytować sekcje.' ) . '</p>';
	if ( $id && get_post_status( $id ) ) {
		echo '<p><b>Strona główna:</b> <a href="' . esc_url( get_permalink( $id ) ) . '" target="_blank">' . esc_html( get_the_title( $id ) ) . '</a>';
		if ( defined( 'ELEMENTOR_VERSION' ) ) echo ' · <a class="button button-primary" href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=elementor' ) ) . '">Edytuj w Elementorze</a>';
		echo '</p>';
	}
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	wp_nonce_field( 'rx_home' );
	echo '<input type="hidden" name="action" value="rx_home"><p><button class="button">Utwórz od nowa stronę główną Rolmax</button> <small>(nowa strona z domyślnymi sekcjami; obecna zostaje w Stronach)</small></p></form>';
	echo '<p>Podstrony (oferta, maszyny, usługi, kontakt, pokazy DEMO, używane, finansowanie CNH Capital) działają w tym etapie jak w projekcie — pod adresami z #, np. <code>' . esc_html( home_url( '/#maszyna-t5s' ) ) . '</code>.</p></div>';
}
add_action( 'admin_post_rx_home', function () {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'rx_home' ) ) wp_die( 'Brak uprawnień.' );
	rx_create_home_page( true );
	wp_safe_redirect( admin_url( 'themes.php?page=rolmax&rx_done=1' ) );
	exit;
} );

/* Gdy Elementor zostanie włączony po motywie — przypomnienie w kokpicie. */
add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) ) return;
	if ( defined( 'ELEMENTOR_VERSION' ) ) return;
	echo '<div class="notice notice-info"><p><b>Rolmax 2027:</b> zainstaluj i włącz wtyczkę <b>Elementor</b>, aby edytować sekcje strony głównej. Bez niej strona wyświetla się z treściami domyślnymi.</p></div>';
} );
