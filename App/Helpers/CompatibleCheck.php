<?php

namespace Wlper\App\Helpers;
defined('ABSPATH') or die();

class CompatibleCheck
{
    /**
     * initial check
     *
     * @param bool $active_check
     *
     * @return bool
     */
    public static function init_check($active_check = false)
    {
        $status = true;
        if (!self::isEnvironmentCompatible()) {
            if ($active_check) {
                self::adminNotice(esc_html(WLPER_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum PHP version of ', 'wp-loyalty-rules') . WLPER_MINIMUM_PHP_VERSION));
            }
            $status = false;
        }
        if (!self::isWordPressCompatible()) {
            if ($active_check) {
                self::adminNotice(esc_html(WLPER_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum Wordpress version of ', 'wp-loyalty-rules') . WLPER_MINIMUM_WP_VERSION));
            }
            $status = false;
        }
        if (!self::isWoocommerceActive()) {
            if ($active_check) {
                self::adminNotice(esc_html(__('Woocommerce must installed and activated in-order to use ', 'wp-loyalty-rules') . WLPER_PLUGIN_NAME));
            }
            $status = false;
        }
        if (!self::isWooCompatible()) {
            if ($active_check) {
                self::adminNotice(esc_html(WLPER_PLUGIN_NAME . __(' plugin can not be activated because it requires minimum Woocommerce version of ', 'wp-loyalty-rules') . WLPER_MINIMUM_WC_VERSION));
            }
            $status = false;
        }
        return $status;
    }

    protected static function isEnvironmentCompatible()
    {
        return version_compare(PHP_VERSION, WLPER_MINIMUM_PHP_VERSION, '>=');
    }

    public static function adminNotice(string $notice)
    {
        add_action('admin_notices', function () use ($notice) {
            ?>
            <div class="notice notice-error">
                <p><?php echo wp_kses_post($notice); ?></p>
            </div>
            <?php
        }, 1);
    }

    public static function isWordPressCompatible()
    {
        if (!WLPER_MINIMUM_WP_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare(get_bloginfo('version'), WLPER_MINIMUM_WP_VERSION, '>=');
        }
        return true;
    }

    public static function isWoocommerceActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }

        return in_array('woocommerce/woocommerce.php', $active_plugins, false) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }

    public static function isWooCompatible()
    {
        $woo_version = self::woo_version();
        if (!WLPER_MINIMUM_WC_VERSION) {
            $is_compatible = true;
        } else {
            $is_compatible = version_compare($woo_version, WLPER_MINIMUM_WC_VERSION, '>=');
        }

        return $is_compatible;
    }

    public static function woo_version()
    {
        require_once ABSPATH . '/wp-admin/includes/plugin.php';
        $plugin_folder = get_plugins('/woocommerce');
        $plugin_file = 'woocommerce.php';
        $wc_installed_version = '1.0.0';
        if (isset($plugin_folder[$plugin_file]['Version'])) {
            $wc_installed_version = $plugin_folder[$plugin_file]['Version'];
        }

        return $wc_installed_version;
    }

    public static function inactiveNotice()
    {
        $message = '';
        if (!self::isEnvironmentCompatible()) {
            $message = WLPER_PLUGIN_NAME . __(' is inactive. Because, it requires minimum PHP version of ', 'wp-loyalty-rules') . WLPER_MINIMUM_PHP_VERSION;
        } elseif (!self::isWordPressCompatible()) {
            $message = WLPER_PLUGIN_NAME . __(' is inactive. Because, it requires minimum Wordpress version of ', 'wp-loyalty-rules') . WLPER_MINIMUM_WP_VERSION;
        } elseif (!self::isWoocommerceActive()) {
            $message = __('Woocommerce must installed and activated in-order to use ', 'wp-loyalty-rules') . WLPER_PLUGIN_NAME;
        } elseif (!self::isWooCompatible()) {
            $message = WLPER_PLUGIN_NAME . __(' is inactive. Because, it requires minimum Woocommerce version of ', 'wp-loyalty-rules') . WLPER_MINIMUM_WC_VERSION;
        }

        return '<div class="error"><p><strong>' . esc_html($message) . '</strong></p></div>';
    }
}