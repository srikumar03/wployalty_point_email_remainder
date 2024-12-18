<div class="wrap wp-loyalty-container">
    <h1><?php esc_html_e('WPLoyalty Users Point Reminder', 'wp-loyalty'); ?></h1>

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

    <!-- Display next scheduled email date and time -->
    <div class="tab-header">
        <p><strong><?php esc_html_e('Next Scheduled Email:', 'wp-loyalty'); ?></strong>
            <?php echo esc_html(date('d/M/Y', strtotime($next_scheduled_date)) . ' - ' . date('D', strtotime($next_scheduled_date))); ?>
        </p>
    </div>

    <!-- Reminder Interval Form -->
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('save_reminder_interval_nonce'); ?>
        <input type="hidden" name="action" value="save_reminder_interval">

        <table>
            <tr>
                <th><?php esc_html_e('Reminder Interval', 'wp-loyalty'); ?></th>
                <td>
                    <select name="reminder_interval" id="reminder_interval">
                        <option value="monthly" <?php selected(get_option('points_reminder_interval', 'monthly'), 'monthly'); ?>>
                            <?php esc_html_e('Monthly', 'wp-loyalty'); ?>
                        </option>
                        <option value="bimonthly" <?php selected(get_option('points_reminder_interval', 'bimonthly'), 'bimonthly'); ?>>
                            <?php esc_html_e('Bimonthly', 'wp-loyalty'); ?>
                        </option>
                        <option value="custom" <?php selected(get_option('points_reminder_interval', 'custom'), 'custom'); ?>>
                            <?php esc_html_e('Custom', 'wp-loyalty'); ?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr id="custom_days_row"
                style="display: <?php echo (get_option('points_reminder_interval', 'monthly') === 'custom') ? 'table-row' : 'none'; ?>;">
                <th><?php esc_html_e('Custom Days', 'wp-loyalty'); ?></th>
                <td>
                    <input type="number" name="custom_days"
                           value="<?php echo esc_attr(get_option('points_reminder_custom_days', 30)); ?>" min="1"
                           class="small-text">
                </td>
            </tr>
        </table>

        <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'wp-loyalty'); ?></button>
    </form>

    <!-- User Points Table -->
    <h2><?php esc_html_e('Users', 'wp-loyalty'); ?></h2>
    <table class="widefat striped">
        <thead>
        <tr>
            <th><?php esc_html_e('User ID', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('User Name', 'wp-loyalty'); ?></th>
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
                    <td><?php echo esc_html($user->display_name); ?></td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><?php echo esc_html($user->points) . ' / ' . esc_html($user->earn_total_point); ?></td>
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
                <td colspan="5"><?php esc_html_e('No users found.', 'wp-loyalty'); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const intervalSelect = document.getElementById('reminder_interval');
        const customDaysRow = document.getElementById('custom_days_row');

        // Update visibility on change
        intervalSelect.addEventListener('change', function () {
            customDaysRow.style.display = this.value === 'custom' ? 'table-row' : 'none';
        });
    });
</script>
