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


if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    return;
}
require __DIR__ . '/vendor/autoload.php';


use WLPER\App\Router;

add_action('plugins_loaded', function () {
    Router::init();
});