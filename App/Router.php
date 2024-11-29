<?php

namespace Wlper\App;
use Wlper\App\Controllers\AdminController;
use Wlper\App\Controllers\AdminPageController;
class Router
{
    public static function init()
    {

        add_action('admin_init', [AdminController::class, 'scheduleEmailReminder']);
        add_action('admin_init', [AdminController::class, 'clearEmailReminder']);
        add_action('app_send_points_reminder', [AdminController::class, 'sendPointsReminder']);
        add_action('admin_post_send_email_to_user', [AdminPageController::class, 'handleSendEmailRequest']);
        add_action('admin_post_save_reminder_interval', [AdminPageController::class, 'handleAdminPageForm']);
        add_action('admin_menu', [AdminPageController::class, 'registerAdminPage']);
        add_filter('cron_schedules', [AdminController::class,'custom_cron_schedules']);



    }
}
