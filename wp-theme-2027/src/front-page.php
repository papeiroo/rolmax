<?php
/** Strona główna: treść z Elementora albo (bez Elementora) sekcje domyślne 1:1. */
get_header();
$rx_el = defined( 'ELEMENTOR_VERSION' ) && is_page() && get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder';
if ( $rx_el ) {
	while ( have_posts() ) { the_post(); the_content(); }
} elseif ( ! is_page() || get_post_meta( get_the_ID(), '_rx_home', true ) ) {
	foreach ( array_keys( rx_sections() ) as $name ) rx_section( $name );
} else {
	while ( have_posts() ) { the_post(); echo '<div class="rx-page"><div class="wrap">'; the_content(); echo '</div></div>'; }
}
get_footer();
