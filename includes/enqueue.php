<?php
defined('ABSPATH') || exit;

/** ----------------------------------------------------------------
 * Helpers de detección de shortcodes (post_content + Elementor JSON)
 * --------------------------------------------------------------- */

// Busca cualquiera de $shortcodes en una cadena
function iap_string_has_any_shortcode($content, array $shortcodes) {
    if (!is_string($content) || $content === '') return false;
    foreach ($shortcodes as $sc) {
        // busca "[shortcode" para evitar falsos positivos en palabras
        if (stripos($content, '[' . $sc) !== false) return true;
    }
    return false;
}

// Inspecciona post_content y el JSON de Elementor (_elementor_data)
function iap_post_has_shortcodes($post_id, array $shortcodes) {
    $post = get_post($post_id);
    if (!$post) return false;

    // 1) post_content
    if (iap_string_has_any_shortcode($post->post_content ?? '', $shortcodes)) {
        return true;
    }

    // 2) Elementor: meta _elementor_data (JSON)
    $edata = get_post_meta($post_id, '_elementor_data', true);
    if (empty($edata)) return false;

    // Elementor guarda JSON; a veces viene serializado o con entidades
    if (is_string($edata)) {
        // intenta parsear como JSON
        $json = json_decode($edata, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // fallback: búsqueda de texto directa
            return iap_string_has_any_shortcode($edata, $shortcodes);
        }
    } else {
        $json = $edata; // por si acaso ya vino como array
    }

    // Recorrer recursivamente el árbol de widgets / columnas / secciones
    $stack = [$json];
    while ($stack) {
        $node = array_pop($stack);

        if (is_array($node)) {
            // detectar widget Shortcode: ['elType' => 'widget', 'widgetType' => 'shortcode']
            if (
                (isset($node['elType']) && $node['elType'] === 'widget') &&
                (isset($node['widgetType']) && $node['widgetType'] === 'shortcode')
            ) {
                $settings = $node['settings'] ?? [];
                $shortcode_text = $settings['shortcode'] ?? '';
                if (iap_string_has_any_shortcode($shortcode_text, $shortcodes)) {
                    return true;
                }
            }

            // recorrer children
            foreach ($node as $child) {
                if (is_array($child)) $stack[] = $child;
            }
        }
    }

    return false;
}

/** ----------------------------------------------------------------
 * Enqueue GLOBAL (igual que ya tenías)
 * --------------------------------------------------------------- */
function iap_enqueue_global_styles() {
    $ver = '1.2.0';
    wp_enqueue_style(
        'iap-style',
        plugins_url('assets/css/style.css', IAP_PLUGIN_FILE),
        [],
        $ver
    );
}
add_action('wp_enqueue_scripts', 'iap_enqueue_global_styles');


/** ----------------------------------------------------------------
 * Popups de candidaturas (tus shortcodes existentes)
 * + compat Elementor
 * --------------------------------------------------------------- */
