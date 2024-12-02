<?php

namespace Wlper\App\Controllers;

use Wlper\App\Models\UserPointsModel;

class AdminPageController
{

    public static function registerAdminPage()
    {
        add_menu_page(
            __('WP Loyalty Users', 'wp-loyalty'),
            __('Loyalty Users', 'wp-loyalty'),
            'manage_options',
            'wp-loyalty-users',
            [self::class, 'renderAdminPage'],
            'dashicons-email',
            30
        );
    }

    public static function renderAdminPage()
    {
        $users = UserPointsModel::getUsersWithPoints();
        wc_get_template(
            'Main.php',
            array('users' => $users),
            '',
            WLPER_PLUGIN_PATH . 'App/Views/Admin/'
        );
    }


    public static function handleSendEmailRequest()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to access this page.', 'wp-loyalty'));
        }

        $user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($user_id) {
            $user = UserPointsModel::getUserById($user_id);

            if ($user) {
                AdminController::sendPointsReminder([$user]);
                wp_redirect(admin_url('admin.php?page=wp-loyalty-users&message=success'));
                exit;
            }
        }
        wp_redirect(admin_url('admin.php?page=wp-loyalty-users&message=error'));
        exit;
    }

    public static function handleAdminPageForm()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to perform this action.', 'wp-loyalty'));
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'save_reminder_interval_nonce')) {
            wp_die(__('Security check failed.', 'wp-loyalty'));
        }

        $interval = isset($_POST['reminder_interval']) ? sanitize_text_field($_POST['reminder_interval']) : 'monthly';
        $custom_days = isset($_POST['custom_days']) ? absint($_POST['custom_days']) : 30;


        update_option('points_reminder_interval', $interval);
        if ($interval === 'custom') {
            update_option('points_reminder_custom_days', $custom_days);
        }

        // Clear and reschedule
        AdminController::clearEmailReminder();
        AdminController::scheduleEmailReminder();

        wp_redirect(admin_url('admin.php?page=wp-loyalty-users&message=interval_saved'));
        exit;
    }


}