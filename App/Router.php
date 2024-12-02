<?php

namespace Wlper\App;

use Wlper\App\Controllers\AdminController;
use Wlper\App\Controllers\AdminPageController;

class Router
{
    public static function init()
    {
        // Schedule email reminders on admin init
        add_action('admin_init', [self::class, 'initializeScheduler']);

        // Register the admin menu
        add_action('admin_menu', [AdminPageController::class, 'registerAdminPage']);

        // Handle admin form submissions
        add_action('admin_post_save_reminder_interval', [AdminPageController::class, 'handleAdminPageForm']);
        add_action('admin_post_send_email_to_user', [AdminPageController::class, 'handleSendEmailRequest']);

        // Hook for the email reminder scheduler
        add_action('app_send_points_reminder', [AdminController::class, 'sendPointsReminderFromScheduler']);
    }

    /**
     * Initialize the Action Scheduler for email reminders.
     */
    public static function initializeScheduler()
    {
        // Ensure WooCommerce is active
        if (!class_exists('WC')) {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error"><p>' . esc_html__('WooCommerce must be active for WPLoyalty Point Email Reminder to work.', 'wp-loyalty') . '</p></div>';
            });
            return;
        }

        // Clear old schedules and create a new one
        AdminController::clearEmailReminder();
        AdminController::scheduleEmailReminder();
    }
}
