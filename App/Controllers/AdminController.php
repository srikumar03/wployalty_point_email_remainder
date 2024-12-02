<?php

namespace Wlper\App\Controllers;

use Wlper\App\Models\UserPointsModel;

class AdminController
{
    public static function scheduleEmailReminder()
    {
        $interval = get_option('points_reminder_interval', 'monthly');
        $custom_days = get_option('points_reminder_custom_days', 30);

        // Determine interval time in seconds
        switch ($interval) {
            case 'monthly':
                $schedule_time = MONTH_IN_SECONDS;
                break;
            case 'bimonthly':
                $schedule_time = MONTH_IN_SECONDS * 2;
                break;
            case 'custom':
                $schedule_time = $custom_days * DAY_IN_SECONDS;
                break;
            default:
                $schedule_time = MONTH_IN_SECONDS; // Fallback
        }

        // Schedule the action if not already scheduled
        if (!as_next_scheduled_action('app_send_points_reminder')) {
            as_schedule_recurring_action(time(), $schedule_time, 'app_send_points_reminder');
        }
    }

    public static function clearEmailReminder()
    {
        $actions = as_get_scheduled_actions(['hook' => 'app_send_points_reminder'], 'ids');
        foreach ($actions as $action_id) {
            as_unschedule_action_by_id($action_id);
        }
    }

    public static function sendPointsReminderFromScheduler()
    {
        $users = UserPointsModel::getUsersWithPoints();
        if (!empty($users)) {
            self::sendPointsReminder($users);
        }
    }

    public static function sendPointsReminder($users)
    {
        foreach ($users as $user) {
            $email_content = self::generateEmailContent($user);
            $subject = sprintf(__('Your Points Update - %d Points!', 'wp-loyalty'), $user->points);
            $headers = ['Content-Type: text/html; charset=UTF-8'];

            wp_mail($user->user_email, $subject, $email_content, $headers);
        }
    }

    private static function generateEmailContent($user)
    {
        ob_start();
        $user_data = $user;
        include plugin_dir_path(__FILE__) . '/../Views/EmailTemplate.php';
        return ob_get_clean();
    }
}
