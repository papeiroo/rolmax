<?php
/**
 * WP-CLI: import danych ze scrapera do WordPressa — z zachowaniem slugów (= tych samych URL-i).
 *
 *   wp rolmax import /sciezka/do/out            # maszyny, kategorie, usługi, aktualności, galerie
 *   wp rolmax import /sciezka/do/out --dry-run  # tylko raport
 *
 * Wymaga: folder out/ z scrape_rolmax.py (products.json, services.json, news.json, galleries.json, media/).
 * Zdjęcia brane są z out/media (oryginalna jakość), nie z internetu.
 */
defined('ABSPATH') || exit;

class Rolmax_Import_Command {
    private string $dir;
    private bool $dry = false;
    private array $media_cache = [];

    /**
     * @when after_wp_load
     */
    public function import($args, $assoc) {
        $this->dir = rtrim($args[0] ?? '', '/');
        $this->dry = isset($assoc['dry-run']);
        if (!is_dir($this->dir)) WP_CLI::error("Brak folderu {$this->dir}");
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $this->products();
        $this->simple('services.json', 'usluga');
        $this->simple('news.json', 'post');
        $this->simple('galleries.json', 'galeria');
        flush_rewrite_rules();
        WP_CLI::success('Import zakończony. Sprawdź: wp rewrite list | grep maszyny');
    }

    private function json(string $f): array {
        $p = "{$this->dir}/{$f}";
        return is_file($p) ? json_decode(file_get_contents($p), true) : [];
    }

    private function attach(string $url, int $post_id): int {
        if (isset($this->media_cache[$url])) return $this->media_cache[$url];
        $local = $this->dir . '/media/' . ltrim(parse_url($url, PHP_URL_PATH), '/');
        if (!is_file($local)) return 0;
        $tmp = wp_tempnam($local);
        copy($local, $tmp);
        $id = media_handle_sideload(['name' => basename($local), 'tmp_name' => $tmp], $post_id);
        if (is_wp_error($id)) { WP_CLI::warning($id->get_error_message()); return 0; }
        update_post_meta($id, '_rx_source', $url);
        return $this->media_cache[$url] = $id;
    }

    private function upsert(string $type, string $slug, array $data): int {
        $existing = get_page_by_path($slug, OBJECT, $type);
        $post = array_merge(['post_type' => $type, 'post_name' => $slug, 'post_status' => 'publish'], $data);
        if ($existing) $post['ID'] = $existing->ID;
        if ($this->dry) { WP_CLI::log("[dry] {$type}: {$slug}"); return 0; }
        $id = wp_insert_post($post, true);
        if (is_wp_error($id)) { WP_CLI::warning($id->get_error_message()); return 0; }
        return $id;
    }

    private function seo(int $id, ?string $desc) {
        if (!$id || !$desc) return;
        update_post_meta($id, '_yoast_wpseo_metadesc', $desc);   // Yoast
        update_post_meta($id, 'rank_math_description', $desc);    // Rank Math
    }

    private function products() {
        $order = 0;
        foreach ($this->json('products.json') as $cat) {
            $term = term_exists($cat['slug'], 'kategoria_maszyn') ?: ($this->dry ? ['term_id' => 0] :
                wp_insert_term($cat['name'], 'kategoria_maszyn', ['slug' => $cat['slug'], 'description' => $cat['meta_description'] ?? '']));
            $tid = is_array($term) ? (int) $term['term_id'] : 0;
            foreach ($cat['items'] as $i => $m) {
                $id = $this->upsert('maszyna', $m['slug'], [
                    'post_title' => $m['name'], 'post_content' => wp_kses_post($m['description_html'] ?? ''),
                    'menu_order' => $i,
                ]);
                if (!$id) continue;
                wp_set_object_terms($id, [$tid], 'kategoria_maszyn');
                update_post_meta($id, 'rx_brochure', $m['brochure'] ?? '');
                update_post_meta($id, 'rx_manufacturer', $m['manufacturer_url'] ?? '');
                update_post_meta($id, 'rx_old_url', $m['url']);
                $this->seo($id, $m['meta_description'] ?? null);
                $gal = [];
                foreach ($m['images'] ?? [] as $k => $img) {
                    $aid = $this->attach($img, $id);
                    if ($aid && $k === 0) set_post_thumbnail($id, $aid);
                    if ($aid) $gal[] = $aid;
                }
                update_post_meta($id, 'rx_gallery', implode(',', $gal));
                WP_CLI::log("✓ maszyna {$cat['slug']}/{$m['slug']} (" . count($gal) . " zdjęć)");
                $order++;
            }
        }
        WP_CLI::log("Maszyn: {$order}");
    }

