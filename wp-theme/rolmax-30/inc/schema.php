<?php
/**
 * Dane strukturalne (JSON-LD) dla Google: 4 oddziały jako AutoDealer/LocalBusiness,
 * Product na kartach maszyn, BreadcrumbList. Plus hreflang dla wersji językowych.
 */
defined('ABSPATH') || exit;

function rx_branches(): array {
    $json = get_theme_mod('rx_branches');
    $data = $json ? json_decode($json, true) : null;
    return $data ?: [
        ['name' => 'Rolmax Świdnik (siedziba)', 'street' => 'ul. Piasecka 208', 'zip' => '21-040', 'city' => 'Świdnik', 'tel' => '+48817236960', 'email' => 'rolmax@rolmax.net.pl'],
        ['name' => 'Rolmax Miączyn', 'street' => 'Miączyn 59', 'zip' => '22-455', 'city' => 'Miączyn', 'tel' => '+48846397186', 'email' => 'miaczyn@rolmax.net.pl'],
        ['name' => 'Rolmax Rudnik', 'street' => 'Rudnik Kolonia 74', 'zip' => '23-212', 'city' => 'Wilkołaz', 'tel' => '+48504044574', 'email' => 'rudnik@rolmax.net.pl'],
        ['name' => 'Rolmax Białka', 'street' => 'Białka 50c', 'zip' => '21-300', 'city' => 'Radzyń Podlaski', 'tel' => '+48506215663', 'email' => 'bialka@rolmax.net.pl'],
    ];
}

add_action('wp_head', function () {
    $graph = [];
    if (is_front_page()) {
        $graph[] = ['@type' => 'Organization', '@id' => home_url('/#org'), 'name' => 'Rolmax', 'url' => home_url('/'),
            'foundingDate' => '1997', 'logo' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') ?: null,
            'sameAs' => ['https://www.facebook.com/RolmaxNewHolland', 'https://www.instagram.com/rolmaxswidnik', 'https://www.tiktok.com/@rolmaxswidnik', 'https://www.youtube.com/@rolmaxswidnik616']];
        foreach (rx_branches() as $b) {
            $graph[] = ['@type' => ['AutoDealer', 'LocalBusiness'], 'name' => $b['name'], 'parentOrganization' => ['@id' => home_url('/#org')],
                'brand' => ['@type' => 'Brand', 'name' => 'New Holland'], 'telephone' => $b['tel'], 'email' => $b['email'],
                'address' => ['@type' => 'PostalAddress', 'streetAddress' => $b['street'], 'postalCode' => $b['zip'], 'addressLocality' => $b['city'], 'addressCountry' => 'PL'],
                'openingHours' => 'Mo-Fr 08:00-16:00'];
        }
    }
    if (is_singular('maszyna')) {
        $terms = get_the_terms(get_the_ID(), 'kategoria_maszyn');
        $graph[] = ['@type' => 'Product', 'name' => 'New Holland ' . get_the_title(), 'brand' => ['@type' => 'Brand', 'name' => 'New Holland'],
            'category' => $terms ? $terms[0]->name : null, 'image' => get_the_post_thumbnail_url(null, 'full') ?: null,
            'description' => wp_strip_all_tags(get_the_excerpt()), 'url' => get_permalink(),
            'offers' => ['@type' => 'Offer', 'availability' => 'https://schema.org/InStock', 'priceCurrency' => 'PLN',
                'seller' => ['@type' => 'AutoDealer', 'name' => 'Rolmax']]];
    }
    if ($graph) {
        echo '<script type="application/ld+json">' . wp_json_encode(['@context' => 'https://schema.org', '@graph' => array_values(array_filter($graph))], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n";
    }
}, 20);

/* Canonical: WordPress dodaje go sam (rel_canonical) — nie dublujemy. */
