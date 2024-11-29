<div class="wrap">
    <h1><?php esc_html_e('Loyalty Users', 'wp-loyalty'); ?></h1>

    <!-- Form to choose email schedule interval -->
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="save_reminder_interval">
        <label for="reminder_interval"><?php esc_html_e('Email Reminder Interval:', 'wp-loyalty'); ?></label>
        <select name="reminder_interval" id="reminder_interval" onchange="toggleCustomDaysField(this.value);">
            <option value="monthly" <?php selected($current_interval, 'monthly'); ?>><?php esc_html_e('Monthly', 'wp-loyalty'); ?></option>
            <option value="bimonthly" <?php selected($current_interval, 'bimonthly'); ?>><?php esc_html_e('Bimonthly', 'wp-loyalty'); ?></option>
            <option value="custom" <?php selected($current_interval, 'custom'); ?>><?php esc_html_e('Custom Days', 'wp-loyalty'); ?></option>
        </select>
        <inputz
                type="number"
                name="custom_days"
                id="custom_days"
                placeholder="Enter days (e.g., 15)"
                value="<?php echo esc_attr($custom_days); ?>"
            <?php echo ($current_interval !== 'custom' ? 'style="display:none;"' : ''); ?>
        >
        <button type="submit" class="button-primary"><?php esc_html_e('Save', 'wp-loyalty'); ?></button>
    </form>

    <?php if ($next_scheduled_time): ?>
        <p>
            <?php esc_html_e('Next email will be sent on:', 'wp-loyalty'); ?>
            <strong><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $next_scheduled_time)); ?></strong>
        </p>
    <?php else: ?>
        <p>
            <?php esc_html_e('Next email is not scheduled yet.', 'wp-loyalty'); ?>
        </p>
    <?php endif; ?>

    <!-- User table -->
    <table class="widefat fixed striped">
        <thead>
        <tr>
            <th><?php esc_html_e('User ID', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Email', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Points', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Next Email Reminder', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Actions', 'wp-loyalty'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo esc_html($user->id); ?></td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><?php echo esc_html($user->points); ?></td>
                    <td>
                        <?php
                        // Calculate next email reminder time for each user
                        $next_email_time = self::getNextEmailTime($current_interval, $custom_days);
                        echo esc_html($next_email_time ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $next_email_time) : __('Not Scheduled', 'wp-loyalty'));
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin-post.php?action=send_email_to_user&id=' . $user->id)); ?>" class="button">
                            <?php esc_html_e('Send Email', 'wp-loyalty'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5"><?php esc_html_e('No users found.', 'wp-loyalty'); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    function toggleCustomDaysField(value) {
        const customDaysField = document.getElementById('custom_days');
        if (value === 'custom') {
            customDaysField.style.display = 'block';
        } else {
            customDaysField.style.display = 'none';
        }
    }
</script>