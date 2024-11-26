<?php

namespace Wlper\App\Models;

class UserPointsModel
{
    public static function getUsersWithPoints()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wlr_users';

        // Fetch users with points (assuming there's a `points` column in the `wp_wlr_users` table)
        $query = "SELECT id, points, user_email FROM {$table_name}";
        return $wpdb->get_results($query);
    }

    public static function getUserById($user_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wlr_users';

        $query = $wpdb->prepare("SELECT id, points, user_email FROM {$table_name} WHERE id = %d", $user_id);

        return $wpdb->get_row($query);
    }
}