    private function simple(string $file, string $type) {
        foreach ($this->json($file) as $i => $row) {
            $date = !empty($row['date']) ? DateTime::createFromFormat('d.m.Y', $row['date']) : null;
            $id = $this->upsert($type, $row['slug'], array_filter([
                'post_title' => $row['title'], 'post_content' => wp_kses_post($row['content_html'] ?? ''),
                'menu_order' => $i, 'post_date' => $date ? $date->format('Y-m-d 10:00:00') : null,
            ]));
            if (!$id) continue;
            $this->seo($id, $row['meta_description'] ?? null);
            $first = true; $gal = [];
            foreach ($row['media'] ?? [] as $u) {
                if (!preg_match('/\.(jpe?g|png|webp)$/i', $u)) continue;
                $aid = $this->attach($u, $id);
                if ($aid && $first) { set_post_thumbnail($id, $aid); $first = false; }
                if ($aid) $gal[] = $aid;
            }
            if ($type === 'galeria' && $gal) {
                wp_update_post(['ID' => $id, 'post_content' => '[gallery link="file" size="large" ids="' . implode(',', $gal) . '"]']);
            }
            WP_CLI::log("✓ {$type}: {$row['slug']}");
        }
    }
    /**
     * Synchronizacja z katalogiem New Holland PL (plik nh_sync.json z pakietu).
     *   wp rolmax nh_sync /sciezka/nh_sync.json [--dry-run]
     * - aktualizuje linki producenta i broszury PDF istniejących maszyn,
     * - dodaje nowe modele (zdjęcie pobierane z biblioteki New Holland),
     * - wycofane modele -> szkic + przekierowanie 301 na następcę (wtyczka Redirection),
     * - kategorie spoza katalogu NH -> status „zapytaj”.
     *
     * @when after_wp_load
     */
    public function nh_sync($args, $assoc) {
        $this->dry = isset($assoc['dry-run']);
        $data = json_decode(file_get_contents($args[0] ?? ''), true) ?: WP_CLI::error('Nie mogę odczytać pliku nh_sync.json');
        require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
        foreach ($data['categories_new'] ?? [] as $c) if (!term_exists($c['slug'], 'kategoria_maszyn') && !$this->dry) wp_insert_term($c['name'], 'kategoria_maszyn', ['slug' => $c['slug']]);
        foreach ($data['existing'] as $e) {
            if ($e['slug'] === '*') { // cała kategoria spoza katalogu NH
                foreach (rx_machines((int) (get_term_by('slug', $e['category'], 'kategoria_maszyn')->term_id ?? 0)) as $m) if (!$this->dry) update_post_meta($m->ID, 'rx_status', 'zapytaj');
                WP_CLI::log("• {$e['category']}: status „zapytaj o dostępność”"); continue;
            }
            $post = get_page_by_path($e['slug'], OBJECT, 'maszyna'); if (!$post) { WP_CLI::warning("brak maszyny {$e['slug']}"); continue; }
            if ($this->dry) { WP_CLI::log("[dry] {$e['slug']}: {$e['status']}"); continue; }
            if ($e['status'] === 'archiwalny') {
                wp_update_post(['ID' => $post->ID, 'post_status' => 'draft']);
                $this->redirect("/pl/maszyny/{$e['category']}/{$e['slug']}", "/pl/maszyny/{$e['category']}/{$e['successor']}");
                WP_CLI::log("⟲ {$e['slug']} -> 301 {$e['successor']}"); continue;
            }
            if (!empty($e['manufacturer_url'])) update_post_meta($post->ID, 'rx_manufacturer', $e['manufacturer_url']);
            if (!empty($e['brochure_pdf'])) update_post_meta($post->ID, 'rx_brochure', $e['brochure_pdf']);
            update_post_meta($post->ID, 'rx_status', 'aktualny'); update_post_meta($post->ID, 'rx_nh_sync', $data['generated']);
            WP_CLI::log("✓ {$e['slug']} zaktualizowany");
        }
        foreach ($data['new'] as $i => $n) {
            $id = $this->upsert('maszyna', $n['slug'], ['post_title' => $n['name'], 'post_content' => '<p>' . esc_html($n['description']) . '</p>', 'post_excerpt' => $n['description'], 'menu_order' => -100 + $i]);
            if (!$id) continue;
            wp_set_object_terms($id, [$n['category']], 'kategoria_maszyn');
            foreach (['rx_manufacturer' => 'manufacturer_url', 'rx_brochure' => 'brochure_pdf', 'rx_power' => 'power', 'rx_badge' => 'badge'] as $k => $f) update_post_meta($id, $k, $n[$f] ?? '');
            update_post_meta($id, 'rx_nh_sync', $data['generated']);
            if (!has_post_thumbnail($id) && !empty($n['image_url'])) {
                $tmp = download_url($n['image_url']);
                if (!is_wp_error($tmp)) { $aid = media_handle_sideload(['name' => $n['slug'] . '.jpg', 'tmp_name' => $tmp], $id, $n['name']); if (!is_wp_error($aid)) set_post_thumbnail($id, $aid); }
            }
            WP_CLI::log("+ nowa maszyna {$n['category']}/{$n['slug']}");
        }
        flush_rewrite_rules();
        WP_CLI::success('Synchronizacja z New Holland zakończona.');
    }

