<?php
/**
 * Rolmax 30 — functions.php
 *
 * Najważniejsze: struktura URL identyczna ze starą stroną (SEO):
 *   /pl/maszyny                         -> archiwum maszyn
 *   /pl/maszyny/{kategoria}             -> kategoria (taksonomia kategoria_maszyn)
 *   /pl/maszyny/{kategoria}/{maszyna}   -> karta maszyny (CPT maszyna)
 *   /pl/uslugi/{usluga}                 -> usługa (CPT usluga)
 *   /pl/aktualnosci/{wpis}              -> wpisy (posty)
 *   /pl/galerie/{galeria}               -> galerie (CPT galeria)
 *   /pl/strony/o-nas, /pl/kontakt       -> zwykłe strony (rodzice: "strony", "kontakt")
 *
 * Wersje językowe: Polylang (URL z prefiksem języka /pl/ /en/ /de/ /uk/).
 * Bez Polylanga prefiks "pl/" jest doklejany przez motyw (ROLMAX_PREFIX).
 */

defined('ABSPATH') || exit;

define('ROLMAX_VER', '1.0.0');
define('ROLMAX_PREFIX', function_exists('pll_current_language') ? '' : 'pl/');

require_once __DIR__ . '/inc/schema.php';
require_once __DIR__ . '/inc/meta.php';
if (defined('WP_CLI') && WP_CLI) {
    require_once __DIR__ . '/inc/import.php';
}

/* ---------- setup ---------- */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', ['height' => 120, 'width' => 400, 'flex-width' => true]);
    add_image_size('rx-tile', 420, 315, false);
    add_image_size('rx-hero', 1920, 1080, true);
    register_nav_menus(['primary' => 'Menu główne', 'footer' => 'Stopka']);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('rx-fonts', 'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=Barlow:wght@400;500;600&display=swap', [], null);
    wp_enqueue_style('rolmax', get_theme_file_uri('assets/rolmax.css'), [], ROLMAX_VER);
    wp_enqueue_script('rolmax', get_theme_file_uri('assets/rolmax.js'), [], ROLMAX_VER, ['strategy' => 'defer', 'in_footer' => true]);
});

/* ---------- typy treści ---------- */
add_action('init', function () {
    $p = ROLMAX_PREFIX;

    register_taxonomy('kategoria_maszyn', ['maszyna'], [
        'label' => 'Kategorie maszyn',
        'hierarchical' => true,           // kategorie + podkategorie
        'show_in_rest' => true,
        'rewrite' => ['slug' => $p . 'maszyny', 'with_front' => false, 'hierarchical' => false],
    ]);

    register_post_type('maszyna', [
        'label' => 'Maszyny',
        'labels' => ['singular_name' => 'Maszyna', 'add_new_item' => 'Dodaj maszynę'],
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-car',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields'],
        'has_archive' => $p . 'maszyny',
        'rewrite' => ['slug' => $p . 'maszyny/%kategoria_maszyn%', 'with_front' => false],
    ]);

    register_post_type('usluga', [
        'label' => 'Usługi', 'labels' => ['singular_name' => 'Usługa'],
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-admin-tools',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields'],
        'has_archive' => $p . 'uslugi',
        'rewrite' => ['slug' => $p . 'uslugi', 'with_front' => false],
    ]);

    register_taxonomy('temat', ['blog'], [
        'label' => 'Tematy bloga', 'hierarchical' => true, 'show_in_rest' => true,
        'rewrite' => ['slug' => $p . 'blog/temat', 'with_front' => false],
    ]);

    register_post_type('blog', [
        'label' => 'Blog', 'labels' => ['singular_name' => 'Artykuł', 'add_new_item' => 'Dodaj artykuł'],
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-welcome-write-blog', 'menu_position' => 5,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions', 'custom-fields'],
        'has_archive' => $p . 'blog',
        'rewrite' => ['slug' => $p . 'blog', 'with_front' => false],
    ]);

    register_post_type('promocja', [
        'label' => 'Promocje', 'labels' => ['singular_name' => 'Promocja'],
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-megaphone',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields'],
        'has_archive' => $p . 'promocje',
        'rewrite' => ['slug' => $p . 'promocje', 'with_front' => false],
    ]);

    register_post_type('galeria', [
        'label' => 'Galerie', 'labels' => ['singular_name' => 'Galeria'],
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-format-gallery',
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'has_archive' => $p . 'galerie',
        'rewrite' => ['slug' => $p . 'galerie', 'with_front' => false],
    ]);
});

/* %kategoria_maszyn% w linku maszyny -> slug pierwszej (najgłębszej) kategorii */
add_filter('post_type_link', function ($link, $post) {
    if ($post->post_type !== 'maszyna' || strpos($link, '%kategoria_maszyn%') === false) return $link;
    $terms = get_the_terms($post, 'kategoria_maszyn');
    $slug = 'inne';
    if ($terms && !is_wp_error($terms)) {
        usort($terms, fn($a, $b) => count(get_ancestors($b->term_id, 'kategoria_maszyn')) <=> count(get_ancestors($a->term_id, 'kategoria_maszyn')));
        $slug = $terms[0]->slug;
    }
    return str_replace('%kategoria_maszyn%', $slug, $link);
}, 10, 2);

