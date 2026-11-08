<?php
/**
 * Pola dodatkowe maszyn i usług (bez wtyczek — natywne meta + prosty panel w edytorze).
 * Pracownicy edytują: broszurę, link producenta, galerię zdjęć, wideo, podpis "tag" usługi, punkty usługi.
 */
defined('ABSPATH') || exit;

const RX_META = [
    'maszyna' => [
        'rx_brochure'     => 'Link do broszury (PDF / New Holland)',
        'rx_manufacturer' => 'Link do strony producenta',
        'rx_video'        => 'Wideo maszyny (URL MP4 lub YouTube)',
        'rx_gallery'      => 'Galeria — ID załączników po przecinku',
        'rx_old_url'      => 'Stary URL (tylko informacyjnie / SEO)',
        'rx_power'        => 'Moc / zakres (np. „360–435 KM”)',
        'rx_badge'        => 'Etykieta na kafelku (np. NOWOŚĆ, NAJPOPULARNIEJSZY) — puste = brak',
        'rx_status'       => 'Status: aktualny / zapytaj (brak w katalogu NH — przycisk „Zapytaj o dostępność”)',
        'rx_nh_sync'      => 'Data ostatniej synchronizacji z New Holland (automatycznie)',
    ],
    'blog' => [
        'rx_related' => 'Maszyny w artykule — slugi po przecinku (np. t7-xd, nowy-t7-swb)',
        'rx_faq'     => 'FAQ — każda linia: Pytanie | Odpowiedź (Google pokaże je w wynikach)',
        'rx_source'  => 'Źródło informacji (link do New Holland)',
        'rx_keyword' => 'Główna fraza SEO artykułu',
    ],
    'promocja' => [
        'rx_tag'   => 'Etykieta (Serwis / Finansowanie / Nowość)',
        'rx_link'  => 'Link docelowy',
        'rx_until' => 'Ważna do (RRRR-MM-DD) — po tej dacie znika ze strony',
    ],
    'usluga' => [
        'rx_tag'    => 'Wyróżnik (np. „24/7 w żniwa”)',
        'rx_points' => 'Punkty usługi (każdy w nowej linii)',
        'rx_video'  => 'Wideo usługi (URL)',
    ],
];

add_action('init', function () {
    foreach (RX_META as $type => $fields) {
        foreach ($fields as $key => $label) {
            register_post_meta($type, $key, ['type' => 'string', 'single' => true, 'show_in_rest' => true,
                'auth_callback' => fn() => current_user_can('edit_posts')]);
        }
    }
});

add_action('add_meta_boxes', function () {
    foreach (RX_META as $type => $fields) {
        add_meta_box('rx_meta', 'Rolmax — dane dodatkowe', function ($post) use ($fields) {
            wp_nonce_field('rx_meta', 'rx_meta_nonce');
            foreach ($fields as $key => $label) {
                $v = get_post_meta($post->ID, $key, true);
                printf('<p><label><strong>%s</strong><br>', esc_html($label));
                if (in_array($key, ['rx_points', 'rx_faq'], true)) {
                    printf('<textarea name="%s" rows="4" style="width:100%%">%s</textarea>', esc_attr($key), esc_textarea($v));
                } else {
                    printf('<input type="text" name="%s" value="%s" style="width:100%%">', esc_attr($key), esc_attr($v));
                }
                echo '</label></p>';
            }
        }, $type, 'normal', 'high');
    }
});

add_action('save_post', function ($id) {
    if (!isset($_POST['rx_meta_nonce']) || !wp_verify_nonce($_POST['rx_meta_nonce'], 'rx_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $id)) return;
    $type = get_post_type($id);
    foreach (array_keys(RX_META[$type] ?? []) as $key) {
        if (isset($_POST[$key])) {
            $val = in_array($key, ['rx_brochure', 'rx_manufacturer', 'rx_video', 'rx_old_url', 'rx_source', 'rx_link'], true)
                ? esc_url_raw(wp_unslash($_POST[$key])) : sanitize_textarea_field(wp_unslash($_POST[$key]));
            update_post_meta($id, $key, $val);
        }
    }
});
