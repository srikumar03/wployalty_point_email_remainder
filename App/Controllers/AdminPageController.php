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
        $users = UserPointsModel::getUsersWithPoints(); // Fetch users from the `wp_wlr_users` table
        include plugin_dir_path(__FILE__) . '/../Views/AdminPage.php';
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
}
