<div class="wrap">
    <h1><?php esc_html_e('Loyalty Users', 'wp-loyalty'); ?></h1>

    <?php if (isset($_GET['message'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                $message = sanitize_text_field($_GET['message']);
                if ($message === 'success') {
                    esc_html_e('Email sent successfully.', 'wp-loyalty');
                } elseif ($message === 'interval_saved') {
                    esc_html_e('Reminder interval saved successfully.', 'wp-loyalty');
                } else {
                    esc_html_e('An error occurred.', 'wp-loyalty');
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Fetch saved interval, custom days, and next scheduled date with time -->
    <?php
    $current_interval = get_option('points_reminder_interval', 'monthly'); // Default to 'monthly'
    $custom_days = get_option('points_reminder_custom_days', 30); // Default to 30 days for custom interval
    $next_scheduled_date = get_option('next_email_scheduled_date', ''); // Get next scheduled date
    $next_scheduled_time = get_option('next_email_scheduled_time', ''); // Get next scheduled time

    // If there's no scheduled date, calculate based on the current interval
    if (!$next_scheduled_date || !$next_scheduled_time) {
        switch ($current_interval) {
            case 'monthly':
                $next_scheduled_date = date('Y-m-d', strtotime('+1 month'));
                $next_scheduled_time = '09:00'; // Default time of 9 AM for monthly
                break;
            case 'bimonthly':
                $next_scheduled_date = date('Y-m-d', strtotime('+2 months'));
                $next_scheduled_time = '09:00'; // Default time of 9 AM for bimonthly
                break;
            case 'custom':
                $next_scheduled_date = date('Y-m-d', strtotime("+$custom_days days"));
                $next_scheduled_time = '09:00'; // Default time of 9 AM for custom
                break;
        }
    }
    ?>


    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('save_reminder_interval_nonce'); ?>
        <input type="hidden" name="action" value="save_reminder_interval">
        <!-- Show the next scheduled event with time -->
        <p><strong><?php esc_html_e('Next Scheduled Email:', 'wp-loyalty'); ?></strong>
            <?php echo esc_html(date('d/M/Y', strtotime($next_scheduled_date)) . ' - ' . date('D', strtotime($next_scheduled_date)) . ' at ' . $next_scheduled_time); ?>
        </p>

        <table class="form-table">
            <tr>
                <th><?php esc_html_e('Reminder Interval', 'wp-loyalty'); ?></th>
                <td>
                    <select name="reminder_interval">
                        <option value="monthly" <?php selected($current_interval, 'monthly'); ?>>
                            <?php esc_html_e('Monthly', 'wp-loyalty'); ?>
                        </option>
                        <option value="bimonthly" <?php selected($current_interval, 'bimonthly'); ?>>
                            <?php esc_html_e('Bimonthly', 'wp-loyalty'); ?>
                        </option>
                        <option value="custom" <?php selected($current_interval, 'custom'); ?>>
                            <?php esc_html_e('Custom', 'wp-loyalty'); ?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr class="custom-days"
                style="display: <?php echo ($current_interval === 'custom') ? 'table-row' : 'none'; ?>;">
                <th><?php esc_html_e('Custom Days', 'wp-loyalty'); ?></th>
                <td>
                    <input type="number" name="custom_days" value="<?php echo esc_attr($custom_days); ?>" min="1"
                           class="small-text">
                </td>
            </tr>
        </table>

        <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'wp-loyalty'); ?></button>
    </form>

    <h2><?php esc_html_e('Users', 'wp-loyalty'); ?></h2>
    <table class="widefat fixed striped">
        <thead>
        <tr>
            <th><?php esc_html_e('User ID', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Email', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Points', 'wp-loyalty'); ?></th>
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
                        <a href="<?php echo esc_url(admin_url('admin-post.php?action=send_email_to_user&id=' . $user->id)); ?>"
                           class="button button-secondary">
                            <?php esc_html_e('Send Email', 'wp-loyalty'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><?php esc_html_e('No users found.', 'wp-loyalty'); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Toggle visibility of the custom days field based on selected interval
    document.addEventListener('DOMContentLoaded', function () {
        const intervalSelect = document.querySelector('select[name="reminder_interval"]');
        const customDaysRow = document.querySelector('.custom-days');

        intervalSelect.addEventListener('change', function () {
            if (this.value === 'custom') {
                customDaysRow.style.display = 'table-row';
            } else {
                customDaysRow.style.display = 'none';
            }
        });
    });
</script>