/* Aktualności: /pl/aktualnosci/{slug} (bez zmiany globalnych permalinków) */
add_filter('post_link', function ($link, $post) {
    return $post->post_type === 'post' ? home_url('/' . ROLMAX_PREFIX . 'aktualnosci/' . $post->post_name) : $link;
}, 10, 2);
add_action('init', function () {
    $p = preg_quote(ROLMAX_PREFIX, '#');
    add_rewrite_rule('^' . $p . 'aktualnosci/?$', 'index.php?pagename=aktualnosci', 'top');
    add_rewrite_rule('^' . $p . 'aktualnosci/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');
    // kategoria/maszyna — reguła szczegółowa przed kategorią
    add_rewrite_rule('^' . $p . 'maszyny/([^/]+)/(?!page/)([^/]+)/?$', 'index.php?maszyna=$matches[2]', 'top');
});

/* Bez końcowego ukośnika — jak na starej stronie (unikamy duplikatów) */
add_filter('user_trailingslashit', fn($url) => untrailingslashit($url), 99);

/* ---------- helpery dla szablonów ---------- */
function rx_categories(): array {
    return get_terms(['taxonomy' => 'kategoria_maszyn', 'hide_empty' => true, 'parent' => 0, 'orderby' => 'term_order']) ?: [];
}
function rx_machines(int $term_id, int $limit = -1): array {
    return get_posts(['post_type' => 'maszyna', 'numberposts' => $limit, 'orderby' => 'menu_order', 'order' => 'ASC',
        'tax_query' => [['taxonomy' => 'kategoria_maszyn', 'terms' => $term_id, 'include_children' => true]]]);
}
function rx_services(): array {
    return get_posts(['post_type' => 'usluga', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
}
function rx_badge($post): string {
    $b = get_post_meta(is_object($post) ? $post->ID : $post, 'rx_badge', true);
    return $b ? '<em class="badge">' . esc_html($b) . '</em>' : '';
}
function rx_blog_posts(int $n = 3): array {
    return get_posts(['post_type' => 'blog', 'numberposts' => $n]);
}
function rx_promos(): array {
    $today = wp_date('Y-m-d');
    return array_values(array_filter(get_posts(['post_type' => 'promocja', 'numberposts' => 12, 'orderby' => 'menu_order', 'order' => 'ASC']),
        fn($p) => !($u = get_post_meta($p->ID, 'rx_until', true)) || $u >= $today));
}
function rx_reading_time($post): int {
    return max(2, (int) round(str_word_count(wp_strip_all_tags(get_post_field('post_content', $post))) / 200));
}
function rx_img($post, $size = 'rx-tile', $attr = []) {
    return get_the_post_thumbnail($post, $size, $attr + ['loading' => 'lazy', 'alt' => 'New Holland ' . get_the_title($post)]);
}

/* ---------- Customizer: wideo, 30-lecie, oddziały ---------- */
add_action('customize_register', function ($c) {
    $c->add_section('rolmax', ['title' => 'Rolmax — strona główna', 'priority' => 30]);
    $fields = [
        'rx_hero_video'   => ['Wideo hero (URL MP4/WebM, 1080p/4K)', 'url', ''],
        'rx_band_video'   => ['Wideo — sekcja premium New Holland', 'url', ''],
        'rx_service_video'=> ['Wideo — sekcja serwis 24/7', 'url', ''],
        'rx_hero_title'   => ['Nagłówek hero', 'text', 'Klasa premium'],
        'rx_hero_text'    => ['Tekst hero', 'textarea', 'Od 1997 roku dostarczamy gospodarstwom Lubelszczyzny maszyny jednej z najbardziej prestiżowych marek rolniczych świata.'],
        'rx_jubilee_text' => ['Tekst jubileuszu 30-lecia', 'textarea', 'W 2027 roku Rolmax obchodzi 30-lecie. Dziękujemy za trzy dekady zaufania.'],
        'rx_branches'     => ['Oddziały (JSON)', 'textarea', ''],
    ];
    foreach ($fields as $id => [$label, $type, $default]) {
        $c->add_setting($id, ['default' => $default, 'sanitize_callback' => $type === 'url' ? 'esc_url_raw' : 'wp_kses_post']);
        $c->add_control($id, ['label' => $label, 'section' => 'rolmax', 'type' => $type]);
    }
});

/* Liczba lat od założenia — liczy się sama (1997) */
function rx_years(): int { return (int) wp_date('Y') - 1997; }

/* Przekierowania 301 zapisane przez importer, gdy brak wtyczki Redirection */
add_action('template_redirect', function () {
    if (!is_404()) return;
    $r = get_option('rx_redirects', []);
    $path = untrailingslashit(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
    if (isset($r[$path])) { wp_safe_redirect(home_url($r[$path]), 301); exit; }
});
