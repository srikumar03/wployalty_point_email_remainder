<?php

namespace App\Controller;

use App\Helper\PointsHelper;

class EmailController
{
    public function displayUserPoints()
    {
        $users = get_users(); // Fetch all users.

        echo '<div class="wrap">';
        echo '<h1>User Points Overview</h1>';
        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<thead><tr><th>User</th><th>Email</th><th>Points</th></tr></thead>';
        echo '<tbody>';

        foreach ($users as $user) {
            $points = PointsHelper::getUserPoints($user->ID);
            echo '<tr>';
            echo '<td>' . esc_html($user->display_name) . '</td>';
            echo '<td>' . esc_html($user->user_email) . '</td>';
            echo '<td>' . esc_html($points) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }
}
