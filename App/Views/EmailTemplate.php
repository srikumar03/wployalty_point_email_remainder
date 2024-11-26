<?php
/**
 * Email Template for Points Reminder
 * Variables: $user_data->email, $user_data->points
 */
?>
<div>
    <h1><?php printf(__('Hello %s!', 'wp-loyalty'), esc_html($user_data->user_email)); ?></h1>
    <p><?php printf(__('You currently have %d points.', 'wp-loyalty'), esc_html($user_data->points)); ?></p>
    <a href="<?php echo esc_url(home_url('/my-account/loyalty-points')); ?>">
        <?php esc_html_e('Redeem Your Points', 'wp-loyalty'); ?>
    </a>
</div>
