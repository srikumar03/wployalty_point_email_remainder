<?php

namespace WLPER\App\View;

class EmailTemplate
{
    public static function render($user, $points)
    {
        ob_start();
        ?>
        <html>
        <body>
        <h1>Hello, <?php echo esc_html($user->display_name); ?>!</h1>
        <p>You currently have <strong><?php echo esc_html($points); ?></strong> points in your account.</p>
        <p>Use them to get amazing rewards! <a href="<?php echo esc_url(home_url('/rewards')); ?>">Check out rewards</a>.</p>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
