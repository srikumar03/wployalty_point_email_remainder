<?php

namespace Wlper\App;

use Wlper\App\Controllers\AdminController;
use Wlper\App\Controllers\AdminPageController;

class Router
{
    public static function init()
    {
        add_action('admin_menu', [AdminPageController::class, 'registerAdminPage']);
        add_action('admin_post_save_reminder_interval', [AdminPageController::class, 'handleAdminPageForm']);
        add_action('admin_post_send_email_to_user', [AdminPageController::class, 'handleSendEmailRequest']);
        add_action('wployalty_points_remainder_schedule', [AdminController::class, 'sendPointsReminderFromScheduler']);
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('admin-style', plugin_dir_url(__FILE__) . 'Views/Admin/Main.css');
        });
    }


}
