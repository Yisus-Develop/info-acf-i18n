<?php
/**
 * Router de shortcodes
 * Carga centralizada (y controlada) de todos los shortcodes del plugin.
 * Ubicación: eweb-content-functionalities/includes/shortcodes/router.php
 */
defined('ABSPATH') || exit;

/**
 * Helper interno para cargar archivos de shortcode con verificación.
 * @param string $relative_file Nombre del archivo dentro de esta carpeta.
 */
function iap_require_shortcode($relative_file) {
    $path = __DIR__ . '/' . ltrim($relative_file, '/');

    if (file_exists($path)) {
        require_once $path;
    } elseif (defined('WP_DEBUG') && WP_DEBUG) {
        // Solo muestra el notice si WP_DEBUG está activado (modo desarrollo)
        trigger_error(sprintf('[eweb-content-functionalities] Shortcode file not found: %s', $path), E_USER_NOTICE);
    }
}

/* =========================
 *  Shortcodes existentes
 * ========================= */

// Core genéricos (según tu ZIP)
iap_require_shortcode('info-acf-shortcode.php');          // list, detail, table, map, gallery, search (según tu implementación)
iap_require_shortcode('info-shortcode.php');              // utilidades o shortcodes adicionales base
iap_require_shortcode('tempo_leitura.php');               // tiempo de lectura

// Departamentos / proyectos / eventos (según tu ZIP)
iap_require_shortcode('dept-projetos-shortcode.php');     // proyectos por dept
iap_require_shortcode('departamento-miembros-shortcode.php'); // miembros por dept
iap_require_shortcode('dept-eventos-shortcode.php');      // eventos por dept

// Vacantes: grid + opciones (según tu ZIP)
iap_require_shortcode('ipp-vacancies-shortcode.php');     // [ipp_vacancies_grid]  ← MODIFICADO: ahora incluye popup + attr form
iap_require_shortcode('ipp-vacancy-options.php');         // select/lista de vacantes, integración CF7


/* =========================
 *  Shortcodes nuevos
 * ========================= */

// Popup “Candidatura Espontânea”
iap_require_shortcode('popup-espontaneo.php');            // [ipp_popup_espontaneo]  ← NUEVO

/* =========================
 *  Nota:
 *  - Añade aquí cualquier otro shortcode nuevo que crees.
 *  - Mantén un archivo por shortcode para máxima claridad.
 * ========================= */
