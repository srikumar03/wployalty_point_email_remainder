<?php

namespace Wlper\App\Controllers;

use Wlper\App\Models\UserPointsModel;

class AdminController
{

    public static function scheduleEmailReminder()
    {
        // Clear any existing scheduled events
        self::clearEmailReminder();

        // Get the interval and custom days from options
        $interval = get_option('points_reminder_interval', 'monthly');
        $custom_days = get_option('points_reminder_custom_days', 30);

        // Calculate the schedule time
        $schedule_time = time();
        switch ($interval) {
            case 'bimonthly':
                $schedule_time = strtotime('+2 months');
                break;
            case 'custom':
                $schedule_time = strtotime('+' . $custom_days . ' days');
                break;
            case 'monthly':
            default:
                $schedule_time = strtotime('+1 month');
                break;
        }

        // Schedule the event if not already scheduled
        if (!wp_next_scheduled('app_send_points_reminder')) {
            wp_schedule_single_event($schedule_time, 'app_send_points_reminder');
        }



    }

    public static function clearEmailReminder()
    {
        $timestamp = wp_next_scheduled('app_send_points_reminder');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'app_send_points_reminder');
        }
    }

// Handle Custom Schedule Interval
public static function custom_cron_schedules($schedules)
    {
        $interval = get_option('points_reminder_interval', 'monthly');
        $custom_days = get_option('points_reminder_custom_days', 30);

        if ($interval === 'custom') {
            print_r(json_encode($schedules) . '\n'. $custom_days);
            $schedules['custom_days_schedule'] = array(
                'interval' => $custom_days * 24 * 60 * 60, // Convert days to seconds
                'display'  => __('Custom Days Interval', 'wp-loyalty'),
            );
        }

        return $schedules;
    }


    public static function sendPointsReminder($users)
    {
        foreach ($users as $user) {
            $email_content = self::generateEmailContent($user);
            $subject = sprintf(__('Your Points Update - %d Points!', 'wp-loyalty'), $user->points);
            $headers = ['Content-Type: text/html; charset=UTF-8'];

            wp_mail($user->user_email, $subject, $email_content, $headers); // Use user_email instead of email
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