function iap_enqueue_popup_assets_conditionally() {
    // Cubre páginas singulares y front/home (Elementor suele usarse ahí)
    if ( ! (is_singular() || is_front_page() || is_home()) ) return;

    $shortcodes = ['ipp_popup_espontaneo', 'ipp_vacancies_grid'];

    $enqueue = false;
    if (is_singular()) {
        $post_id = get_queried_object_id();
        $enqueue = iap_post_has_shortcodes($post_id, $shortcodes);
    } else {
        // Fallback si no es singular: intenta el global $post por si acaso
        global $post;
        if ($post instanceof WP_Post) {
            $enqueue = iap_post_has_shortcodes($post->ID, $shortcodes);
        }
    }

    if ($enqueue) {
        $ver = '1.0.0';
        wp_enqueue_style(
            'iap-popups',
            plugins_url('assets/css/ipp-popups.css', IAP_PLUGIN_FILE),
            [],
            $ver
        );
        wp_enqueue_script(
            'iap-popups',
            plugins_url('assets/js/ipp-popups.js', IAP_PLUGIN_FILE),
            [],
            $ver,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'iap_enqueue_popup_assets_conditionally', 10);


/** ----------------------------------------------------------------
 * Cartão Digital (member_contact_popup / departamento_miembros)
 * + compat Elementor
 * --------------------------------------------------------------- */
function iap_enqueue_member_card_assets_conditionally() {
    if ( ! (is_singular() || is_front_page() || is_home()) ) return;

    $shortcodes = ['member_contact_popup', 'departamento_miembros'];

    $enqueue = false;
    if (is_singular()) {
        $post_id = get_queried_object_id();
        $enqueue = iap_post_has_shortcodes($post_id, $shortcodes);
    } else {
        global $post;
        if ($post instanceof WP_Post) {
            $enqueue = iap_post_has_shortcodes($post->ID, $shortcodes);
        }
    }

    if ($enqueue) {
        $ver = '1.2.0';
        wp_enqueue_style(
            'iap-member-card',
            plugins_url('assets/css/member-card.css', IAP_PLUGIN_FILE),
            [],
            $ver
        );
        wp_enqueue_script(
            'iap-member-card',
            plugins_url('assets/js/member-card.js', IAP_PLUGIN_FILE),
            [],
            $ver,
            true
        );
        
 // 🗣️ Textos para JS (PT como base, traducibles)
wp_localize_script('iap-member-card', 'MCARD_I18N', [
    // Botón móvil
    'save_ios'     => __('Adicionar aos Contactos (iPhone)', 'eweb-content-functionalities'),
    'save_android' => __('Guardar contacto (Android)', 'eweb-content-functionalities'),
    'save_vcf'     => __('Guardar contacto (.vcf)', 'eweb-content-functionalities'),

    // Avisos móviles
    'hint_ios'     => __('Ao tocar, abrirá nos Contactos.', 'eweb-content-functionalities'),
    'hint_android' => __('Será descarregado o ficheiro .vcf; abra-o para adicionar aos seus contactos.', 'eweb-content-functionalities'),

    // Tabs / leyendas QR (desktop)
    'tab_vcf'      => __('iPhone', 'eweb-content-functionalities'),
    'tab_mecard'   => __('Android', 'eweb-content-functionalities'),
    'cap_vcf'      => __('Escaneie com iPhone', 'eweb-content-functionalities'),
    'cap_mecard'   => __('Escaneie com Android', 'eweb-content-functionalities'),

    // Tip en desktop (debajo del QR)
    'tip_desktop'  => __('Escolha o seu dispositivo. Se o QR não abrir contactos, use o botão no telemóvel.', 'eweb-content-functionalities'),
]);


    
    }
}
add_action('wp_enqueue_scripts', 'iap_enqueue_member_card_assets_conditionally', 10);



// -------------------------------------------------------------------
// PIPELINE: Encolar assets SÓLO cuando los shortcodes estén presentes
// -------------------------------------------------------------------
function iap_enqueue_pipeline_assets_conditionally() {
    if ( ! (is_singular() || is_front_page() || is_home()) ) return;

    $shortcodes = ['pipeline_bio_pipeline', 'pipeline_digital_pipeline'];

    $enqueue = false;
    if (is_singular()) {
        $post_id = get_queried_object_id();
        $enqueue = iap_post_has_shortcodes($post_id, $shortcodes);
    } else {
        global $post;
        if ($post instanceof WP_Post) {
            $enqueue = iap_post_has_shortcodes($post->ID, $shortcodes);
        }
    }

    if ($enqueue) {
        $ver = '1.0.0';
        wp_enqueue_style(
            'pipeline-pipeline-css',
            plugins_url('assets/css/pipeline.css', IAP_PLUGIN_FILE),
            [],
            $ver
        );
        wp_enqueue_script(
            'pipeline-pipeline-js',
            plugins_url('assets/js/pipeline.js', IAP_PLUGIN_FILE),
            [],
            $ver,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'iap_enqueue_pipeline_assets_conditionally', 10);


/** ----------------------------------------------------------------
 * “Plan B” ultra-fiable: hook en el render de Elementor
 * Si Elementor está presente y el widget Shortcode incluye nuestros
 * shortcodes, encolamos en caliente (por si vienen de plantillas).
 * --------------------------------------------------------------- */
add_action('elementor/frontend/widget/before_render', function($widget){
    try {
        if ( ! method_exists($widget, 'get_name') ) return;

        // Solo nos interesa el widget Shortcode
        if ( $widget->get_name() !== 'shortcode' ) return;

        $settings = $widget->get_settings_for_display();
        $code     = isset($settings['shortcode']) ? (string)$settings['shortcode'] : '';

        if ($code === '') return;

        // Grupos de shortcodes y sus handles para encolar
        $groups = [
            'member' => [
                'needles' => ['member_contact_popup', 'departamento_miembros'],
                'style'   => ['handle' => 'iap-member-card', 'src' => plugins_url('assets/css/member-card.css', IAP_PLUGIN_FILE), 'ver' => '1.2.0'],
                'script'  => ['handle' => 'iap-member-card', 'src' => plugins_url('assets/js/member-card.js',  IAP_PLUGIN_FILE), 'ver' => '1.2.0'],
            ],
            'vac' => [
                'needles' => ['ipp_popup_espontaneo', 'ipp_vacancies_grid'],
                'style'   => ['handle' => 'iap-popups', 'src' => plugins_url('assets/css/ipp-popups.css', IAP_PLUGIN_FILE), 'ver' => '1.0.0'],
                'script'  => ['handle' => 'iap-popups', 'src' => plugins_url('assets/js/ipp-popups.js',  IAP_PLUGIN_FILE), 'ver' => '1.0.0'],
            ],
        ];

        foreach ($groups as $g) {
            if (iap_string_has_any_shortcode($code, $g['needles'])) {
                // Encola si no está ya
                if (!wp_style_is($g['style']['handle'], 'enqueued')) {
                    wp_enqueue_style($g['style']['handle'], $g['style']['src'], [], $g['style']['ver']);
                }
                if (!wp_script_is($g['script']['handle'], 'enqueued')) {
                    wp_enqueue_script($g['script']['handle'], $g['script']['src'], [], $g['script']['ver'], true);
                }
            }
        }
    } catch (\Throwable $e) {
        // Silencioso para no romper frontend si Elementor cambia estructuras
    }
}, 10, 1);
