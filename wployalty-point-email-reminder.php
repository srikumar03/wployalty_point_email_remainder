<?php
/**
 * Plugin Name: WPLoyalty: Point Email Reminder
 * Plugin URI: https://wployalty.net/
 * Description: This is the addon to send a point email remainder for wployalty
 * Version: 1.0.0
 * Author: WPLoyalty
 * Slug: wployalty-point-email-reminder
 * Text Domain:wployalty-point-email-reminder
 * Domain Path: /i18n/languages/
 * Author URI: https://wployalty.net/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
//Define the plugin version
defined('WLPER_PLUGIN_VERSION') or define('WLPER_PLUGIN_VERSION', '1.0.0');
defined('WLPER_PLUGIN_SLUG') or define('WLPER_PLUGIN_SLUG', 'wp-loyalty-point-expire');
defined('WLPER_PLUGIN_PATH') or define('WLPER_PLUGIN_PATH', __DIR__ . '/');
defined('WLPER_PLUGIN_NAME') or define('WLPER_PLUGIN_NAME', 'WPLoyalty: Point Email Reminder');
defined('WLPER_PLUGIN_FILE') or define('WLPER_PLUGIN_FILE', __FILE__);
defined('WLPER_PLUGIN_AUTHOR') or define('WLPER_PLUGIN_AUTHOR', 'WPLoyalty');
defined('WLPER_PLUGIN_URL') or define('WLPER_PLUGIN_URL', plugin_dir_url(__FILE__));
defined('WLPER_MINIMUM_PHP_VERSION') or define('WLPER_MINIMUM_PHP_VERSION', '5.6.0');
defined('WLPER_MINIMUM_WP_VERSION') or define('WLPER_MINIMUM_WP_VERSION', '4.9');
defined('WLPER_MINIMUM_WC_VERSION') or define('WLPER_MINIMUM_WC_VERSION', '3.0.9');

use Wlper\App\Helpers\CompatibleCheck;
use Wlper\App\Router;

/**
 * Function to check parent plugin wployalty activate or not
 */
if (!function_exists('isWployaltyActiveOrNotInPointRemainder')) {
    function isWployaltyActiveOrNotInPointRemainder()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return array_key_exists('wp-loyalty-rules/wp-loyalty-rules.php', $active_plugins) || in_array('wp-loyalty-rules/wp-loyalty-rules.php', $active_plugins, false) || in_array('wp-loyalty-rules-lite/wp-loyalty-rules-lite.php', $active_plugins, false) || in_array('wployalty/wp-loyalty-rules-lite.php', $active_plugins, false);
    }
}


if (isWployaltyActiveOrNotInPointRemainder()) {
    if (!file_exists(WLPER_PLUGIN_PATH . 'vendor/autoload.php')) {
        return;
    }
    require_once WLPER_PLUGIN_PATH . 'vendor/autoload.php';

    if (class_exists(Router::class)) {
        CompatibleCheck::init_check(true);
        $plugin = new Router();
        if (method_exists($plugin, 'init')) {
            $plugin->init();
        }
    }
}

