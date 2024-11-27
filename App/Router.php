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
        add_action('admin_menu', [AdminPageController::class, 'registerAdminPage']);
        add_action('admin_post_send_email_to_user', [AdminPageController::class, 'handleSendEmailRequest']);
    }
}
