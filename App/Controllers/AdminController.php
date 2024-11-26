<?php

namespace Wlper\App\Controllers;

use Wlper\App\Models\UserPointsModel;

class AdminController
{
    public static function init()
    {
        add_action('admin_init', [self::class, 'scheduleEmailReminder']);
        add_action('admin_init', [self::class, 'clearEmailReminder']);
        add_action('app_send_points_reminder', [self::class, 'sendPointsReminder']);
    }

    public static function scheduleEmailReminder()
    {
        if (!wp_next_scheduled('app_send_points_reminder')) {
            wp_schedule_event(time(), 'monthly', 'app_send_points_reminder');
        }
    }

    public static function clearEmailReminder()
    {
        $timestamp = wp_next_scheduled('app_send_points_reminder');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'app_send_points_reminder');
        }
    }

    public static function sendPointsReminder()
    {
        $users = UserPointsModel::getUsersWithPoints();

        foreach ($users as $user) {
            $email_content = self::generateEmailContent($user);
            $subject = sprintf(__('Your Points Update - %d Points!', 'wp-loyalty'), $user->points);
            $headers = ['Content-Type: text/html; charset=UTF-8'];

            wp_mail($user->email, $subject, $email_content, $headers);
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