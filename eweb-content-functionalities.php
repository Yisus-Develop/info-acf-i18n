<?php
/**
 * Plugin Name: EWEB - Content Functionalities
 * Description: Content functionalities for WordPress projects: ACF-driven shortcodes, pipelines, vacancies, and popups.
 * Version: 1.2.1
 * Author: Yisus Develop
 * Author URI: https://github.com/Yisus-Develop
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: eweb-content-functionalities
 */

defined('ABSPATH') || exit;

/** Constante para rutas/URLs desde cualquier include */
if (!defined('IAP_PLUGIN_FILE')) {
    define('IAP_PLUGIN_FILE', __FILE__);
}

/** i18n */
add_action('plugins_loaded', function () {
    load_plugin_textdomain('eweb-content-functionalities', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

/** Enqueue centralizado (global + condicional popups) */
require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';

/** info_acf (shortcodes) */

require_once plugin_dir_path(__FILE__) . 'includes/admin/shortcodes-guide.php';

/** Router de shortcodes */
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/router.php';

require_once plugin_dir_path(__FILE__) . 'includes/features/vcards.php';
foreach ( glob(plugin_dir_path(__FILE__) . 'includes/shortcodes/*.php') as $file ) {
    include_once $file;
}