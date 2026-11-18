<?php
/** Zwykła strona WordPressa. Strony z Elementora wyświetlają się na pełną szerokość. */
if ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
	get_header(); while ( have_posts() ) { the_post(); the_content(); } get_footer(); return;
}
require __DIR__ . '/index.php';
