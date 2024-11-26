<?php

namespace Wlper\App;
use Wlper\App\Controllers\AdminController;
use Wlper\App\Controllers\AdminPageController;
class Router
{
    public static function init()
    {
        // Load required controllers.
        AdminController::init();
        AdminPageController::init();
    }
}