    /**
     * Import artykułów bloga (blog.json) + domyślne promocje.
     *   wp rolmax blog /sciezka/blog.json
     *
     * @when after_wp_load
     */
    public function blog($args, $assoc) {
        $data = json_decode(file_get_contents($args[0] ?? ''), true) ?: WP_CLI::error('Nie mogę odczytać blog.json');
        $imgdir = dirname($args[0]) . '/img';
        require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
        foreach ($data['posts'] as $p) {
            $id = $this->upsert('blog', $p['slug'], ['post_title' => $p['title'], 'post_content' => wp_kses_post($p['html']), 'post_excerpt' => $p['meta'], 'post_date' => $p['date'] . ' 08:00:00', 'post_status' => 'publish']);
            if (!$id) continue;
            wp_set_object_terms($id, [$p['topic']], 'temat');
            update_post_meta($id, 'rx_related', implode(',', array_map(fn($r) => explode('/', $r)[1], $p['related'])));
            update_post_meta($id, 'rx_faq', implode("\n", array_map(fn($f) => $f[0] . ' | ' . $f[1], $p['faq'] ?? [])));
            update_post_meta($id, 'rx_source', $p['source']); update_post_meta($id, 'rx_keyword', $p['keywords'][0] ?? '');
            $this->seo($id, $p['meta']);
            $img = "$imgdir/{$p['img']}.webp";
            if (!has_post_thumbnail($id) && is_file($img)) { $tmp = wp_tempnam($img); copy($img, $tmp); $aid = media_handle_sideload(['name' => basename($img), 'tmp_name' => $tmp], $id); if (!is_wp_error($aid)) set_post_thumbnail($id, $aid); }
            WP_CLI::log("✓ blog: {$p['slug']}");
        }
        $promos = [
            ['Przegląd posezonowy', 'Serwis', 'Umów przegląd kombajnu i ciągnika na zimę — terminy w Świdniku i Miączynie.', '/pl/blog/przeglad-posezonowy-kombajnu-i-ciagnika-checklista'],
            ['Nawet do 10 lat', 'Finansowanie', 'Akcja New Holland z finansowaniem CNH Capital — zapytaj doradcę o warunki.', '/pl/blog/finansowanie-maszyn-new-holland-cnh-capital'],
            ['T5S na placu', 'Najpopularniejszy w PL', 'T5.90S — najczęściej wybierany ciągnik w Polsce. Finansowanie fabryczne.', '/pl/maszyny/traktory/t5s'],
            ['T7 XD 360–435 KM', 'Nowość', 'Nowa, najmocniejsza seria T7 — dla usługodawców i dużych gospodarstw.', '/pl/maszyny/traktory/t7-xd'],
        ];
        foreach ($promos as $i => [$t, $tag, $ex, $link]) {
            $id = $this->upsert('promocja', sanitize_title($t), ['post_title' => $t, 'post_excerpt' => $ex, 'menu_order' => $i]);
            if ($id) { update_post_meta($id, 'rx_tag', $tag); update_post_meta($id, 'rx_link', $link); }
        }
        flush_rewrite_rules();
        WP_CLI::success('Blog i promocje zaimportowane.');
    }

    private function redirect(string $from, string $to) {
        if (class_exists('Red_Item')) {
            Red_Item::create(['url' => $from, 'action_data' => ['url' => $to], 'match_type' => 'url', 'action_type' => 'url', 'action_code' => 301, 'group_id' => 1]);
        } else {
            $r = get_option('rx_redirects', []); $r[$from] = $to; update_option('rx_redirects', $r);
        }
    }
}
WP_CLI::add_command('rolmax', 'Rolmax_Import_Command');
