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
        $current_interval = get_option('points_reminder_interval', 'monthly'); // Get saved interval
        $custom_days = get_option('points_reminder_custom_days', 30); // Get custom days
        $next_scheduled_time = wp_next_scheduled('app_send_points_reminder'); // Get next scheduled event time
        self::getNextEmailTime($next_scheduled_time,$custom_days);
        // Function to calculate the next reminder date based on the interval
        function getNextEmailTime($interval, $custom_days, $user_id)
        {
            $next_email_time = wp_next_scheduled('app_send_points_reminder'); // Check if the event exists

            if (!$next_email_time) {
                switch ($interval) {
                    case 'bimonthly':
                        $next_email_time = strtotime('+2 months');
                        break;
                    case 'custom':
                        $next_email_time = strtotime('+' . $custom_days . ' days');
                        break;
                    case 'monthly':
                    default:
                        $next_email_time = strtotime('+1 month');
                        break;
                }
            }

            return $next_email_time;
        }

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
    private static function getNextEmailTime($interval, $custom_days)
    {
        $next_email_time = wp_next_scheduled('app_send_points_reminder'); // Check if the event exists

        if (!$next_email_time) {
            switch ($interval) {
                case 'bimonthly':
                    $next_email_time = strtotime('+2 months');
                    break;
                case 'custom':
                    $next_email_time = strtotime('+' . $custom_days . ' days');
                    break;
                case 'monthly':
                default:
                    $next_email_time = strtotime('+1 month');
                    break;
            }
        }

        return $next_email_time;
    }
    public static function handleAdminPageForm()
    {
        if (isset($_POST['action']) && $_POST['action'] === 'save_reminder_interval') {
            check_admin_referer(); // Security check

            $interval = sanitize_text_field($_POST['reminder_interval']);
            update_option('points_reminder_interval', $interval);

            if ($interval === 'custom' && isset($_POST['custom_days'])) {
                $custom_days = intval($_POST['custom_days']);
                update_option('points_reminder_custom_days', $custom_days);
            }

            AdminController::clearEmailReminder();
            AdminController::scheduleEmailReminder();

            wp_redirect(admin_url('admin.php?page=wp-loyalty-users&message=interval_saved'));
            exit;
        }
    }



}
