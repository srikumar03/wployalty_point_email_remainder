<?php
/**
 * Plugin Name: WPLoyalty: Point Email Reminder
 * Plugin URI: https://wployalty.net/
 * Description: This add-on allows check license for wployalty plugins
 * Version: 1.0.0
 * Author: Sri
 * Slug: WPLoyalty Point Email Reminder
 * Text Domain:WPLoyalty Point Email Reminder
 * Domain Path: /i18n/languages/
 * Author URI: https://wployalty.net/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;
defined('WLPER_PLUGIN_PATH') || define('WLPER_PLUGIN_PATH', plugin_dir_path(__FILE__));
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    return;
}
require __DIR__ . '/vendor/autoload.php';

if (class_exists('\Wlper\App\Router')) {
    \Wlper\App\Router::init();
}
