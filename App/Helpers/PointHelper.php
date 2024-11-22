<?php

namespace App\Helper;

class PointsHelper
{
    public static function getUserPoints($user_id)
    {
        // Replace with the actual logic to fetch points. Example uses user meta.
        return get_user_meta($user_id, 'wp_loyalty_points', true) ?: 0;
    }
}
